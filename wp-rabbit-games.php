<?php
/**
 * Plugin Name: WP Rabbit Games
 * Description: Fetch and display game details from RAWG.io using a custom post type and Gutenberg block.
 * Version: 8.0.0
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


wp_enqueue_script('rawg-js', plugin_dir_url(__FILE__) . 'assets/rawg.js', [], null, true);

wp_localize_script('rawg-js', 'rawgData', [
    'apiKey' => get_option('wp_rabbit_games_api_key'),
]);

register_block_type('wprg/upcoming-games', array(
    'editor_script' => 'wprg-block-editor-script',
    'editor_style'  => 'wprg-block-editor-style',
    'render_callback' => 'wprg_render_upcoming_games_block',
));

function wpgr_register_upcoming_games_block() {
    $block_dir = __DIR__ . '/src/upcoming-games';

    wp_register_script(
        'wpgr-block-editor-script',
        plugins_url('src/upcoming-games/index.js', __FILE__),
        ['wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'],
        filemtime($block_dir . '/index.js')
    );

    wp_register_style(
        'wpgr-block-editor-style',
        plugins_url('src/upcoming-games/index.css', __FILE__),
        ['wp-edit-blocks'],
        filemtime($block_dir . '/index.css')
    );

}
add_action('init', 'wpgr_register_upcoming_games_block');

register_block_type('wpgr/upcoming-games', array(
    'editor_script' => 'wpgr-block-editor-script',
    'editor_style'  => 'wpgr-block-editor-style',
    'render_callback' => 'wpgr_render_upcoming_games_block',
));
/**
 * Renders the Upcoming Games block HTML.
 */
function wprg_render_upcoming_games_block() {
    ob_start();

    $currentYear = date('Y');
    ?>
    <div class="rabbit-game-filter-block">
         <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2>Upcoming Games RG</h2>
                    <div id="rawg-filters">
                        <label for="release-year-filter">
                            Release Year:
                            <select id="release-year-filter">
                                <?php
                                for ($y = $currentYear; $y >= $currentYear - 10; $y--) {
                                    echo "<option value='{$y}'>{$y}</option>";
                                }
                                ?>
                            </select>
                        </label>

                        <label for="platform-select" style="margin-left: 20px;">
                        Platform:
                        <select id="platform-select">
                            <option value="all">All Platforms</option>
                            <!-- Filled via JS -->
                        </select>
                        </label>
                    </div>
                </div>

            </div>
         
                 <div id="upcoming-games" class=" game-cards cards row g-4 row-cols-1 row-cols-md-2 row-cols-sm-1" style="margin-top: 20px;"></div>
              </div>         
</div>
   

   
    <?php

    return ob_get_clean();
}


/**
 * Renders the Upcoming Games block HTML.
 */
function wpgr_render_upcoming_games_block() {
    ob_start();

    $currentYear = date('Y');
    ?>
    <div class="rabbit-game-filter-block">
         <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2>Upcoming Games GR</h2>
                    <?php echo do_shortcode( '[wp_rabbit_genres]' ); ?>
                    <div id="rawg-filters">
                        <label for="release-year-filter">
                            Release Year:
                            <select id="release-year-filter">
                                <?php
                                for ($y = $currentYear; $y >= $currentYear - 10; $y--) {
                                    echo "<option value='{$y}'>{$y}</option>";
                                }
                                ?>
                            </select>
                        </label>

                        <label for="platform-select" style="margin-left: 20px;">
                        Platform:
                        <select id="platform-select">
                            <option value="all">All Platforms</option>
                            <!-- Filled via JS -->
                        </select>
                        </label>
                    </div>
                </div>

            </div>
         
                 <div id="upcoming-games" class=" game-cards cards row g-4 row-cols-1 row-cols-md-2 row-cols-sm-1" style="margin-top: 20px;"></div>
              </div>         
</div>
   

   
    <?php

    return ob_get_clean();
}
