<?php
/**
 * functions.php
 * Includes for function files that contain related groups of function definitions
 * Created by PhpStorm.
 * User: gilbert
 * Date: 5/15/2015
 * Time: 9:27 AM
 */

foreach( array(
    "functions-actions",
    "functions-filters",
     "functions-general",
     "functions-shortcodes",
     "functions-ads-images",
     "functions-post-types",
     "functions-contact-forms",
     "functions-podcasts",
     "header_info"
    )
    as $file ) {
    include_once( "includes/$file.php" );
}

function custom_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'custom_excerpt_more' );

// ===============================================================
function do_feed_full(){
// Use the k2 copy of the feed file
    $rss_template = 'wp-content/themes/k2/' . 'feed-full.php';
    load_template( $rss_template );
}
add_action( 'do_feed_full', 'do_feed_full', 10, 1 );

function rewrite_feed_full(){
//    add_rewrite_rule('^feed-full$', 'wp-includes/feed-full.php', 'top');
    add_rewrite_rule('^feed-full$', 'index.php?feed=full', 'top');
}
add_action('init', 'rewrite_feed_full');

//===============================================================
function remove_pagination( $content = '' ){
// Removes "Next page" text and code from $content.
    while (substr_count( $content, '<p><span class="nextpage-link">') > 0) {
        $begin = strpos($content, '<p><span class="nextpage-link">');
        $length = (strpos($content, 'nextpage--', $begin) + 15) - $begin;
        $content = substr_replace($content, '', $begin, $length);
    }
    return ($content);
}

// ===============================================================
function list_media_for_rss($item_id_no ) {
// Gets data about splash images and video included in RSS item and returns a string.

    // Check for splash image
    $data_splash_image = '';
    $image_id = get_post_thumbnail_id();
    $image = wp_get_attachment_image_src($image_id, 'large');
    if ($image && !empty($image[0])) {
        $data_splash_image = ' data-splash-image="' . $image[0] . '"';
    }
    // Check for splash video
    $data_splash_video = '';
    $video = get_post_meta($item_id_no, 'article_videos_meta_url', true);
    if ($video && !empty($video)) {
        $data_splash_video = ' data-splash-video="' . $video . '"';
    }
    // Combine data to create description text
    $description_text = '<div id="meta-media"' . $data_splash_image . $data_splash_video . '></div>';

    return $description_text;
}

// ===============================================================
function get_unique_md5_id(){
    // Set up the Query.
    $md5_string = '';
    $mod_date = '';
    while( have_posts()) {
        the_post();
        // Compile the md5_string.
        $md5_string .= get_the_title();
        $md5_string .= substr(get_the_excerpt(), 0, 200);
        $md5_string .= substr(get_the_content('', FALSE), 0, 200);
        // Get the maximum last modified date.
        $mod_date = max($mod_date, get_the_modified_time('D, d M Y H:i:s O'));
    }
    wp_reset_postdata();
    $ret[0] = md5($md5_string);
    $ret[1] = $mod_date;

    return ($ret);
}

// =================================================== Part of Article-Videos
function is_first_page($page) {
    return $page == 1;
}

function is_all_page($page) {
    return $page <= 0;
}

function has_gallery( $post_id = null ) {
    $post_id = is_null($post_id) ? get_the_ID() : $post_id;
    return class_exists('Kom_Article_Gallery') && Kom_Article_Gallery::post_has_gallery($post_id);
}

function has_splash_video($splash_url) {
    return is_plugin_active( 'kom-article-videos/kom-article-videos.php' ) and !empty($splash_url);
}

function has_splash_image($image) {
    return !empty($image[0]);
}

function has_splash_on_current_page( $page, $splash_url, $image ) {
    return ( is_first_page($page) || is_all_page($page) ) && ( has_splash_video($splash_url) || has_splash_image($image) || has_gallery() );
}

function extract_youtube_video_id($splash_url) {
    $ret = "";
    if (substr_count($splash_url, '/embed/') > 0){
        // www.youtube.com/embed/[code]
        $ret = substr($splash_url, strripos($splash_url, '/embed/') + 7);
    } elseif (substr_count($splash_url, '/watch?v=') > 0){
        // www.youtube.com/watch?v=[code]
        $ret = substr($splash_url, strripos($splash_url, '/watch?v=') + 9);
    } elseif (substr_count($splash_url, 'youtu.be/') > 0){
        // youtu.be/[code]
        $ret = substr($splash_url, strripos($splash_url, 'youtu.be/') + 9);
    }
    return $ret;
}

function extract_vimeo_video_id($splash_url) {
    $ret = "";
    if (substr_count($splash_url, '/video/') > 0){
        // https://player.vimeo.com/video/[code]
        $ret = substr($splash_url, strripos($splash_url, '/video/') + 7);
    } elseif (substr_count($splash_url, '/vimeo.com/') > 0){
        // https://vimeo.com/168920644[code]
        $ret = substr($splash_url, strripos($splash_url, '/vimeo.com/') + 11);
    }
    return $ret;
}

/*
 * Force URLs in srcset attributes into HTTPS scheme.
 * This is particularly useful when you're running a Flexible SSL frontend like Cloudflare
 * Taken from http://wptavern.com/how-to-fix-images-not-loading-in-wordpress-4-4-while-using-ssl
 * After experiencing missing images after upgrading to WP 4.4
 */
function ssl_srcset( $sources ) {
  foreach ( $sources as &$source ) {
    $source['url'] = set_url_scheme( $source['url'], 'https' );
  }

  return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'ssl_srcset' );


// ===============================================================

add_filter('query_vars', 'partner_queryvars' );
function partner_queryvars( $qvars )
{
    $qvars[] = 'id';
    $qvars[] = 'unit';
    return $qvars;
}

function custom_rewrite_tag() {
    add_rewrite_tag('%unit%', '([A-Za-z0-9_])*');
    add_rewrite_tag('%width%', '([^&]+)');
    add_rewrite_tag('%height%', '([^&]+)');
}
add_action('init', 'custom_rewrite_tag', 10, 0);

function add_ads_rules() {
    add_rewrite_rule('^ads/([A-Za-z0-9_])*', 'index.php/?page_id=353631&unit=$matches[1]', 'top');
   // add_rewrite_rule('^ads/([^/]*)/([^/]*)/([^/]*)/?', 'index.php/?page_id=353631&unit=$matches[1]&width=$matches[2]&height=$matches[3]', 'top');
}
add_action( 'init', 'add_ads_rules');
 //http://localhost.komando.com/ads/fb-instant-article-content-1/320/50
function add_scared_shitless_rules() {
    add_rewrite_rule('^listen/scared-shitless/([0-9]+)?', 'index.php/?page_id=353624&id=$matches[1]', 'top');
}
add_action( 'init', 'add_scared_shitless_rules');


// ===============================================================
// Add special feed for Fox News that does not include columns

function do_feed_foxnews(){
// Use the k2 copy of the feed file
    $rss_template = 'wp-content/themes/k2/' . 'feed-foxnews.php';
    load_template( $rss_template );
}
add_action( 'do_feed_foxnews', 'do_feed_foxnews', 10, 1 );

function rewrite_feed_foxnews(){
    add_rewrite_rule('^feed-foxnews$', 'index.php?feed=foxnews', 'top');
}
add_action('init', 'rewrite_feed_foxnews');


// ===============================================================

?>
