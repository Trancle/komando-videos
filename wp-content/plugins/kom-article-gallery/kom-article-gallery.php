<?php
/**
 * Plugin Name: Kom Article Gallery
 * Plugin URI: http://www.komando.com
 * Description: Allows substituting an image gallery for a splash image in an article.
 * Version 1.0
 * Author: Tyger Gilbert
 * Author URI: http://www.TygerGilbert.com
 * Date: 4/22/2016
 * Time: 9:26 AM
 *
 * File: kom-article-gallery.php
 * Class to add a section in the Edit Article page (post.php) for adding
 * an image gallery on the article page itself (not on the article list page).
 */

// Set up the defines
define('KOM_ARTICLE_GALLERY_DIR', dirname(__FILE__));
define('KOM_ARTICLE_GALLERY_URL', plugins_url(null, __FILE__));

// For Activation and deactivation of the plugin
register_activation_hook(__FILE__, array('Kom_Article_Gallery', 'activation'));
register_deactivation_hook(__FILE__, array('Kom_Article_Gallery', 'deactivation'));

// Init the plugin
if (class_exists('Kom_Article_Gallery') || include_once(KOM_ARTICLE_GALLERY_DIR . '/includes/class-kom-article-gallery.php')) {
    require_once KOM_ARTICLE_GALLERY_DIR . "/includes/kom-article-gallery-settings.php";     // The settings options needed for the plugin
    Kom_Article_Gallery::init();
}

// Kills plugin update lookup
function hidden_plugin_kom_article_gallery($r, $url) {
    if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
        return $r; // Not a plugin update request. Bail immediately.
    $plugins = unserialize($r['body']['plugins']);
    unset($plugins->plugins[plugin_basename(__FILE__)]);
    unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
    $r['body']['plugins'] = serialize($plugins);
    return $r;
}

add_filter('http_request_args', 'hidden_plugin_kom_article_gallery', 5, 2);
