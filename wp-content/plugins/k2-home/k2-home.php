<?php
/*
Plugin Name: K2 Home Page: Twitter & News Feeds
Plugin URI: http://www.komando.com
Description: Handles the Twitter & and News feeds for the home page.
author: Kelly Karnetsky
Version: 0.3
Author URI: http://www.komando.com
*/

// Add the admin menu
add_action('admin_menu', 'k2_home_page_menu');
function k2_home_page_menu() {
	add_menu_page('Homepage Twitter Settings', 'Twitter Settings', 'edit_others_posts', 'homepage-twitter-settings', 'k2_home_page_twitter', '', 45);
}

register_deactivation_hook(__FILE__, 'k2_featured_link_deactivation');
function k2_featured_link_deactivation() {

	// This function is legacy to remove the hook from the cron
	// Plugin Kom Homepage superseded this

	$old_next_schedule = wp_next_scheduled('k2_home_featured_links_hook');
	if($old_next_schedule) {
		wp_unschedule_event($old_next_schedule, 'k2_home_featured_links_hook');
	}

	$featured_next = wp_next_scheduled('k2homefeaturedlinkshook');
	wp_unschedule_event($featured_next, 'k2homefeaturedlinkshook');
}

function k2_home_page_twitter() {

	if($_POST == true && $_POST['check'] = 1) {
		update_option('homepage-toggle-twitter', $_POST['homepage-toggle-twitter']);
	}

	$toggle_twitter = get_option('homepage-toggle-twitter');

	?>

	<div class="wrap metabox-holder">
		<h2>Homepage Twitter Settings</h2>

		<form method="post" action="">
			<table class="form-table">
				<tbody>
				<tr valign="top">
					<th scope="row"><label for="homepage-toggle-twitter">Pull from Twitter API?</label></th>
					<td>
						<input name="homepage-toggle-twitter" type="checkbox" id="homepage-toggle-twitter" <?php if($toggle_twitter) { echo 'checked="checked"'; } ?>> Yes
						<p class="description">If checked it will pull from the Twitter API every 15 minutes. If unchecked it will use the last cached version available.</p>
					</td>
				</tr>
				</tbody>
			</table>
			<input type="hidden" name="check" value="1" />
			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save"></p>
		</form>
	</div>
<?php
}

function home_news() {

	$stories = get_transient('news_stories');

	if (empty($stories)) {
	
		$ch = curl_init();
		$header = array("Content-Type:application/json");
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_URL, "http:" . NEWS_BASE_URI . "/index.json");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$file_contents = curl_exec($ch);
		echo curl_error($ch);
		$news = json_decode($file_contents);

		$stories = array();
		if(is_array($news->columns[0]->stories)){
			$stories = array_merge($stories, $news->columns[0]->stories);
		}

		if(is_array($news->columns[1]->stories)){
			$stories = array_merge($stories, $news->columns[1]->stories);
		}

		if(is_array($news->columns[2]->stories)){
			$stories = array_merge($stories, $news->columns[2]->stories);
		}

		set_transient('news_stories', $stories, 900);
	}

	$i = 1;

	foreach ($stories as $story) {
		$date = new DateTime($story->appear_at);
		$formatted_date = $date->format('U');
		$title = $story->title_text_only;

		if($i > 3) {
			echo '<li class="num' . $i . ' hide-mobile hide-tablet" data-article-url="' . NEWS_BASE_URI . '"><a href="' . NEWS_BASE_URI . '">' . $title . '</a><span>' . human_time_diff($formatted_date) . ' ago</span></li>';
		} else {
			echo '<li class="num' . $i . '" data-article-url="' . NEWS_BASE_URI . '"><a href="' . NEWS_BASE_URI . '">' . $title . '</a><span>' . human_time_diff($formatted_date) . ' ago</span></li>';
		}
		
		$i++;
		if($i > 6) { break; }
	}

}

function get_tweets() {

	// Check to see if we should be pulling tweets from Twitter API or use archived
	$toggle_twitter = get_option('homepage-toggle-twitter');

	if(!$toggle_twitter) {
		return get_option('archived_tweets');
	}

	global $wpdb;
	
	$tweets = get_transient('komando_tweets');

	if (!empty($tweets)) {
		return $tweets;
	} else {

		try {
			// Lets lock down the rows to check if we need to update
			$wpdb->query("START TRANSACTION");

			$twitter_next = $wpdb->query("SELECT * FROM $wpdb->options WHERE option_name = 'twitter_homepage_next';");
			
			if(empty($twitter_next)) {
				$twitter_next = time();
				$wpdb->query("UPDATE $wpdb->options SET option_value = $twitter_next WHERE option_name = 'twitter_homepage_next';");
			}

			$twitter_mutex = $wpdb->query("SELECT * FROM $wpdb->options WHERE option_name = 'twitter_homepage_mutex';");

			$do_the_update = $twitter_next != $twitter_mutex;

			if( $do_the_update ) {
				$wpdb->query("UPDATE $wpdb->options SET option_value = $twitter_next WHERE option_name = 'twitter_homepage_mutex';");
			}

			// Update the rows to reflect all the newness
			$wpdb->query("COMMIT");

			if($do_the_update) {

				require_once(plugin_dir_path( __FILE__ ) . 'twitteroauth/twitteroauth.php');
				$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, TWITTER_ACCESS_TOKEN, TWITTER_ACCESS_TOKEN_SECRET);
				$tweets = $connection->get('statuses/user_timeline', array('count' => '5', 'trim_user' => 'false'));

				set_transient('komando_tweets', $tweets, 900);
				update_option('archived_tweets', $tweets);
				update_option('twitter_homepage_next', time());

				return $tweets;

			} else {
				
				return get_option('archived_tweets');
			}

		} catch (Exception $e) {

			// Someone beat us to the punch, fallback to the archived tweets
			$wpdb->query("ROLLBACK");
			return get_option('archived_tweets');
		}

	}
}

function home_twitter() {

	$i = 1;
	foreach (get_tweets() as $tweet) {
		// Parse the datetime from Twitter
		$date = new DateTime($tweet->created_at);
		$date->setTimezone(new DateTimeZone('America/Phoenix'));
		$formatted_date = $date->format('U');

		// Parse any links found in our tweet
		$formatted_text = preg_replace('/(\b(www\.|http\:\/\/|https\:\/\/)\S+\b)/', '<a target="_blank" href="$1">$1</a>', $tweet->text);
		$formatted_text = preg_replace('/\#(\w+)/', '<a target="_blank" href="https://twitter.com/search?q=%23$1">#$1</a>', $formatted_text);
		$formatted_text = preg_replace('/\@(\w+)/', '<a target="_blank" href="https://twitter.com/$1">@$1</a>', $formatted_text);

		if($i > 3) {
			echo '<li class="num' . $i . ' hide-mobile hide-tablet">' . $formatted_text . '<span>' . human_time_diff($formatted_date) . ' ago</span></li>';
		} else {
			echo '<li class="num' . $i . '">' . $formatted_text . '<span>' . human_time_diff($formatted_date) . ' ago</span></li>';
		}

		$i++;
	}
}

function home_twitter_profile() {
	require_once(plugin_dir_path( __FILE__ ) . 'twitteroauth/twitteroauth.php');
	$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, TWITTER_ACCESS_TOKEN, TWITTER_ACCESS_TOKEN_SECRET);
	$profile = $connection->get('users/lookup', array('screen_name' => 'kimkomando'));
	$profile_image = $profile[0]->profile_image_url_https;
	?>

	<div class="twitter-profile-wrapper clearfix">
		<div class="twitter-profile-image">
			<?php if(!empty($profile_image)) {
				echo '<img src="' . $profile_image . '" alt="Kim Komando" />';
			} else { 
				syslog(LOG_ERR, "There was an error with Twitter's API, missing profile_image in function home_twitter_profile, k2-home line 568.");
			} ?>
		</div>
		<div class="twitter-username">
			<h2>@KimKomando</h2>
			<a href="https://twitter.com/kimkomando" class="twitter-follow-button" data-show-count="false" data-lang="en">Follow @kimkomando</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		</div>
	</diV>

	<?php

}

// Kills plugin update lookup
function hidden_plugin_k2_home($r, $url) {
	if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize($r['body']['plugins']);
	unset($plugins->plugins[plugin_basename(__FILE__)]);
	unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
	$r['body']['plugins'] = serialize($plugins);
	return $r;
}

add_filter( 'http_request_args', 'hidden_plugin_k2_home', 5, 2 );
?>