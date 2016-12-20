<?php
/*
Plugin Name: Kom Editor's Picks
Plugin URI: http://www.komando.com
Description: Creates a metabox on the Edit page with a checkbox to select article for Google Editor's Picks.
Version: 1.0
Author: Tyger Gilbert
Author URI: http://www.tygergilbert.com
*/

// Set up the defines
define('KOM_EDITORS_PICKS_DIR', dirname(__FILE__));
define('KOM_EDITORS_PICKS_URL', plugins_url(null, __FILE__));

// For activation and deactivation of the plugin
register_activation_hook(__FILE__, array('Kom_Editors_Picks', 'activation'));
register_deactivation_hook(__FILE__, array('Kom_Editors_Picks', 'deactivation'));

// Init the plugin
if (class_exists('Kom_Editors_Picks') || include_once(KOM_EDITORS_PICKS_DIR . '/includes/class-editors-picks.php')) {
    Kom_Editors_Picks::init();
}

// Kills plugin update lookup
function hidden_plugin_editors_picks($r, $url) {
    if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
        return $r; // Not a plugin update request. Bail immediately.
    $plugins = unserialize($r['body']['plugins']);
    unset($plugins->plugins[plugin_basename(__FILE__)]);
    unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
    $r['body']['plugins'] = serialize($plugins);
    return $r;
}

add_filter('http_request_args', 'hidden_plugin_editors_picks', 5, 2);
