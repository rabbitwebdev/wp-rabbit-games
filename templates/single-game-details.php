<?php
/**
 * Template Name: Single Game Details
 * Description: Displays game details using RAWG API with slug.
 */

get_header(); 

 $api_key = get_option('wp_rabbit_games_api_key');

 // Get game slug from URL
$game_slug = get_query_var('game_slug');

if (!$game_slug) {
    echo "<p>No game selected.</p>";
    get_footer();
    exit;
}

// Fetch game details using slug
$api_url = "https://api.rawg.io/api/games/{$game_slug}?key={$api_key}";
$response = wp_remote_get($api_url);
$game_details = json_decode(wp_remote_retrieve_body($response));

if (!$game_details || isset($game_details->detail)) {
    echo "<p>Game not found.</p>";
    get_footer();
    exit;
}

$title = esc_html($game_details->name);
$description = isset($game_details->description) ? wp_kses_post($game_details->description) : 'No description available.';
$background_image = isset($game_details->background_image) ? esc_url($game_details->background_image) : '';
$rating = isset($game_details->rating) ? floatval($game_details->rating) : 0; // Convert to float
$ratings_count = isset($game_details->ratings_count) ? intval($game_details->ratings_count) : 0; // Convert to integer
$released = isset($game_details->released) ? date('F j, Y', strtotime($game_details->released)) : 'No release date available.';
$website = isset($game_details->website) ? esc_url($game_details->website) : 'No website available.';
$metacritic = isset($game_details->metacritic) ? intval($game_details->metacritic) : '';
// Fetch screenshots
$screenshots_url = "https://api.rawg.io/api/games/{$game_slug}/screenshots?key={$api_key}";
$screenshots_response = wp_remote_get($screenshots_url);
$screenshots_data = json_decode(wp_remote_retrieve_body($screenshots_response));


// Get platforms
$platforms = [];
if (!empty($game_details->platforms)) {
    foreach ($game_details->platforms as $platform) {
        $platforms[] = esc_html($platform->platform->name);
    }
}
$platforms_list = !empty($platforms) ? implode(", ", $platforms) : "No platforms available.";

// Get background image
$background_image = !empty($game_details->background_image) ? esc_url($game_details->background_image) : "No image available.";

// Check if PlayStation 4 is in the platforms list
if (in_array("PlayStation 4", $platforms)) {
    $psfour_logo = "/images/logo-ps4.png"; // Replace with your actual image URL
}
if (in_array("Nintendo Switch", $platforms)) {
    $nintendo_logo = "/images/nintendo-switch-logo.png"; // Replace with your actual image URL
}
if (in_array("Xbox One", $platforms)) {
    $xbox_one_logo = "/images/logo-ps4.png"; // Replace with your actual image URL
}
if (in_array("PlayStation 5", $platforms)) {
    $ps_5_logo = "/images/PS5.png"; // Replace with your actual image URL
}
if (in_array("PlayStation 3", $platforms)) {
    $ps_3_logo = "/images/PS5.png"; // Replace with your actual image URL
}
if (in_array("PC", $platforms)) {
    $pc_logo = "/images/logo-ps4.png"; // Replace with your actual image URL
}
if (in_array("Xbox Series S/X", $platforms)) {
    $xbox_series_logo = "/images/logo-ps4.png"; // Replace with your actual image URL
}
if (in_array("PS Vita", $platforms)) {
    $ps_vita_logo = "/images/logo-ps4.png"; // Replace with your actual image URL
}

if (in_array("iOS", $platforms)) {
    $ios_logo = "/images/logo-ps4.png"; // Replace with your actual image URL
}

if (in_array("Android", $platforms)) {
    $android_logo = "/images/logo-ps4.png"; // Replace with your actual image URL
}

?>

        <div class="single-game-details bg-primary dark text-white">
  <?php if ($background_image): ?>
        <div class="game-banner position-relative bg-primary  dark" style="height: 50vh;" >
               <img class="game-banner-img vh-100 h-100 opacity-50 object-fit-cover img-scroll-hero" src="<?php echo $background_image; ?>"  alt="British Esports <?php echo $title; ?>" /> 
             
  <h1 class="game-title position-absolute p-4 top-50 start-0"><?php echo $title; ?></h1>
        </div>
    <?php endif; ?>
        <div class="container-fluid">
            <div class="row ">
                <div class="col-md-12  col-lg-6">
                    <div class="game-details  bg-primary  shadow-lg position-relative dark p-3">
                          <h2 style="text-decoration: underline;
  text-decoration-thickness: 2px;
  text-underline-offset: 5px;
  width: fit-content;
  padding-right: 40px;" class="fw-bold mt-4  fs-4 mb-4  position-relative"><?php echo $title; ?> 
  <?php if(!empty($metacritic)) { ?>
    <span class="position-absolute top-0 p-3 start-100 translate-middle badge rounded-pill bg-success"> 

        <?php echo $metacritic ; ?>
    </span> 
         <?php  } ?></h2>
                               <?php if ($rating > 4.5) { ?>
 <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
 <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
 <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
 <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
     <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
<?php } elseif ($rating > 4 ) { ?> 
 <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
 <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
 <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
     <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
<?php  } elseif ($rating > 3 ) { ?> 
     <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
 <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
     <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
    
    <?php } elseif ($rating > 2 ) { ?>  
         <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
     <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
        
        <?php } else { ?> 
    
     <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="red" class="bi bi-star-fill" viewBox="0 0 16 16">
  <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
</svg>
            
            <?php } ?>
                      
                         <div class="platform-wrp d-flex justify-content-between align-items-center mt-4 mb-4">
       <?php if ($ps_3_logo) { ?>
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-playstation" viewBox="0 0 16 16">
  <path d="M15.858 11.451c-.313.395-1.079.676-1.079.676l-5.696 2.046v-1.509l4.192-1.493c.476-.17.549-.412.162-.538-.386-.127-1.085-.09-1.56.08l-2.794.984v-1.566l.161-.054s.807-.286 1.942-.412c1.135-.125 2.525.017 3.616.43 1.23.39 1.368.962 1.056 1.356M9.625 8.883v-3.86c0-.453-.083-.87-.508-.988-.326-.105-.528.198-.528.65v9.664l-2.606-.827V2c1.108.206 2.722.692 3.59.985 2.207.757 2.955 1.7 2.955 3.825 0 2.071-1.278 2.856-2.903 2.072Zm-8.424 3.625C-.061 12.15-.271 11.41.304 10.984c.532-.394 1.436-.69 1.436-.69l3.737-1.33v1.515l-2.69.963c-.474.17-.547.411-.161.538.386.126 1.085.09 1.56-.08l1.29-.469v1.356l-.257.043a8.45 8.45 0 0 1-4.018-.323Z"/>
</svg>
                   
        <?php } ?>
             <?php if ($psfour_logo) { ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-playstation" viewBox="0 0 16 16">
  <path d="M15.858 11.451c-.313.395-1.079.676-1.079.676l-5.696 2.046v-1.509l4.192-1.493c.476-.17.549-.412.162-.538-.386-.127-1.085-.09-1.56.08l-2.794.984v-1.566l.161-.054s.807-.286 1.942-.412c1.135-.125 2.525.017 3.616.43 1.23.39 1.368.962 1.056 1.356M9.625 8.883v-3.86c0-.453-.083-.87-.508-.988-.326-.105-.528.198-.528.65v9.664l-2.606-.827V2c1.108.206 2.722.692 3.59.985 2.207.757 2.955 1.7 2.955 3.825 0 2.071-1.278 2.856-2.903 2.072Zm-8.424 3.625C-.061 12.15-.271 11.41.304 10.984c.532-.394 1.436-.69 1.436-.69l3.737-1.33v1.515l-2.69.963c-.474.17-.547.411-.161.538.386.126 1.085.09 1.56-.08l1.29-.469v1.356l-.257.043a8.45 8.45 0 0 1-4.018-.323Z"/>
</svg>
                   
        <?php } ?>
         <?php if ($ps_5_logo) { ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-playstation" viewBox="0 0 16 16">
  <path d="M15.858 11.451c-.313.395-1.079.676-1.079.676l-5.696 2.046v-1.509l4.192-1.493c.476-.17.549-.412.162-.538-.386-.127-1.085-.09-1.56.08l-2.794.984v-1.566l.161-.054s.807-.286 1.942-.412c1.135-.125 2.525.017 3.616.43 1.23.39 1.368.962 1.056 1.356M9.625 8.883v-3.86c0-.453-.083-.87-.508-.988-.326-.105-.528.198-.528.65v9.664l-2.606-.827V2c1.108.206 2.722.692 3.59.985 2.207.757 2.955 1.7 2.955 3.825 0 2.071-1.278 2.856-2.903 2.072Zm-8.424 3.625C-.061 12.15-.271 11.41.304 10.984c.532-.394 1.436-.69 1.436-.69l3.737-1.33v1.515l-2.69.963c-.474.17-.547.411-.161.538.386.126 1.085.09 1.56-.08l1.29-.469v1.356l-.257.043a8.45 8.45 0 0 1-4.018-.323Z"/>
</svg>
                   
        <?php } ?>
         
          <?php if ($xbox_one_logo) { ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-xbox" viewBox="0 0 16 16">
  <path d="M7.202 15.967a8 8 0 0 1-3.552-1.26c-.898-.585-1.101-.826-1.101-1.306 0-.965 1.062-2.656 2.879-4.583C6.459 7.723 7.897 6.44 8.052 6.475c.302.068 2.718 2.423 3.622 3.531 1.43 1.753 2.088 3.189 1.754 3.829-.254.486-1.83 1.437-2.987 1.802-.954.301-2.207.429-3.239.33m-5.866-3.57C.589 11.253.212 10.127.03 8.497c-.06-.539-.038-.846.137-1.95.218-1.377 1.002-2.97 1.945-3.95.401-.417.437-.427.926-.263.595.2 1.23.638 2.213 1.528l.574.519-.313.385C4.056 6.553 2.52 9.086 1.94 10.653c-.315.852-.442 1.707-.306 2.063.091.24.007.15-.3-.319Zm13.101.195c.074-.36-.019-1.02-.238-1.687-.473-1.443-2.055-4.128-3.508-5.953l-.457-.575.494-.454c.646-.593 1.095-.948 1.58-1.25.381-.237.927-.448 1.161-.448.145 0 .654.528 1.065 1.104a8.4 8.4 0 0 1 1.343 3.102c.153.728.166 2.286.024 3.012a9.5 9.5 0 0 1-.6 1.893c-.179.393-.624 1.156-.82 1.404-.1.128-.1.127-.043-.148ZM7.335 1.952c-.67-.34-1.704-.705-2.276-.803a4 4 0 0 0-.759-.043c-.471.024-.45 0 .306-.358A7.8 7.8 0 0 1 6.47.128c.8-.169 2.306-.17 3.094-.005.85.18 1.853.552 2.418.9l.168.103-.385-.02c-.766-.038-1.88.27-3.078.853-.361.176-.676.316-.699.312a12 12 0 0 1-.654-.319Z"/>
</svg>
                    
        <?php } ?>
         <?php if ($xbox_series_logo) { ?>
                 <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-xbox" viewBox="0 0 16 16">
  <path d="M7.202 15.967a8 8 0 0 1-3.552-1.26c-.898-.585-1.101-.826-1.101-1.306 0-.965 1.062-2.656 2.879-4.583C6.459 7.723 7.897 6.44 8.052 6.475c.302.068 2.718 2.423 3.622 3.531 1.43 1.753 2.088 3.189 1.754 3.829-.254.486-1.83 1.437-2.987 1.802-.954.301-2.207.429-3.239.33m-5.866-3.57C.589 11.253.212 10.127.03 8.497c-.06-.539-.038-.846.137-1.95.218-1.377 1.002-2.97 1.945-3.95.401-.417.437-.427.926-.263.595.2 1.23.638 2.213 1.528l.574.519-.313.385C4.056 6.553 2.52 9.086 1.94 10.653c-.315.852-.442 1.707-.306 2.063.091.24.007.15-.3-.319Zm13.101.195c.074-.36-.019-1.02-.238-1.687-.473-1.443-2.055-4.128-3.508-5.953l-.457-.575.494-.454c.646-.593 1.095-.948 1.58-1.25.381-.237.927-.448 1.161-.448.145 0 .654.528 1.065 1.104a8.4 8.4 0 0 1 1.343 3.102c.153.728.166 2.286.024 3.012a9.5 9.5 0 0 1-.6 1.893c-.179.393-.624 1.156-.82 1.404-.1.128-.1.127-.043-.148ZM7.335 1.952c-.67-.34-1.704-.705-2.276-.803a4 4 0 0 0-.759-.043c-.471.024-.45 0 .306-.358A7.8 7.8 0 0 1 6.47.128c.8-.169 2.306-.17 3.094-.005.85.18 1.853.552 2.418.9l.168.103-.385-.02c-.766-.038-1.88.27-3.078.853-.361.176-.676.316-.699.312a12 12 0 0 1-.654-.319Z"/>
</svg>
                   
        <?php } ?>

         <?php if ($nintendo_logo) { ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-nintendo-switch" viewBox="0 0 16 16">
  <path d="M9.34 8.005c0-4.38.01-7.972.023-7.982C9.373.01 10.036 0 10.831 0c1.153 0 1.51.01 1.743.05 1.73.298 3.045 1.6 3.373 3.326.046.242.053.809.053 4.61 0 4.06.005 4.537-.123 4.976-.022.076-.048.15-.08.242a4.14 4.14 0 0 1-3.426 2.767c-.317.033-2.889.046-2.978.013-.05-.02-.053-.752-.053-7.979m4.675.269a1.62 1.62 0 0 0-1.113-1.034 1.61 1.61 0 0 0-1.938 1.073 1.9 1.9 0 0 0-.014.935 1.63 1.63 0 0 0 1.952 1.107c.51-.136.908-.504 1.11-1.028.11-.285.113-.742.003-1.053M3.71 3.317c-.208.04-.526.199-.695.348-.348.301-.52.729-.494 1.232.013.262.03.332.136.544.155.321.39.556.712.715.222.11.278.123.567.133.261.01.354 0 .53-.06.719-.242 1.153-.94 1.03-1.656-.142-.852-.95-1.422-1.786-1.256"/>
  <path d="M3.425.053a4.14 4.14 0 0 0-3.28 3.015C0 3.628-.01 3.956.005 8.3c.01 3.99.014 4.082.08 4.39.368 1.66 1.548 2.844 3.224 3.235.22.05.497.06 2.29.07 1.856.012 2.048.009 2.097-.04.05-.05.053-.69.053-7.94 0-5.374-.01-7.906-.033-7.952-.033-.06-.09-.063-2.03-.06-1.578.004-2.052.014-2.26.05Zm3 14.665-1.35-.016c-1.242-.013-1.375-.02-1.623-.083a2.81 2.81 0 0 1-2.08-2.167c-.074-.335-.074-8.579-.004-8.907a2.85 2.85 0 0 1 1.716-2.05c.438-.176.64-.196 2.058-.2l1.282-.003v13.426Z"/>
</svg>
                    
        <?php } ?>
         
          <?php if ($pc_logo) { ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-pc-display" viewBox="0 0 16 16">
  <path d="M8 1a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1zm1 13.5a.5.5 0 1 0 1 0 .5.5 0 0 0-1 0m2 0a.5.5 0 1 0 1 0 .5.5 0 0 0-1 0M9.5 1a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM9 3.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 0-1h-5a.5.5 0 0 0-.5.5M1.5 2A1.5 1.5 0 0 0 0 3.5v7A1.5 1.5 0 0 0 1.5 12H6v2h-.5a.5.5 0 0 0 0 1H7v-4H1.5a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5H7V2z"/>
</svg>
                   
        <?php } ?>
         
          <?php if ($ps_vita_logo) { ?>
                    <img class="platform-logo" width="30" height="30" style="width:25px; height:25px; object-fit:contain;" src="<?php echo plugins_url('/images/mobile.png', __FILE__); ?>"  alt="" /> 
        <?php } ?>
         <?php if ($ios_logo) { ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-apple" viewBox="0 0 16 16">
  <path d="M11.182.008C11.148-.03 9.923.023 8.857 1.18c-1.066 1.156-.902 2.482-.878 2.516s1.52.087 2.475-1.258.762-2.391.728-2.43m3.314 11.733c-.048-.096-2.325-1.234-2.113-3.422s1.675-2.789 1.698-2.854-.597-.79-1.254-1.157a3.7 3.7 0 0 0-1.563-.434c-.108-.003-.483-.095-1.254.116-.508.139-1.653.589-1.968.607-.316.018-1.256-.522-2.267-.665-.647-.125-1.333.131-1.824.328-.49.196-1.422.754-2.074 2.237-.652 1.482-.311 3.83-.067 4.56s.625 1.924 1.273 2.796c.576.984 1.34 1.667 1.659 1.899s1.219.386 1.843.067c.502-.308 1.408-.485 1.766-.472.357.013 1.061.154 1.782.539.571.197 1.111.115 1.652-.105.541-.221 1.324-1.059 2.238-2.758q.52-1.185.473-1.282"/>
  <path d="M11.182.008C11.148-.03 9.923.023 8.857 1.18c-1.066 1.156-.902 2.482-.878 2.516s1.52.087 2.475-1.258.762-2.391.728-2.43m3.314 11.733c-.048-.096-2.325-1.234-2.113-3.422s1.675-2.789 1.698-2.854-.597-.79-1.254-1.157a3.7 3.7 0 0 0-1.563-.434c-.108-.003-.483-.095-1.254.116-.508.139-1.653.589-1.968.607-.316.018-1.256-.522-2.267-.665-.647-.125-1.333.131-1.824.328-.49.196-1.422.754-2.074 2.237-.652 1.482-.311 3.83-.067 4.56s.625 1.924 1.273 2.796c.576.984 1.34 1.667 1.659 1.899s1.219.386 1.843.067c.502-.308 1.408-.485 1.766-.472.357.013 1.061.154 1.782.539.571.197 1.111.115 1.652-.105.541-.221 1.324-1.059 2.238-2.758q.52-1.185.473-1.282"/>
</svg>
                    
        <?php } ?>
         <?php if ($android_logo) { ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-android2" viewBox="0 0 16 16">
  <path d="m10.213 1.471.691-1.26q.069-.124-.048-.192-.128-.057-.195.058l-.7 1.27A4.8 4.8 0 0 0 8.005.941q-1.032 0-1.956.404l-.7-1.27Q5.281-.037 5.154.02q-.117.069-.049.193l.691 1.259a4.25 4.25 0 0 0-1.673 1.476A3.7 3.7 0 0 0 3.5 5.02h9q0-1.125-.623-2.072a4.27 4.27 0 0 0-1.664-1.476ZM6.22 3.303a.37.37 0 0 1-.267.11.35.35 0 0 1-.263-.11.37.37 0 0 1-.107-.264.37.37 0 0 1 .107-.265.35.35 0 0 1 .263-.11q.155 0 .267.11a.36.36 0 0 1 .112.265.36.36 0 0 1-.112.264m4.101 0a.35.35 0 0 1-.262.11.37.37 0 0 1-.268-.11.36.36 0 0 1-.112-.264q0-.154.112-.265a.37.37 0 0 1 .268-.11q.155 0 .262.11a.37.37 0 0 1 .107.265q0 .153-.107.264M3.5 11.77q0 .441.311.75.311.306.76.307h.758l.01 2.182q0 .414.292.703a.96.96 0 0 0 .7.288.97.97 0 0 0 .71-.288.95.95 0 0 0 .292-.703v-2.182h1.343v2.182q0 .414.292.703a.97.97 0 0 0 .71.288.97.97 0 0 0 .71-.288.95.95 0 0 0 .292-.703v-2.182h.76q.436 0 .749-.308.31-.307.311-.75V5.365h-9zm10.495-6.587a.98.98 0 0 0-.702.278.9.9 0 0 0-.293.685v4.063q0 .406.293.69a.97.97 0 0 0 .702.284q.42 0 .712-.284a.92.92 0 0 0 .293-.69V6.146a.9.9 0 0 0-.293-.685 1 1 0 0 0-.712-.278m-12.702.283a1 1 0 0 1 .712-.283q.41 0 .702.283a.9.9 0 0 1 .293.68v4.063a.93.93 0 0 1-.288.69.97.97 0 0 1-.707.284 1 1 0 0 1-.712-.284.92.92 0 0 1-.293-.69V6.146q0-.396.293-.68"/>
</svg>
                  
        <?php } ?>
    </div>
                        <div class="lead mt-4  mb-4"><?php echo $description; ?></div>
           
                    </div>
                </div>
                         <?php // Check if screenshots exist
              if (!empty($screenshots_data->results)) {
                echo '<div class="col-lg-5 offset-lg-1 ">';
                  echo '<div class="game-screenshots d-flex flex-wrap shadow-bg justify-content-center position-sticky top-0 p-0">';
                  foreach ($screenshots_data->results as $screenshot) {
                      echo '<img class="object-fit-cover  " src="' . esc_url($screenshot->image) . '" alt="Game Screenshot" style="height:200px; width:45%; margin:3px;">';
                  }
                  echo '</div>';
                   echo '</div>';
              } else {
                  echo "<p>No screenshots available.</p>";
              }
              ?>
                 <?php if(!empty($ratings_count)) {
                echo $ratings_count ;
              } ?>
             
                  <?php 
              $trailers_url = "https://api.rawg.io/api/games/{$game_slug}/movies?key={$api_key}";
$trailers_response = wp_remote_get($trailers_url);
$trailers_data = json_decode(wp_remote_retrieve_body($trailers_response));
              // Check if trailers exist
if (!empty($trailers_data->results)) {
    echo '<div class="game-trailer-wrp mt-5">';
    echo '<div class="game-trailers row">';
    foreach ($trailers_data->results as $trailer) {
            echo '<div class="trailer mb-3 col-sm-12 col-md-6">';
            echo '<video class="object-fit-cover" width="100%" height="360px" controls poster="' . esc_url($trailer->preview) . '">';
            echo '<source src="' . esc_url($trailer->data->max) . '" type="video/mp4">';
            echo 'Your browser does not support the video tag.';
            echo '</video>';
               echo '<h4 class="fs-5" style="text-align: center;
  background-color: black;
  margin: 0;
    margin-top: 0px;
  padding: 20px 5px;
  line-height: 1;
  font-size: 12px !important;
  font-weight: 400;
  top: 0;
  margin-top: -8px;">' . esc_html($trailer->name) . '</h4>';
            echo '</div>';
    }
    echo '</div>';
    echo '</div>';
} else {
    echo '<p class="mt-5 fs-4" >No trailers available.</p>';
}
?>
<div class="col-lg-6">
<?php if(!empty($website)) {
                echo '<a href="' . esc_url($website) . '" class="btn btn-secondary mt-5 mb-5" target="_blank">Visit Game Website</a>';
              } ?>
    </div>

            </div>
        </div>
</div>



<?php
get_footer();
