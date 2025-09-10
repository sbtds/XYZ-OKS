<?php

function oks_theme_setup() {
    add_theme_support('title-tag');

    add_theme_support('post-thumbnails');

    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
}
add_action('after_setup_theme', 'oks_theme_setup');

function oks_enqueue_styles() {
    wp_enqueue_style('oks-style', get_stylesheet_uri(), array(), '1.0.0');

    // Enqueue single job styles for job detail pages
    if (is_singular('job')) {
        wp_enqueue_style(
            'oks-single-job',
            get_template_directory_uri() . '/assets/css/single-job.css',
            array('oks-style'),
            '1.0.0'
        );
    }


    // Enqueue dist styles for all pages
    wp_enqueue_style(
        'oks-dist-style',
        get_template_directory_uri() . '/dist/assets/css/style.css',
        array(),
        '1.0.0'
    );
}

function oks_enqueue_scripts() {
    // Enqueue dist JavaScript
    wp_enqueue_script(
        'oks-bundle',
        get_template_directory_uri() . '/dist/assets/js/bundle.js',
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'oks_enqueue_styles');
add_action('wp_enqueue_scripts', 'oks_enqueue_scripts');

function oks_widgets_init() {
    register_sidebar(array(
        'name'          => 'サイドバー',
        'id'            => 'sidebar-1',
        'description'   => 'サイドバーウィジェットエリア',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'oks_widgets_init');

/**
 * Include CSV Import functionality
 */
if (file_exists(get_template_directory() . '/includes/csv-import/csv-import-loader.php')) {
    require_once get_template_directory() . '/includes/csv-import/csv-import-loader.php';
}

/**
 * Include Job Search functionality
 */
if (file_exists(get_template_directory() . '/includes/search/job-search-loader.php')) {
    require_once get_template_directory() . '/includes/search/job-search-loader.php';
}

/**
 * Include Comment Disable functionality
 */
if (file_exists(get_template_directory() . '/includes/comment-disable/comment-disable-loader.php')) {
    require_once get_template_directory() . '/includes/comment-disable/comment-disable-loader.php';
}