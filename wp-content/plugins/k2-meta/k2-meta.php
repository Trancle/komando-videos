<?php
/*
Plugin Name: K2 Meta Boxes
Plugin URI: http://www.komando.com
Description: Creates the meta boxes for The Kim Komando Show website
author: Kelly Karnetsky
Version: 0.2
Author URI: http://www.komando.com
*/

function download_data_fields($cnt, $field = null) {
	if($field === null) {
		$name = $link = $price = $alert = '';
	} else {
		$name = $field['name'];
		$link = $field['link'];
		$price = $field['price'];
	}

	$default_fields = '<tr>
	  <td width="135">
	    <select name="download_data[' . $cnt . '][name]" class="large-text">
	      <optgroup label="Operating Systems">
	        <option value="windows" ' . (($name == 'windows') ? 'selected' : '') . '>Windows</option>
	        <option value="windows8" ' . (($name == 'windows8') ? 'selected' : '') . '>Windows 8</option>
	        <option value="windows10" ' . (($name == 'windows10') ? 'selected' : '') . '>Windows 10</option>
	        <option value="mac" ' . (($name == 'mac') ? 'selected' : '') . '>Mac</option>
	        <option value="linux" ' . (($name == 'linux') ? 'selected' : '') . '>Linux</option>
	      </optgroup>
	      <optgroup label="Apps">
	        <option value="ios" ' . (($name == 'ios') ? 'selected' : '') . '>iOS</option>
	        <option value="android" ' . (($name == 'android') ? 'selected' : '') . '>Android</option>
	        <option value="windowsphone" ' . (($name == 'windowsphone') ? 'selected' : '') . '>Windows Phone</option>
	        <option value="windowsrt" ' . (($name == 'windowsrt') ? 'selected' : '') . '>Windows RT</option>
	        <option value="blackberry" ' . (($name == 'blackberry') ? 'selected' : '') . '>Blackberry</option>
	        <option value="symbian" ' . (($name == 'symbian') ? 'selected' : '') . '>Symbian</option>
	        <option value="kindle" ' . (($name == 'kindle') ? 'selected' : '') . '>Kindle</option>
	        <option value="nook" ' . (($name == 'nook') ? 'selected' : '') . '>Nook</option>
	      </optgroup>
	      <optgroup label="Wearables">
	        <option value="applewatch" ' . (($name == 'applewatch') ? 'selected' : '') . '>Apple Watch</option>
	        <option value="androidwear" ' . (($name == 'androidwear') ? 'selected' : '') . '>Android Wear</option>
	      </optgroup>
	      <optgroup label="Browsers">
	        <option value="chrome" ' . (($name == 'chrome') ? 'selected' : '') . '>Chrome</option>
	        <option value="firefox" ' . (($name == 'firefox') ? 'selected' : '') . '>Firefox</option>
	        <option value="internetexplorer" ' . (($name == 'internetexplorer') ? 'selected' : '') . '>Internet Explorer</option>
	        <option value="opera" ' . (($name == 'opera') ? 'selected' : '') . '>Opera</option>
	        <option value="safari" ' . (($name == 'safari') ? 'selected' : '') . '>Safari</option>
	      </optgroup>
	      <option value="all" ' . (($name == 'all') ? 'selected' : '') . '>All</option>
	    </select>
	  </td>
	  <td>
	    <input type="text" name="download_data[' . $cnt . '][link]" class="large-text" value="' . $link . '" />
	  </td>
	  <td width="50">
	    <input type="text" name="download_data[' . $cnt . '][price]" class="large-text" value="' . $price . '" />
	  </td>
	  <td width="70"><span class="remove"><a class="button">Remove</a></span></td>
	</tr>';

	// Strip the line breaks for javascript
	$default_fields = preg_replace('/\r|\n/', '', $default_fields);

	return $default_fields;
}

//add custom field - price
add_action('add_meta_boxes', 'object_init');

function object_init() {
	add_meta_box('download_meta_id', 'Download Links', 'download_meta', 'post', 'normal', 'low');
	add_meta_box('download_meta_id', 'Download Links', 'download_meta', 'downloads', 'normal', 'low');
	add_meta_box('download_meta_id', 'Download Links', 'download_meta', 'apps', 'normal', 'low');
	add_meta_box('cool_site_meta_id', 'Cool Site Link', 'cool_site_meta', 'cool_sites', 'normal', 'low');
	add_meta_box('show_picks_meta_id', 'Show Picks', 'show_picks_meta', 'previous_shows', 'normal', 'low');

	add_meta_box('source_attribution_meta_id', 'Source Attribution', 'source_attribution_meta', 'columns', 'normal', 'low');
	add_meta_box('source_attribution_meta_id', 'Source Attribution', 'source_attribution_meta', 'downloads', 'normal', 'low');
	add_meta_box('source_attribution_meta_id', 'Source Attribution', 'source_attribution_meta', 'apps', 'normal', 'low');
	add_meta_box('source_attribution_meta_id', 'Source Attribution', 'source_attribution_meta', 'cool_sites', 'normal', 'low');
	add_meta_box('source_attribution_meta_id', 'Source Attribution', 'source_attribution_meta', 'tips', 'normal', 'low');
	add_meta_box('source_attribution_meta_id', 'Source Attribution', 'source_attribution_meta', 'happening_now', 'normal', 'low');
	add_meta_box('source_attribution_meta_id', 'Source Attribution', 'source_attribution_meta', 'qotd', 'normal', 'low');
	add_meta_box('source_attribution_meta_id', 'Source Attribution', 'source_attribution_meta', 'small_business', 'normal', 'low');
	add_meta_box('source_attribution_meta_id', 'Source Attribution', 'source_attribution_meta', 'new_technologies', 'normal', 'low');

	add_meta_box('newsletter_meta_id', 'Newsletter Information', 'newsletter_meta', 'columns', 'normal', 'low');
	add_meta_box('newsletter_meta_id', 'Newsletter Information', 'newsletter_meta', 'downloads', 'normal', 'low');
	add_meta_box('newsletter_meta_id', 'Newsletter Information', 'newsletter_meta', 'apps', 'normal', 'low');
	add_meta_box('newsletter_meta_id', 'Newsletter Information', 'newsletter_meta', 'cool_sites', 'normal', 'low');
	add_meta_box('newsletter_meta_id', 'Newsletter Information', 'newsletter_meta', 'tips', 'normal', 'low');
	add_meta_box('newsletter_meta_id', 'Newsletter Information', 'newsletter_meta', 'happening_now', 'normal', 'low');
	add_meta_box('newsletter_meta_id', 'Newsletter Information', 'newsletter_meta', 'qotd', 'normal', 'low');
	add_meta_box('newsletter_meta_id', 'Newsletter Information', 'newsletter_meta', 'small_business', 'normal', 'low');
	add_meta_box('newsletter_meta_id', 'Newsletter Information', 'newsletter_meta', 'new_technologies', 'normal', 'low');
}

function newsletter_meta() {
	$data['newsletter-promo'] = '';
	$data['newsletter-cta'] = '';

	global $post;
	$data = get_post_meta($post->ID, 'newsletter_meta', true);
	?>

	<div>
		<label for="newsletter-promo">Newsletter Promo</label>
		<textarea class="large-text code" id="newsletter-promo" name="newsletter_meta[newsletter-promo]"><?php if (!empty($data['newsletter-promo'])) { echo $data['newsletter-promo']; } ?></textarea>

		<label for="newsletter-cta">Newsletter Call to Action</label>
		<textarea class="large-text code" id="newsletter-cta" name="newsletter_meta[newsletter-cta]"><?php if (!empty($data['newsletter-cta'])) { echo $data['newsletter-cta']; } ?></textarea>
	</div>

	<?php
}

function download_meta() {
	global $post;

	$data = get_post_meta($post->ID, 'download_data')[0];

	echo '<div>';
	echo '<table width="100%" border="0" cellspacing="0" cellpadding="4" style="margin: 10px 0;"><thead><tr width="100%"><th width="135">Platform</th><th>Link</th><th width="50">Price</th><th width="70"></th></tr></thead>';
	echo '<tbody id="download_links">';
	$c = 1;
	if(count($data) > 0) {
		foreach((array)$data as $field) {
			if (isset($field['name']) || isset($field['link']) || isset($field['price'])) {
				echo download_data_fields($c, $field);
				$c++;
			}
		}
	}

	echo '</tbody></table>';

	?>
	<span id="here"></span>
	<span class="add"><a class="button">Add Download Link</a></span>
	<script>
		jQuery(document).ready(function($) {
			var count = <?php echo $c; ?>;
			jQuery('.add').click(function() {
				count = count + <?php echo $c; ?>;
				jQuery('#download_links').append('<?php echo implode('', explode('\n', download_data_fields('count'))); ?>'.replace(/count/g, count));
				return false;
			});
			jQuery('.remove').live('click', function() {
				jQuery(this).parent().parent().remove();
			});
		});
	</script>
	<style>#download_links {list-style: none;}</style>
	<?php
	echo '</div>';
}

function cool_site_meta() {
	global $post;

	$data = get_post_meta($post->ID, 'coolsite_data')[0];
	if(isset($data)){
		$title = $data['title'];
		$desc = $data['desc'];
		$link = $data['link'];
	} else {
		$title = '';
		$desc = '';
		$link = '';
	}

	$cool_site_form = '<div>
	  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 10px 0;">
	    <thead>
	      <tr>
	        <th width="25%">Title</th>
	        <th width="45%">Description</th>
	        <th width="30%">Link</th>
	      </tr>
	    </thead>
	    <tbody id="cool_site_link">
	      <tr>
	        <td width="25%">
	          <input type="text" name="coolsite_data[title]" class="large-text" value="' . $title . '" />
	        </td>
	        <td width="45%">
	          <input type="text" name="coolsite_data[desc]" class="large-text" value="' . $desc . '" />
	        </td>
	        <td width="30%">
	          <input type="text" name="coolsite_data[link]" class="large-text" value="' . $link . '" />
	        </td>
	      </tr>
	    </tbody>
	  </table>
	</div>';

	// Strip the line breaks for javascript
	$cool_site_form = preg_replace('/\r|\n/', '', $cool_site_form);

	echo $cool_site_form;
}

function show_picks_meta() {
	global $post;

	$data = get_post_meta($post->ID, 'show_picks_data')[0];
	if(isset($data)){
		$cuff = $data['cuff'];
		$money = $data['money'];
		$security = $data['security'];
		$column = $data['column'];
	} else {
		$cuff = '';
		$money = '';
		$security = '';
		$column = '';
	}

	echo '<div><table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 10px 0;"><tbody id="cool_site_link"><tr width="100%"><td><label for="show_picks_data[security]">Privacy and Security Tip</label><input type="text" name="show_picks_data[security]" class="large-text" placeholder="Post ID" value="' . $security . '" /></td></tr><tr width="100%"><td><label for="show_picks_data[money]">Money Tip</label><input type="text" name="show_picks_data[money]" class="large-text" placeholder="Post ID" value="' . $money . '" /></td></tr><tr width="100%"><td><label for="show_picks_data[cuff]">Small Business Tip</label><input type="text" name="show_picks_data[cuff]" class="large-text" placeholder="Post ID" value="' . $cuff . '" /></td></tr><tr width="100%"><td><label for="show_picks_data[column]">USA Today Column</label><input type="text" name="show_picks_data[column]" class="large-text" placeholder="Post ID" value="' . $column . '" /></td></tr></tbody></table></div>';
}

function source_attribution_meta() {
	global $post;

	$data = get_post_meta($post->ID, 'source_attribution_data')[0];
	if(isset($data)){
		$source_title = $data['source_title'];
		$source_url = $data['source_url'];
	} else {
		$source_title = '';
		$source_url = '';
	}

	echo '<div><table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin: 10px 0;"><thead><tr width="100%"><th width="50%">Source Title</th><th width="50%">Source Link</th></tr></thead><tbody id="source_attribution_link"><tr width="100%"><td width="50%"><input type="text" name="source_attribution_data[source_title]" class="large-text" value="' . $source_title . '" /></td><td width="50%"><input type="text" name="source_attribution_data[source_url]" class="large-text" value="' . $source_url . '" /></td></tr></tbody></table></div>';
}

//Save product price
add_action('save_post', 'save_details');

function save_details($post_id) {
	global $post;

	// to prevent metadata or custom fields from disappearing... 
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	if(defined('DOING_AJAX') && DOING_AJAX) {
		return $post_id;
	}

	$post_status = get_post_status($post_id);

	// OK, we're authenticated: we need to find and save the data
	if(isset($_POST['download_data'])) {

		$prev_data = get_post_meta($post_id, 'download_data')[0];
		$new_data = $_POST['download_data'];

		if($prev_data != $new_data && $post_status != 'inherit') {

			delete_post_meta($post_id, 'download_data');
			update_post_meta($post_id, 'download_data', $new_data);

		}

	}

	if(isset($_POST['coolsite_data'])) {

		$prev_data = get_post_meta($post_id, 'coolsite_data')[0];
		$new_data = $_POST['coolsite_data'];

		if($prev_data != $new_data && $post_status != 'inherit') {

			delete_post_meta($post_id, 'coolsite_data');
			update_post_meta($post_id, 'coolsite_data', $new_data);

		}
		
	}

	if(isset($_POST['show_picks_data'])) {
		delete_post_meta($post_id, 'show_picks_data');
		$data = $_POST['show_picks_data'];
		update_post_meta($post_id, 'show_picks_data', $data);
	}

	if(isset($_POST['source_attribution_data'])) {
		delete_post_meta($post_id, 'source_attribution_data');
		$data = $_POST['source_attribution_data'];
		update_post_meta($post_id, 'source_attribution_data', $data);
	}

	if(isset($_POST['newsletter_meta'])) {
		$data = $_POST['newsletter_meta'];
		update_post_meta($post_id, 'newsletter_meta', $data);
	}
}

// Prevents the post from losing its data when going from scheduled to published
add_action(
	'future_to_publish',
	function($post) {
		remove_action('save_post', 'save_details');
	}
);

// Display download button
function get_download_meta() {
	global $post;
	$k2_data = get_post_type_object($post->post_type);

	if($k2_data->name != 'cool_sites') { 
		$data = get_post_meta($post->ID, 'download_data')[0];
		
		if(!empty($data)) {
		?>
		<div class="download-links clearfix">
			<h4><?php echo $k2_data->labels->singular_name; ?> Links</h4>
		<?php
		$map = [];
		foreach((array)$data as $field) {

			$name = $field['name'];
			$map = array(
				'ios' => 'iOS',
				'android' => 'Android',
				'blackberry' => 'BlackBerry',
				'linux' => 'Linux',
				'symbian' => 'Symbian',
				'windows' => 'Windows',
				'windows8' => 'Windows 8',
				'windows10' => 'Windows 10',
				'windowsrt' => 'Windows RT',
				'mac' => 'Mac',
				'windowsphone' => 'Windows Phone',
				'kindle' => 'Kindle',
                'nook' => 'Nook',
				'applewatch' => 'Apple Watch',
				'androidwear' => 'Android Wear',
				'chrome' => 'Chrome',
				'firefox' => 'Firefox',
				'internetexplorer' => 'Internet Explorer',
				'opera' => 'Opera',
				'safari' => 'Safari',
				'all' => 'All'
			);

			if(array_key_exists($name, $map)) {
				$pretty_name = $map[$name];
			}

			$price = strtolower($field['price']);

			if($price == 'free' || $price == 0) {
				$price = 'Free';
			} else {
				$price = '$' . $price;
			}

			if($name == 'all') { ?>

			<div class="download-button"><a href="<?php echo $field['link']; ?>" class="btn btn-blue btn-downloads button-<?php echo $field['name']; ?>" target="_blank"><span>Download - <em><?php echo $price; ?></em></span></a></div>

			<?php } else { ?>

			<div class="download-button"><a href="<?php echo $field['link']; ?>" class="btn btn-blue btn-downloads button-<?php echo $field['name']; ?>" target="_blank"><span>Download for <?php echo $pretty_name; ?> - <em><?php echo $price; ?></em></span></a></div>

			<?php } ?>
			
		<?php } ?>
		</div>
	<?php }
	} else {

			$data = get_post_meta($post->ID, 'coolsite_data')[0];
			if(!empty($data['link'])) {
		?>

			<div class="cool-site-link"><a href="<?php echo $data['link'] ?>" class="btn btn-large btn-blue" target="_blank" title="<?php echo $data['desc'] ?>"><?php echo $data['title'] ?> <i class="fa fa-external-link" style="margin:0 0 0 10px"></i></a></div>

		<?php
		}
	}
}

// Returns Download button html
function download_links() {
    global $post;
    $download = '';
    $k2_data = get_post_type_object($post->post_type);

    if($k2_data->name != 'cool_sites') {
        $data = get_post_meta($post->ID, 'download_data')[0];

        if(!empty($data)) {

            $download .= '<div class="download-links">';
            $download .= '<h4>' . $k2_data->labels->singular_name . 'Links</h4>';
            $map = [];
            foreach((array)$data as $field) {

                $name = $field['name'];
                $map = array(
                    'ios' => 'iOS',
                    'android' => 'Android',
                    'blackberry' => 'BlackBerry',
                    'linux' => 'Linux',
                    'symbian' => 'Symbian',
                    'windows' => 'Windows',
                    'windows8' => 'Windows 8',
                    'windows10' => 'Windows 10',
                    'windowsrt' => 'Windows RT',
                    'mac' => 'Mac',
                    'windowsphone' => 'Windows Phone',
                    'kindle' => 'Kindle',
                    'nook' => 'Nook',
                    'applewatch' => 'Apple Watch',
                    'androidwear' => 'Android Wear',
                    'chrome' => 'Chrome',
                    'firefox' => 'Firefox',
                    'internetexplorer' => 'Internet Explorer',
                    'opera' => 'Opera',
                    'safari' => 'Safari',
                    'all' => 'All'
                );

                if(array_key_exists($name, $map)) {
                    $pretty_name = $map[$name];
                }

                $price = strtolower($field['price']);

                if($price == 'free' || $price == 0) {
                    $price = 'Free';
                } else {
                    $price = '$' . $price;
                }

                if($name == 'all') {

                    $download .= '<div class="download-button"><a href="' . $field['link'] . '" class="btn btn-blue btn-downloads button-' . $field['name'] . '" target="_blank"><span>Download - <em>' . $price . '</em></span></a></div>';

                } else {

                    $download .= '<div class="download-button"><a href="' . $field['link'] . '" class="btn btn-blue btn-downloads button-' . $field['name'] . '" target="_blank"><span>Download for ' . $pretty_name . ' - <em>' . $price . '</em></span></a></div>';

                }

            }
            $download .= '</div>';
        }
    } else {

        $data = get_post_meta($post->ID, 'coolsite_data')[0];
        if(!empty($data['link'])) {

            $download .= '<div class="cool-site-link"><a href="' . $data['link'] . '" class="btn btn-large btn-blue" target="_blank" title="' . $data['desc'] . '">' . $data['title'] . '<i class="fa fa-external-link" style="margin:0 0 0 10px"></i></a></div>';

        }
    }
    return ($download);
}

// Kills plugin update lookup
function hidden_plugin_k2_meta($r, $url) {
	if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize($r['body']['plugins']);
	unset($plugins->plugins[plugin_basename(__FILE__)]);
	unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
	$r['body']['plugins'] = serialize($plugins);
	return $r;
}

add_filter( 'http_request_args', 'hidden_plugin_k2_meta', 5, 2 );
?>
