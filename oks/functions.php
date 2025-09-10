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

/**
 * 投稿ラベルを「お役立ち情報」に変更（管理画面のみ）
 */
function oks_change_post_labels() {
    // 管理画面でのみ実行
    if (!is_admin()) {
        return;
    }
    
    global $menu, $submenu;
    
    // メインメニューのラベルを変更
    foreach ($menu as $key => $item) {
        if ($item[2] === 'edit.php') {
            $menu[$key][0] = 'お役立ち情報';
            break;
        }
    }
    
    // サブメニューのラベルを変更
    if (isset($submenu['edit.php'])) {
        foreach ($submenu['edit.php'] as $key => $item) {
            if ($item[2] === 'edit.php') {
                $submenu['edit.php'][$key][0] = 'お役立ち情報一覧';
            } elseif ($item[2] === 'post-new.php') {
                $submenu['edit.php'][$key][0] = '新規追加';
            }
        }
    }
}
add_action('admin_menu', 'oks_change_post_labels');

/**
 * 投稿タイプのラベルを変更（管理画面のみ）
 */
function oks_change_post_object_labels() {
    // 管理画面でのみ実行
    if (!is_admin()) {
        return;
    }
    
    global $wp_post_types;
    
    if (isset($wp_post_types['post'])) {
        $labels = &$wp_post_types['post']->labels;
        $labels->name = 'お役立ち情報';
        $labels->singular_name = 'お役立ち情報';
        $labels->add_new = '新規追加';
        $labels->add_new_item = 'お役立ち情報を追加';
        $labels->edit_item = 'お役立ち情報を編集';
        $labels->new_item = '新しいお役立ち情報';
        $labels->view_item = 'お役立ち情報を表示';
        $labels->search_items = 'お役立ち情報を検索';
        $labels->not_found = 'お役立ち情報が見つかりません';
        $labels->not_found_in_trash = 'ゴミ箱にお役立ち情報が見つかりません';
        $labels->all_items = 'すべてのお役立ち情報';
        $labels->menu_name = 'お役立ち情報';
        $labels->name_admin_bar = 'お役立ち情報';
    }
}
add_action('admin_init', 'oks_change_post_object_labels');

/**
 * 投稿の表示名を変更（フロントエンドでも）
 */
function oks_change_post_type_labels() {
    global $wp_post_types;
    
    if (isset($wp_post_types['post'])) {
        // 投稿タイプの表示名を変更
        $wp_post_types['post']->label = 'お役立ち情報';
        $wp_post_types['post']->labels->name = 'お役立ち情報';
        $wp_post_types['post']->labels->singular_name = 'お役立ち情報';
    }
}
add_action('wp_loaded', 'oks_change_post_type_labels');

/**
 * 投稿のみにGutenbergエディタスタイルを適用
 */
function oks_enqueue_post_editor_styles() {
    global $typenow;
    
    // 投稿の編集画面のみ
    if ($typenow === 'post') {
        $custom_css = '
        .editor-styles-wrapper h2,
        .editor-styles-wrapper h3,
        .editor-styles-wrapper h4,
        .editor-styles-wrapper h5,
        .editor-styles-wrapper h6 {
            position: relative !important;
            color: #c81b21 !important;
            font-weight: bold !important;
            padding-left: 26px !important;
            margin-top: 10px !important;
            padding-top: 10px !important;
        }
        
        .editor-styles-wrapper h2::before,
        .editor-styles-wrapper h3::before,
        .editor-styles-wrapper h4::before,
        .editor-styles-wrapper h5::before,
        .editor-styles-wrapper h6::before {
            content: "" !important;
            display: inline-block !important;
            width: 0 !important;
            height: 0 !important;
            position: absolute !important;
            left: 0 !important;
            top: 0.6em !important;
            border-style: solid !important;
            border-width: 5px 0 5px 9px !important;
            border-color: transparent transparent transparent #c81b21 !important;
            vertical-align: middle !important;
        }
        
        .editor-styles-wrapper h2 {
            font-size: 32px !important;
        }
        
        .editor-styles-wrapper h2::before {
            border-width: 9px 0 9px 12px !important;
        }
        
        .editor-styles-wrapper h3 {
            font-size: 28px !important;
        }
        
        .editor-styles-wrapper h3::before {
            border-width: 9px 0 9px 12px !important;
        }
        
        .editor-styles-wrapper h4 {
            font-size: 24px !important;
        }
        
        .editor-styles-wrapper h4::before {
            border-width: 9px 0 9px 12px !important;
        }
        
        .editor-styles-wrapper h5,
        .editor-styles-wrapper h6 {
            font-size: 18px !important;
        }
        
        .editor-styles-wrapper h5::before,
        .editor-styles-wrapper h6::before {
            margin-top: -3px !important;
            margin-right: 16px !important;
            border-width: 9px 0 9px 12px !important;
        }
        
        .editor-styles-wrapper img {
            width: 100% !important;
            height: auto !important;
        }
        
        @media (min-width: 768px) {
            .editor-styles-wrapper h2::before,
            .editor-styles-wrapper h3::before,
            .editor-styles-wrapper h4::before,
            .editor-styles-wrapper h5::before,
            .editor-styles-wrapper h6::before {
                top: 0.8em !important;
            }
        }
        ';
        
        // インラインCSSとして出力
        wp_add_inline_style('wp-block-editor', $custom_css);
    }
}
add_action('enqueue_block_editor_assets', 'oks_enqueue_post_editor_styles');

/**
 * コンテンツ内のh2タグにIDを自動付与（ページ内リンク用）
 */
function oks_add_id_to_h2_tags($content) {
    // 投稿詳細ページでのみ実行
    if (!is_singular('post')) {
        return $content;
    }
    
    // h2タグを検索してIDを付与
    $pattern = '/<h2([^>]*)>(.*?)<\/h2>/i';
    $counter = 1;
    
    $content = preg_replace_callback($pattern, function($matches) use (&$counter) {
        $attributes = $matches[1];
        $heading_text = $matches[2];
        
        // 既にIDがある場合はスキップ
        if (preg_match('/id=["\']/', $attributes)) {
            return $matches[0];
        }
        
        // IDを生成
        $id = 'heading-' . $counter;
        $counter++;
        
        // IDを追加
        return '<h2' . $attributes . ' id="' . $id . '">' . $heading_text . '</h2>';
    }, $content);
    
    return $content;
}
add_filter('the_content', 'oks_add_id_to_h2_tags');