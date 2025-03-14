<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Admin settings page
function wp_rabbit_games_admin_menu() {
    add_options_page(
        'WP Rabbit Games Settings',
        'WP Rabbit Games',
        'manage_options',
        'wp-rabbit-games-settings',
        'wp_rabbit_games_settings_page'
    );
}
add_action('admin_menu', 'wp_rabbit_games_admin_menu');

function wp_rabbit_games_settings_page() {
    ?>
    <div class="wrap">
        <h1>WP Rabbit Games Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('wp_rabbit_games_options');
            do_settings_sections('wp-rabbit-games-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function wp_rabbit_games_register_settings() {
    register_setting('wp_rabbit_games_options', 'wp_rabbit_games_api_key');
    add_settings_section('wp_rabbit_games_main', 'API Settings', null, 'wp-rabbit-games-settings');
    add_settings_field(
        'wp_rabbit_games_api_key',
        'RAWG.io API Key',
        'wp_rabbit_games_api_key_field',
        'wp-rabbit-games-settings',
        'wp_rabbit_games_main'
    );
}
add_action('admin_init', 'wp_rabbit_games_register_settings');

function wp_rabbit_games_api_key_field() {
    $api_key = get_option('wp_rabbit_games_api_key', '');
    echo '<input type="text" name="wp_rabbit_games_api_key" value="' . esc_attr($api_key) . '" class="regular-text">';
}
