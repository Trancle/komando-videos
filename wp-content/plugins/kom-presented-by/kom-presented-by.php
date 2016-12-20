<?php
/*
Plugin Name: Kom Presented By
Plugin URI: http://www.komando.com
Description: Adds a "Presented by" promotional ad to a single page.
Version: 1.0
Author: Tyger Gilbert
Author URI: http://www.tygergilbert.com
*/

// Set up the defines
define('KOM_PRESENTED_BY_DIR', dirname(__FILE__));
define('KOM_PRESENTED_BY_URL', plugins_url(null, __FILE__));

// Init the plugin
if (class_exists('Kom_Presented_By') || include_once(KOM_PRESENTED_BY_DIR . '/includes/class-kom-presented-by.php')) {
    Kom_Presented_By::init();
}

// Kills plugin update lookup
function hidden_plugin_kom_presented_by($r, $url) {
    if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
        return $r; // Not a plugin update request. Bail immediately.
    $plugins = unserialize($r['body']['plugins']);
    unset($plugins->plugins[plugin_basename(__FILE__)]);
    unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
    $r['body']['plugins'] = serialize($plugins);
    return $r;
}
add_filter('http_request_args', 'hidden_plugin_kom_presented_by', 5, 2);
