<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


// Register Custom Post Type for Games
function wp_rabbit_games_register_cpt() {
    $labels = array(
        'name' => 'Games',
        'singular_name' => 'Game',
        'menu_name' => 'WP Rabbit Games',
        'name_admin_bar' => 'Game',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Game',
        'new_item' => 'New Game',
        'edit_item' => 'Edit Game',
        'view_item' => 'View Game',
        'all_items' => 'All Games',
        'search_items' => 'Search Games',
        'not_found' => 'No games found.',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_position' => 5,
        'menu_icon' => 'dashicons-games',
        'show_in_rest' => true,
    );

    register_post_type('wp_rabbit_games', $args);
}
add_action('init', 'wp_rabbit_games_register_cpt');