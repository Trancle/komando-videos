<?php
/*
Plugin Name: K2 Usercaps
Plugin URI: http://www.komando.com
Description: Creates new user roles for Kim's Club
author: Kelly Karnetsky
Version: 0.2
Author URI: http://www.komando.com
*/

register_activation_hook(__FILE__, 'usercaps_activation');
function usercaps_activation() {
    if (!current_user_can('activate_plugins')) {
    	return;
    }

    add_role('premium_member', 'Premium Member', array(
    	'read' => true
    ));
    add_role('basic_member', 'Basic Member', array(
    	'read' => true
    ));

    $role = get_role('administrator');
    $role->add_cap('premium_member');

    $role = get_role('editor');
    $role->add_cap('premium_member');

    $role = get_role('author');
    $role->add_cap('premium_member');

    $role = get_role('contributor');
    $role->add_cap('premium_member');

    $role = get_role('premium_member');
    $role->add_cap('premium_member');

    $role = get_role('basic_member');
    $role->add_cap('basic_member');
}

register_deactivation_hook(__FILE__, 'usercaps_deactivation');
function usercaps_deactivation() {
    if (!current_user_can('activate_plugins')) {
    	return;
    }

	$role = get_role('administrator');
    $role->remove_cap('premium_member');

    $role = get_role('editor');
    $role->remove_cap('premium_member');

    $role = get_role('author');
    $role->remove_cap('premium_member');

    $role = get_role('contributor');
    $role->remove_cap('premium_member');

    $role = get_role('premium_member');
    $role->remove_cap('premium_member');

    $role = get_role('basic_member');
    $role->remove_cap('basic_member'); 

    remove_role('premium_member');
    remove_role('basic_member'); 
}

// Kills plugin update lookup
add_filter('http_request_args', 'hidden_plugin_k2_usercaps', 5, 2);
function hidden_plugin_k2_usercaps($r, $url) {
	if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize($r['body']['plugins']);
	unset($plugins->plugins[plugin_basename(__FILE__)]);
	unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
	$r['body']['plugins'] = serialize($plugins);
	return $r;
}
?>