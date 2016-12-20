=== Cron Debug Log ===
Contributors: wpmuguru, cuny-academic-commons
Tags: cron, debug
Requires at least: 3.0
Tested up to: 3.5
Stable tag: trunk

A rudimentary plugin for debugging cron.

== Description ==

When active, this plugin logs unsuccessful results of wp_remote_post calls from wp-cron to a sites's options table with an option name of ra_http_WP_Http_Streams + timestamp. The option_value contains the results of the call.

This plugin is intended to be enabled only on sites that are experiencing cron issues as a troubleshooting aid. Once the cron issue had been identified and corrected, the plugin should be deactivated. When finished troubleshooting you should also remove the option records created by this plugin using your database management tool.

In a WP network use the [Plugin Manager](http://wordpress.org/extend/plugins/plugin-premium-package-manager-for-wp-networks/) to hide this plugin and prevent site admins from activating it.

This plugin was written by [Ron Rennick](http://ronandandrea.com/) in collaboration with the [CUNY Academic Commons](http://dev.commons.gc.cuny.edu/).

== Installation ==

1. Upload the entire `cron-debug-log` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 0.1 =
* Original version.
