<?php
/**
 * Database operations for SuperRSS
 */

if (!defined('ABSPATH')) {
    exit;
}

class SuperRSS_DB {
    
    private static $table_name = 'superrss_sources';
    
    /**
     * Create database tables
     */
    public static function create_tables() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . self::$table_name;
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            feed_url varchar(500) NOT NULL,
            feed_name varchar(255) NOT NULL,
            category_id bigint(20) DEFAULT NULL,
            post_status varchar(20) DEFAULT 'draft',
            author_id bigint(20) DEFAULT NULL,
            last_fetch datetime DEFAULT NULL,
            active tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY feed_url (feed_url(191))
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    
    /**
     * Get all RSS sources
     */
    public static function get_sources($active_only = false) {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$table_name;
        
        $where = $active_only ? 'WHERE active = 1' : '';
        
        return $wpdb->get_results("SELECT * FROM $table_name $where ORDER BY created_at DESC");
    }
    
    /**
     * Add a new RSS source
     */
    public static function add_source($data) {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$table_name;
        
        $insert_data = array(
            'feed_url' => sanitize_text_field($data['feed_url']),
            'feed_name' => sanitize_text_field($data['feed_name']),
            'category_id' => isset($data['category_id']) ? intval($data['category_id']) : null,
            'post_status' => isset($data['post_status']) ? sanitize_text_field($data['post_status']) : 'draft',
            'author_id' => isset($data['author_id']) ? intval($data['author_id']) : get_current_user_id(),
            'active' => isset($data['active']) ? intval($data['active']) : 1,
        );
        
        $result = $wpdb->insert($table_name, $insert_data);
        
        if ($result) {
            return $wpdb->insert_id;
        }
        
        return false;
    }
    
    /**
     * Update RSS source
     */
    public static function update_source($id, $data) {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$table_name;
        
        $update_data = array();
        
        if (isset($data['feed_url'])) {
            $update_data['feed_url'] = sanitize_text_field($data['feed_url']);
        }
        if (isset($data['feed_name'])) {
            $update_data['feed_name'] = sanitize_text_field($data['feed_name']);
        }
        if (isset($data['category_id'])) {
            $update_data['category_id'] = intval($data['category_id']);
        }
        if (isset($data['post_status'])) {
            $update_data['post_status'] = sanitize_text_field($data['post_status']);
        }
        if (isset($data['author_id'])) {
            $update_data['author_id'] = intval($data['author_id']);
        }
        if (isset($data['active'])) {
            $update_data['active'] = intval($data['active']);
        }
        if (isset($data['last_fetch'])) {
            $update_data['last_fetch'] = $data['last_fetch'];
        }
        
        return $wpdb->update($table_name, $update_data, array('id' => intval($id)));
    }
    
    /**
     * Delete RSS source
     */
    public static function delete_source($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$table_name;
        
        return $wpdb->delete($table_name, array('id' => intval($id)));
    }
    
    /**
     * Get single source
     */
    public static function get_source($id) {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$table_name;
        
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", intval($id)));
    }
}
