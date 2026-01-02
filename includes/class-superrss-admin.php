<?php
/**
 * Admin interface for SuperRSS
 */

if (!defined('ABSPATH')) {
    exit;
}

class SuperRSS_Admin {
    
    /**
     * Add admin menu
     */
    public static function add_admin_menu() {
        add_menu_page(
            __('SuperRSS', 'superrss'),
            __('SuperRSS', 'superrss'),
            'manage_options',
            'superrss',
            array('SuperRSS_Admin', 'render_admin_page'),
            'dashicons-rss',
            30
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public static function enqueue_scripts($hook) {
        if ($hook !== 'toplevel_page_superrss') {
            return;
        }
        
        wp_enqueue_style('superrss-admin', SUPERRSS_PLUGIN_URL . 'assets/css/admin.css', array(), SUPERRSS_VERSION);
        wp_enqueue_script('superrss-admin', SUPERRSS_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), SUPERRSS_VERSION, true);
        
        wp_localize_script('superrss-admin', 'superrss_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('superrss_nonce')
        ));
    }
    
    /**
     * Render admin page
     */
    public static function render_admin_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $sources = SuperRSS_DB::get_sources();
        $categories = get_categories(array('hide_empty' => false));
        $users = get_users(array('who' => 'authors'));
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('SuperRSS - RSS Kaynak Yönetimi', 'superrss'); ?></h1>
            
            <div class="superrss-container">
                <!-- Add New Source Form -->
                <div class="superrss-add-form">
                    <h2><?php echo esc_html__('Yeni RSS Kaynağı Ekle', 'superrss'); ?></h2>
                    <form id="superrss-add-form">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="feed_name"><?php echo esc_html__('Kaynak Adı', 'superrss'); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="feed_name" name="feed_name" class="regular-text" required>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="feed_url"><?php echo esc_html__('RSS Feed URL', 'superrss'); ?></label>
                                </th>
                                <td>
                                    <input type="url" id="feed_url" name="feed_url" class="regular-text" required>
                                    <p class="description"><?php echo esc_html__('Örnek: https://example.com/feed/', 'superrss'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="category_id"><?php echo esc_html__('Kategori', 'superrss'); ?></label>
                                </th>
                                <td>
                                    <select id="category_id" name="category_id">
                                        <option value=""><?php echo esc_html__('-- Kategori Seçin --', 'superrss'); ?></option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo esc_attr($category->term_id); ?>">
                                                <?php echo esc_html($category->name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="post_status"><?php echo esc_html__('Yazı Durumu', 'superrss'); ?></label>
                                </th>
                                <td>
                                    <select id="post_status" name="post_status">
                                        <option value="draft"><?php echo esc_html__('Taslak', 'superrss'); ?></option>
                                        <option value="publish"><?php echo esc_html__('Yayınla', 'superrss'); ?></option>
                                        <option value="pending"><?php echo esc_html__('İnceleme Bekliyor', 'superrss'); ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="author_id"><?php echo esc_html__('Yazar', 'superrss'); ?></label>
                                </th>
                                <td>
                                    <select id="author_id" name="author_id">
                                        <?php foreach ($users as $user): ?>
                                            <option value="<?php echo esc_attr($user->ID); ?>" <?php selected($user->ID, get_current_user_id()); ?>>
                                                <?php echo esc_html($user->display_name); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="active"><?php echo esc_html__('Durum', 'superrss'); ?></label>
                                </th>
                                <td>
                                    <label>
                                        <input type="checkbox" id="active" name="active" value="1" checked>
                                        <?php echo esc_html__('Aktif', 'superrss'); ?>
                                    </label>
                                </td>
                            </tr>
                        </table>
                        <p class="submit">
                            <button type="submit" class="button button-primary">
                                <?php echo esc_html__('Kaynak Ekle', 'superrss'); ?>
                            </button>
                        </p>
                    </form>
                </div>
                
                <!-- Sources List -->
                <div class="superrss-sources-list">
                    <h2><?php echo esc_html__('RSS Kaynakları', 'superrss'); ?></h2>
                    
                    <?php if (empty($sources)): ?>
                        <p><?php echo esc_html__('Henüz RSS kaynağı eklenmemiş.', 'superrss'); ?></p>
                    <?php else: ?>
                        <table class="wp-list-table widefat fixed striped">
                            <thead>
                                <tr>
                                    <th><?php echo esc_html__('Kaynak Adı', 'superrss'); ?></th>
                                    <th><?php echo esc_html__('Feed URL', 'superrss'); ?></th>
                                    <th><?php echo esc_html__('Kategori', 'superrss'); ?></th>
                                    <th><?php echo esc_html__('Durum', 'superrss'); ?></th>
                                    <th><?php echo esc_html__('Son Çekim', 'superrss'); ?></th>
                                    <th><?php echo esc_html__('İşlemler', 'superrss'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sources as $source): ?>
                                    <tr>
                                        <td><strong><?php echo esc_html($source->feed_name); ?></strong></td>
                                        <td>
                                            <a href="<?php echo esc_url($source->feed_url); ?>" target="_blank">
                                                <?php echo esc_html(substr($source->feed_url, 0, 50) . '...'); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <?php 
                                            if ($source->category_id) {
                                                $cat = get_category($source->category_id);
                                                echo $cat ? esc_html($cat->name) : '-';
                                            } else {
                                                echo '-';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $source->active ? 
                                                '<span class="superrss-status-active">' . esc_html__('Aktif', 'superrss') . '</span>' : 
                                                '<span class="superrss-status-inactive">' . esc_html__('Pasif', 'superrss') . '</span>'; 
                                            ?>
                                        </td>
                                        <td>
                                            <?php echo $source->last_fetch ? 
                                                esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($source->last_fetch))) : 
                                                esc_html__('Henüz çekilmedi', 'superrss'); 
                                            ?>
                                        </td>
                                        <td>
                                            <button class="button button-small superrss-fetch-now" data-id="<?php echo esc_attr($source->id); ?>">
                                                <?php echo esc_html__('Şimdi Çek', 'superrss'); ?>
                                            </button>
                                            <button class="button button-small button-link-delete superrss-delete" data-id="<?php echo esc_attr($source->id); ?>">
                                                <?php echo esc_html__('Sil', 'superrss'); ?>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                
                <div class="superrss-info">
                    <h3><?php echo esc_html__('Bilgi', 'superrss'); ?></h3>
                    <p><?php echo esc_html__('RSS kaynakları otomatik olarak saatte bir kez kontrol edilir ve yeni yazılar eklenir.', 'superrss'); ?></p>
                    <p><?php echo esc_html__('Manuel olarak çekmek için "Şimdi Çek" butonuna tıklayabilirsiniz.', 'superrss'); ?></p>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * AJAX: Add new source
     */
    public static function ajax_add_source() {
        check_ajax_referer('superrss_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Yetkiniz yok.', 'superrss')));
        }
        
        $feed_url = isset($_POST['feed_url']) ? sanitize_text_field($_POST['feed_url']) : '';
        $feed_name = isset($_POST['feed_name']) ? sanitize_text_field($_POST['feed_name']) : '';
        
        if (empty($feed_url) || empty($feed_name)) {
            wp_send_json_error(array('message' => __('Lütfen tüm zorunlu alanları doldurun.', 'superrss')));
        }
        
        $data = array(
            'feed_url' => $feed_url,
            'feed_name' => $feed_name,
            'category_id' => isset($_POST['category_id']) ? intval($_POST['category_id']) : null,
            'post_status' => isset($_POST['post_status']) ? sanitize_text_field($_POST['post_status']) : 'draft',
            'author_id' => isset($_POST['author_id']) ? intval($_POST['author_id']) : get_current_user_id(),
            'active' => isset($_POST['active']) ? 1 : 0,
        );
        
        $source_id = SuperRSS_DB::add_source($data);
        
        if ($source_id) {
            wp_send_json_success(array('message' => __('Kaynak başarıyla eklendi.', 'superrss')));
        } else {
            wp_send_json_error(array('message' => __('Kaynak eklenirken bir hata oluştu.', 'superrss')));
        }
    }
    
    /**
     * AJAX: Delete source
     */
    public static function ajax_delete_source() {
        check_ajax_referer('superrss_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Yetkiniz yok.', 'superrss')));
        }
        
        $source_id = isset($_POST['source_id']) ? intval($_POST['source_id']) : 0;
        
        if (!$source_id) {
            wp_send_json_error(array('message' => __('Geçersiz kaynak ID.', 'superrss')));
        }
        
        if (SuperRSS_DB::delete_source($source_id)) {
            wp_send_json_success(array('message' => __('Kaynak başarıyla silindi.', 'superrss')));
        } else {
            wp_send_json_error(array('message' => __('Kaynak silinirken bir hata oluştu.', 'superrss')));
        }
    }
    
    /**
     * AJAX: Fetch feed now
     */
    public static function ajax_fetch_now() {
        check_ajax_referer('superrss_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Yetkiniz yok.', 'superrss')));
        }
        
        $source_id = isset($_POST['source_id']) ? intval($_POST['source_id']) : 0;
        
        if (!$source_id) {
            wp_send_json_error(array('message' => __('Geçersiz kaynak ID.', 'superrss')));
        }
        
        $source = SuperRSS_DB::get_source($source_id);
        
        if (!$source) {
            wp_send_json_error(array('message' => __('Kaynak bulunamadı.', 'superrss')));
        }
        
        $imported_count = SuperRSS_Fetcher::fetch_feed($source);
        
        if ($imported_count !== false) {
            wp_send_json_success(array(
                'message' => sprintf(__('%d yeni yazı içe aktarıldı.', 'superrss'), $imported_count)
            ));
        } else {
            wp_send_json_error(array('message' => __('Feed çekilirken bir hata oluştu.', 'superrss')));
        }
    }
}
