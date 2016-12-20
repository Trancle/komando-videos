<?php
/*
Plugin Name: K2 Podcast Listing 
Plugin URI: http://www.komando.com
Description: Allows for the configuration of podcast listings and caches the podcast RSS feed data
author: Komando
Version: 0.1
Author URI: http://www.komando.com
*/

// Set up the defines
define('K2_PODCAST_LISTING_CONFIG_DIR', dirname(__FILE__));
define('K2_PODCAST_LISTING_CONFIG_URL', plugins_url(null, __FILE__));

// Init the plugin
if (class_exists('\K2\Podcast\Main') || include_once(K2_PODCAST_LISTING_CONFIG_DIR . '/includes/class-main.php')) {

    $k2_podcast_main = new \K2\Podcast\Main();

    // For activation and deactivation of the plugin
    register_activation_hook(__FILE__, [$k2_podcast_main, 'activation']);
    register_deactivation_hook(__FILE__, [$k2_podcast_main, 'deactivation']);
}

// Kills plugin update lookup
function hidden_plugin_k2_podcast($r, $url) {
    if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
        return $r; // Not a plugin update request. Bail immediately.
    $plugins = unserialize($r['body']['plugins']);
    unset($plugins->plugins[plugin_basename(__FILE__)]);
    unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
    $r['body']['plugins'] = serialize($plugins);
    return $r;
}
add_filter('http_request_args', 'hidden_plugin_k2_podcast', 5, 2);

function k2_rewrite_podcast_pages() {
    $page = get_page_by_path("listen/podcast-episodes");
    if ($page) {
        add_rewrite_rule('^listen/show/([0-9]+)/?.*?', 'index.php?page_id=' . $page->ID . '&podcast_id=$matches[1]', 'top');
        return true;
    } else {
        return null;
    }
}
add_action('init', 'k2_rewrite_podcast_pages');

function k2_rewrite_podcast_episode_pages() {
    $page = get_page_by_path("listen/episode");
    if ($page) {
        add_rewrite_rule('^listen/episode/([0-9]+)/?.*?', 'index.php?page_id=' . $page->ID . '&episode_id=$matches[1]', 'top');
        return true;
    } else {
        return null;
    }
}
add_action('init', 'k2_rewrite_podcast_episode_pages');

add_filter( 'query_vars', 'k2_rewrite_podcast_pages_query_vars' );
function k2_rewrite_podcast_pages_query_vars( $query_vars )
{
    $query_vars[] = 'podcast_id';
    $query_vars[] = 'episode_id';
    return $query_vars;
}