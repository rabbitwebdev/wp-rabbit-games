<?php
/**
 * Plugin Name: WP Rabbit Games
 * Plugin URI: https://dev.rabbitwebdesign.co.uk/wp-rabbit-games
 * Author URI: https://rabbitwebdesign.co.uk
 * Description: Fetch and display game details from RAWG.io using a custom post type and Gutenberg block.
 * Version: 10.1.0
 * Author: P York
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
        'render_callback' => 'myplugin_render_select_game_block',
        'attributes' => array(
            'selectGame' => array(
                'type' => 'string',
                'default' => 'wp_rabbit_genres',
            ),
            'buttonText' => array(
                'type' => 'string',
                'default' => 'View Game',
            ),
             'contentText' => array(
                'type' => 'string',
                'default' => 'text',
            ),
        ),
    ) );
}
add_action( 'init', 'wprg_register_custom_block' );


wp_enqueue_script('rawg-js', plugin_dir_url(__FILE__) . 'assets/rawg.js', [], null, true);

wp_localize_script('rawg-js', 'rawgData', [
    'apiKey' => get_option('wp_rabbit_games_api_key'),
]);

function myplugin_render_select_game_block($attributes) {
      if (empty($attributes['selectGame'])) {
        return '';
    }

     if (empty($attributes['buttonText'])) {
        return '';
    }

      if (empty($attributes['contentText'])) {
        return '';
    }

    $type = esc_attr($attributes['selectGame']);
   $buttontext = esc_attr($attributes['buttonText']);
     $contenttext = esc_attr($attributes['contentText']);

    // Wrap the shortcode output in custom HTML
    $shortcode_output = do_shortcode('[' . $type . ']');

    ob_start();
    ?>
    <section class="custom-game-block-wrapper  section-block  bg-primary theme-light text-white pt-4 pb-4 mt-0  mb-0">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-uppercase">  <?php echo esc_html($contenttext); ?></h2>
                    <?php echo esc_html($buttontext); ?>
                   
                </div>
            </div>
        <div class="game-shortcode-output">
            <?php echo $shortcode_output; ?>
        </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}


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
    <div class="rabbit-game-filter-block pt-5 pb-5 bg-primary theme-light text-white section-block">
         <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2>Upcoming Games RG</h2>
                    <div id="rawg-filters">
                        <label for="release-year-filter">
                            Release Year:
                            <select id="release-year-filter">
                                <?php
                                for ($y = $currentYear; $y >= $currentYear - 15; $y--) {
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
                           <label for="developer-select" style="margin-left: 20px;">
    Developer:
    <select id="developer-select">
        <option value="all">All Developers</option>
        <!-- Filled by JS -->
    </select>
</label>
                    </div>
                </div>

            </div>
         
                 <div id="upcoming-games" class=" game-cards cards row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-sm-1" style="margin-top: 20px;"></div>
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
    <div class="rabbit-game-filter-block pt-5 pb-5">
         <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2>Upcoming Games GR</h2>
                    <div id="rawg-filters">
                        <label for="release-year-filter">
                            Release Year:
                            <select id="release-year-filter">
                                <?php
                                for ($y = $currentYear; $y >= $currentYear - 15; $y--) {
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

                        <label for="developer-select" style="margin-left: 20px;">
    Developer:
    <select id="developer-select">
        <option value="all">All Developers</option>
        <!-- Filled by JS -->
    </select>
</label>
                    </div>
                </div>

            </div>
         
                 <div id="upcoming-games" class=" game-cards cards row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-sm-1" style="margin-top: 20px;"></div>
              </div>         
</div>
   

   
    <?php

    return ob_get_clean();
}
