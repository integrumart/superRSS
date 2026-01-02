<?php
/**
 * Plugin Name: SuperRSS
 * Plugin URI: https://github.com/integrumart/superRSS
 * Description: WordPress süper RSS eklentisi - Sınırsız kaynaktan yazı çeker ve WordPress'e ekler
 * Version: 1.0.0
 * Author: Integrumart
 * Text Domain: superrss
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SUPERRSS_VERSION', '1.0.0');
define('SUPERRSS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SUPERRSS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once SUPERRSS_PLUGIN_DIR . 'includes/class-superrss-db.php';
require_once SUPERRSS_PLUGIN_DIR . 'includes/class-superrss-fetcher.php';
require_once SUPERRSS_PLUGIN_DIR . 'includes/class-superrss-admin.php';

/**
 * Main SuperRSS class
 */
class SuperRSS {
    
    private static $instance = null;
    
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init_hooks();
    }
    
    private function init_hooks() {
        // Activation and deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Initialize admin
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('admin_menu', array('SuperRSS_Admin', 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array('SuperRSS_Admin', 'enqueue_scripts'));
        
        // AJAX handlers
        add_action('wp_ajax_superrss_add_source', array('SuperRSS_Admin', 'ajax_add_source'));
        add_action('wp_ajax_superrss_delete_source', array('SuperRSS_Admin', 'ajax_delete_source'));
        add_action('wp_ajax_superrss_fetch_now', array('SuperRSS_Admin', 'ajax_fetch_now'));
        
        // Cron job for fetching RSS feeds
        add_action('superrss_fetch_feeds', array('SuperRSS_Fetcher', 'fetch_all_feeds'));
        
        // Schedule cron if not scheduled
        if (!wp_next_scheduled('superrss_fetch_feeds')) {
            wp_schedule_event(time(), 'hourly', 'superrss_fetch_feeds');
        }
    }
    
    public function activate() {
        SuperRSS_DB::create_tables();
        
        // Schedule cron job
        if (!wp_next_scheduled('superrss_fetch_feeds')) {
            wp_schedule_event(time(), 'hourly', 'superrss_fetch_feeds');
        }
    }
    
    public function deactivate() {
        // Clear scheduled cron job
        $timestamp = wp_next_scheduled('superrss_fetch_feeds');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'superrss_fetch_feeds');
        }
    }
    
    public function load_textdomain() {
        load_plugin_textdomain('superrss', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
}

// Initialize the plugin
SuperRSS::get_instance();
