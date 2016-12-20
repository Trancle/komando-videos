<?php
/*
Plugin Name: Kom Article Videos
Plugin URI: http://www.komando.com
Description: Allows substituting a video for a splash image in an article.
Version: 1.0
Author: Tyger Gilbert
Author URI: http://www.tygergilbert.com
*/

/**
 * Date: 11/30/2015
 * Time: 4:42 PM
 *
 * File: kom-article-videos.php
 * Class to add an entry field in the Edit Article page (post.php)
 * for adding a URL to a video that will replace the Shutterstock
 * image on the article page itself (not on the article list page).
 */

// Set up the defines
define('KOM_ARTICLE_VIDEOS_DIR', dirname(__FILE__));
define('KOM_ARTICLE_VIDEOS_URL', plugins_url(null, __FILE__));

// For Activation and deactivation of the plugin
register_activation_hook(__FILE__, array('Kom_Article_Videos', 'activation'));
register_deactivation_hook(__FILE__, array('Kom_Article_Videos', 'deactivation'));

// Init the plugin
if (class_exists('Kom_Article_Videos') || include_once(KOM_ARTICLE_VIDEOS_DIR . '/includes/class-article-videos.php')) {
    Kom_Article_Videos::init();
}

// Kills plugin update lookup
function hidden_plugin_article_videos($r, $url) {
    if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
        return $r; // Not a plugin update request. Bail immediately.
    $plugins = unserialize($r['body']['plugins']);
    unset($plugins->plugins[plugin_basename(__FILE__)]);
    unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
    $r['body']['plugins'] = serialize($plugins);
    return $r;
}

add_filter('http_request_args', 'hidden_plugin_article_videos', 5, 2);
