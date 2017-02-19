<?php

// Adding menu page
add_action('admin_menu','ssb_admin_menupage');
function ssb_admin_menupage(){

	add_menu_page( 'Ultimate Bar', "Ultimate Bar",'administrator', 'ssb_bar','FrontpageUI','dashicons-admin-plugins');
	add_submenu_page( 'ssb_bar', 'Ultimate Bar', 'Settings', 'administrator', 'ssb_bar_plugin_ui', 'ssb_bar_plugin_ui' );
	add_submenu_page( 'ssb_bar', 'MailChimp Settings', 'MailChimp Settings', 'administrator', 'ssb_mailchimp', 'add_ssb_mailchimp' );
	add_submenu_page( 'ssb_bar', 'Subscribers-List', 'Subscribers-List', 'administrator', 'ssb_list', 'add_ssb_subscribers_list_menu' );
	
}





 ?>