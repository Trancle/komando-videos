<?php
/*
Plugin Name: K2 Archiver
Plugin URI: http://www.komando.com
Description: Archives content older than 36 months by unpublishing it.
Author: Kelly Karnetsky
Version: 0.1
Author URI: http://www.komando.com
*/

add_action('k2archivercheckpostshook', 'archiver_check_posts');
function archiver_check_posts() {
	global $wpdb;
	global $post;
	$daystogo = '1065';
	$querystr = "
	SELECT $wpdb->posts.*
	FROM $wpdb->posts
	WHERE $wpdb->posts.post_type IN ('post', 'columns', 'downloads', 'apps', 'cool_sites', 'tips', 'buying_guides', 'charts', 'newsletters', 'previous_shows', 'small_business')
	AND DATEDIFF(NOW(), $wpdb->posts.post_date) > '$daystogo'
	AND $wpdb->posts.post_status = 'publish'
	ORDER BY $wpdb->posts.post_date DESC
	";

	$pageposts = $wpdb->get_results($querystr, OBJECT);

	if($pageposts) {
		foreach ($pageposts as $post) {

			$wpdb->query($wpdb->prepare("UPDATE $wpdb->posts SET $wpdb->posts.post_status = 'archived' WHERE $wpdb->posts.ID = '%d'", $post->ID));

			$id_array[] = $post->ID;

			if(function_exists('mss_handle_delete')) { 
				mss_handle_delete($post->ID);
			}

		}
	}
	if(!empty($id_array)) {
		$ids = implode(', ', $id_array);
		syslog(LOG_INFO, "Post IDs Archived: $ids");
		// wp_mail('kelly.karnetsky@komando.com', 'Post Archiver cron info', "Post IDs Archived: $ids");
	}
}

register_activation_hook(__FILE__, 'archiver_activation');
function archiver_activation() {
    if (!current_user_can('activate_plugins')) {
    	return;
    }

    wp_schedule_event('1380006000', 'daily', 'k2archivercheckpostshook');
}

register_deactivation_hook(__FILE__, 'archiver_deactivation');
function archiver_deactivation() {
    if (!current_user_can('activate_plugins')) {
    	return;
    }

    $old_next_schedule = wp_next_scheduled('archiver_check_posts_hook');
    if($old_next_schedule) {
    	wp_unschedule_event($old_next_schedule, 'archiver_check_posts_hook');
	}

    $archiver_next = wp_next_scheduled('k2archivercheckpostshook');
    wp_unschedule_event($archiver_next, 'k2archivercheckpostshook');
}

// Kills plugin update lookup
function hidden_plugin_k2_archiver($r, $url) {
	if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize($r['body']['plugins']);
	unset($plugins->plugins[plugin_basename(__FILE__)]);
	unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
	$r['body']['plugins'] = serialize($plugins);
	return $r;
}

add_filter('http_request_args', 'hidden_plugin_k2_archiver', 5, 2 );
?>
