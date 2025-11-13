<?php

add_filter('post_row_actions', function($actions, $post) {
    if ($post->post_type === 'faq') {
        unset($actions['view']);
    }
    return $actions;
}, 10, 2);

add_filter('page_row_actions', function($actions, $post) {
    if ($post->post_type === 'faq') {
        unset($actions['view']);
    }
    return $actions;
}, 10, 2);

add_action('template_redirect', function() {
    if (is_singular('faq')) {
        wp_redirect(home_url('/faq/'), 301);
        exit;
    }
});
