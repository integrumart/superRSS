<?php
/**
 * RSS Feed Fetcher for SuperRSS
 */

if (!defined('ABSPATH')) {
    exit;
}

class SuperRSS_Fetcher {
    
    /**
     * Fetch all active RSS feeds
     */
    public static function fetch_all_feeds() {
        $sources = SuperRSS_DB::get_sources(true);
        
        foreach ($sources as $source) {
            self::fetch_feed($source);
        }
    }
    
    /**
     * Fetch a single RSS feed
     */
    public static function fetch_feed($source) {
        $feed_url = $source->feed_url;
        
        // Fetch RSS feed
        $rss = fetch_feed($feed_url);
        
        if (is_wp_error($rss)) {
            error_log('SuperRSS Error fetching feed: ' . $feed_url . ' - ' . $rss->get_error_message());
            return false;
        }
        
        $maxitems = $rss->get_item_quantity(10); // Fetch up to 10 items
        $rss_items = $rss->get_items(0, $maxitems);
        
        $imported_count = 0;
        
        foreach ($rss_items as $item) {
            if (self::import_post($item, $source)) {
                $imported_count++;
            }
        }
        
        // Update last fetch time
        SuperRSS_DB::update_source($source->id, array(
            'last_fetch' => current_time('mysql')
        ));
        
        return $imported_count;
    }
    
    /**
     * Import RSS item as WordPress post
     */
    private static function import_post($item, $source) {
        // Get item data
        $title = $item->get_title();
        $content = $item->get_content();
        $description = $item->get_description();
        $link = $item->get_permalink();
        $date = $item->get_date('Y-m-d H:i:s');
        
        // Use description if content is empty
        if (empty($content)) {
            $content = $description;
        }
        
        // Check if post with same title already exists
        $existing_post = get_page_by_title($title, OBJECT, 'post');
        if ($existing_post) {
            return false; // Post already exists
        }
        
        // Check by URL in post meta
        $existing_by_url = get_posts(array(
            'post_type' => 'post',
            'meta_key' => 'superrss_source_url',
            'meta_value' => $link,
            'numberposts' => 1
        ));
        
        if (!empty($existing_by_url)) {
            return false; // Post already imported
        }
        
        // Prepare post data
        $post_data = array(
            'post_title' => wp_strip_all_tags($title),
            'post_content' => $content,
            'post_status' => $source->post_status,
            'post_author' => $source->author_id ? $source->author_id : get_current_user_id(),
            'post_date' => $date ? $date : current_time('mysql'),
            'post_type' => 'post'
        );
        
        // Insert post
        $post_id = wp_insert_post($post_data);
        
        if ($post_id && !is_wp_error($post_id)) {
            // Add category if specified
            if ($source->category_id) {
                wp_set_post_categories($post_id, array($source->category_id));
            }
            
            // Store source URL as post meta
            update_post_meta($post_id, 'superrss_source_url', $link);
            update_post_meta($post_id, 'superrss_source_id', $source->id);
            update_post_meta($post_id, 'superrss_feed_name', $source->feed_name);
            
            // Try to get and set featured image
            $enclosure = $item->get_enclosure();
            if ($enclosure) {
                $image_url = $enclosure->get_thumbnail();
                if (!$image_url) {
                    $image_url = $enclosure->get_link();
                }
                if ($image_url) {
                    self::set_featured_image($post_id, $image_url);
                }
            }
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Set featured image from URL
     */
    private static function set_featured_image($post_id, $image_url) {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        
        $image_id = media_sideload_image($image_url, $post_id, null, 'id');
        
        if (!is_wp_error($image_id)) {
            set_post_thumbnail($post_id, $image_id);
            return true;
        }
        
        return false;
    }
}
