<?php
/**
 * Plugin Name: Super RSS
 * Plugin URI: https://github.com/integrumart/superRSS
 * Description: WordPress RSS eklentisi - Sınırsız RSS kaynağından otomatik makale çekme
 * Version: 1.0.0
 * Author: IntegrumArt
 * Author URI: https://github.com/integrumart
 * License: GPL2
 * Text Domain: super-rss
 */

// Güvenlik kontrolü
if (!defined('ABSPATH')) {
    exit;
}

// Plugin sabitleri
define('SUPER_RSS_VERSION', '1.0.0');
define('SUPER_RSS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SUPER_RSS_PLUGIN_URL', plugin_dir_url(__FILE__));

class SuperRSS {
    
    private $table_name;
    
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'super_rss_feeds';
        
        // Aktivasyon hook'u
        register_activation_hook(__FILE__, array($this, 'activate'));
        
        // Admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Admin sayfası için stil ve script
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        // AJAX işlemleri
        add_action('wp_ajax_super_rss_add_feed', array($this, 'ajax_add_feed'));
        add_action('wp_ajax_super_rss_delete_feed', array($this, 'ajax_delete_feed'));
        add_action('wp_ajax_super_rss_fetch_now', array($this, 'ajax_fetch_now'));
        
        // Cron job
        add_action('super_rss_fetch_feeds', array($this, 'fetch_all_feeds'));
        
        // Cron schedule
        if (!wp_next_scheduled('super_rss_fetch_feeds')) {
            wp_schedule_event(time(), 'hourly', 'super_rss_fetch_feeds');
        }
    }
    
    /**
     * Plugin aktivasyonu
     */
    public function activate() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            feed_url varchar(500) NOT NULL,
            feed_name varchar(200) NOT NULL,
            status varchar(20) DEFAULT 'active',
            last_fetch datetime DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Admin menü ekleme
     */
    public function add_admin_menu() {
        add_menu_page(
            'Super RSS',
            'Super RSS',
            'manage_options',
            'super-rss',
            array($this, 'admin_page'),
            'dashicons-rss',
            30
        );
    }
    
    /**
     * Admin stil ve scriptleri yükleme
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_super-rss') {
            return;
        }
        
        wp_enqueue_style('super-rss-admin', SUPER_RSS_PLUGIN_URL . 'assets/admin.css', array(), SUPER_RSS_VERSION);
        wp_enqueue_script('super-rss-admin', SUPER_RSS_PLUGIN_URL . 'assets/admin.js', array('jquery'), SUPER_RSS_VERSION, true);
        
        wp_localize_script('super-rss-admin', 'superRssAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('super_rss_nonce')
        ));
    }
    
    /**
     * Admin sayfası
     */
    public function admin_page() {
        global $wpdb;
        
        $feeds = $wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY created_at DESC");
        
        include SUPER_RSS_PLUGIN_DIR . 'templates/admin-page.php';
    }
    
    /**
     * AJAX: RSS feed ekleme
     */
    public function ajax_add_feed() {
        check_ajax_referer('super_rss_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Yetkiniz yok');
        }
        
        $feed_url = sanitize_text_field($_POST['feed_url']);
        $feed_name = sanitize_text_field($_POST['feed_name']);
        
        if (empty($feed_url)) {
            wp_send_json_error('RSS URL gerekli');
        }
        
        // URL'nin geçerli bir RSS feed olup olmadığını kontrol et
        $rss = fetch_feed($feed_url);
        if (is_wp_error($rss)) {
            wp_send_json_error('Geçersiz RSS URL: ' . $rss->get_error_message());
        }
        
        // Feed adı boşsa, RSS başlığını kullan
        if (empty($feed_name)) {
            $feed_name = $rss->get_title();
        }
        
        global $wpdb;
        
        $result = $wpdb->insert(
            $this->table_name,
            array(
                'feed_url' => $feed_url,
                'feed_name' => $feed_name,
                'status' => 'active'
            ),
            array('%s', '%s', '%s')
        );
        
        if ($result) {
            wp_send_json_success('RSS feed başarıyla eklendi');
        } else {
            wp_send_json_error('RSS feed eklenirken hata oluştu');
        }
    }
    
    /**
     * AJAX: RSS feed silme
     */
    public function ajax_delete_feed() {
        check_ajax_referer('super_rss_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Yetkiniz yok');
        }
        
        $feed_id = intval($_POST['feed_id']);
        
        global $wpdb;
        
        $result = $wpdb->delete(
            $this->table_name,
            array('id' => $feed_id),
            array('%d')
        );
        
        if ($result) {
            wp_send_json_success('RSS feed silindi');
        } else {
            wp_send_json_error('RSS feed silinirken hata oluştu');
        }
    }
    
    /**
     * AJAX: Şimdi çek
     */
    public function ajax_fetch_now() {
        check_ajax_referer('super_rss_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Yetkiniz yok');
        }
        
        $feed_id = intval($_POST['feed_id']);
        
        $result = $this->fetch_feed($feed_id);
        
        if ($result['success']) {
            wp_send_json_success($result['message']);
        } else {
            wp_send_json_error($result['message']);
        }
    }
    
    /**
     * Tüm feedleri çek
     */
    public function fetch_all_feeds() {
        global $wpdb;
        
        $feeds = $wpdb->get_results("SELECT * FROM {$this->table_name} WHERE status = 'active'");
        
        foreach ($feeds as $feed) {
            $this->fetch_feed($feed->id);
        }
    }
    
    /**
     * Tek bir feed'i çek ve post'ları oluştur
     */
    public function fetch_feed($feed_id) {
        global $wpdb;
        
        $feed = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$this->table_name} WHERE id = %d", $feed_id));
        
        if (!$feed) {
            return array('success' => false, 'message' => 'Feed bulunamadı');
        }
        
        $rss = fetch_feed($feed->feed_url);
        
        if (is_wp_error($rss)) {
            return array('success' => false, 'message' => 'RSS çekilemedi: ' . $rss->get_error_message());
        }
        
        $maxitems = $rss->get_item_quantity(10);
        $rss_items = $rss->get_items(0, $maxitems);
        
        $imported_count = 0;
        
        foreach ($rss_items as $item) {
            $title = $item->get_title();
            $link = $item->get_permalink();
            $content = $item->get_content();
            $description = $item->get_description();
            $date = $item->get_date('Y-m-d H:i:s');
            
            // Bu linkin daha önce eklenip eklenmediğini kontrol et
            $existing_post = get_posts(array(
                'post_type' => 'post',
                'meta_key' => 'super_rss_source_url',
                'meta_value' => $link,
                'posts_per_page' => 1
            ));
            
            if (!empty($existing_post)) {
                continue; // Bu post zaten var
            }
            
            // Post oluştur
            $post_data = array(
                'post_title' => $title,
                'post_content' => !empty($content) ? $content : $description,
                'post_status' => 'publish',
                'post_author' => 1,
                'post_date' => $date,
                'post_type' => 'post'
            );
            
            $post_id = wp_insert_post($post_data);
            
            if ($post_id && !is_wp_error($post_id)) {
                // Kaynak URL'yi meta olarak ekle
                add_post_meta($post_id, 'super_rss_source_url', $link, true);
                add_post_meta($post_id, 'super_rss_feed_name', $feed->feed_name, true);
                $imported_count++;
            }
        }
        
        // Son çekilme zamanını güncelle
        $wpdb->update(
            $this->table_name,
            array('last_fetch' => current_time('mysql')),
            array('id' => $feed_id),
            array('%s'),
            array('%d')
        );
        
        return array(
            'success' => true,
            'message' => $imported_count . ' yeni yazı içe aktarıldı'
        );
    }
}

// Plugin'i başlat
new SuperRSS();

// Deaktivasyon hook'u
register_deactivation_hook(__FILE__, 'super_rss_deactivate');

function super_rss_deactivate() {
    wp_clear_scheduled_hook('super_rss_fetch_feeds');
}
