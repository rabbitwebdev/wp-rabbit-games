<?php

if (!defined('ABSPATH')) {
    exit;
}

function rb_bootstrap_theme_scripts() {

    // CSS
    // Bootstrap CSS

    wp_enqueue_style('bootstrap-styles', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css', array(), '5.3.7', 'all');  

    // Bootstrap JavaScript

    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.7', true);

}
add_action('wp_enqueue_scripts', 'rb_bootstrap_theme_scripts');


function rabbit_api_game_load_template($template) {
    if (get_query_var('game_slug')) {
        $new_template = plugin_dir_path(__FILE__) . '../templates/single-game-details.php';
        if (file_exists($new_template)) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'rabbit_api_game_load_template');


function rabbit_api_game_rewrite_rule() {
    add_rewrite_rule('game-details/([^/]+)/?$', 'index.php?game_slug=$matches[1]', 'top');
}
add_action('init', 'rabbit_api_game_rewrite_rule');

function rabbit_api_game_query_vars($vars) {
    $vars[] = 'game_slug';
    return $vars;
}
add_filter('query_vars', 'rabbit_api_game_query_vars');