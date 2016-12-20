<?php
/*
Plugin Name: K2 Grid Blocks
Plugin URI: http://www.komando.com
Description: The blocks that get peppered into the grids
author: Kelly Karnetsky
Version: 0.1
Author URI: http://www.komando.com
*/

// Add the admin menu
add_action('admin_menu', 'k2_grid_blocks_page_menu');
function k2_grid_blocks_page_menu() {
	add_menu_page('Grid Blocks Settings', 'Grid Blocks', 'administrator', 'grid-blocks-settings', 'k2_grid_blocks_page');
	//add_submenu_page('grid-blocks-settings', 'Grid Blocks Settings', 'Grid Blocks', 'administrator', 'grid-blocks-settings', 'k2_grid_blocks_page');
}

// Register the settings
add_action('admin_init', 'k2_grid_blocks_register_settings');
function k2_grid_blocks_register_settings() {
	if (isset($_GET['page'])) { 
		if($_GET['page'] == 'grid-blocks-settings') {
			register_setting('grid_blocks_settings_options_group', 'grid_blocks_settings_options');
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('dashboard');
		}	
	}
}

function k2_grid_blocks_page() { ?>

	<div class="wrap">
		<?php screen_icon('themes'); ?> <h2>Grid Blocks Settings</h2>
		<?php 
		if($_POST == true && $_POST['check'] = 1) { 
			$data = array(
				'asker-name' => $_POST['asker-name'],
				'asker-url' => $_POST['asker-url'],
				'question-timeago' => $_POST['question-timeago'],
				'question' => $_POST['question'],
				'answer-timeago' => $_POST['answer-timeago'],
				'answer' => $_POST['answer'],
				'order' => array($_POST['block-one'], $_POST['block-two'], $_POST['block-three'])
			);
			update_option('twitter_qotd', $data);
		?>
			<div id="message" class="updated fade below-h2"><p>Changes have been updated!</p></div>
		<?php 
		} else {
			$data = get_option('twitter_qotd');
		}
		?>

		<form method="post" action="">
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row"><label for="block-one">First Block</label></th>
						<td>
							<select name="block-one" id="block-one">
								<option value="station_finder_grid_block" <?php if($data['order'][0] == 'station_finder_grid_block') { echo 'selected="selected"'; } ?>>Station Finder</option>
								<option value="kims_club_grid_block" <?php if($data['order'][0] == 'kims_club_grid_block') { echo 'selected="selected"'; } ?>>Kim's Club</option>
								<option value="twitter_question_grid_block" <?php if($data['order'][0] == 'twitter_question_grid_block') { echo 'selected="selected"'; } ?>>Twitter Question</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="block-two">Second Block</label></th>
						<td>
							<select name="block-two" id="block-two">
								<option value="station_finder_grid_block" <?php if($data['order'][1] == 'station_finder_grid_block') { echo 'selected="selected"'; } ?>>Station Finder</option>
								<option value="kims_club_grid_block" <?php if($data['order'][1] == 'kims_club_grid_block') { echo 'selected="selected"'; } ?>>Kim's Club</option>
								<option value="twitter_question_grid_block" <?php if($data['order'][1] == 'twitter_question_grid_block') { echo 'selected="selected"'; } ?>>Twitter Question</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="block-three">Third Block</label></th>
						<td>
							<select name="block-three" id="block-three">
								<option value="station_finder_grid_block" <?php if($data['order'][2] == 'station_finder_grid_block') { echo 'selected="selected"'; } ?>>Station Finder</option>
								<option value="kims_club_grid_block" <?php if($data['order'][2] == 'kims_club_grid_block') { echo 'selected="selected"'; } ?>>Kim's Club</option>
								<option value="twitter_question_grid_block" <?php if($data['order'][2] == 'twitter_question_grid_block') { echo 'selected="selected"'; } ?>>Twitter Question</option>
							</select>
						</td>
					</tr>

					<hr />

					<tr valign="top">
						<th scope="row"><label for="asker-name">Question Asker Name</label></th>
						<td><input name="asker-name" type="text" id="asker-name" value="<?php echo $data['asker-name']; ?>" class="large-text code"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="asker-url">Question Asker URL</label></th>
						<td><input name="asker-url" type="text" id="asker-url" value="<?php echo $data['asker-url']; ?>" class="large-text code"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="question-timeago">Question Timeago</label></th>
						<td><input name="question-timeago" type="text" id="question-timeago" value="<?php echo $data['question-timeago']; ?>" class="large-text code"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="question">Question</label></th>
						<td><textarea name="question" type="text" id="question" class="large-text code"><?php echo $data['question']; ?></textarea></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="answer-timeago">Answer Timeago</label></th>
						<td><input name="answer-timeago" type="text" id="answer-timeago" value="<?php echo $data['answer-timeago']; ?>" class="large-text code"></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="answer">Answer</label></th>
						<td><textarea name="answer" type="text" id="answer" class="large-text code"><?php echo $data['answer']; ?></textarea></td>
					</tr>
				</tbody>
			</table>
			<input type="hidden" name="check" value="1" />
			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
		</form>
	</div>

<?php }

function station_finder_grid_block() { ?>
	<div class="grid-item-station-finder">
		<h3><img src="<?php echo k2_get_static_url('v2'); ?>/img/station-finder-icon.png" width="24" height="24" alt="[ICON] Station Finder" />Station Finder</h3>
		<div class="sf-content">
			<span class="sf-description">Find the nearest station to listen to The Kim Komando Show!</span>
			<form class="sf-zip-lookup"><input type="text" placeholder="Postal Code..." /><button type="submit"><i class="fa fa-chevron-right"></i></button></form>
			<div class="sf-results">
				<span>Your local station:</span>
				<div class="sf-station"></div>
			</div>
		</div>
	</div>
	<script>
	jQuery(document).ready(function($) {
		$('.sf-zip-lookup').submit(function(e) {

			var form = $(this);

			$.ajax({
				type: 'GET',
				url: '<?php echo STATION_FINDER_BASE_URI; ?>/lu/zip/' + $('.sf-zip-lookup input').val() + '.json'
			}).done(
				function(stations) {
					if( stations.length == 0 ) {
						// No stations
						station_lookup_no_stations();

					} else {
						// at least 1 station, use the top one as it's the best (usually)
						var station = $.grep(stations, function(e) {
							return $.grep(e.show_times, function(f) {
								return f.show_id == 15;
							}).length != 0;
						});

						if(station.length >= 1) {

							var show_time = $.grep(station[0]['show_times'], function(e) {
								return e.show_id == 15;
							});

							$('.sf-station').html(station[0]['dial_position'] + ' ' + station[0]['band'] + ' ' + station[0]['call_sign'] + '<br />' + station[0]['city'] + ', ' + station[0]['state'] + '<br />' + show_time[0]['air_time']).parent().show();

						} else {
							station_lookup_no_stations();

						}
					}
				}
			);

			function station_lookup_no_stations() {
				$('.sf-station').html('Could not locate a station in your area.');
			}

			e.preventDefault();
		});
	});
	</script>
<?php }

function kims_club_grid_block() { ?>
	<div class="grid-item-kims-club">
		<h3><img src="<?php echo k2_get_static_url('v2'); ?>/img/kims-club-logo.png" width="160" alt="[LOGO] Kim's Club" /></h3>
		<div class="kc-content">
			<ul>
				<li>Automatic contest entries</li>
				<li>Member's only chat forum</li>
				<li>Full show archive</li>
				<li>Watch Kim <span class="gold">Live</span></li>
				<li>Lots more!</li>
			</ul>
			<div class="kc-button"><a href="<?php echo CLUB_BASE_URI; ?>">Try it Free today! <i class="fa fa-chevron-right"></i></a></div>
		</div>
		<span class="kc-lock"><i class="fa fa-lock"></i></span>
		<div class="kc-kim"><img src="<?php echo k2_get_static_url('v2'); ?>/img/kims-club-kim-retina.png" width="125" alt="Kim Komando" /></div>
	</div>
<?php }

function twitter_question_grid_block() { 
	$data = get_option('twitter_qotd');
	?>
	<div class="grid-item-twitter-question">
		<h3><img src="<?php echo k2_get_static_url('v2'); ?>/img/share-twitter.png" width="24" height="24" alt="[ICON] Question of the Day" />@KimKomando Question of the Day</h3>
		<div class="tq-content">
			<div class="tq-question-wrap">
				<div class="tq-question-head">
					<a href="<?php echo $data['asker-url']; ?>" target="_blank"><?php echo $data['asker-name']; ?></a> <span><?php echo $data['question-timeago']; ?></span>
				</div>
				<div class="tq-question-text">
					<?php echo $data['question']; ?> <a href="https://twitter.com/search?q=%23askkim" target="_blank">#askkim</a>
				</div>
			</div>
			<div class="tq-answer-wrap">
				<div class="tq-answer-head">
					<img src="<?php echo k2_get_static_url('v2'); ?>/img/tq-kim.jpg" alt="Kim Komando" class="tq-kim-img" />
					<div class="tq-answer-name">
						<a href="https://twitter.com/kimkomando" target="_blank">@KimKomando</a> <span><?php echo $data['answer-timeago']; ?></span>
					</div>
					<div class="tq-answer-ask-follow">
						<a href="https://twitter.com/intent/tweet?button_hashtag=AskKim&screen_name=KimKomando" class="tq-ask-kim">Ask Kim</a>

						<a href="https://twitter.com/KimKomando" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false">Follow @KimKomando</a>
						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
					</div>
				</div>
				<div class="tq-answer-text">
					<?php echo $data['answer']; ?> <a href="https://twitter.com/search?q=%23askkim" target="_blank">#askkim</a>
				</div>
			</div>
		</div>
	</div>
<?php }

function random_grid_block() {
	$blocks = array('station_finder_grid_block', 'kims_club_grid_block', 'twitter_question_grid_block');

	$block = $blocks[array_rand($blocks)];

	$block();
}

function next_grid_block($num) {
	$data = get_option('twitter_qotd');

	$data['order'][$num]();
}

// Kills plugin update lookup
function hidden_plugin_k2_grid_blocks($r, $url) {
	if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize($r['body']['plugins']);
	unset($plugins->plugins[plugin_basename(__FILE__)]);
	unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
	$r['body']['plugins'] = serialize($plugins);
	return $r;
}

add_filter( 'http_request_args', 'hidden_plugin_k2_grid_blocks', 5, 2 );
?>