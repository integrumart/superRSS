<?php
/**
 * Plugin Name: Super RSS
 * Plugin URI: https://github.com/integrumart/superRSS
 * Description: Allows adding unlimited RSS sources and automatically imports posts from them.
 * Version: 1.0.0
 * Author: Integrumart
 * Author URI: https://github.com/integrumart
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: super-rss
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Plugin constants
define('SUPER_RSS_VERSION', '1.0.0');
define('SUPER_RSS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SUPER_RSS_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main Super RSS Class
 */
class Super_RSS {
    
    private $table_name;
    
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'super_rss_sources';
        
        // Activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // AJAX handlers
        add_action('wp_ajax_super_rss_add_source', array($this, 'ajax_add_source'));
        add_action('wp_ajax_super_rss_delete_source', array($this, 'ajax_delete_source'));
        add_action('wp_ajax_super_rss_update_source', array($this, 'ajax_update_source'));
        
        // Cron action
        add_action('super_rss_fetch_feeds', array($this, 'fetch_all_feeds'));
        
        // Add custom cron interval
        add_filter('cron_schedules', array($this, 'add_custom_cron_interval'));
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            feed_url varchar(500) NOT NULL,
            feed_name varchar(255) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        
        // Schedule the cron event
        if (!wp_next_scheduled('super_rss_fetch_feeds')) {
            wp_schedule_event(time(), 'every_minute', 'super_rss_fetch_feeds');
        }
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Clear the scheduled event
        $timestamp = wp_next_scheduled('super_rss_fetch_feeds');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'super_rss_fetch_feeds');
        }
    }
    
    /**
     * Add custom cron interval (every minute)
     */
    public function add_custom_cron_interval($schedules) {
        $schedules['every_minute'] = array(
            'interval' => 60,
            'display'  => __('Every Minute', 'super-rss')
        );
        return $schedules;
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Super RSS', 'super-rss'),
            __('Super RSS', 'super-rss'),
            'manage_options',
            'super-rss',
            array($this, 'admin_page'),
            'dashicons-rss',
            30
        );
    }
    
    /**
     * Admin page HTML
     */
    public function admin_page() {
        global $wpdb;
        
        $sources = $wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY id DESC");
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Super RSS - Manage RSS Sources', 'super-rss'); ?></h1>
            
            <div class="super-rss-container" style="margin-top: 20px;">
                <div class="add-source-form" style="background: #fff; padding: 20px; margin-bottom: 20px; border: 1px solid #ccc;">
                    <h2><?php echo esc_html__('Add New RSS Source', 'super-rss'); ?></h2>
                    <form id="super-rss-add-form">
                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="feed_name"><?php echo esc_html__('Feed Name', 'super-rss'); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="feed_name" name="feed_name" class="regular-text" required>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="feed_url"><?php echo esc_html__('Feed URL', 'super-rss'); ?></label>
                                </th>
                                <td>
                                    <input type="url" id="feed_url" name="feed_url" class="regular-text" required>
                                    <p class="description"><?php echo esc_html__('Enter the RSS feed URL', 'super-rss'); ?></p>
                                </td>
                            </tr>
                        </table>
                        <?php wp_nonce_field('super_rss_add_source', 'super_rss_nonce'); ?>
                        <p>
                            <button type="submit" class="button button-primary"><?php echo esc_html__('Add RSS Source', 'super-rss'); ?></button>
                        </p>
                    </form>
                    <div id="add-source-message"></div>
                </div>
                
                <div class="sources-list" style="background: #fff; padding: 20px; border: 1px solid #ccc;">
                    <h2><?php echo esc_html__('RSS Sources', 'super-rss'); ?></h2>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php echo esc_html__('ID', 'super-rss'); ?></th>
                                <th><?php echo esc_html__('Feed Name', 'super-rss'); ?></th>
                                <th><?php echo esc_html__('Feed URL', 'super-rss'); ?></th>
                                <th><?php echo esc_html__('Created At', 'super-rss'); ?></th>
                                <th><?php echo esc_html__('Actions', 'super-rss'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="sources-table-body">
                            <?php if (empty($sources)): ?>
                                <tr>
                                    <td colspan="5"><?php echo esc_html__('No RSS sources found. Add one above!', 'super-rss'); ?></td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($sources as $source): ?>
                                    <tr id="source-<?php echo esc_attr($source->id); ?>">
                                        <td><?php echo esc_html($source->id); ?></td>
                                        <td class="source-name"><?php echo esc_html($source->feed_name); ?></td>
                                        <td class="source-url"><?php echo esc_html($source->feed_url); ?></td>
                                        <td><?php echo esc_html($source->created_at); ?></td>
                                        <td>
                                            <button class="button button-small edit-source" data-id="<?php echo esc_attr($source->id); ?>"><?php echo esc_html__('Edit', 'super-rss'); ?></button>
                                            <button class="button button-small delete-source" data-id="<?php echo esc_attr($source->id); ?>"><?php echo esc_html__('Delete', 'super-rss'); ?></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Add source
            $('#super-rss-add-form').on('submit', function(e) {
                e.preventDefault();
                
                var formData = {
                    action: 'super_rss_add_source',
                    feed_name: $('#feed_name').val(),
                    feed_url: $('#feed_url').val(),
                    nonce: $('#super_rss_nonce').val()
                };
                
                $.post(ajaxurl, formData, function(response) {
                    if (response.success) {
                        $('#add-source-message').html('<div class="notice notice-success"><p>' + response.data.message + '</p></div>');
                        $('#super-rss-add-form')[0].reset();
                        location.reload();
                    } else {
                        $('#add-source-message').html('<div class="notice notice-error"><p>' + response.data.message + '</p></div>');
                    }
                });
            });
            
            // Delete source
            $(document).on('click', '.delete-source', function() {
                if (!confirm('<?php echo esc_js(__('Are you sure you want to delete this RSS source?', 'super-rss')); ?>')) {
                    return;
                }
                
                var sourceId = $(this).data('id');
                
                $.post(ajaxurl, {
                    action: 'super_rss_delete_source',
                    source_id: sourceId,
                    nonce: '<?php echo wp_create_nonce('super_rss_delete_source'); ?>'
                }, function(response) {
                    if (response.success) {
                        $('#source-' + sourceId).fadeOut(300, function() {
                            $(this).remove();
                        });
                    } else {
                        alert(response.data.message);
                    }
                });
            });
            
            // Edit source
            $(document).on('click', '.edit-source', function() {
                var sourceId = $(this).data('id');
                var row = $('#source-' + sourceId);
                var currentName = row.find('.source-name').text();
                var currentUrl = row.find('.source-url').text();
                
                var newName = prompt('<?php echo esc_js(__('Enter new feed name:', 'super-rss')); ?>', currentName);
                if (newName === null || newName === '') return;
                
                var newUrl = prompt('<?php echo esc_js(__('Enter new feed URL:', 'super-rss')); ?>', currentUrl);
                if (newUrl === null || newUrl === '') return;
                
                $.post(ajaxurl, {
                    action: 'super_rss_update_source',
                    source_id: sourceId,
                    feed_name: newName,
                    feed_url: newUrl,
                    nonce: '<?php echo wp_create_nonce('super_rss_update_source'); ?>'
                }, function(response) {
                    if (response.success) {
                        row.find('.source-name').text(newName);
                        row.find('.source-url').text(newUrl);
                        alert(response.data.message);
                    } else {
                        alert(response.data.message);
                    }
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * AJAX: Add source
     */
    public function ajax_add_source() {
        check_ajax_referer('super_rss_add_source', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'super-rss')));
        }
        
        $feed_name = sanitize_text_field($_POST['feed_name']);
        $feed_url = esc_url_raw($_POST['feed_url']);
        
        if (empty($feed_name) || empty($feed_url)) {
            wp_send_json_error(array('message' => __('Feed name and URL are required', 'super-rss')));
        }
        
        global $wpdb;
        $result = $wpdb->insert(
            $this->table_name,
            array(
                'feed_name' => $feed_name,
                'feed_url' => $feed_url
            ),
            array('%s', '%s')
        );
        
        if ($result) {
            wp_send_json_success(array('message' => __('RSS source added successfully', 'super-rss')));
        } else {
            wp_send_json_error(array('message' => __('Failed to add RSS source', 'super-rss')));
        }
    }
    
    /**
     * AJAX: Delete source
     */
    public function ajax_delete_source() {
        check_ajax_referer('super_rss_delete_source', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'super-rss')));
        }
        
        $source_id = intval($_POST['source_id']);
        
        global $wpdb;
        $result = $wpdb->delete(
            $this->table_name,
            array('id' => $source_id),
            array('%d')
        );
        
        if ($result) {
            wp_send_json_success(array('message' => __('RSS source deleted successfully', 'super-rss')));
        } else {
            wp_send_json_error(array('message' => __('Failed to delete RSS source', 'super-rss')));
        }
    }
    
    /**
     * AJAX: Update source
     */
    public function ajax_update_source() {
        check_ajax_referer('super_rss_update_source', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'super-rss')));
        }
        
        $source_id = intval($_POST['source_id']);
        $feed_name = sanitize_text_field($_POST['feed_name']);
        $feed_url = esc_url_raw($_POST['feed_url']);
        
        global $wpdb;
        $result = $wpdb->update(
            $this->table_name,
            array(
                'feed_name' => $feed_name,
                'feed_url' => $feed_url
            ),
            array('id' => $source_id),
            array('%s', '%s'),
            array('%d')
        );
        
        if ($result !== false) {
            wp_send_json_success(array('message' => __('RSS source updated successfully', 'super-rss')));
        } else {
            wp_send_json_error(array('message' => __('Failed to update RSS source', 'super-rss')));
        }
    }
    
    /**
     * Fetch all RSS feeds
     */
    public function fetch_all_feeds() {
        global $wpdb;
        
        $sources = $wpdb->get_results("SELECT * FROM {$this->table_name}");
        
        foreach ($sources as $source) {
            $this->fetch_feed($source->feed_url);
        }
    }
    
    /**
     * Fetch and import a single RSS feed
     */
    private function fetch_feed($feed_url) {
        $rss = fetch_feed($feed_url);
        
        if (is_wp_error($rss)) {
            return;
        }
        
        $maxitems = $rss->get_item_quantity(10);
        $rss_items = $rss->get_items(0, $maxitems);
        
        foreach ($rss_items as $item) {
            $this->import_item($item, $feed_url);
        }
    }
    
    /**
     * Import a single RSS item as a WordPress post
     */
    private function import_item($item, $feed_url) {
        global $wpdb;
        
        $title = $item->get_title();
        $link = $item->get_permalink();
        $description = $item->get_description();
        $content = $item->get_content();
        $date = $item->get_date('Y-m-d H:i:s');
        
        // Get GUID for duplicate checking
        $guid = $item->get_id();
        if (empty($guid)) {
            $guid = $link;
        }
        
        // Check if post already exists by GUID or source URL
        $existing_post = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE guid = %s OR post_content LIKE %s LIMIT 1",
            $guid,
            '%' . $wpdb->esc_like($link) . '%'
        ));
        
        if ($existing_post) {
            return; // Skip if post already exists
        }
        
        // Prepare post content
        $post_content = !empty($content) ? $content : $description;
        $post_content .= '<p><a href="' . esc_url($link) . '" target="_blank">' . __('Read more', 'super-rss') . '</a></p>';
        
        // Insert post
        $post_data = array(
            'post_title'    => sanitize_text_field($title),
            'post_content'  => wp_kses_post($post_content),
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_date'     => $date ? $date : current_time('mysql'),
            'guid'          => $guid
        );
        
        wp_insert_post($post_data);
    }
}

// Initialize the plugin
new Super_RSS();
