<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


// Register Gutenberg block
function wp_rabbit_games_register_block() {
    wp_register_script(
        'wp-rabbit-games-block',
        plugin_dir_path('block.js', __FILE__),
        array('wp-blocks', 'wp-editor', 'wp-components', 'wp-element'),
        WP_RABBIT_GAMES_VERSION,
        true
    );

    register_block_type('wp-rabbit-games/game-list', array(
        'editor_script' => 'wp-rabbit-games-block',
        'render_callback' => 'wp_rabbit_games_render_block',
    ));
}
add_action('init', 'wp_rabbit_games_register_block');


