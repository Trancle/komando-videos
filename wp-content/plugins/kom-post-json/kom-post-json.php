<?php
/*
Plugin Name: Kom Post API
Plugin URI: http://www.komando.com
Description: Returns JSON for a post object for allowed IPs.
author: Kelly Karnetsky
Version: 0.1
Author URI: http://www.komando.com
*/

// Set up the defines
define('KOM_POST_JSON_DIR', dirname(__FILE__));
define('KOM_POST_JSON_URL', plugins_url(null, __FILE__));

use Kom_Post_Json\Plugin;

// Autoloader for the plugin
spl_autoload_register('kom_post_json_autoloader');
function kom_post_json_autoloader($class) {
	if (strpos($class, 'Kom_Post_Json') !== false) {
		require_once(KOM_POST_JSON_DIR . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php');
	}
}

// Autoloader for Leth IPAddress Class
spl_autoload_register('leth_autoloader');
function leth_autoloader($class) {
	if (strpos($class, 'Leth') !== false) {
		require_once(KOM_POST_JSON_DIR . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php');
	}
}

$Kom_Post_Json = new Plugin();

// For activation and deactivation of the plugin
register_activation_hook(__FILE__, [$Kom_Post_Json, 'activation']);
register_deactivation_hook(__FILE__, [$Kom_Post_Json, 'deactivation']);

// Kills plugin update lookup
function hidden_plugin_kom_post_json($r, $url) {
	if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize($r['body']['plugins']);
	unset($plugins->plugins[plugin_basename(__FILE__)]);
	unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
	$r['body']['plugins'] = serialize($plugins);
	return $r;
}
add_filter('http_request_args', 'hidden_plugin_kom_post_json', 5, 2);
