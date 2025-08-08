<?php
/**
 * CSV Import Loader
 * 
 * @package OKS
 * @subpackage CSV_Import
 */

// Direct access protection
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main CSV Import Loader Class
 */
class OKS_CSV_Import_Loader {
    
    /**
     * Instance
     */
    private static $instance = null;
    
    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
    }
    
    /**
     * Define constants
     */
    private function define_constants() {
        define('OKS_CSV_IMPORT_DIR', get_template_directory() . '/includes/csv-import/');
        define('OKS_CSV_IMPORT_URL', get_template_directory_uri() . '/includes/csv-import/');
    }
    
    /**
     * Include required files
     */
    private function includes() {
        require_once OKS_CSV_IMPORT_DIR . 'class-csv-processor.php';
        require_once OKS_CSV_IMPORT_DIR . 'class-admin-page.php';
        require_once OKS_CSV_IMPORT_DIR . 'class-job-importer.php';
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'init'));
        
        // Admin only hooks
        if (is_admin()) {
            $admin_page = new OKS_CSV_Import_Admin_Page();
            $admin_page->init();
        }
    }
    
    /**
     * Initialize
     */
    public function init() {
        // Register custom post type if not exists
        if (!post_type_exists('job')) {
            $this->register_job_post_type();
        }
    }
    
    /**
     * Register job post type
     */
    private function register_job_post_type() {
        $labels = array(
            'name'               => '求人情報',
            'singular_name'      => '求人',
            'menu_name'          => '求人管理',
            'add_new'            => '新規追加',
            'add_new_item'       => '新規求人を追加',
            'edit_item'          => '求人を編集',
            'new_item'           => '新規求人',
            'view_item'          => '求人を表示',
            'search_items'       => '求人を検索',
            'not_found'          => '求人が見つかりません',
            'not_found_in_trash' => 'ゴミ箱に求人が見つかりません',
        );
        
        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'publicly_queryable'  => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'query_var'           => true,
            'rewrite'             => array('slug' => 'job'),
            'capability_type'     => 'post',
            'has_archive'         => true,
            'hierarchical'        => false,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-businessman',
            'supports'            => array('title', 'editor', 'thumbnail'),
        );
        
        register_post_type('job', $args);
    }
}

// Initialize
OKS_CSV_Import_Loader::get_instance();