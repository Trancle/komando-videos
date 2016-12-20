<?php
/*
Plugin Name: K2 Meta Tags
Plugin URI: http://www.komando.com
Description: Creates the meta tags for posts
Author: Kelly Karnetsky
Version: 0.1
Author URI: http://www.komando.com
*/

// Addding the meta boxes in the admin
function setup_meta_tags_meta_boxes() {
	add_meta_box('meta_tags_id', 'Meta Tags', 'meta_tags_admin', 'post', 'normal', 'low');
	add_meta_box('meta_tags_id', 'Meta Tags', 'meta_tags_admin', 'page', 'normal', 'low');
	add_meta_box('meta_tags_id', 'Meta Tags', 'meta_tags_admin', 'columns', 'normal', 'low');
	add_meta_box('meta_tags_id', 'Meta Tags', 'meta_tags_admin', 'downloads', 'normal', 'low');
	add_meta_box('meta_tags_id', 'Meta Tags', 'meta_tags_admin', 'apps', 'normal', 'low');
	add_meta_box('meta_tags_id', 'Meta Tags', 'meta_tags_admin', 'cool_sites', 'normal', 'low');
	add_meta_box('meta_tags_id', 'Meta Tags', 'meta_tags_admin', 'tips', 'normal', 'low');
	add_meta_box('meta_tags_id', 'Meta Tags', 'meta_tags_admin', 'buying_guides', 'normal', 'low');
	add_meta_box('meta_tags_id', 'Meta Tags', 'meta_tags_admin', 'charts', 'normal', 'low');
	add_meta_box('meta_tags_id', 'Meta Tags', 'meta_tags_admin', 'newsletters', 'normal', 'low');
	add_meta_box('meta_tags_id', 'Meta Tags', 'meta_tags_admin', 'happening_now', 'normal', 'low');
	add_meta_box('meta_tags_id', 'Meta Tags', 'meta_tags_admin', 'qotd', 'normal', 'low');
	add_meta_box('meta_tags_id', 'Meta Tags', 'meta_tags_admin', 'small_business', 'normal', 'low');
	add_meta_box('meta_tags_id', 'Meta Tags', 'meta_tags_admin', 'new_technologies', 'normal', 'low');
}

add_action('add_meta_boxes', 'setup_meta_tags_meta_boxes');

function meta_tags_admin() {
	$data['meta-title'] = '';
	$data['meta-description'] = '';
	
	global $post;
	$data = get_post_meta($post->ID, 'meta_tags_admin', true);
	?>

		<div>
			<label for="meta-title">Meta title</label>
			<input class="large-text code" type="text" id="meta-title" name="meta_tags_admin[meta-title]" value="<?php if (!empty($data['meta-title'])) { echo $data['meta-title']; } ?>" />

			<label for="meta-description">Meta description</label>
			<textarea class="large-text code" id="meta-description" name="meta_tags_admin[meta-description]"><?php if (!empty($data['meta-description'])) { echo $data['meta-description']; } ?></textarea>
		</div>

	<?php
}

//Save meta tags
function k2_meta_tags_save_details($post_id) {
	global $post;

	// to prevent metadata or custom fields from disappearing... 
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	if(defined('DOING_AJAX') && DOING_AJAX) {
		return $post_id;
	}

	// OK, we're authenticated: we need to find and save the data
	if(isset($_POST['meta_tags_admin'])) {
		$data = $_POST['meta_tags_admin'];
		update_post_meta($post_id, 'meta_tags_admin', $data);
	}

}

add_action('save_post', 'k2_meta_tags_save_details');

function k2_meta_title($page_data) {

	global $page;

	$paged = get_query_var('paged');

	$urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$segments = explode('/', $urlArray);
	$numSegments = count($segments); 
	$currentSegment = $segments[$numSegments - 1];

	$data = get_post_meta($page_data, 'meta_tags_admin', true);
	$site_name = get_bloginfo('name');
	$post_page_title = get_the_title($page_data);

	if(!empty($page) && $page > 0) {
		$page_num = ' | Page ' . $page;
	}

	if(!empty($paged) && $paged > 0) {
		$page_num = ' | Page ' . $paged;
	}

	if($currentSegment == 'all') {
		$page_num = ' | One Page';
	}

	if(!empty($_GET['offset']) && $_GET['offset'] != 0) {
		if($_GET['count'] != 0) {
			$page_num = $_GET['offset'] / $_GET['count'] + 1;
		} else {
			$page_num = 1;
		}
		$page_num = ' | Page ' . $page_num;
	}

	if(empty($page_num)) {
		$page_num = '';
	}

	if(is_array($page_data)) {
		if($page_data['page_type'] == 'front') {
			return 'Tech News, Tips, Security Alerts &amp; Digital Trends | ' . $site_name;
		}

		if($page_data['page_type'] == 'archive') {
			switch($page_data['post_type']) {
				case 'columns':
					return 'Reviews and Buying Advice on All Things Digital | Kim\'s Columns';
				case 'downloads':
					return 'Daily Downloads, Freeware, Software & Download Tools | ' . $site_name;
				case 'apps':
					return 'Apps for iPads, iPhones, Androids | Apps for Tablets , PC and more!';
				case 'cool_sites':
					return 'Cool Sites - Interesting Cool Websites To Visit at Komando.com';
				case 'tips':
					return 'Kim\'s Tips for Computers, Laptops, Mobile Phones | ' . $site_name;
				case 'buying_guides':
					return 'Buying Guide for Computers, Digital Camera, Laptops' . $page_num . ' | ' . $site_name;
				case 'charts':
					return 'Charts - Smartphone Comparison Charts | Tablet, E-reader Charts' . $page_num;
				case 'newsletters':
					return 'Newsletters from The Kim Komando Show' . $page_num . ' | ' . $site_name;
				case 'previous_shows':
					return 'The Kim Komando Show Previous Show Picks' . $page_num . ' | ' . $site_name;
				case 'happening_now':
					return 'Happening Now On Komando.com | Latest Tech & Digital News' . $page_num;
				case 'new_technologies':
					return 'New Technologies On Komando.com | Latest Tech & Digital News' . $page_num;
				case 'qotd':
					return 'Question of the Day | Komando.com';
				case 'small_business':
					return 'Small Business Advice | Apps, Software, Security &amp; More | Komando.com';	
			}
		}

		if($page_data['page_type'] == 'search') {
			return stripslashes($page_data['search_query']) . ' - Search results for ' . stripslashes($page_data['search_query']) . $page_num . ' | ' . $site_name;
		}

		if($page_data['page_type'] == 'category') {
			return 'Articles about ' . $page_data['category_name'] . $page_num . ' | ' . $site_name;
		}

		if($page_data['page_type'] == 'tag') {
			return 'Articles about ' . $page_data['tag_name'] . $page_num . ' | ' . $site_name;
		}
	}
	
	if(empty($data['meta-title'])) {
		return $post_page_title . $page_num . ' | ' . $site_name;
	} else {
		return $data['meta-title'] . $page_num . ' | ' . $site_name;
	}
}

function k2_meta_description($page_data) {

	global $page;

	$paged = get_query_var('paged');

	$urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$segments = explode('/', $urlArray);
	$numSegments = count($segments); 
	$currentSegment = $segments[$numSegments - 1];

	$data = get_post_meta($page_data, 'meta_tags_admin', true);
	$site_name = get_bloginfo('name');
	$site_description = get_bloginfo('description');

	if(!empty($page) && $page > 0) {
		$page_num = ' | Page ' . $page;
	}

	if(!empty($paged) && $paged > 0) {
		$page_num = ' | Page ' . $paged;
	}

	if($currentSegment == 'all') {
		$page_num = ' | One Page';
	}

	if(!empty($_GET['offset']) && $_GET['offset'] != 0) {
		if($_GET['count'] != 0) {
			$page_num = $_GET['offset'] / $_GET['count'] + 1;
		} else {
			$page_num = 1;
		}
		$page_num = ' | Page ' . $page_num;
	}

	if(empty($page_num)) {
		$page_num = '';
	}

	if(is_array($page_data)) {
		if($page_data['page_type'] == 'front') {
			return 'Komando.com, home of the Kim Komando Show, is your source for the latest news on everything digital including security threats, scams, tips and tricks.'; //$site_description;
		}

		if($page_data['page_type'] == 'archive') {
			switch($page_data['post_type']) {
				case 'columns':
					return 'Check out the latest articles and buying advice on all things digital. Information about computer security, smartphones, tablets, future tech and more.' . $page_num;
				case 'downloads':
					return 'Download freeware, software and more daily at Komando.com. Plus download free desktop security, utilies, video and photo tools and more at Komando.com.' . $page_num;
				case 'apps':
					return 'Find all the latest apps for iPads, iPhones, Androids and more! Get the latest apps for your Blackberry, Mac, PC and tablet. Apps for free as well!' . $page_num;
				case 'cool_sites':
					return 'Discover the latest cool websites to visit on the internet at Komando.com. There are interesting cool site designs, game sites, sites for kids and more.' . $page_num;
				case 'tips':
					return 'Here are the latest computer tips from Komando.com. These tips like how to keep your computer safe, create strong passwords and more will save you time!' . $page_num;
				case 'buying_guides':
					return 'Kim Komando\'s Buying Guides and advice on shopping for laptops, digital cameras, iPods, computers, HDTVs, and more digital products on Komando.com.' . $page_num;
				case 'charts':
					return 'View smartphone comparison charts, tablets and mini tablets, video game consoles, streaming video gadgets and more comparison charts at Komando.com.' . $page_num;
				case 'newsletters':
					return 'Read the latest newsletters from the Kim Komando Show with tips of the day, weekend updates, daily news, alerts and more at Komando.com.' . $page_num;
				case 'previous_shows':
					return 'Latest articles in Previous Show Picks.' . $page_num;
				case 'happening_now':
					return 'Read the latest tech and digital news about computer security, smartphones, tablets, future tech and more in the Happening Now Section at Komando.com.' . $page_num;
				case 'new_technologies':
					return 'Read about the latest new technologies at Komando.com.' . $page_num;
				case 'qotd':
					return 'Click here to view the answer.';
				case 'small_business':
					return 'Get  the most current answers to your small business questions about software, apps, computers, servers, security, marketing and more at Komando.com.' . $page_num;	
			}
		}

		if($page_data['page_type'] == 'search') {
			return 'Columns, buying guides, articles, tips, cool sites, videos, apps and other up to date information for ' . stripslashes($page_data['search_query']) . '.' . $page_num;
		}

		if($page_data['page_type'] == 'category') {
			return 'Articles about ' . $page_data['category_name'] . $page_num . ' | ' . $site_name;
		}

		if($page_data['page_type'] == 'tag') {
			return 'Articles about ' . $page_data['tag_name'] . $page_num . ' | ' . $site_name;
		}
	}

	if(empty($data['meta-description'])) {
		$shortContent = get_post($page_data);
		if(empty($shortContent->post_excerpt)) {
			$shortContent = strip_tags($shortContent->post_content); 
		} else {
			$shortContent = strip_tags($shortContent->post_excerpt); 
		}
		$shortContent = preg_replace("/\[caption.*\[\/caption\]/", '', $shortContent);
		$shortContent = preg_replace("/\A(AVAILABLE FOR)/", '', $shortContent); 
		$shortContent = trim($shortContent); 
		$shortContent = substr($shortContent, 0, 180); 
		$shortContent = str_replace("\"", "'", $shortContent);
		$shortContent = str_replace(array("\r\n", "\r", "\n"), "", $shortContent);
		
		return $shortContent . '...';

	} else {
		return $data['meta-description'];
	}
}

// Kills plugin update lookup
function hidden_plugin_k2_meta_tags($r, $url) {
	if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize($r['body']['plugins']);
	unset($plugins->plugins[plugin_basename(__FILE__)]);
	unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
	$r['body']['plugins'] = serialize($plugins);
	return $r;
}

add_filter( 'http_request_args', 'hidden_plugin_k2_meta_tags', 5, 2 );
?>
