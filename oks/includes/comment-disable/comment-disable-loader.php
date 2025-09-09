<?php
/**
 * OKS Comment Disable Module Loader
 *
 * @package OKS
 * @subpackage Comment_Disable
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('OKS_Comment_Disable_Loader')) {
    /**
     * Comment Disable Module Loader Class
     */
    class OKS_Comment_Disable_Loader {
        /**
         * Instance
         *
         * @var OKS_Comment_Disable_Loader
         */
        private static $instance = null;

        /**
         * Get instance
         *
         * @return OKS_Comment_Disable_Loader
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
            define('OKS_COMMENT_DISABLE_DIR', get_template_directory() . '/includes/comment-disable/');
            define('OKS_COMMENT_DISABLE_URL', get_template_directory_uri() . '/includes/comment-disable/');
        }

        /**
         * Include required files
         */
        private function includes() {
            require_once OKS_COMMENT_DISABLE_DIR . 'class-comment-disable.php';
        }

        /**
         * Initialize hooks
         */
        private function init_hooks() {
            $comment_disable = OKS_Comment_Disable::get_instance();
            $comment_disable->init();
        }
    }

    OKS_Comment_Disable_Loader::get_instance();
}