<?php 
// finds the last URL segment
$site_url = get_bloginfo('url');
$url = $_SERVER['REQUEST_URI'];
$parse_url = parse_url($url, PHP_URL_PATH);
$url_segments = explode('/', $parse_url);
$numSegments = count($url_segments); 
$currentSegment = $url_segments[$numSegments - 1];

if(strpos($currentSegment, '.')) {
	$ext = substr(strrchr($currentSegment, '.'), 1);
}

if(!empty($ext) && ($ext == 'html' || $ext == 'htm' || $ext == 'asp' || $ext == 'aspx' || $ext == 'php')) {
	if($currentSegment == 'index.htm') {
		
		$prevSegement = $url_segments[$numSegments - 2];

		if(!empty($prevSegement)) {

			switch ($prevSegement) {
				case 'newsletters':
				$redirect = $site_url . '/newsletters';
				break;

				case 'columns':
				$redirect = $site_url . '/columns';
				break;

				case 'downloads':
				$redirect = $site_url . '/downloads';
				break;

				case 'apps':
				$redirect = $site_url . '/apps';
				break;

				case 'coolsites':
				$redirect = $site_url . '/cool-sites';
				break;

				case 'tips':
				$redirect = $site_url . '/tips';
				break;

				case 'buyguide':
				$redirect = $site_url . '/buying-guides';
				break;

				case 'charts':
				$redirect = $site_url . '/charts';
				break;
			}

		} else {

			$redirect = $site_url;

		}

		status_header(301);

	} else if($currentSegment == 'smartphones.asp') {

		$redirect = $site_url . '/charts/238326/smartphone-comparison-chart';
		status_header(301);

	} else if($currentSegment == 'tablets.asp') {

		$redirect = $site_url . '/charts/242385/tablet-comparison-chart';
		status_header(301);

	} else if($currentSegment == 'tabletmini.asp') {

		$redirect = $site_url . '/charts/238344/mini-tablet-comparison-chart';
		status_header(301);

	} else if($currentSegment == 'streaming-gadget.asp') {

		$redirect = $site_url . '/charts/238342/streaming-video-gadgets';
		status_header(301);

	} else if($currentSegment == 'freesmartphones.asp') {

		$redirect = $site_url . '/charts/238317/free-smartphone-comparison-chart';
		status_header(301);

	} else if($currentSegment == 'cloud_storage.asp') {

		$redirect = $site_url . '/charts/238307/cloud-storage';
		status_header(301);

	} else if($currentSegment == 'video-game-console.asp') {

		$redirect = $site_url . '/charts/243232/video-game-consoles';
		status_header(301);

	} else if($url_segments[1] == 'careers') {

		$redirect = $site_url . '/jobs';
		status_header(301);

	} else if($url_segments[1] == 'moneycenter') {

		$redirect = $site_url . '/money-center';
		status_header(301);

	} else if($url_segments[1] == 'operationkomando') {

		$redirect = $site_url . '/operation-komando';
		status_header(301);

	} else if($url_segments[1] == 'faq') {

		$redirect = $site_url . '/faqs';
		status_header(301);

	} else if($url_segments[1] == 'ereader') {

		$redirect = $site_url . '/ereaders';
		status_header(301);

	} else if($currentSegment == 'messageboard') {

		$redirect = FORUM_BASE_URI;
		status_header(301);

	} else if($currentSegment == 'manage-newsletter.aspx') {

		$redirect = CLUB_BASE_URI . '/newsletters';
		status_header(301);

	} else if($currentSegment == 'show-picks.aspx') {

		$redirect = $site_url . '/show-picks';
		status_header(301);

	} else if(isset($_GET['id'])) {

		$old_id = $_GET['id'];

		$args = array(
			'posts_per_page' => 1,
			'post_type' => array('columns', 'downloads', 'apps', 'cool_sites', 'tips', 'buying_guides', 'charts'),
			'post_status' => 'any',
			'meta_query' => array(
								array(
									'key' => 'old_id',
									'value' => $old_id,
									'compare' => '='
								)
							)
		);

		$wp_query = new WP_Query($args);

		if(!empty($wp_query->posts)) {
			foreach ($wp_query->posts as $post) {
				$redirect = get_permalink($post->ID);
			}

			wp_reset_query();

			status_header(301);
		} else {
			$search_query = preg_replace(array('/-/', '/ /', '/%20/'), '+', $currentSegment);
			$redirect = $site_url . '/?s=' . $search_query;
			status_header(404);
		}

	} else {

		$search_query = preg_replace(array('/-/', '/ /', '/%20/'), '+', $currentSegment);
		$redirect = $site_url . '/?s=' . $search_query;
		status_header(404);

	}

	header("Location: $redirect");
} else {

	status_header(404);
	get_header(); 

?>

	<section class="content-full clearfix" role="main">
		<br /><br />
		<center>The page or file you are looking for cannot be found. Please use the navigation above to find what you are looking for.</center>
		<br /><br />
	</section>

<?php get_footer(); } ?>