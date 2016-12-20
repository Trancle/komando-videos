<?php
/*
Plugin Name: K2 Native Ads Manager
Plugin URI: http://www.komando.com
Description: Manages all the custom ad units that aren't in DFP.
author: Kelly Karnetsky
Version: 0.2
Author URI: http://www.komando.com
*/

// Add the admin menu
add_action('admin_menu', 'k2_section_featured_ads_menu');
function k2_section_featured_ads_menu() {
	add_menu_page('Native Ads Manager', 'Native Ads', 'edit_others_posts', 'native-ads', 'k2_native_ads');
}

function k2_section_featured_ads_scripts() {
	if (isset($_GET['page']) && $_GET['page'] == 'native-ads') {
		wp_enqueue_media();
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-slider');
		wp_register_style('jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('jquery-ui');

		wp_register_script('k2-home-js', WP_PLUGIN_URL . '/k2-native-ads/k2-native-ads.js', array('jquery'));
		wp_enqueue_script('k2-home-js');

		wp_register_script('jquery-ui-timepicker-addon', WP_PLUGIN_URL . '/k2-native-ads/jquery-ui-timepicker-addon.js', array('jquery-ui-datepicker'));
		wp_enqueue_script('jquery-ui-timepicker-addon');
	}
}
add_action('admin_enqueue_scripts', 'k2_section_featured_ads_scripts');

register_activation_hook(__FILE__, 'k2_section_featured_ads_setup');
function k2_section_featured_ads_setup() {
    wp_schedule_event('1395871200', 'fiver', 'k2sectionfeaturedadshook');
}

register_deactivation_hook(__FILE__, 'k2_section_featured_ads_deactivation');
function k2_section_featured_ads_deactivation() {

	$old_next_schedule = wp_next_scheduled('k2_section_featured_ads_hook');
    if($old_next_schedule) {
    	wp_unschedule_event($old_next_schedule, 'k2_section_featured_ads_hook');
	}

    $featured_next = wp_next_scheduled('k2sectionfeaturedadshook');
    wp_unschedule_event($featured_next, 'k2sectionfeaturedadshook');
}

require_once('lib/native-ads-check-time.php');
require_once('lib/native-ads-section-ads.php');
require_once('lib/native-ads-trending-ads.php');

function k2_native_ads() { ?>

	<div class="wrap">
		<h2>Native Ads Manager</h2>
		<?php $active_tab = isset($_GET['tab'] ) ? $_GET['tab'] : 'section-ads'; ?>
		<h2 class="nav-tab-wrapper">
			<a href="?page=native-ads&tab=section-ads" class="nav-tab <?php echo $active_tab == 'section-ads' ? 'nav-tab-active' : ''; ?>">Section Featured Ads</a>  
			<a href="?page=native-ads&tab=trending-ads" class="nav-tab <?php echo $active_tab == 'trending-ads' ? 'nav-tab-active' : ''; ?>">Trending Now Ads</a>  
		</h2>
	
		<?php

		if($active_tab == 'section-ads') {
			native_ads_section_ads();
		}

		if($active_tab == 'trending-ads') {
			native_ads_trending_ads();
		}

		?>

	</div>

<?php }

// Kills plugin update lookup
function hidden_plugin_k2_native_ads($r, $url) {
	if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize($r['body']['plugins']);
	unset($plugins->plugins[plugin_basename(__FILE__)]);
	unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
	$r['body']['plugins'] = serialize($plugins);
	return $r;
}

add_filter('http_request_args', 'hidden_plugin_k2_native_ads', 5, 2);
?>