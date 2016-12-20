<?php
/*
Plugin Name: K2 Google Analytics
Plugin URI: http://www.komando.com
Description: Does the Google Analytics stuff, currently the trending content.
Author: Kelly Karnetsky
Version: 0.1
Author URI: http://www.komando.com
*/

register_activation_hook(__FILE__, 'k2_google_analytics_setup');
function k2_google_analytics_setup() {

	wp_schedule_event('1380067200', 'hourly', 'k2trendingrefreshhook');
	k2_trending_refresh('8', '24');
}

register_deactivation_hook(__FILE__, 'k2_google_analytics_deactivation');
function k2_google_analytics_deactivation() {

	$old_next_schedule = wp_next_scheduled('k2_trending_refresh_hook');
	if($old_next_schedule) {
		wp_unschedule_event($old_next_schedule, 'k2_trending_refresh_hook');
	}

	$analytics_next = wp_next_scheduled('k2trendingrefreshhook');
	wp_unschedule_event($analytics_next, 'k2trendingrefreshhook');
}

add_action('admin_menu', 'k2_google_analytics_page_menu');
function k2_google_analytics_page_menu() {
	add_menu_page('Google Analytics', 'Google Analytics', 'edit_others_posts', 'google-analytics-settings', 'k2_google_analytics_page');
}

function k2_google_analytics_page() {

	if($_POST['check'] == '1') {

		if($_POST['trending-time-switch'] > 23) {
			$time_switch = '23';
		} elseif($_POST['trending-time-switch'] < 0) {
			$time_switch = '1';
		} else {
			$time_switch = $_POST['trending-time-switch'];
		}

		if($_POST['trending-number-pages'] > 50) {
			$trending_amount = '50';
		} elseif($_POST['trending-number-pages'] < 0) {
			$trending_amount = '1';
		} else {
			$trending_amount = $_POST['trending-number-pages'];
		}

		k2_trending_refresh($time_switch, $trending_amount);

	}

	date_default_timezone_set('America/Phoenix');
	$data = get_option('google_analytics_data');

	?>
	<div class="wrap">
		<h2>Google Analytics - Trending Now Settings</h2>

		<?php

		if($data['last-refresh-status'] == 'good') {
			echo '<div class="updated"><p><strong>No Issues!</strong> Trending now last updated at ' . date('F j, Y, g:i a', $data['last-refresh']) . '.</p></div>';

		} elseif($data['last-refresh-status'] == 'fallback') {
			echo '<div class="error"><p><strong>Oh Noes!</strong> There weren\'t enough trending articles so we\'re using the trending now data was last updated at ' . date('F j, Y, g:i a', $data['last-refresh']) . '. I tried to talk to Google at <strong>' . date('F j, Y, g:i a', $data['last-failed-refresh']) . '</strong></p></div>';

		} elseif($data['last-refresh-status'] == 'recent') {
			echo '<div class="error"><p><strong>Oh Noes!</strong> There was an issue talking with Google so we\'re using recent articles. I tried to talk to Google at <strong>' . date('F j, Y, g:i a', $data['last-failed-refresh']) . '</strong></p></div>';

		}

		?>

		<form method="post" action="">
			<table class="form-table">
				<tbody>
				<tr valign="top">
					<th scope="row"><label for="trending-number-pages">Number of Posts</label></th>
					<td><input name="trending-number-pages" type="number" id="trending-number-pages" min="0" max="50" value="<?php echo $data['trending-amount'] ?>"></td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="trending-time-switch">Switch Hour (24 hour format)</label></th>
					<td><input name="trending-time-switch" type="number" id="trending-time-switch" min="0" max="23" value="<?php echo $data['time-switch'] ?>"></td>
				</tr>
				</tbody>
			</table>
			<input type="hidden" name="check" value="1" />
			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes / Refresh Trending Cache"></p>
		</form>

		<div id="k2-init-pageview-refresh" class="button">Refresh Google Pageview Counts</div>

		<div id="poststuff" class="metabox-holder">
			<div class="meta-box-sortabless">
				<div id="k2-debug" class="postbox" style="display:none;">
					<h3 class="hndle"><span>Debug data</span></h3>
					<div class="inside"></div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			jQuery(document).ready(function($) {

				var ajaxurl = "<?php echo get_template_directory_uri() . '/k2-ajax.php'; ?>";

				$('#k2-init-pageview-refresh').on('click', function() {
					if(!$(this).attr('disabled')) {
						$(this).attr('disabled', 'disabled');
						$('#k2-debug .inside').html('');
						$('#k2-debug').slideDown('fast');
						$('#k2-debug .inside').append('<pre><span style="color:red;">Starting.</span></pre>');
						$('#k2-debug .inside').append('<pre><span style="color:red;">Fetching IDs...</span></pre>');

						$.ajax({
							type: 'GET',
							url: ajaxurl,
							data: {action: 'k2_ga_fetch_ids'}
						}).done(function(data) {

							var jsonids = $.parseJSON(data);
							$('#k2-debug .inside').append('<pre class="splitids">' + jsonids + '</pre>');

							var splitids = $('.splitids').text().split(',').join(', ');
							$('.splitids').text(splitids);

							$('#k2-debug .inside').append('<pre><span style="color:red;">Asking Google for page views...</span></pre>');

							var count = jsonids.length;
							var c = 1;
							$.each(jsonids, function(i, id) {
								$.ajax({
									type: 'GET',
									url: ajaxurl,
									data: {action: 'k2_post_views_refresh', ids: id}
								}).done(function(data) {
									$('#k2-debug .inside').append(data);

									c++;
									if(c > count) {
										$('#k2-debug .inside').append('<pre><span style="color:red;">Done.</span></pre>');
										$('#k2-init-pageview-refresh').removeAttr('disabled', 'disabled');
									}
								});

							});

						});
					}

				});

			});
		</script>

		<style type="text/css">
			.splitids {
				word-wrap: break-word;
			}
		</style>

	</div>
<?php }

add_action('k2_ajax_k2_trending_pages', 'k2_trending_pages'); // Allows the trending pages to be accessed via ajax
add_action('k2_ajax_nopriv_k2_trending_pages', 'k2_trending_pages');

function k2_trending_pages() {

	$data = get_option('google_analytics_data');

	if(isset($data['links'])) { ?>
		<section class="trending">
		<h1><span class="trending-icon"><img src="<?php echo k2_get_static_url('v2'); ?>/img/trending-icon.png" /></span> Popular on Komando.com</h1>
		<div class="trending-pager">
			<div class="trending-back">
				<i class="fa fa-angle-left"></i>
			</div>
			<div class="trending-next">
				<i class="fa fa-angle-right"></i>
			</div>
		</div>
		<div class="trending-grid clearfix">
	<?php }

	foreach ($data['links'] as $link) {
		$url_array = parse_url($link, PHP_URL_PATH);
		$segments = explode('/', $url_array);
		$num_segments = count($segments);
		$post_id = $segments[2];

		if(is_numeric($post_id)) {
			$post_check = get_post($post_id);

			if(!empty($post_check) && get_post_status($post_id) == 'publish') {
				$all_ids[] = $post_id;
			}
		}
	}

	$ga_ids = array_unique($all_ids);

	$i = 0;

	foreach ($ga_ids as $ga_id) {
		if($i >= $data['trending-amount']) {
			break;
		}

		$i++;
		$image_id = get_post_thumbnail_id($ga_id);

		$placeholder_image = k2_get_static_url('v2') . '/img/placeholder-image.png';
		$thumbnail_image = wp_get_attachment_image_src($image_id, 'thumbnail')[0];

		if(empty($thumbnail_image) || wp_get_attachment_image_src($image_id, 'thumbnail')[1] < 310) {
			$thumbnail_image = $placeholder_image;
		}
		?>
		<article class="trending-grid-item">
			<a href="<?php echo get_permalink($ga_id); ?>">
				<figure>
					<div><img src="<?php echo $placeholder_image; ?>" data-src="<?php echo $thumbnail_image; ?>" alt="<?php echo get_the_title($ga_id); ?>" /></div>
				</figure>
				<div class="trending-grid-title">
					<h3><?php echo get_the_title($ga_id); ?></h3>
				</div>
			</a>
		</article>
	<?php
	}

	if(isset($data['links'])) {
		echo '</div></section>';
	}
}

function k2_sidebar_trending_pages() {

	global $wp_query, $post;
	$id = $wp_query->post->ID;
	$k2_post_type = $wp_query->query['post_type'];

	$data = get_option('google_analytics_data');

	if(isset($data['links'])) { ?>
		<h2 class="arrow">Popular on Komando.com</h2>

	<?php }

	foreach ($data['links'] as $link) {
		$url_array = parse_url($link, PHP_URL_PATH);
		$segments = explode('/', $url_array);
		$num_segments = count($segments);
		$post_id = $segments[2];

		if(is_numeric($post_id)) {
			$post_check = get_post($post_id);

			if(!empty($post_check) && get_post_status($post_id) == 'publish') {
				$all_ids[] = $post_id;
			}
		}
	}

	$ga_ids = array_unique($all_ids);

	$k2_trending_ads = get_option('k2_trending_ads');
	foreach ($k2_trending_ads as $key => $trending_ad) {
		if ($trending_ad['active'] == 1) {
			$trending_active_ads[] = $trending_ad;
		}
	}

	if($trending_active_ads) {

		if(count($trending_active_ads) > 1) {

			shuffle($trending_active_ads);

			$i = 1;
		foreach ($trending_active_ads as $trending_ad) {

			if($i > 2) {
				break;
			}
			?>

			<article class="grid-item grid-ad" data-article-url="<?php echo $trending_ad['ad-link']; ?>" data-article-id="999999">
				<figure>
					<a href="<?php echo $trending_ad['ad-link']; ?>">
						<img src="<?php echo k2_get_static_url('v2') . '/img/placeholder-image.png'; ?>" data-src="<?php echo $trending_ad['trending-image']; ?>" alt="<?php echo $trending_ad['ad-text']; ?>" />
					</a>
				</figure>
				<div class="grid-item-body">
					<header>
						<?php if($trending_ad['presented-by-link']) { echo '<a href="' . $trending_ad['presented-by-link'] . '">'; } ?><span class="grid-item-section">Presented by <?php echo $trending_ad['presented-by-text']; ?></span><?php if($trending_ad['presented-by-link']) { echo '</a>'; } ?>
						<h3><a href="<?php echo $trending_ad['ad-link']; ?>"><?php echo $trending_ad['ad-text']; ?></a></h3>
					</header>
				</div>
			</article>
		<?php echo $trending_ad['ad-html']; ?>
			<script type="text/javascript">
				jQuery('document').ready(function() {
					ga("set", "Trending Sidebar Ad - <?php echo $k2_post_type; ?>", "<?php echo $trending_ad['advertiser-name']; ?>");
				});
			</script>

		<?php
		$i++;
		}

		$trending_count = 4;
		} else { ?>

			<article class="grid-item grid-ad" data-article-url="<?php echo $trending_active_ads[0]['ad-link']; ?>" data-article-id="999999">
				<figure>
					<a href="<?php echo $trending_active_ads[0]['ad-link']; ?>">
						<img src="<?php echo k2_get_static_url('v2') . '/img/placeholder-image.png'; ?>" data-src="<?php echo $trending_active_ads[0]['trending-image']; ?>" alt="<?php echo $trending_active_ads[0]['ad-text']; ?>" />
					</a>
				</figure>
				<div class="grid-item-body">
					<header>
						<?php if($trending_active_ads[0]['presented-by-link']) { echo '<a href="' . $trending_active_ads[0]['presented-by-link'] . '">'; } ?><span class="grid-item-section">Presented by <?php echo $trending_active_ads[0]['presented-by-text']; ?></span><?php if($trending_active_ads[0]['presented-by-link']) { echo '</a>'; } ?>
						<h3><a href="<?php echo $trending_active_ads[0]['ad-link']; ?>"><?php echo $trending_active_ads[0]['ad-text']; ?></a></h3>
					</header>
				</div>
			</article>
		<?php echo $trending_active_ads[0]['ad-html']; ?>
			<script type="text/javascript">
				jQuery('document').ready(function() {
					ga("set", "Trending Sidebar Ad - <?php echo $k2_post_type; ?>", "<?php echo $trending_active_ads[0]['advertiser-name']; ?>");
				});
			</script>

			<?php
			$trending_count = 5;
		}

	} else {
		$trending_count = 6;
	}

	$i = 1;

	foreach ($ga_ids as $ga_id) {
		if($i > $trending_count) {
			break;
		}

		$post_type = get_post_type($ga_id);

		$i++;
		$image_id = get_post_thumbnail_id($ga_id);

		$placeholder_image = k2_get_static_url('v2') . '/img/placeholder-image.png';
		$thumbnail_image = wp_get_attachment_image_src($image_id, 'medium')[0];

		if(empty($thumbnail_image) || wp_get_attachment_image_src($image_id, 'medium')[1] < 520) {
			$thumbnail_image = $placeholder_image;
		} ?>
            <article class="grid-item<?php if($post_type == 'viral_video') { echo ' video'; } if(!empty($app_thumb)) { echo ' app'; } ?>" data-article-url="<?php echo get_permalink($post_id); ?>" data-article-id="<?php echo $post_id; ?>">
                <figure>
                    <a href="<?php echo get_permalink($ga_id); ?>">
                        <img src="<?php echo $placeholder_image; ?>" data-src="<?php echo $thumbnail_image; ?>" alt="<?php echo get_the_title($ga_id); ?>" />
                        <?php if($post_type == 'viral_video') { echo '<div><i class="fa fa-play-circle-o"></i></div>'; } if(!empty($app_thumb)) { echo '<div><img src="' . $app_thumb . '" alt="' . get_the_title($post_id) . '" /></div>'; } ?>
                    </a>
                </figure>
                <div class="grid-item-body">
                    <header>
                        <span class="grid-item-section hide-mobile"><?php echo k2_get_post_type($post_type); ?></span>
                        <h3><a href="<?php echo get_permalink($ga_id); ?>"><?php echo get_the_title($ga_id); ?></a></h3>
                    </header>
                    <div class="grid-item-meta hide-mobile clearfix">
                        <div class="grid-item-share">
                            <span class="icon-k2-share"></span> Share
                        </div>
                        <div class="grid-item-share-icons hide-mobile">
                            <div class="st_email_custom share-button" st_url="<?php echo get_permalink($ga_id); ?>" st_title="<?php echo get_the_title($ga_id); ?>"></div>
                            <div class="st_facebook_custom share-button" st_url="<?php echo get_permalink($ga_id); ?>" st_title="<?php echo get_the_title($ga_id); ?>"></div>
                            <div class="st_twitter_custom share-button" st_url="<?php echo get_permalink($ga_id); ?>" st_title="<?php echo get_the_title($ga_id); ?>"></div>
                            <div class="st_googleplus_custom share-button" st_url="<?php echo get_permalink($ga_id); ?>" st_title="<?php echo get_the_title($ga_id); ?>"></div>
                            <div class="st_pinterest_custom share-button" st_url="<?php echo get_permalink($ga_id);?>" st_title="<?php echo get_the_title($ga_id); ?>"></div>
                        </div>
                        <?php k2_post_view($ga_id, get_permalink($ga_id), $post_type); ?>
                    </div>
                </div>
            </article>
        <?php }
}

add_action('k2trendingrefreshhook', 'k2_trending_refresh_pre');

function k2_trending_refresh_pre() {
	$data = get_option('google_analytics_data');
	k2_trending_refresh($data['time-switch'], $data['trending-amount']);
}

add_action('k2_ajax_k2_trending_refresh', 'k2_trending_refresh'); // This handles the fetch ids for admins
add_action('k2_ajax_nopriv_k2_trending_refresh', 'k2_trending_refresh');

function k2_trending_refresh($time_switch = '8', $trending_amount = '24') {

	date_default_timezone_set('America/Phoenix');
	$data = get_option('google_analytics_data');

	//Yossi: I'm leaving this duplicate line here because it needs to run only once when the cron job for this hook runs
	require_once dirname(__FILE__) . '/google-api-php-client/autoload.php';

	$sort = '-ga:pageviews';
	$max_results = $trending_amount * 5;
	$gadata = getGoogleAnalyticsData("1daysAgo", array('sort' => $sort, 'max-results' => $max_results));

	foreach ($gadata['rows'] as $row) {
		$links[] = $row[0];
	}

	if(!empty($links) && count($links) >= 20) {
		$last_refresh = time();
		$last_failed_refresh = '';
		$refresh_status = 'good';
		$link_dump = $links;
	} elseif(!empty($links) && count($links) <= 20 && count($links) >= 1) {
		$last_refresh = $data['last-refresh'];
		$last_failed_refresh = time();
		$refresh_status = 'fallback';
		$link_dump = $data['links'];
		$error_message = "There were not enough trending articles, using cached data from $last_refresh";
		error_log($error_message);
		syslog(LOG_ERR, $error_message);
	} else {
		$last_refresh = $data['last-refresh'];
		$last_failed_refresh = time();
		$refresh_status = 'recent';

		$links = new WP_Query([
			'numberposts' => 24,
		    'offset' => 0,
		    'category' => 0,
		    'orderby' => 'post_date',
		    'order' => 'DESC',
			'post_type' => ['columns', 'downloads', 'apps', 'cool_sites', 'tips', 'buying_guides', 'small_business']
		]);

		$link_dump = [];
		foreach ($links->posts as $link) {
			$link_dump[] = get_permalink($link->ID);
		}

		$error_message = "There was an error with Google Analytics API, using recent articles";
		error_log($error_message);
		syslog(LOG_ERR, "There was an error with Google Analytics API, using recent articles");
	}

	$data = [
		'time-switch' => $time_switch,
		'trending-amount' => $trending_amount,
		'last-refresh' => $last_refresh,
		'last-failed-refresh' => $last_failed_refresh,
		'last-refresh-status' => $refresh_status,
		'links' => $link_dump
	];

	update_option('google_analytics_data', $data);
}

function k2_post_view($id) {

	// Gets the post view count and shows it if over 7000
	$post_meta = get_post_meta($id, 'ga_pageviews', true);

	if(!empty($post_meta['views'])) {

		if($post_meta['views'] > 7000) {

			if($post_meta['views'] > 29000) {
				return '<div class="grid-item-views fire-red"><i class="fa fa-fire"></i> <span class="hide-tablet">' . number_format($post_meta['views']) . '</span><span class="hide-desktop">' . numbertok($post_meta['views']) . '</span></div>';
			} elseif($post_meta['views'] > 15000) {
				return '<div class="grid-item-views fire-orange"><i class="fa fa-fire"></i> <span class="hide-tablet">' . number_format($post_meta['views']) . '</span><span class="hide-desktop">' . numbertok($post_meta['views']) . '</span></div>';
			} elseif($post_meta['views'] > 7000) {
				return '<div class="grid-item-views"><i class="fa fa-fire"></i> <span class="hide-tablet">' . number_format($post_meta['views']) . '</span><span class="hide-desktop">' . numbertok($post_meta['views']) . '</span></div>';
			}
		}
	}
}

add_action('k2_ajax_k2_ga_fetch_ids', 'k2_ga_fetch_ids'); // This handles the fetch ids for admins
add_action('k2_ajax_nopriv_k2_ga_fetch_ids', 'k2_ga_fetch_ids');

function k2_ga_fetch_ids($num = 50) {

	if(empty($num)) {
		$num = 50;
	}

	$args = array(
		'posts_per_page' => $num,
		'post_type' => array('columns', 'downloads', 'apps', 'cool_sites', 'tips', 'buying_guides', 'happening_now', 'small_business', 'new_technologies'),
		'post_status' => 'publish'
	);

	$my_query = new WP_Query($args);

	foreach ($my_query->posts as $post) {
		$ids[] = $post->ID;
	}

	wp_reset_query();

	echo json_encode($ids);
	return $ids;
}

add_action('k2_ajax_k2_post_views_refresh', 'k2_post_views_refresh'); // This handles the fetch ids for admins
add_action('k2_ajax_nopriv_k2_post_views_refresh', 'k2_post_views_refresh');

add_action('k2postviewsrefreshhook', 'k2_post_views_refresh');

function getGoogleAnalyticsData($start_date = "2014-12-01", $extra_opts = array(), $url = "", $end_date = "today"){

	//get Google Analytics data - url filtering can be specified to limit to a single query, in addition to other filtering

	try{
		$client = new Google_Client();
		$client->setApplicationName('Google Analytics API - Kim Komando');

		$client->setClientId(GOOGLE_CLIENT_ID);
		$client->setClientSecret(GOOGLE_CLIENT_SECRET);
		$client->setRedirectUri(GOOGLE_REDIRECT_URI);
		$client->setDeveloperKey(GOOGLE_DEVELOPER_KEY);
		$client->refreshToken(GOOGLE_REFRESH_TOKEN);

		$service = new Google_Service_Analytics($client);

		$ids = 'ga:328788';
		$metrics = 'ga:pageviews';
		$dimensions = 'ga:pagePath';

		$filters = 'ga:hostname==' . GOOGLE_HOSTNAME;
		if(!empty($url)){
			$filters = 'ga:pagePath==' . GOOGLE_HOSTNAME . $url;
		}

		$opts = array_merge(array('dimensions' => $dimensions, 'filters' => $filters), $extra_opts);
		$gadata = $service->data_ga->get($ids, $start_date, $end_date, $metrics, $opts);
		return $gadata;
	}
	catch(Exception $e){
		$error_message = "Error in 'app/wp-content/plugins/k2-google-analytics/k2-google-analytics.php' in function 'getGoogleAnalyticsData': error retrieving analytics data from Google.";
		if(!empty($url)){
			$error_message .= " URL: " . $url;
		}
		error_log($error_message);
		syslog(LOG_ERR, $error_message);
		return false;
	}
}

function k2_post_views_refresh() {

	// Google only allows 10 requests per second and 50,000 per day
	// https://developers.google.com/analytics/devguides/reporting/core/v2/limits-quotas#discovery

	date_default_timezone_set('America/Phoenix');

	//Yossi: I'm leaving this duplicate line here because it needs to run only once when the cron job for this hook runs
	require_once dirname(__FILE__) . '/google-api-php-client/autoload.php';

	$ids = array();
	$single_update = false;
	if(isset($_GET['ids'])) {
		$ids[] = $_GET['ids'];
		if(isset($_GET['single_article_update']) && $_GET['single_article_update'] = 1){
//			$ids = array($_GET['ids']);
			$single_update = true;
		}
	}

	if(empty($ids)){
		$ids = k2_ga_fetch_ids(50);
	}

	foreach ($ids as $id) {

		$post_id = $id;
		$post_meta = get_post_meta($post_id, 'ga_pageviews', true);
		$url = get_permalink($post_id);
		$url = parse_url($url, PHP_URL_PATH);

		$i = 1;

		if(empty($post_meta) || time() > strtotime('+2 hours', $post_meta['last_refresh']) || $post_meta['views'] <= 0) {

			$gadata = getGoogleAnalyticsData('2014-12-01', array(), $url);

			if(!$gadata){
				$data = array(
					'views' => '-1',
					'last_refresh' => time()
				);
			}
			else{
				$data = array(
					'views' => $gadata['totalsForAllResults']['ga:pageviews'],
					'last_refresh' => time()
				);
			}

			if ($single_update){
				$update = array('views' => $data['views']);
				echo json_encode($update);
			}
			else{
				echo '<pre>' . $post_id . ', ' . $data['views'] . '</pre>';
			}
			update_post_meta($post_id, 'ga_pageviews', $data);

			usleep(500000);
			$i++;

		}
		else {
            if ($single_update){
                $update = array(
                    'views' => 'No update done.<br>Wait 2 hrs and try again.');
                echo json_encode($update);
            } else {
                echo '<pre style="color: #cacaca;">ID ' . $post_id . ': Up to date as of ' . date('F j, Y, g:i a', $post_meta['last_refresh']) . ' with ' . $post_meta['views'] . ' views</pre>';
            }
		}
	}
}

register_activation_hook(__FILE__, 'k2_post_views_refresh_activation');

function k2_post_views_refresh_activation() {
	if (!current_user_can('activate_plugins')) {
		return;
	}

	wp_schedule_event('1387670400', 'quarterday', 'k2postviewsrefreshhook');
}

register_deactivation_hook(__FILE__, 'k2_post_views_refresh_deactivation');

function k2_post_views_refresh_deactivation() {
	if (!current_user_can('activate_plugins')) {
		return;
	}

	$old_next_schedule = wp_next_scheduled('k2_post_views_refresh_hook');
	if($old_next_schedule) {
		wp_unschedule_event($old_next_schedule, 'k2_post_views_refresh_hook');
	}

	$restrictor_next = wp_next_scheduled('k2postviewsrefreshhook');
	wp_unschedule_event($restrictor_next, 'k2postviewsrefreshhook');
}


/**
 * Update Google Pageviews List
 *
 * Create the box in the editor
 */
add_action('add_meta_boxes', 'k2_google_analytics_create_button_box');
function k2_google_analytics_create_button_box(){
    if (current_user_can('edit_posts')) {
        // Update Google Pageviews button container
        add_meta_box('update_pageviews_box_id', "Update Google Pageviews", 'add_update_previews_button', 'post', 'side', 'high');
        add_meta_box('update_pageviews_box_id', "Update Google Pageviews", 'add_update_previews_button', 'columns', 'side', 'high');
        add_meta_box('update_pageviews_box_id', "Update Google Pageviews", 'add_update_previews_button', 'downloads', 'side', 'high');
        add_meta_box('update_pageviews_box_id', "Update Google Pageviews", 'add_update_previews_button', 'apps', 'side', 'high');
        add_meta_box('update_pageviews_box_id', "Update Google Pageviews", 'add_update_previews_button', 'cool_sites', 'side', 'high');
        add_meta_box('update_pageviews_box_id', "Update Google Pageviews", 'add_update_previews_button', 'tips', 'side', 'high');
        add_meta_box('update_pageviews_box_id', "Update Google Pageviews", 'add_update_previews_button', 'buying_guides', 'side', 'high');
        add_meta_box('update_pageviews_box_id', "Update Google Pageviews", 'add_update_previews_button', 'charts', 'side', 'high');
        add_meta_box('update_pageviews_box_id', "Update Google Pageviews", 'add_update_previews_button', 'happening_now', 'side', 'high');
        add_meta_box('update_pageviews_box_id', "Update Google Pageviews", 'add_update_previews_button', 'small_business', 'side', 'high');
        add_meta_box('update_pageviews_box_id', "Update Google Pageviews", 'add_update_previews_button', 'new_technologies', 'side', 'high');
    }
}

function add_update_previews_button() {
    global $post;
    $pageviews = get_post_meta($post->ID, 'ga_pageviews', true);
    if (is_array($pageviews)){
        $refreshed = date('M j, Y, h:i a', $pageviews['last_refresh'] - (7 * 3600));
        $viewsnow = number_format($pageviews['views']);
    } elseif (is_string($pageviews)){
        $refreshed = 'Not yet counted.';
        $viewsnow = 'Unknown';
    }
    // Show Update Google Pageviews button here ?>
    <em><span style="font-size: 8pt">Use this function sparingly!</span></em>
    <a href="#" id="add_update_pageviews_button" class="button-secondary" title="Click to Update Google Pageviews">
        <span>Update Google Pageviews</span></a><div class="waiticon waiticon-hide"></div>
    <p>Last Update:&nbsp;&nbsp;<b><?php echo $refreshed; ?></b>
    <br>Current Pageviews:&nbsp;&nbsp;<?php echo $viewsnow; ?>
    <div class="update-message">Updated Pageviews:&nbsp;&nbsp;<span class="update-count"></span></div>
    <style>
        .update-message {
            display: none;
        }
        .waiticon {
            background: url('/wp-admin/images/wpspin_light.gif') no-repeat;
            background-size: 16px 16px;
            opacity: .7;
            filter: alpha(opacity=70);
            width: 16px;
            height: 16px;
            margin: 5px 5px 0;
            visibility: visible;
            float: none;
            display: inline-block;
        }
        .waiticon-hide {
            display: none;
        }
    </style>

<script>
jQuery(function($){

    var ajaxurl = "<?php echo get_template_directory_uri() . '/k2-ajax.php'; ?>";

    $('#add_update_pageviews_button').on('click', function(e){
        e.preventDefault();
        $('.waiticon').removeClass('waiticon-hide');
        $.ajax({
            type: 'GET',
            url: ajaxurl,
            data: {action: 'k2_post_views_refresh', ids: <?php echo $post->ID; ?>, single_article_update: 1},
            dataType:'json'
        }).done(function(update) {
            console.log(update);
            $('.update-count').html(update.views);
            $('.update-message').show();
            $('.waiticon').addClass('waiticon-hide');
        });
    })

})

</script>
<?php
    //            $('.update-count').html(data.views + ' ' + data.last_refresh);
}
// Kills plugin update lookup
add_filter('http_request_args', 'hidden_plugin_k2_google_analytics', 5, 2);

function hidden_plugin_k2_google_analytics($r, $url) {
    if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
        return $r; // Not a plugin update request. Bail immediately.
    $plugins = unserialize($r['body']['plugins']);
    unset($plugins->plugins[plugin_basename(__FILE__)]);
    unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
    $r['body']['plugins'] = serialize($plugins);
    return $r;
}
