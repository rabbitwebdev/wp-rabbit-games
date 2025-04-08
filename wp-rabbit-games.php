<?php
/**
 * Plugin Name: WP Rabbit Games
 * Description: Fetch and display game details from RAWG.io using a custom post type and Gutenberg block.
 * Version: 5.1.0
 * Author: Your Name
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('WP_RABBIT_GAMES_VERSION', '4.0.0');
define('WP_RABBIT_GAMES_API_URL', 'https://api.rawg.io/api/');
define('WP_RABBIT_GAMES_PLUGIN_DIR', plugin_dir_path(__FILE__));
// Include necessary files
include_once WP_RABBIT_GAMES_PLUGIN_DIR . 'includes/cpt-games.php';
include_once WP_RABBIT_GAMES_PLUGIN_DIR . 'includes/api-handler.php';
include_once WP_RABBIT_GAMES_PLUGIN_DIR . 'includes/gutenberg-block.php';
include_once WP_RABBIT_GAMES_PLUGIN_DIR . 'includes/settings.php';
include_once WP_RABBIT_GAMES_PLUGIN_DIR . 'includes/functions.php';

// Register activation hook
function wp_rabbit_games_activate() {
    wp_rabbit_games_register_cpt(); // Ensure CPT is registered before flushing
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'wp_rabbit_games_activate');

// Register deactivation hook
function wp_rabbit_games_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'wp_rabbit_games_deactivate');



function wprg_register_custom_block() {
    $dir = __DIR__;

    // Build paths
    $script_asset_path = "$dir/build/index.js";
    $style_asset_path = "$dir/build/index.css";

    // Register the block editor script
    wp_register_script(
        'wprg-block-editor-script',
        plugins_url( 'build/index.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n' ),
        filemtime( $script_asset_path )
    );

    // Register editor stylesheet
    wp_register_style(
        'wprg-block-editor-style',
        plugins_url( 'build/index.css', __FILE__ ),
        array( 'wp-edit-blocks' ),
        filemtime( $style_asset_path )
    );

    register_block_type( 'wprg/rabbit-game-block', array(
        'editor_script' => 'wprg-block-editor-script',
        'editor_style'  => 'wprg-block-editor-style',
    ) );
}
add_action( 'init', 'wprg_register_custom_block' );


