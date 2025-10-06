<?php
/**
 * Job Search Loader
 *
 * @package OKS
 * @subpackage Job_Search
 */

// Direct access protection
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Job Search Loader Class
 */
class OKS_Job_Search_Loader {

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
         define('OKS_JOB_SEARCH_DIR', get_template_directory() . '/includes/job-search/');
         define('OKS_JOB_SEARCH_URL', get_template_directory_uri() . '/includes/job-search/');
     }

    /**
     * Include required files
     */
    private function includes() {
        require_once OKS_JOB_SEARCH_DIR . 'class-search-form.php';
        require_once OKS_JOB_SEARCH_DIR . 'class-search-handler.php';
        require_once OKS_JOB_SEARCH_DIR . 'class-search-data.php';
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Register shortcode
        add_shortcode('oks_job_search', array($this, 'render_search_shortcode'));

        // Ajax handlers
        add_action('wp_ajax_oks_job_search', array($this, 'handle_ajax_search'));
        add_action('wp_ajax_nopriv_oks_job_search', array($this, 'handle_ajax_search'));

        // Register rewrite rules
        add_action('init', array($this, 'add_rewrite_rules'));
        add_filter('query_vars', array($this, 'add_query_vars'));
    }

    /**
     * Initialize
     */
    public function init() {
        // Flush rewrite rules if needed
        if (get_option('oks_job_search_flush_rewrite_rules')) {
            flush_rewrite_rules();
            delete_option('oks_job_search_flush_rewrite_rules');
        }
    }

    /**
     * Add rewrite rules
     */
    public function add_rewrite_rules() {
        add_rewrite_rule(
            '^job-search/?$',
            'index.php?oks_job_search=1',
            'top'
        );
    }

    /**
     * Add query vars
     */
    public function add_query_vars($vars) {
        $vars[] = 'oks_job_search';
        return $vars;
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        if (is_page() || get_query_var('oks_job_search')) {
            // CSS
            wp_enqueue_style(
                'oks-job-search',
                OKS_JOB_SEARCH_URL . 'assets/job-search.css',
                array(),
                '1.0.0'
            );

            // JavaScript
            wp_enqueue_script(
                'oks-job-search',
                OKS_JOB_SEARCH_URL . 'assets/job-search.js',
                array('jquery'),
                '1.0.0',
                true
            );

            // Localize script
            wp_localize_script('oks-job-search', 'oks_job_search', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('oks_job_search_nonce')
            ));
        }
    }

    /**
     * Render search shortcode
     */
    public function render_search_shortcode($atts) {
        $atts = shortcode_atts(array(
            'show_results' => 'true'
        ), $atts);

        ob_start();

        $search_form = new OKS_Search_Form();
        $search_form->render();

        if ($atts['show_results'] === 'true') {
            echo '<div id="oks-search-results" class="oks-search-results"></div>';

            // If there are search parameters, perform initial search
            if (!empty($_GET)) {
                $search_handler = new OKS_Search_Handler();
                $results = $search_handler->search($_GET);
                echo '<script>
                    jQuery(document).ready(function($) {
                        OKS_Job_Search.displayResults(' . json_encode($results) . ');
                    });
                </script>';
            }
        }

        return ob_get_clean();
    }

    /**
     * Handle Ajax search
     */
    public function handle_ajax_search() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'oks_job_search_nonce')) {
            wp_die('Security check failed');
        }

        $search_handler = new OKS_Search_Handler();
        $results = $search_handler->search($_POST);

        wp_send_json_success($results);
    }
}

// Initialize
OKS_Job_Search_Loader::get_instance();