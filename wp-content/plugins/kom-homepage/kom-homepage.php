<?php
/*
Plugin Name: Kom Homepage
Plugin URI: http://www.komando.com
Description: Creates the featured links (Kim's Desk) and grid on the home page
author: Kelly Karnetsky
Version: 0.1
Author URI: http://www.komando.com
*/

// Set up the defines
define('KOM_HOMEPAGE_DIR', dirname(__FILE__));
define('KOM_HOMEPAGE_URL', plugins_url(null, __FILE__));

// For activation and deactivation of the plugin
register_activation_hook(__FILE__, array('Kom_Homepage', 'activation'));
register_deactivation_hook(__FILE__, array('Kom_Homepage', 'deactivation'));

// Init the plugin
if (class_exists('Kom_Homepage') || include_once(KOM_HOMEPAGE_DIR . '/includes/class-kom-homepage.php')) {
    Kom_Homepage::init();
}

// Kills plugin update lookup
function hidden_plugin_kom_homepage($r, $url) {
    if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
        return $r; // Not a plugin update request. Bail immediately.
    $plugins = unserialize($r['body']['plugins']);
    unset($plugins->plugins[plugin_basename(__FILE__)]);
    unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
    $r['body']['plugins'] = serialize($plugins);
    return $r;
}

add_filter('http_request_args', 'hidden_plugin_kom_homepage', 5, 2);