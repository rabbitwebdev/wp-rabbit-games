<?php

if (!defined('ABSPATH')) {
    exit;
}


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