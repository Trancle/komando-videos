<?php
/*
Plugin Name: Kom WWW API
Plugin URI: http://www.komando.com
Description: Handles all the API stuffs for WWW
author: Kelly Karnetsky
Version: 0.1
Author URI: http://www.komando.com
*/

// Set up the defines
define('KOM_WWW_API_DIR', dirname(__FILE__));
define('KOM_WWW_API_URL', plugins_url(null, __FILE__));

// Init the plugin
if (class_exists('Kom_Www_Api') || include_once(KOM_WWW_API_DIR . '/includes/class-kom-www-api.php')) {

    $Kom_Www_Api = new Kom_Www_Api();

    // For activation and deactivation of the plugin
    register_activation_hook(__FILE__, [$Kom_Www_Api, 'activation']);
    register_deactivation_hook(__FILE__, [$Kom_Www_Api, 'deactivation']);
}

// Kills plugin update lookup
function hidden_plugin_kom_www_api($r, $url) {
    if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
        return $r; // Not a plugin update request. Bail immediately.
    $plugins = unserialize($r['body']['plugins']);
    unset($plugins->plugins[plugin_basename(__FILE__)]);
    unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
    $r['body']['plugins'] = serialize($plugins);
    return $r;
}
add_filter('http_request_args', 'hidden_plugin_kom_www_api', 5, 2);
