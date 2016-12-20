<?php
/**
 * Plugin Name: K2 Free Weekend
 * Plugin URI: http://www.komando.com
 * Description: Configuration for free weekend media.
 * Version 1.0
 * Author: Yossi Wolfe
 * Author URI: http://www.komando.com
 * Date: 8/10/2016
 * Time: 11:35 AM
 *
 * File: k2-free-weekend.php
 */

// Set up the defines
define('K2_FREE_WEEKEND_DIR', dirname(__FILE__));
define('K2_FREE_WEEKEND_URL', plugins_url(null, __FILE__));

// For Activation and deactivation of the plugin
register_activation_hook(__FILE__, array('K2_Free_Weekend', 'activation'));
register_deactivation_hook(__FILE__, array('K2_Free_Weekend', 'deactivation'));

require_once K2_FREE_WEEKEND_DIR . "/includes/k2-free-weekend-settings.php";     // The settings options needed for the plugin

// Kills plugin update lookup
function hidden_plugin_k2_free_weekend($r, $url) {
    if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
        return $r; // Not a plugin update request. Bail immediately.
    $plugins = unserialize($r['body']['plugins']);
    unset($plugins->plugins[plugin_basename(__FILE__)]);
    unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
    $r['body']['plugins'] = serialize($plugins);
    return $r;
}

add_filter('http_request_args', 'hidden_plugin_k2_free_weekend', 5, 2);
