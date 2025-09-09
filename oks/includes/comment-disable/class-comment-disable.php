<?php
/**
 * OKS Comment Disable Class
 *
 * @package OKS
 * @subpackage Comment_Disable
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('OKS_Comment_Disable')) {
    /**
     * Comment Disable Class
     */
    class OKS_Comment_Disable {
        /**
         * Instance
         *
         * @var OKS_Comment_Disable
         */
        private static $instance = null;

        /**
         * Get instance
         *
         * @return OKS_Comment_Disable
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
        }

        /**
         * Initialize
         */
        public function init() {
            add_action('init', array($this, 'disable_comments_post_types_support'));
            add_action('admin_init', array($this, 'disable_comments_dashboard'));
            add_action('admin_init', array($this, 'disable_comments_admin_menu_redirect'));
            add_action('admin_menu', array($this, 'disable_comments_admin_menu'));
            add_action('wp_loaded', array($this, 'disable_comments_admin_bar'));
            add_filter('comments_open', array($this, 'disable_comments_status'), 20, 2);
            add_filter('pings_open', array($this, 'disable_comments_status'), 20, 2);
            add_filter('comments_array', array($this, 'disable_comments_hide_existing_comments'), 10, 2);
            add_action('wp_head', array($this, 'disable_comments_feed'));
            add_action('redirect_canonical', array($this, 'disable_comments_redirect'), 10, 2);
            add_filter('comment_link', array($this, 'disable_comment_link'), 10, 2);
            add_filter('get_comments_number', array($this, 'disable_comments_number'), 10, 2);
        }

        /**
         * Remove comments support from all post types
         */
        public function disable_comments_post_types_support() {
            $post_types = get_post_types();
            foreach ($post_types as $post_type) {
                if (post_type_supports($post_type, 'comments')) {
                    remove_post_type_support($post_type, 'comments');
                    remove_post_type_support($post_type, 'trackbacks');
                }
            }
        }

        /**
         * Redirect comments pages in dashboard
         */
        public function disable_comments_admin_menu_redirect() {
            global $pagenow;
            if ($pagenow === 'edit-comments.php' || $pagenow === 'options-discussion.php') {
                wp_safe_redirect(admin_url());
                exit;
            }
        }

        /**
         * Remove comments metabox from dashboard
         */
        public function disable_comments_dashboard() {
            remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
        }

        /**
         * Remove comments links from admin menu
         */
        public function disable_comments_admin_menu() {
            remove_menu_page('edit-comments.php');
            remove_submenu_page('options-general.php', 'options-discussion.php');
        }

        /**
         * Remove comments links from admin bar
         */
        public function disable_comments_admin_bar() {
            if (is_admin_bar_showing()) {
                remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
            }
        }

        /**
         * Close comments on all post types
         */
        public function disable_comments_status() {
            return false;
        }

        /**
         * Hide existing comments
         */
        public function disable_comments_hide_existing_comments($comments) {
            $comments = array();
            return $comments;
        }

        /**
         * Remove comments feed
         */
        public function disable_comments_feed() {
            if (is_comment_feed()) {
                wp_die(__('Comments are closed.', 'oks'), '', array('response' => 403));
            }
        }

        /**
         * Prevent redirect to comments
         */
        public function disable_comments_redirect($redirect_url, $requested_url) {
            if (preg_match('/comment-page-/', $requested_url)) {
                $redirect_url = preg_replace('/comment-page-[0-9]+\//', '', $redirect_url);
            }
            return $redirect_url;
        }

        /**
         * Remove comment links
         */
        public function disable_comment_link($link, $comment_id) {
            return '#';
        }

        /**
         * Always return 0 comments
         */
        public function disable_comments_number($count, $post_id) {
            return 0;
        }
    }
}