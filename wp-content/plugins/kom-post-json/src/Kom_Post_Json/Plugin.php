<?php

namespace Kom_Post_Json;

use K2\Podcast\Episode;
use K2\Podcast\Listing;
use Leth\IPAddress\IP\NetworkAddress;
use Leth\IPAddress\IP\Address;

class Plugin
{
	public function __construct()
	{
		add_filter('query_vars', [$this, 'query_vars']);
		add_action('init', [$this, 'rewrites']);
		add_action('parse_request', [$this, 'parse_request']);
		add_action('admin_menu', [$this, 'admin_page_menu']);
	}

	public function activation()
	{
		if (!current_user_can('activate_plugins')) {
			return;
		}
	}

	public function deactivation()
	{
		if (!current_user_can('activate_plugins')) {
			return;
		}
	}

	public function query_vars($query_vars)
	{
		$query_vars[] = 'kom_post_json';
		$query_vars[] = 'kom_post_json_post';
		$query_vars[] = 'kom_podcast_json_episode';
		return $query_vars;
	}

	public function rewrites()
	{
		add_rewrite_rule('^api\/k2\/post\/(\d+)(.json)?$', 'index.php?kom_post_json=1&kom_post_json_post=$matches[1]', 'top');
		add_rewrite_rule('^api\/k2\/podcast\/episode\/(\d+)(.json)?$', 'index.php?kom_post_json=1&kom_podcast_json_episode=$matches[1]', 'top');
	}

	public function parse_request($wp_query)
	{
		if ($wp_query->query_vars['kom_post_json']) {
			header("Content-type: application/json; charset=utf-8");
			header("Cache-Control: no-cache, must-revalidate");
			header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");

			if ($this->ip_allowed($_SERVER['REMOTE_ADDR'])) {

				$episode_id = $wp_query->query_vars['kom_podcast_json_episode'];
				if(!empty($episode_id)){
					$episode = \K2\Podcast\Episode::get_episode_by_id($episode_id);
					if(!is_null($episode)){
						echo Episode::get_episode_details_as_json($episode);
					} else {
						status_header(404);
						echo '{"error": "Episode ID does not exist."}';
					}
					die();
				}

				if (get_post_status($wp_query->query_vars['kom_post_json_post'])) {

					echo $this->post_content($wp_query->query_vars['kom_post_json_post']);
				} else {

					status_header(404);
					echo '{"error": "Post ID does not exist."}';
				}
			} else {

				status_header(403);
				echo '{"error": "You are not authorized to access this."}';
			}

			die();
		}

	}

	public function post_content($post_id)
	{
		$post = get_post($post_id);
		$newsletter_post_meta = (array)get_post_meta($post_id, 'newsletter_meta', true);

		$video_url = get_post_custom_values('article_videos_meta_url', $post->ID);
		$video_url = is_null($video_url) || !isset($video_url[0]) || empty($video_url) ? '' : $video_url[0];

		$post_array = [
			'post_id' => $post->ID,
			'post_type' => $post->post_type,
			'published_date' => $post->post_date,
			'post_author' => get_the_author_meta('user_login', $post->post_author),
			'post_title' => $post->post_title,
			'post_content' => $post->post_content,
			'post_image' => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large')[0],
			'post_image_medium' => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium')[0],
			'post_image_thumbnail' => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail')[0],
			'post_image_small' => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'small')[0],
			'post_newsletter_description' => ($newsletter_post_meta ? $newsletter_post_meta['newsletter-promo'] : ''),
			'post_newsletter_cta_text' => ($newsletter_post_meta ? $newsletter_post_meta['newsletter-cta'] : ''),
			'post_video_url' => $video_url,
		];

		return json_encode($post_array);
	}

	public function ip_allowed($remote_ip)
	{
		// Creating an array removing any empty lines
		$allowed_ips = preg_split("/\\r\\n|\\r|\\n/", get_option('kom_post_json_ips'), -1, PREG_SPLIT_NO_EMPTY);

		// Remote IP needs to be an object of Address
		$remote_ip = Address::factory($remote_ip);

		foreach ($allowed_ips as $allowed_ip) {
			if (NetworkAddress::factory(trim($allowed_ip))->encloses_address($remote_ip)) {
				return true;
			}
		}
		return false;
	}

	public function admin_page_menu()
	{
		add_options_page('Allowed IPs', 'Allowed IPs', 'manage_options', 'allowed-ips', [$this, 'admin_page']);
	}

	public function admin_page()
	{
		if($_POST == true && $_POST['check'] = 1) {
			update_option('kom_post_json_ips', $_POST['kom_post_json_ips']);
		}

		$allowed_ips = get_option('kom_post_json_ips');
		?>

		<div class="wrap metabox-holder">
			<h2>Allowed IPs for post JSON access</h2>

			<?php if ($_POST == true) { ?>
				<div id="message" class="updated notice notice-success is-dismissible below-h2"><p>IPs saved.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
			<?php } ?>

			<p>Access the JSON by going to www.komando.com/api/k2/post/[post_id]</p>

			<form method="post" action="">
				<table class="form-table">
					<tbody>
					<tr valign="top">
						<th scope="row"><label for="kom_post_json_ips">Allowed IPs</label></th>
						<td>
							<textarea name="kom_post_json_ips" id="kom_post_json_ips" rows="5" cols="30"><?php echo $allowed_ips; ?></textarea>
							<p class="description">Line separated and can look like 192.168.1.1 or 192.168.1.1/22</p>
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
}