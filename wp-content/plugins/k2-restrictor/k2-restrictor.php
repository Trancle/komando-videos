<?php
/*
Plugin Name: K2 Restrictor
Plugin URI: http://www.komando.com
Description: Restricts access to posts, allows to change date
author: Kelly Karnetsky
Version: 0.1
Author URI: http://www.komando.com
*/

// Adding the meta boxes
add_action('add_meta_boxes', 'add_restrictor_meta');

function add_restrictor_meta() {
	if(current_user_can('edit_others_posts')) {
		add_meta_box('restrictor_meta_id', 'Content Status', 'restrictor_meta_box', 'post', 'side', 'high');
		add_meta_box('restrictor_meta_id', 'Content Status', 'restrictor_meta_box', 'columns', 'side', 'high');
		add_meta_box('restrictor_meta_id', 'Content Status', 'restrictor_meta_box', 'downloads', 'side', 'high');
		add_meta_box('restrictor_meta_id', 'Content Status', 'restrictor_meta_box', 'apps', 'side', 'high');
		add_meta_box('restrictor_meta_id', 'Content Status', 'restrictor_meta_box', 'cool_sites', 'side', 'high');
		add_meta_box('restrictor_meta_id', 'Content Status', 'restrictor_meta_box', 'tips', 'side', 'high');
		add_meta_box('restrictor_meta_id', 'Content Status', 'restrictor_meta_box', 'buying_guides', 'side', 'high');
		add_meta_box('restrictor_meta_id', 'Content Status', 'restrictor_meta_box', 'charts', 'side', 'high');
		add_meta_box('restrictor_meta_id', 'Content Status', 'restrictor_meta_box', 'small_business', 'side', 'high');
	}
}

function restrictor_meta_box() {
	global $post;

	$content_status = get_post_meta($post->ID, 'restrictor_data', true);

	if($content_status['is_membership'] == 'yes') {
		$is_membership = 'yes';
	} else if(!empty($content_status['sched_date']) && $content_status['is_membership'] == 'sched') {
		$is_membership = 'sched';
	} else {
		$is_membership = 'no';
	}

	if(empty($content_status['sched_date'])) {
		$sched_date = strtotime('7am tomorrow');
	} else {
		$sched_date = $content_status['sched_date'];
	}

	$sched_days = $content_status['sched_days'];
	$sched_release = (86400 * $sched_days) + $sched_date;
	$now = strtotime('7am tomorrow');

	$sched_release_conv = new DateTime("@$sched_release");
	$now_conv = new DateTime("@$now");
	$interval = $now_conv->diff($sched_release_conv);
	$days_to_go = $interval->format('%a');
?>

<style>
.clearfix {
  *zoom: 1;
}

.clearfix:before,
.clearfix:after {
  display: table;
  line-height: 0;
  content: "";
}

.clearfix:after {
  clear: both;
}

.restrictor-wrapper {
	width: 95%;
	margin: auto;
}

.restrictor-status {
	height: 44px;
	overflow: hidden;
}

.restrictor-public,
.restrictor-added-time {
	width: 100%;
	background: #5ec54c;
	color: #ffffff;
	font-size: 18px;
	text-align: center;
	padding: 14px 0;
}

.restrictor-membership,
.restrictor-make-membership {
	width: 100%;
	background: #333333;
	color: #ffffff;
	font-size: 18px;
	text-align: center;
	padding: 14px 0;
}

.restrictor-make-member-only {
	float: left;
	display: inline-block;
	background: #393939;
	color: #ffffff;
	font-size: 10px;
	text-align: center;
	padding: 4px 6px;
	-webkit-border-radius: 6px;
	border-radius: 6px;
	border: 1px solid #000000;
	cursor: pointer;
}

.restrictor-change-date {
	float: right;
	display: inline-block;
	color: #000000;
	margin: -15px 0 0 0;
}

.restrictor-change-date-infinity,
.restrictor-change-date-custom {
	display: inline-block;
	background: #f8f8f8;
	color: #000000;
	font-size: 10px;
	text-align: center;
	padding: 4px 6px;
	-webkit-border-radius: 6px;
	border-radius: 6px;
	border: 1px solid #bbbbbb;
	cursor: pointer;
}

.restrictor-added-time,
.restrictor-make-membership { 
	display: none;
}

.make-public {
	font-size: 10px;
	text-align: center;
	margin: 8px 0 12px 0;
}

.sched-options {
	font-size: 10px;
	text-align: center;
	margin: 20px 0 12px 0;
}

.make-public-90,
.make-public-infinity,
.make-public-custom {
	display: inline-block;
	background: #f8f8f8;
	color: #000000;
	font-size: 10px;
	text-align: center;
	padding: 4px;
	-webkit-border-radius: 6px;
	border-radius: 6px;
	border: 1px solid #bbbbbb;
	cursor: pointer;
}

.make-public-selected {
	background: #333333;
	color: #ffffff;
}

.oversize {
	font-size: 34px;
	vertical-align: bottom;
}

#custom_days {
	width: 40px;
	text-align: center;
	vertical-align: baseline;
	padding: 0;
	margin: -10px 0 0 0;
}

.public-cancel,
.sched-cancel {
	display: none;
	color: #ff0000;
	text-decoration: underline;
	margin: auto;
	cursor: pointer;
	text-align: center;
}

.restrictor-save {
	display: none;
	text-align: center;
	font-size: 10px;
	margin: 12px 0;
	font-style: italic;
}

</style>

<div class="restrictor-wrapper">
	<?php if($is_membership == 'yes') { ?>
		<div class="restrictor-status">
			<div class="restrictor-added-time">Will be Public for 90 Days</div>
			<div class="restrictor-membership">Requires Membership</div>
		</div>
		<div class="make-public">Make Public for:<br />
			<div class="make-public-90">90 Days</div> 
			<div class="make-public-custom">Custom</div> 
			<div class="make-public-infinity">Forever</div>
		</div>
		<div class="public-cancel">Cancel</div>
		<div class="restrictor-save">** Don't forget to save **</div>
	<?php } else if($is_membership == 'sched') { ?>
		<div class="restrictor-status">
			<div class="restrictor-public">Public for <?php echo $days_to_go; ?> More Days</div>
			<div class="restrictor-make-membership">Will Require Membership</div>
		</div>
		<div class="sched-options clearfix">
			<div class="restrictor-make-member-only">Make Members Only</div> 
			<div class="restrictor-change-date">
				Change Date<br />
				<div class="restrictor-change-date-infinity">Infinity</div>
				<div class="restrictor-change-date-custom">Custom</div>
			</div>
		</div>
		<div class="sched-cancel">Cancel</div>
		<div class="restrictor-save">** Don't forget to save **</div>
	<?php } else { ?>
		<div class="restrictor-status">
			<div class="restrictor-public">Public for <span class="oversize">∞</span> More Days</div>
			<div class="restrictor-make-membership">Will Require Membership</div>
		</div>
		<div class="sched-options clearfix">
			<div class="restrictor-make-member-only">Make Members Only</div> 
			<div class="restrictor-change-date">
				Change Date<br />
				<div class="restrictor-change-date-infinity">Infinity</div>
				<div class="restrictor-change-date-custom">Custom</div>
			</div>
		</div>
		<div class="sched-cancel">Cancel</div>
		<div class="restrictor-save">** Don't forget to save **</div>
	<?php } ?>

	<input name="cur_status" value="<?php echo $is_membership; ?>" type="hidden">
	<input id="is_membership" name="restrictor_data[is_membership]" value="<?php echo $is_membership; ?>" type="hidden">
	<input id="sched_date" name="restrictor_data[sched_date]" value="<?php echo $sched_date; ?>" type="hidden">
	<input id="sched_days" name="restrictor_data[sched_days]" value="<?php echo $sched_days; ?>" type="hidden">

</div>

<script>
jQuery(document).ready(function() {
	jQuery('.restrictor-make-member-only').click(function() {
		jQuery('.restrictor-public').slideUp(400);
		jQuery('.restrictor-make-membership').slideDown(400);
		jQuery('.sched-cancel, .restrictor-save').show();
		jQuery('#is_membership').val('yes');
	});
	jQuery('.restrictor-change-date-custom').click(function() {
		jQuery('.restrictor-public').slideDown(400);
		jQuery('.restrictor-make-membership').slideUp(400);
		jQuery('.restrictor-public').html('Will be Public for <input id="custom_days" name="custom_days" value="<?php if(is_numeric($days_to_go) && $days_to_go != 0) { echo $days_to_go; } else { echo "∞"; } ?>" maxlength="3"> Days');
		jQuery('.sched-cancel, .restrictor-save').show();
		jQuery('#custom_days').keyup(function() {
			var inVal = jQuery(this).val();
			jQuery('#sched_days').val(inVal);
		});
		jQuery('#is_membership').val('sched');
	});
	jQuery('.restrictor-change-date-infinity').click(function() {
		jQuery('.restrictor-public').slideDown(400);
		jQuery('.restrictor-make-membership').slideUp(400);
		jQuery('.restrictor-public').html('Will be Public for <span class="oversize">∞</span> Days');
		jQuery('.sched-cancel, .restrictor-save').show();
		jQuery('#is_membership').val('no');
		jQuery('#sched_days').val('∞');
	});
	jQuery('.sched-cancel').click(function() {
		jQuery('.restrictor-public').slideDown(400);
		jQuery('.restrictor-make-membership').slideUp(400);
		jQuery('.restrictor-public').html('Public for <?php if(is_numeric($days_to_go) && $days_to_go != 0) { echo $days_to_go; } else { echo "<span class=\"oversize\">∞</span>"; } ?> More Days');
		jQuery(this).hide();
		jQuery('.restrictor-save').hide();
	});
	jQuery('.make-public-90, .make-public-custom, .make-public-infinity').click(function() {
		if(jQuery(this).hasClass('make-public-90')) {
			jQuery(this).addClass('make-public-selected');
			jQuery('.make-public-custom, .make-public-infinity').removeClass('make-public-selected');
			jQuery('.restrictor-added-time').html('Will be Public for 90 Days');
			jQuery('#is_membership').val('sched');
			jQuery('#sched_days').val('90');

		} else if(jQuery(this).hasClass('make-public-custom')) {
			jQuery(this).addClass('make-public-selected');
			jQuery('.make-public-90, .make-public-infinity').removeClass('make-public-selected');
			jQuery('.restrictor-added-time').html('Will be Public for <input id="custom_days" name="custom_days" value="90" maxlength="3"> Days');
			jQuery('#custom-days').focus();
			jQuery('#is_membership').val('sched');
			jQuery('#custom-days').keyup(function() {
				var inVal = jQuery(this).val();
				jQuery('#sched_days').val(inVal);
			});

		} else if(jQuery(this).hasClass('make-public-infinity')) {
			jQuery(this).addClass('make-public-selected');
			jQuery('.make-public-90, .make-public-custom').removeClass('make-public-selected');
			jQuery('.restrictor-added-time').html('Will be Public for <span class="oversize">∞</span> Days');
			jQuery('#is_membership').val('no');
			jQuery('#sched_days').val('∞');
		}

		jQuery('.restrictor-membership').slideUp(400);
		jQuery('.restrictor-added-time').slideDown(400);
		jQuery('.public-cancel, .restrictor-save').show();
	});
	jQuery('.public-cancel').click(function(){
		jQuery('.make-public-90, .make-public-custom, .make-public-infinity').removeClass('make-public-selected');
		jQuery('.restrictor-added-time').slideUp(400);
		jQuery('.restrictor-membership').slideDown(400);
		jQuery('#is_membership').val('yes');
		jQuery(this).hide();
		jQuery('.restrictor-save').hide();
	});
});
</script>

<?php 
}

add_filter('manage_posts_columns', 'add_restrictor_column');
add_filter('manage_columns_posts_columns', 'add_restrictor_column');
add_filter('manage_downloads_posts_columns', 'add_restrictor_column');
add_filter('manage_apps_posts_columns', 'add_restrictor_column');
add_filter('manage_cool_sites_posts_columns', 'add_restrictor_column');
add_filter('manage_tips_posts_columns', 'add_restrictor_column');
add_filter('manage_buying_guides_posts_columns', 'add_restrictor_column');
add_filter('manage_charts_pages_columns', 'add_restrictor_column');
add_filter('manage_small_business_posts_columns', 'add_restrictor_column');

function add_restrictor_column($columns) {
	$rebuild = array();
	foreach($columns as $key => $title) {
		if($key == 'author') {
			$rebuild['membership_status'] = 'Membership Status';
		}
		$rebuild[$key] = $title;
	}
    return $rebuild;
}

add_action('manage_posts_custom_column', 'restrictor_custom_column', 10, 2);
add_action('manage_columns_posts_custom_column', 'restrictor_custom_column', 10, 2);
add_action('manage_downloads_posts_custom_column', 'restrictor_custom_column', 10, 2);
add_action('manage_apps_posts_custom_column', 'restrictor_custom_column', 10, 2);
add_action('manage_cool_sites_posts_custom_column', 'restrictor_custom_column', 10, 2);
add_action('manage_tips_posts_custom_column', 'restrictor_custom_column', 10, 2);
add_action('manage_buying_guides_posts_custom_column', 'restrictor_custom_column', 10, 2);
add_action('manage_charts_pages_custom_column', 'restrictor_custom_column', 10, 2);
add_action('manage_small_business_posts_custom_column', 'restrictor_custom_column', 10, 2);

function restrictor_custom_column($column_name, $post_ID) {
    if($column_name == 'membership_status') {
        $content_status = get_post_meta($post_ID, 'restrictor_data', true);

        if(isset($content_status['is_membership'])) {
	        if($content_status['is_membership'] == 'yes') {
	        	echo '<div style="color:#ffffff;background:#000000;display:inline-block;padding:0 4px;">Membership Required</div>';
	        } else if($content_status['is_membership'] == 'sched') {
	        	
	        	if(empty($content_status['sched_date'])) {
					$sched_date = strtotime('7am tomorrow');
				} else {
					$sched_date = $content_status['sched_date'];
				}

				$sched_days = $content_status['sched_days'];
				$sched_release = (86400 * $sched_days) + $sched_date;
				$now = strtotime('7am tomorrow');

				$sched_release_conv = new DateTime("@$sched_release");
				$now_conv = new DateTime("@$now");
				$interval = $now_conv->diff($sched_release_conv);
				$days_to_go = $interval->format('%a');

	        	echo '<div style="color:#ffffff;background:#5ec54c;display:inline-block;padding:0 4px;">'. $days_to_go .' days left<div>';
	        } else {
	        	echo '<div style="color:#ffffff;background:#5ec54c;display:inline-block;padding:0 4px;">Public</div>';
	        }
        } else {
        	echo '<div style="color:#ffffff;background:#5ec54c;display:inline-block;padding:0 4px;">Public</div>';
        }
    }
}

//Save data
add_action('save_post', 'restrictor_save_data');

function restrictor_save_data($post_id) {
	global $post;

	// to prevent metadata or custom fields from disappearing... 
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	if(defined('DOING_AJAX') && DOING_AJAX) {
		return $post_id;
	}

	// OK, we're authenticated: we need to find and save the data
	if(isset($_POST['restrictor_data'])) {

		$sched_date = strtotime('7am tomorrow');

		if($_POST['cur_status'] == 'yes') {
			if($_POST[restrictor_data][is_membership] == 'sched') {
				$data = array(
					'is_membership' => $_POST[restrictor_data][is_membership],
					'sched_date' => $sched_date,
					'sched_days' => $_POST[restrictor_data][sched_days]
				);
				update_post_meta($post_id, 'restrictor_data', $data);

			} else if($_POST[restrictor_data][is_membership] == 'no') {
				$data = array(
					'is_membership' => $_POST[restrictor_data][is_membership],
					'sched_date' => $sched_date,
					'sched_days' => $_POST[restrictor_data][sched_days]
				);
				update_post_meta($post_id, 'restrictor_data', $data);

			} else {
				$data = $_POST['restrictor_data'];
				update_post_meta($post_id, 'restrictor_data', $data);
			}

		} else if ($_POST['cur_status'] == 'sched') {
			$data = $_POST['restrictor_data'];
			update_post_meta($post_id, 'restrictor_data', $data);

		} else {
			$data = $_POST['restrictor_data'];
			update_post_meta($post_id, 'restrictor_data', $data);

		}
	} else {
		$sched_date = strtotime('7am tomorrow');
		$data = array(
			'is_membership' => 'no',
			'sched_date' => $sched_date,
			'sched_days' => '∞'
		);
		update_post_meta($post_id, 'restrictor_data', $data);
	}
}

function restrictor_require_memebership() {
	global $post;
	$content_status = get_post_meta($post->ID, 'restrictor_data', true);

	if(isset($content_status['is_membership'])) {
		if($content_status['is_membership'] == 'yes') {
			return true;
		} else {
			return false;
		}
	}
}

add_action('k2restrictorcheckpostshook', 'restrictor_check_posts');
function restrictor_check_posts() {
	global $wpdb;
	global $post;
	$querystr = "
    SELECT $wpdb->posts.*
    FROM $wpdb->posts, $wpdb->postmeta
    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id
    AND $wpdb->postmeta.meta_key = 'restrictor_data'
    AND $wpdb->postmeta.meta_value LIKE 'a:3:{s:13:\"is_membership\";s:5:\"sched\"%'
    AND $wpdb->posts.post_status = 'publish'
    AND $wpdb->posts.post_type IN ('post', 'columns', 'downloads', 'apps', 'cool_sites', 'tips', 'buying_guides', 'charts', 'small_business')
    AND $wpdb->posts.post_date < NOW()
    ORDER BY $wpdb->posts.post_date DESC
 	";

 	$pageposts = $wpdb->get_results($querystr, OBJECT);

 	if($pageposts) {
	 	foreach ($pageposts as $post) {
	 		setup_postdata($post);
	 		$content_status = get_post_meta($post->ID, 'restrictor_data', true);

	 		$sched_date = $content_status['sched_date'];
	 		$sched_days = $content_status['sched_days'];
			$sched_release = (86400 * $sched_days) + $sched_date;
			$now = time();

			if($now >= $sched_release) {
				$post_data = $post->post_title . '<br />' . $post->guid . '<br />';
				$email_data[] = $post_data;
				$data = array('is_membership' => 'yes', 'sched_date' => '0', 'sched_days' => '0');
				update_post_meta($post->ID, 'restrictor_data', $data);
			}
	 	}
 	}
 	if(empty($email_data)) {
 		$email_data = 'No posts have switch to premium.';
 	}
 	//wp_mail('kelly.karnetsky@komando.com', 'Post Restrictor cron info', '$email_data');
}

register_activation_hook(__FILE__, 'restrictor_activation');
function restrictor_activation() {
    if (!current_user_can('activate_plugins')) {
    	return;
    }

    wp_schedule_event('1380006000', 'daily', 'k2restrictorcheckpostshook');
}

register_deactivation_hook(__FILE__, 'restrictor_deactivation');
function restrictor_deactivation() {
    if (!current_user_can('activate_plugins')) {
    	return;
    }

    $old_next_schedule = wp_next_scheduled('restrictor_check_posts_hook');
    if($old_next_schedule) {
    	wp_unschedule_event($old_next_schedule, 'restrictor_check_posts_hook');
	}

    $restrictor_next = wp_next_scheduled('k2restrictorcheckpostshook');
    wp_unschedule_event($restrictor_next, 'k2restrictorcheckpostshook');
}

// Kills plugin update lookup
function hidden_plugin_k2_restrictor($r, $url) {
	if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize($r['body']['plugins']);
	unset($plugins->plugins[plugin_basename(__FILE__)]);
	unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
	$r['body']['plugins'] = serialize($plugins);
	return $r;
}

add_filter( 'http_request_args', 'hidden_plugin_k2_restrictor', 5, 2 );
?>
