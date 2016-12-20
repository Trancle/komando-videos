<?php
/*
Plugin Name: K2 Section Sliders
Plugin URI: http://www.komando.com
Description: Creates the section pages for The Kim Komando Show website
author: Kelly Karnetsky
Version: 0.1
Author URI: http://www.komando.com
*/

// Add the admin menus
add_action('admin_menu', 'k2_sections_sliders_page_menu');
function k2_sections_sliders_page_menu() {
    add_submenu_page('home-sliders-settings', 'Columns Sliders Order', 'Columns Sliders', 'administrator', 'columns-sliders-settings', 'k2_sections_sliders_page');
    add_submenu_page('home-sliders-settings', 'Downloads Sliders Order', 'Downloads Sliders', 'administrator', 'downloads-sliders-settings', 'k2_sections_sliders_page');
    add_submenu_page('home-sliders-settings', 'Apps Sliders Order', 'Apps Sliders', 'administrator', 'apps-sliders-settings', 'k2_sections_sliders_page');
    add_submenu_page('home-sliders-settings', 'Cool Sites Sliders Order', 'Cool Sites Sliders', 'administrator', 'cool-sites-sliders-settings', 'k2_sections_sliders_page');
    add_submenu_page('home-sliders-settings', 'Tips Sliders Order', 'Tips Sliders', 'administrator', 'tips-sliders-settings', 'k2_sections_sliders_page');
    add_submenu_page('home-sliders-settings', 'Buying Guides Sliders Order', 'Buying Guides Sliders', 'administrator', 'buying-guides-sliders-settings', 'k2_sections_sliders_page');
}

// Register the settings
add_action('admin_init', 'k2_sections_sliders_register_settings');
function k2_sections_sliders_register_settings() {
	if (isset($_GET['page'])) { 
		if($_GET['page'] == 'columns-sliders-settings' || $_GET['page'] == 'downloads-sliders-settings' || $_GET['page'] == 'apps-sliders-settings' || $_GET['page'] == 'cool-sites-sliders-settings' || $_GET['page'] == 'tips-sliders-settings' || $_GET['page'] == 'buying-guides-sliders-settings') {
		    register_setting('content_boxes_group', 'columns_content_boxes');
		    register_setting('content_boxes_group', 'downloads_content_boxes');
		    register_setting('content_boxes_group', 'apps_content_boxes');
		    register_setting('content_boxes_group', 'coolsites_content_boxes');
		    register_setting('content_boxes_group', 'tips_content_boxes');
		    register_setting('content_boxes_group', 'buyingguides_content_boxes');
		    wp_enqueue_script('jquery-ui-sortable');
		    wp_enqueue_script('dashboard');
		}
	}
}

function k2_sections_sliders_page() {
	$page = $_GET['page'];

	switch($page) {
		case 'columns-sliders-settings':
			$title = 'Columns Section Page Slider Blocks Ordering';
			$options = 'columns_content_boxes';
			break;
		case 'downloads-sliders-settings':
			$title = 'Downloads Section Page Slider Blocks Ordering';
			$options = 'downloads_content_boxes';
			break;
		case 'apps-sliders-settings':
			$title = 'Apps Section Page Slider Blocks Ordering';
			$options = 'apps_content_boxes';
			break;
		case 'cool-sites-sliders-settings':
			$title = 'Cool Sites Section Page Slider Blocks Ordering';
			$options = 'coolsites_content_boxes';
			break;
		case 'tips-sliders-settings':
			$title = 'Tips Section Page Slider Blocks Ordering';
			$options = 'tips_content_boxes';
			break;
		case 'buying-guides-sliders-settings':
			$title = 'Buying Guides Section Page Slider Blocks Ordering';
			$options = 'buyingguides_content_boxes';
			break;
	}

	if($options == false || isset($_POST['reset'])) {
		$content_boxes = array(
			array('title' => 'Top Ad - 728x90', 'id' => 'k2-settings-ad-1', 'ptype' => 'ad', 'order' => '1', 'style' => 'default', 'cat' => 'ad', 'header' => '--advertisement--', 'gadid' => 'div-gpt-ad-1377800947364-0'),
			array('title' => 'Pos 2 Ad - 728x90', 'id' => 'k2-settings-ad-2', 'ptype' => 'ad', 'order' => '2', 'style' => 'default', 'cat' => 'ad', 'header' => '--advertisement--', 'gadid' => 'div-gpt-ad-1377801067256-0'),
			array('title' => 'Pos 3 Ad - 728x90', 'id' => 'k2-settings-ad-3', 'ptype' => 'ad', 'order' => '3', 'style' => 'default', 'cat' => 'ad', 'header' => '--advertisement--', 'gadid' => 'div-gpt-ad-1377801045498-0'),
			array('title' => 'Bottom Ad - 728x90', 'id' => 'k2-settings-ad-4', 'ptype' => 'ad', 'order' => '4', 'style' => 'default', 'cat' => 'ad', 'header' => '--advertisement--', 'gadid' => 'div-gpt-ad-1377801009394-0')
		);
		update_option($options, $content_boxes);
	}

	// Saving the new array that has been created with options
	if($_POST == true && $_POST['check'] = 1 && !isset($_POST['reset'])) {

		foreach($_POST['id'] as $key => $id) {
			
			if($_POST['ptype'][$key] == 'ad') {
				$header = esc_html(wp_kses(stripslashes($_POST['title'][$key]), array()));
			} else {
				$header = esc_html(wp_kses(stripslashes($_POST['header'][$key]), array()));
			}

	    	$content_boxes[] = array(
	    		'title' => $header,
	    		'id' => $id,
	    		'ptype' => wp_kses($_POST['ptype'][$key], array()),
	    		'order' => wp_kses($_POST['order'][$key], array()),
	    		'cat' => wp_kses($_POST['cat'][$key], array()),
	    		'style' => wp_kses($_POST['style'][$key], array()),
	    		'header' => esc_html(wp_kses(stripslashes($_POST['header'][$key]), array())),
	    		'gadid' => esc_html(wp_kses(stripslashes($_POST['gadid'][$key]), array()))
	    	);
		}  
		update_option($options, $content_boxes);
	}

	// Grabbing the array and sorting it numerically
	$content_boxes = get_option($options);
	if(!is_array($content_boxes)) {
	    $content_boxes = array();
	}

	foreach ($content_boxes as $boxes) {
	    $order[] = $boxes['order']; 
	}
	array_multisort($order, SORT_NUMERIC, $content_boxes);
	?>

	<div class="wrap">
		<style>
		.remove-block {
			cursor: pointer;
			margin: 10px 0 0 0;
			color: #ff0000;
		}

		.remove-block:hover {
			color: #ff0000;
			text-decoration: underline;
		}
		</style>
		<?php screen_icon('themes'); ?> <h2><?php echo $title; ?></h2>
		<?php if($_POST == true && $_POST['check'] = 1 && !isset($_POST['reset'])) { ?>
			<div id="message" class="updated fade below-h2"><p>Yay and stuff, your changes have been saved!</p></div>
		<?php } elseif($_POST == true && $_POST['check'] = 1 && isset($_POST['reset'])) { ?>
			<div id="message" class="updated fade below-h2"><p>Disaster averted, everything has been reset to stable levels.</p></div>
		<?php } ?>
		<form method="post" action="">
			<input type="hidden" name="check" value="1">
			<?php settings_fields('content_boxes_group'); ?>
			<div class="metabox-holder">
				<div id="advanced-sortables" class="meta-box-sortables">
					<br class="clear" />
					<div class="meta-box-sortables widget-holder ui-sortable slider-blocks">
					<?php foreach($content_boxes as $boxes) { ?>
					<div id="<?php echo $boxes['id']; ?>" class="postbox closed" style="min-width: 200px;">
						<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle"><span><?php echo $boxes['title']; ?></span></h3>
						<div class="inside">
							<?php sections_box_settings(array('back', $boxes['ptype'], $boxes['style'], $boxes['cat'], $boxes['header'], $boxes['order'], $boxes['gadid'])); ?>

							<?php if($boxes['ptype'] != 'ad') { ?><a class="remove-block">Remove</a><?php } ?>
						</div>
						<input type="hidden" name="title[<?php echo $boxes['order']; ?>]" value="<?php echo $boxes['title']; ?>">
						<input type="hidden" name="id[<?php echo $boxes['order']; ?>]" value="<?php echo $boxes['id']; ?>">
						<input type="hidden" name="ptype[<?php echo $boxes['order']; ?>]" value="<?php echo $boxes['ptype']; ?>">
						<input type="hidden" name="order[<?php echo $boxes['order']; ?>]" value="<?php echo $boxes['order']; ?>">
						<?php if($boxes['ptype'] == 'ad') { ?><input type="hidden" name="cat[<?php echo $boxes['order']; ?>]" value="ad"><?php } ?>
					</div>
					<?php } ?>
					</div>
				</div>
			</div>
			<div class="submit">
				<input type="submit" class="button-primary" value="Save Order">
				<button type="submit" class="button-secondary" name="reset" value="1">Reset All Sliders</button>
				<a id="add-slider" class="button-secondary" style="float:right;">Add Slider</a>
			</div>
	   </form>
	</div>
	<script>
	jQuery(document).ready(function() {
		jQuery('#add-slider').live('click', function() {
			var lastID;
			jQuery('.slider-blocks').children().each(function() {
				var getID = jQuery(this).attr('id');
				var getID = getID.replace(/\D/g,'');
				lastID = lastID > getID ? lastID : getID;
			});
			var makeID = Number(lastID) + 1;
			jQuery('.slider-blocks').append('<?php k2_sections_new_block(); ?>'.replace(/orderposition/g, makeID));
			jQuery('.slider-blocks').find('#cat\\['+makeID+'\\]').append('<?php k2_sections_get_terms(array(k2_get_post_type(), '0')); ?>');
			jQuery('.slider-blocks').children().each(function(i) {
				var order = jQuery(this).index() + 1;
				jQuery(this).find('[name^="order"]').attr('value', order);
			});
		});
		jQuery('.remove-block').live('click', function() {
			jQuery(this).parent().parent().remove();
			jQuery('.slider-blocks').children().each(function(i) {
				var order = jQuery(this).index() + 1;
				jQuery(this).find('[name^="order"]').attr('value', order);
			});
		});
		jQuery('.handlediv, .hndle').on('click', function() { 
			if(jQuery(this).parent().hasClass('closed')) {
				jQuery(this).parent().removeClass('closed');
			} else {
				jQuery(this).parent().addClass('closed');
			}
		});
		jQuery('.handlediv, .hndle').live('click', function() { 
			if(jQuery(this).parent().hasClass('closed')) {
				jQuery(this).parent().removeClass('closed');
			} else {
				jQuery(this).parent().addClass('closed');
			}
		});
	    jQuery('.slider-blocks').sortable({
	        update: function(event, ui) {
	            jQuery('.slider-blocks').children().each(function(i) {
					var order = jQuery(this).index() + 1;
					jQuery(this).find('[name^="order"]').attr('value', order);					
	            });
	        }
	    }); 
	});
	</script>

	<?php
}

function k2_sections_get_terms($args) {

	$tax = $args[0].'_categories';
	$terms = get_terms($tax, 'hide_empty=0');
	$count = count($terms);
	$theone = '';
	
	if ($count > 0){
		foreach ($terms as $term) {
			if(isset($args[1])) {
				if($args[1] == $term->slug) { 
					$theone = 'selected'; 
				} else {
					$theone = '';
				}
			}
			$droppy[] = '<option value="' . $term->slug . '"' . $theone . '>' . $term->name . '</option>';
		}
		$drop = implode($droppy);
	}
	echo $drop;
}

function k2_sections_new_block() {

	$k2_post_type = k2_get_post_type();

$new_block = <<<HTML
<div id="ks-settings-orderposition" class="postbox" style="min-width: 200px;">
	<div class="handlediv" title="Click to toggle"><br></div><h3 class="hndle">New Block<span></span></h3>
	<div class="inside">
		<p><label for="header[orderposition]">Title:</label><input type="text" id="header[orderposition]" class="widefat" name="header[orderposition]" value=""></p>
		<p>
			<label for="cat[orderposition]">Choose the category: </label>
			<select id="cat[orderposition]" name="cat[orderposition]">
			</select>
		</p>
		<p>
			<label for="style[orderposition]">Choose the slider style: </label>
			<select id="style[orderposition]" name="style[orderposition]">
				<option value="default">Default</option>
				<option value="featured">Featured</option>
				<option value="live">Live</option>
			</select>
		</p>

		<a class="remove-block">Remove</a>
	</div>
	<input type="hidden" name="id[orderposition]" value="k2-settings-orderposition">
	<input type="hidden" name="ptype[orderposition]" value="$k2_post_type">
	<input type="hidden" name="order[orderposition]" value="orderposition">
</div>
HTML;
// semicolon may need to be on it's own line.

	$new_block = str_replace(array("\r\n", "\r"), "\n", $new_block);
	$lines = explode("\n", $new_block);
	$new_lines = array();

	foreach ($lines as $i => $line) {
	    if(!empty($line))
	        $new_lines[] = trim($line);
	}
	echo implode($new_lines);
}

// function called to build the content in the boxes on the settings page and the front end
function sections_box_settings($args) {

	if($args[0] == 'back') { // This is for the settings page
		if($args[1] == 'ad') { // Indicating it's an ad block
		?>
			<p><label for="header[<?php echo $args[5]; ?>]">Title:</label><input type="text" id="header[<?php echo $args[5]; ?>]" class="widefat" name="header[<?php echo $args[5]; ?>]" value="<?php echo $args[4]; ?>"></p>
			<p><label for="header[<?php echo $args[5]; ?>]">Google DIV ID (ex: div-gpt-ad-1377800947364-0):</label><input type="text" id="header[<?php echo $args[5]; ?>]" class="widefat" name="header[<?php echo $args[5]; ?>]" value="<?php echo $args[6]; ?>"></p>
			<input type="hidden" name="style[<?php echo $args[5]; ?>]" value="ad">
		<?php
		} else { // Indicating slider block
		?>
		<p><label for="header[<?php echo $args[5]; ?>]">Title:</label><input type="text" id="header[<?php echo $args[5]; ?>]" class="widefat" name="header[<?php echo $args[5]; ?>]" value="<?php echo $args[4]; ?>"></p>
		<p>
			<label for="cat[<?php echo $args[5]; ?>]">Choose the category: </label>
			<select id="cat[<?php echo $args[5]; ?>]" name="cat[<?php echo $args[5]; ?>]">
				<?php k2_sections_get_terms(array($args[1], $args[3])); ?>
			</select>
		</p>
		<p>
			<label for="style[<?php echo $args[5]; ?>]">Choose the slider style: </label>
			<select id="style[<?php echo $args[5]; ?>]" name="style[<?php echo $args[5]; ?>]">
				<option value="default" <?php if($args[2] == 'default'){ echo 'selected'; } ?>>Default</option>
				<option value="featured" <?php if($args[2] == 'featured'){ echo 'selected'; } ?>>Featured</option>
				<option value="live" <?php if($args[2] == 'live'){ echo 'selected'; } ?>>Live</option>
			</select>
		</p>
		<?php
		}
	} else { // This is for the front end
		if($args[1] == 'ad') { // Indicating it's an ad block
			?>
			<div class="leaderboard-ad clearfix">
				<?php /* <span>-advertisement-</span> */ ?>
				<div id="<?php echo $args[6]; ?>" style="width:728px; height:90px;">
					<script type='text/javascript'>
					googletag.cmd.push(function() { googletag.display('<?php echo $args[6]; ?>'); });
					</script> 
				</div>
			</div>
			<?php
		} else { // Indicating slider block

			// Creating the array to pass to slider functions
			$data = array(
				'title' => $args[4],
				'post_type' => $args[1],
				'cat' => $args[3]
			);

			// Deciding what slider is used
			if($args[2] == 'default') {
				sections_default_slider($data);
			} elseif($args[2] == 'featured') {
				sections_featured_slider($data);
			} elseif($args[2] == 'live') {
				sections_live_slider($data);
			}
		}
	}
}

// Default slider template that is set for all blocks on install or reset
function sections_default_slider($data) {

$tax_name = $data['post_type'] . '_categories';
$cat_link = get_term_link($data['cat'], $tax_name);

?>

<div class="default-slider slider clearfix">
<div class="slider-info"><h2><a href="<?php echo $cat_link; ?>"><?php echo $data['title']; ?></a></h2> <div class="slide-button-count"><a href="<?php echo $cat_link; ?>" class="btn">View All <?php echo $data['title']; ?></a> <div class="slide-count"></div></div></div>
	<?php 
	// Args for the loop
	query_posts(array('post_type' => $data['post_type'], 'tax_query' => array(array('taxonomy' => $tax_name, 'field' => 'slug', 'terms' => $data['cat'])), 'posts_per_page' => '12')); 
	$image_dump = array('24330', '24331', '24332', '24333', '24334', '24335');
	$random_image = shuffle($image_dump);
	$random_image = array_rand($image_dump, 6);
	$count = 0;

	if (have_posts()) : while (have_posts()) : the_post();
	$id = get_the_ID();
	$image_id = get_post_thumbnail_id();
	$image = wp_get_attachment_image_src($image_id, 'thumbnail');
	if(empty($image)) {
		$image = wp_get_attachment_image_src($image_dump[$random_image[$count]], 'thumbnail');
		if($count === 5) {
			$count = 0;
		} else {
			$count++;
		}
	}

	$the_excerpt = get_the_excerpt();
	if($the_excerpt != '') {
		$postOutput = preg_replace('/<p[^>]*><\\/p[^>]*>/','', $the_excerpt);
	} else {
		$theContent = get_the_content('Click here for more...'); // setting the content to a variable and defining the read more link
		$postOutput = preg_replace('/<img[^>]+./','', $theContent); // Removes any images from the content
		$postOutput = preg_replace('/<p[^>]*><\\/p[^>]*>/','', $postOutput); // Removing paragraph tags
		$postOutput = wp_trim_words($postOutput, 30, '...');
		$postOutputSingleQuotes = str_replace('"', "'", $postOutput); // Replaces double quotes with single quotes
	}
	?>

	<div class="slide">
		<a href="<?php echo get_permalink(); ?>"><img src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title($id); ?>" /></a>
		<h3><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title($id); ?></a></h3>
		<p><?php echo $postOutput; ?></p>
	</div>

	<?php
	endwhile;
	endif;
	// Reset Query
	wp_reset_query();
	?>
</div>

<?php
}

// This is the featured slider template
function sections_featured_slider($data) {

$tax_name = $data['post_type'] . '_categories';
$cat_link = get_term_link($data['cat'], $tax_name);
?>

<div class="featured-slider slider clearfix">
<div class="slider-info"><h2><a href="<?php echo $cat_link; ?>"><?php echo $data['title']; ?></a></h2> <div class="slide-button-count"><a href="<?php echo $cat_link; ?>" class="btn">View All <?php echo $data['title']; ?></a> <div class="slide-count"></div></div></div>
	<?php 
	$i = 1;
	// Args for the loop
	query_posts(array('post_type' => $data['post_type'], 'tax_query' => array(array('taxonomy' => $tax_name, 'field' => 'slug', 'terms' => $data['cat'])), 'posts_per_page' => '12')); 

	$image_dump = array('24330', '24331', '24332', '24333', '24334', '24335');
	$random_image = shuffle($image_dump);
	$random_image = array_rand($image_dump, 6);
	$count = 0;

	if (have_posts()) : while (have_posts()) : the_post();
	$id = get_the_ID();
	$image_id = get_post_thumbnail_id();

	$the_excerpt = get_the_excerpt();
	if($the_excerpt != '') {
		$postOutput = preg_replace('/<p[^>]*><\\/p[^>]*>/','', $the_excerpt);
	} else {
		$theContent = get_the_content('Click here for more...'); // setting the content to a variable and defining the read more link
		$postOutput = preg_replace('/<img[^>]+./','', $theContent); // Removes any images from the content
		$postOutput = preg_replace('/<p[^>]*><\\/p[^>]*>/','', $postOutput); // Removing paragraph tags
		$postOutput = wp_trim_words($postOutput, 30, '...');
		$postOutputSingleQuotes = str_replace('"', "'", $postOutput); // Replaces double quotes with single quotes
	}

	// Finding out what size image to use
	if($i % 2 == 0) {
		$image = wp_get_attachment_image_src($image_id, 'thumbnail');
		if(empty($image)) {
			$image = wp_get_attachment_image_src($image_dump[$random_image[$count]], 'thumbnail');
			if($count === 5) {
				$count = 0;
			} else {
				$count++;
			}
		}
		?>
		<div class="slide">
			<a href="<?php echo get_permalink(); ?>"><img src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title($id); ?>" /></a>
			<h3><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title($id); ?></a></h3>
			<p><?php echo $postOutput; ?></p>
		</div>
		<?php

	} else {
		$image = wp_get_attachment_image_src($image_id, 'medium');
		if(empty($image)) {
			$image = wp_get_attachment_image_src($image_dump[$random_image[$count]], 'medium');
			if($count === 5) {
				$count = 0;
			} else {
				$count++;
			}
		}
		?>
		<div class="slide">
			<a href="<?php echo get_permalink(); ?>">
				<img src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title($id); ?>" />
				<h3><?php echo get_the_title($id); ?></h3>
			</a>
			<p><?php echo $postOutput; ?></p>
		</div>
		<?php

	}
	$i++;
	endwhile;
	endif;

	// Reset Query
	wp_reset_query();
	?>
</div>

<?php
}

// This is the live slider template
function sections_live_slider($data) {

$tax_name = $data['post_type'] . '_categories';
$cat_link = get_term_link($data['cat'], $tax_name);
?>

<div class="live-slider slider clearfix">
<div class="slider-info"><h2><a href="<?php echo $cat_link; ?>"><?php echo $data['title']; ?></a></h2> <div class="slide-button-count"><a href="<?php echo $cat_link; ?>" class="btn">View All <?php echo $data['title']; ?></a> <div class="slide-count"></div></div></div>
	<?php 
	$i = 3;
	// Args for the loop
	query_posts(array('post_type' => $data['post_type'], 'tax_query' => array(array('taxonomy' => $tax_name, 'field' => 'slug', 'terms' => $data['cat'])), 'posts_per_page' => '12')); 
	$image_dump = array('24330', '24331', '24332', '24333', '24334', '24335');
	$random_image = shuffle($image_dump);
	$random_image = array_rand($image_dump, 6);
	$count = 0;

	if (have_posts()) : while (have_posts()) : the_post();
	$id = get_the_ID();
	$image_id = get_post_thumbnail_id();

	$the_excerpt = get_the_excerpt();
	if($the_excerpt != '') {
		$postOutput = preg_replace('/<p[^>]*><\\/p[^>]*>/','', $the_excerpt);
	} else {
		$theContent = get_the_content('Click here for more...'); // setting the content to a variable and defining the read more link
		$postOutput = preg_replace('/<img[^>]+./','', $theContent); // Removes any images from the content
		$postOutput = preg_replace('/<p[^>]*><\\/p[^>]*>/','', $postOutput); // Removing paragraph tags
		$postOutput = wp_trim_words($postOutput, 30, '...');
		$postOutputSingleQuotes = str_replace('"', "'", $postOutput); // Replaces double quotes with single quotes
	}

	// Finding out what size image to use
	if($i % 3 == 0) {
		$image = wp_get_attachment_image_src($image_id, 'medium');
		if(empty($image)) {
			$image = wp_get_attachment_image_src($image_dump[$random_image[$count]], 'medium');
			if($count === 5) {
				$count = 0;
			} else {
				$count++;
			}
		}
	} else {
		$image = wp_get_attachment_image_src($image_id, 'thumbnail');
		if(empty($image)) {
			$image = wp_get_attachment_image_src($image_dump[$random_image[$count]], 'thumbnail');
			if($count === 5) {
				$count = 0;
			} else {
				$count++;
			}
		}
	}
	$i++;
	?>

	<div class="slide" data-toggle="popover" title="<?php echo get_the_title($id); ?>" data-content="<?php echo $postOutputSingleQuotes; ?>">
		<a href="<?php echo get_permalink(); ?>">
			<img src="<?php echo $image[0]; ?>" alt="<?php echo get_the_title($id); ?>" />
			<h3><?php echo get_the_title($id); ?></h3>
		</a>
	</div>

	<?php
	endwhile;
	endif;
	// Reset Query
	wp_reset_query();
	?>
</div>

<?php
}

// Sort the array numerically for the front end of the website
function sections_sliders($page) {

	switch($page) {
		case 'columns':
			$options = 'columns_content_boxes';
			$ptype = 'columns';
			break;
		case 'downloads':
			$options = 'downloads_content_boxes';
			$ptype = 'downloads';
			break;
		case 'apps':
			$options = 'apps_content_boxes';
			$ptype = 'apps';
			break;
		case 'cool-sites':
			$options = 'coolsites_content_boxes';
			$ptype = 'cool_sites';
			break;
		case 'tips':
			$options = 'tips_content_boxes';
			$ptype = 'tips';
			break;
		case 'buying-guides':
			$options = 'buyingguides_content_boxes';
			$ptype = 'buying_guides';
			break;
	}

	$content_boxes = get_option($options);

	if(!is_array($content_boxes)) {
	    $content_boxes = array();
	}

	foreach ($content_boxes as $boxes) {
	    $order[] = $boxes['order']; 
	}
	array_multisort($order, SORT_NUMERIC, $content_boxes);
	
	foreach($content_boxes as $boxes) {
		switch($page) {
			case 'columns':
				$ptype = 'columns';
				break;
			case 'downloads':
				$ptype = 'downloads';
				break;
			case 'apps':
				$ptype = 'apps';
				break;
			case 'cool-sites':
				$ptype = 'cool_sites';
				break;
			case 'tips':
				$ptype = 'tips';
				break;
			case 'buying-guides':
				$ptype = 'buying_guides';
				break;
		}
		if($boxes['ptype'] == 'ad') {
			$ptype = 'ad';
		}
		sections_box_settings(array('front', $ptype, $boxes['style'], $boxes['cat'], $boxes['header'], $boxes['order'], $boxes['gadid']));
	}
}

// Finding the post type, arghhhh!
function k2_get_post_type() {
		switch($_GET['page']) {
		case 'columns-sliders-settings':
			return 'columns';
			break;
		case 'downloads-sliders-settings':
			return 'downloads';
			break;
		case 'apps-sliders-settings':
			return 'apps';
			break;
		case 'cool-sites-sliders-settings':
			return 'cool_sites';
			break;
		case 'tips-sliders-settings':
			return 'tips';
			break;
		case 'buying-guides-sliders-settings':
			return 'buying_guides';
			break;
	}
}

// Kills plugin update lookup
function hidden_plugin_k2_sections($r, $url) {
	if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize($r['body']['plugins']);
	unset($plugins->plugins[plugin_basename(__FILE__)]);
	unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
	$r['body']['plugins'] = serialize($plugins);
	return $r;
}

add_filter( 'http_request_args', 'hidden_plugin_k2_sections', 5, 2 );
?>