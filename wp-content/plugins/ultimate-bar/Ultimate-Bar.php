<?php
/*
Plugin Name: Ultimate Bar Free
Version: 3.3
Author: umarbajwa
Plugin URI:http://web-settler.com/ultimate-bar/
Author URI :http://web-settler.com/contact/
Donate link: http://web-settler.com/ultimate-bar/
License: GPL V2 
Contact : http://web-settler.com/contact/
*/


require_once "ssb_options_file.php";
require_once "ssb_UI_file.php";
require_once "ssb_menu_file.php";
require_once "ssb_scripts_file.php";
require_once "ssb_bar_html.php";
require_once 'add_ssb_subscribers_list_menu.php';
require_once 'FrontpageUI.php';
require_once 'add_ssb_mailchimp.php';

function ssb_plugin_add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=ssb_bar">' . __( 'Plugin Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'ssb_plugin_add_settings_link' );


global $jal_db_version;
$jal_db_version = '1.0';

function ssb_db_table_create() {
	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'ssb_data';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		name tinytext NOT NULL,
		email text NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );


}


register_activation_hook( __FILE__, 'ssb_db_table_create' );




 ?>