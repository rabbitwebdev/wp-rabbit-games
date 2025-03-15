<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


// API Handler: Fetch game data from RAWG.io
function wp_rabbit_games_fetch_game_data($game_id) {
    $api_key = get_option('wp_rabbit_games_api_key');
    if (!$api_key) {
        return new WP_Error('missing_api_key', 'RAWG.io API key is not set.');
    }

    $url = WP_RABBIT_GAMES_API_URL . "games/{$game_id}?key={$api_key}";
    $response = wp_remote_get($url);
    
    if (is_wp_error($response)) {
        return $response;
    }

    $body = wp_remote_retrieve_body($response);
    return json_decode($body, true);
}

// Shortcode to display game details
function wp_rabbit_games_display_game($atts) {
    $atts = shortcode_atts(['id' => ''], $atts, 'wp_rabbit_game');
    if (!$atts['id']) {
        return 'No game ID provided.';
    }

    $game_data = wp_rabbit_games_fetch_game_data($atts['id']);
    if (is_wp_error($game_data)) {
        return 'Error fetching game data.';
    }

    ob_start();
    ?>
    <div class="wp-rabbit-game">
        <p><?php echo esc_html($game_data['released']); ?></p>
        <h2><?php echo esc_html($game_data['name']); ?></h2>
        <img src="<?php echo esc_url($game_data['background_image']); ?>" alt="<?php echo esc_attr($game_data['name']); ?>">
      
        <p><?php echo esc_html($game_data['description_raw']); ?></p>
          <a class="btn mt-3 mb-3 btn-outline" target="_blank" href="<?php echo esc_url($game_data['website']); ?>" >Website</a>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('wp_rabbit_game', 'wp_rabbit_games_display_game');




// API Handler: Fetch game data from RAWG.io
function wp_rabbit_games_fetch_dev_data($dev_id) {
    $api_key = get_option('wp_rabbit_games_api_key');
    if (!$api_key) {
        return new WP_Error('missing_api_key', 'RAWG.io API key is not set.');
    }

  
    
  

    $api_url = "https://api.rawg.io/api/games?key={$api_key}&dates=2012-10-10,2025-10-10&developers=10";
$response = wp_remote_get($api_url);
$games = json_decode(wp_remote_retrieve_body($response));

   if (is_wp_error($response)) {
        return $response;
    }
}

// Shortcode to display game details
function wp_rabbit_games_display_dev($atts) {
    $atts = shortcode_atts(['id' => ''], $atts, 'wp_rabbit_dev');
    if (!$atts['id']) {
        return 'No game ID provided.';
    }

    $dev_id = $atts['id'];
    if (is_wp_error($dev_id)) {
        return 'Error fetching all games.';
    }

    ob_start();
    ?>
    <div class="wp-rabbit-game row row-cols-2 game-card-group">
          <?php
         $api_key = get_option('wp_rabbit_games_api_key');
              $api_url = "https://api.rawg.io/api/games?key={$api_key}&ordering=-rating&developers={$dev_id}&page_size=62";
$response = wp_remote_get($api_url);
$games = json_decode(wp_remote_retrieve_body($response));
        foreach ($games->results as $game) {
            $game_slug = esc_attr($game->slug); // Get game slug
            $game_name = esc_html($game->name);
            $game_image = esc_url($game->background_image);
             $platforms = [];
                            if (!empty($game->platforms)) {
                                foreach ($game->platforms as $platform) {
                                    if (isset($platform->platform->name)) {
                                        $platforms[] = $platform->platform->name;
                                    }
                                }
                            }
                            $platforms_list = !empty($platforms) ? implode(", ", $platforms) : "No platforms available.";
            $game_url = site_url("/game-details/{$game_slug}/"); // Use slug in the URL
            echo "<div class='game-card col mb-3 text-bg-info  card' style='height:300px'>
                    <a class='game-link' href='{$game_url}'>
                        <img src='{$game_image}' alt='{$game_name}' class='h-100 object-fit-cover card__image'>
                        <div class='card__content'>
                            <h3 class='card__title'>{$game_name}</h3>
                            <p class='post-card__tag'>{$platforms_list}</p>
                              <p class='post-card__tag'> " . ($game->released ?? 'TBA') . "</p>
                        </div>
                    </a>
                </div>";
        }
    ?>
      

    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('wp_rabbit_dev', 'wp_rabbit_games_display_dev');

// Extra Shortcode: Display list of developers
function wp_rabbit_dev_list_extra_shortcode($atts) {
    $api_key = get_option('wp_rabbit_games_api_key');
    if (!$api_key) {
        return 'RAWG.io API key is not set.';
    }

    $url = WP_RABBIT_GAMES_API_URL . "developers?key={$api_key}&page_size=52";
    $response = wp_remote_get($url);
    
    if (is_wp_error($response)) {
        return 'Error fetching developers.';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data['results'])) {
        return 'No developers found.';
    }

    $output = '<ul class="wp-rabbit-developers">';
    foreach ($data['results'] as $developer) {
        $output .= '<li>' . esc_html($developer['name']) . '</li>';
    }
    $output .= '</ul>';

    return $output;
}
add_shortcode('wp_rabbit_extra_dev_names', 'wp_rabbit_dev_list_extra_shortcode');





// Extra Shortcode: Display dropdown of developers and their games
function wp_rabbit_games_extra_shortcode() {
    $api_key = get_option('wp_rabbit_games_api_key');
    if (!$api_key) {
        return 'RAWG.io API key is not set.';
    }

    $url = WP_RABBIT_GAMES_API_URL . "developers?key={$api_key}&page_size=32";
    $response = wp_remote_get($url);
    
    if (is_wp_error($response)) {
        return 'Error fetching developers.';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data['results'])) {
        return 'No developers found.';
    }

    ob_start();
    ?>
    <select class="w-100 mb-4 mt-4 position-relative" id="wp-rabbit-developer-select">
        <option  value="">Select a Developer</option>
        <?php foreach ($data['results'] as $developer): ?>
            <option class="w-100 " value="<?php echo esc_attr($developer['id']); ?>"><?php echo esc_html($developer['name']); ?></option>
        <?php endforeach; ?>
    </select>
    <div id="wp-rabbit-developer-games" class="mt-3 mb-3"></div>

    <script>
    document.getElementById('wp-rabbit-developer-select').addEventListener('change', function() {
        let developerId = this.value;
        let gamesContainer = document.getElementById('wp-rabbit-developer-games');
        gamesContainer.innerHTML = 'Loading games...';

        if (developerId) {
            fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>?action=wp_rabbit_fetch_developer_games&developer_id=' + developerId)
                .then(response => response.text())
                .then(data => gamesContainer.innerHTML = data);
        } else {
            gamesContainer.innerHTML = '';
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('wp_rabbit_extra', 'wp_rabbit_games_extra_shortcode');

// AJAX Handler: Fetch games by developer
function wp_rabbit_fetch_developer_games() {
    $developer_id = isset($_GET['developer_id']) ? sanitize_text_field($_GET['developer_id']) : '';
    $api_key = get_option('wp_rabbit_games_api_key');

    if (!$developer_id || !$api_key) {
        wp_die('Invalid request.');
    }

    $url = WP_RABBIT_GAMES_API_URL . "games?key={$api_key}&developers={$developer_id}&ordering=-rating&page_size=82";
    $response = wp_remote_get($url);
    
    if (is_wp_error($response)) {
        wp_die('Error fetching games.');
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data['results'])) {
        wp_die('No games found.');
    }

    echo '<div class="row g-4 row-cols-md-2 row-cols-sm-1">';
    foreach ($data['results'] as $game) {
         $game_slug = esc_attr($game['slug']);
         $game_url = site_url("/game-details/$game_slug/");
         echo '<div class="col">'; 
        echo '<div class="card rounded-0 bg-dark text-bg-dark" style="height:300px;">'; 
        echo '<a class="h-100 bg-dark dark text-white" href="' . esc_url($game_url) . '">';
        echo '<img class="card-img opacity-50 h-100 rounded-0 object-fit-cover" src="' . esc_url($game['background_image']) . '" alt="' . esc_attr($game['name']) . '">';
        echo '<div class="card-img-overlay">';
        echo  '<h3 class="card-title fs-6 fw-light ">' . esc_html($game['name']) . '</h3>';
        echo '<p class="date">' . esc_html($game['released']) . '</p>';
        echo '</div>';
        echo '</a>';
         echo '</div>';
            echo '</div>';
    }
    echo '</div>';
    wp_die();
}
add_action('wp_ajax_wp_rabbit_fetch_developer_games', 'wp_rabbit_fetch_developer_games');
add_action('wp_ajax_nopriv_wp_rabbit_fetch_developer_games', 'wp_rabbit_fetch_developer_games');



// Extra Shortcode: Display dropdown of gaming platforms
function wp_rabbit_games_platforms_shortcode() {
    $api_key = get_option('wp_rabbit_games_api_key');
    if (!$api_key) {
        return 'RAWG.io API key is not set.';
    }

    $url = WP_RABBIT_GAMES_API_URL . "platforms?key={$api_key}&page_size=32";
    $response = wp_remote_get($url);
    
    if (is_wp_error($response)) {
        return 'Error fetching platforms.';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data['results'])) {
        return 'No platforms found.';
    }

    ob_start();
    ?>
    <select class="w-100 mb-4 mt-4 position-relative" id="wp-rabbit-platform-select">
        <option value="">Select a Platform</option>
        <?php foreach ($data['results'] as $platform): ?>
            <option value="<?php echo esc_attr($platform['id']); ?>"><?php echo esc_html($platform['name']); ?></option>
        <?php endforeach; ?>
    </select>
    <div id="wp-rabbit-platform-games"></div>

    <script>
    document.getElementById('wp-rabbit-platform-select').addEventListener('change', function() {
        let platformId = this.value;
        let gamesContainer = document.getElementById('wp-rabbit-platform-games');
        gamesContainer.innerHTML = 'Loading games...';

        if (platformId) {
            fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>?action=wp_rabbit_fetch_platform_games&platform_id=' + platformId)
                .then(response => response.text())
                .then(data => gamesContainer.innerHTML = data);
        } else {
            gamesContainer.innerHTML = '';
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('wp_rabbit_platforms', 'wp_rabbit_games_platforms_shortcode');

// AJAX Handler: Fetch games by platform
function wp_rabbit_fetch_platform_games() {
    $platform_id = isset($_GET['platform_id']) ? sanitize_text_field($_GET['platform_id']) : '';
    $api_key = get_option('wp_rabbit_games_api_key');

    if (!$platform_id || !$api_key) {
        wp_die('Invalid request.');
    }

    $url = WP_RABBIT_GAMES_API_URL . "games?platforms={$platform_id}&key={$api_key}&ordering=-rating&page_size=72";  
    $response = wp_remote_get($url);
    
    if (is_wp_error($response)) {
        wp_die('Error fetching games.');
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data['results'])) {
        wp_die('No games found.');
    }

      echo '<div class="row g-4 row-cols-md-2 row-cols-sm-1">';
    foreach ($data['results'] as $game) {
          $game_slug = esc_attr($game['slug']);
         $game_url = site_url("/game-details/$game_slug/");
         echo '<div class="col">'; 
        echo '<div class="card rounded-0 bg-dark text-bg-dark" style="height:300px;">'; 
        echo '<a class="h-100 bg-dark dark text-white" href="' . esc_url($game_url) . '">';
        echo '<img class="card-img opacity-50 h-100 rounded-0 object-fit-cover" src="' . esc_url($game['background_image']) . '" alt="' . esc_attr($game['name']) . '">';
        echo '<div class="card-img-overlay">';
        echo  '<h3 class="card-title fs-6 fw-light">' . esc_html($game['name']) . '</h3>';
        echo '<p class="date">' . esc_html($game['released']) . '</p>';
        echo '</div>';
        echo '</a>';
         echo '</div>';
            echo '</div>';
    }
    echo '</div>';
    wp_die();
}
add_action('wp_ajax_wp_rabbit_fetch_platform_games', 'wp_rabbit_fetch_platform_games');
add_action('wp_ajax_nopriv_wp_rabbit_fetch_platform_games', 'wp_rabbit_fetch_platform_games');




// Extra Shortcode: Display dropdown of genres
function wp_rabbit_games_genres_shortcode() {
    $api_key = get_option('wp_rabbit_games_api_key');
    if (!$api_key) {
        return 'RAWG.io API key is not set.';
    }

    $url = WP_RABBIT_GAMES_API_URL . "genres?key={$api_key}&page_size=32";
    $response = wp_remote_get($url);
    
    if (is_wp_error($response)) {
        return 'Error fetching genres.';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data['results'])) {
        return 'No genres found.';
    }

    ob_start();
    ?>
    <select class="w-100 mb-4 mt-4 position-relative" id="wp-rabbit-genre-select">
        <option value="">Select a Genre</option>
        <?php foreach ($data['results'] as $genre): ?>
            <option value="<?php echo esc_attr($genre['id']); ?>"><?php echo esc_html($genre['name']); ?></option>
        <?php endforeach; ?>
    </select>
    <div id="wp-rabbit-genre-games"></div>

    <script>
    document.getElementById('wp-rabbit-genre-select').addEventListener('change', function() {
        let genreId = this.value;
        let gamesContainer = document.getElementById('wp-rabbit-genre-games');
        gamesContainer.innerHTML = 'Loading games...';

        if (genreId) {
            fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>?action=wp_rabbit_fetch_genre_games&genre_id=' + genreId)
                .then(response => response.text())
                .then(data => gamesContainer.innerHTML = data);
        } else {
            gamesContainer.innerHTML = '';
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('wp_rabbit_genres', 'wp_rabbit_games_genres_shortcode');

// AJAX Handler: Fetch games by genre
function wp_rabbit_fetch_genre_games() {
    $genre_id = isset($_GET['genre_id']) ? sanitize_text_field($_GET['genre_id']) : '';
    $api_key = get_option('wp_rabbit_games_api_key');

    if (!$genre_id || !$api_key) {
        wp_die('Invalid request.');
    }

    $url = WP_RABBIT_GAMES_API_URL . "games?genres={$genre_id}&key={$api_key}&page_size=82";
    $response = wp_remote_get($url);
    
    if (is_wp_error($response)) {
        wp_die('Error fetching games.');
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (empty($data['results'])) {
        wp_die('No games found.');
    }

       echo '<div class="row g-4 row-cols-md-2 row-cols-sm-1">';
    foreach ($data['results'] as $game) {
          $game_slug = esc_attr($game['slug']);
         $game_url = site_url("/game-details/$game_slug/");
         echo '<div class="col">'; 
        echo '<div class="card rounded-0 bg-dark text-bg-dark" style="height:300px;">'; 
        echo '<a class="h-100 bg-dark dark bg-dark text-white" href="' . esc_url($game_url) . '">';
        echo '<img class="card-img opacity-50 h-100 rounded-0 object-fit-cover" src="' . esc_url($game['background_image']) . '" alt="' . esc_attr($game['name']) . '">';
        echo '<div class="card-img-overlay">';
        echo  '<h3 class="card-title fs-6 fw-light">' . esc_html($game['name']) . '</h3>';
        echo '<p class="date">' . esc_html($game['released']) . '</p>';
        echo '</div>';
        echo '</a>';
         echo '</div>';
            echo '</div>';
    }
    echo '</div>';
    wp_die();
}
add_action('wp_ajax_wp_rabbit_fetch_genre_games', 'wp_rabbit_fetch_genre_games');
add_action('wp_ajax_nopriv_wp_rabbit_fetch_genre_games', 'wp_rabbit_fetch_genre_games');
