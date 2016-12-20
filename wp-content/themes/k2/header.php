<?php
// finds the last URL segment
$url = strtok($_SERVER['REQUEST_URI'], '?');
$login_boomerang = '/wp-login.php?redirect_to=' . urlencode(site_url($url));
$parse_url = parse_url($url, PHP_URL_PATH);
$url_segments = explode('/', $parse_url);
global $wp_query;
$id = $wp_query->post->ID;
$k2_post_type = $wp_query->query['post_type'];
$k2_post_type_machine = $k2_post_type;
$search_query = stripslashes(htmlentities($_GET["s"]));
// For ticket https://projects.komando.com/issues/2142 -- Google does not liking you passing emails, go figure.
if (strpos($search_query, '@') !== false && strpos($search_query, '.') !== false) {
	$filtered_search_query = '';
} else {
	$filtered_search_query = $search_query;
}

if(empty($k2_post_type) || $k2_post_type == 'any') {
	$k2query = $wp_query->tax_query->queries['0'];
	$k2taxnames = array('columns_categories', 'downloads_categories', 'apps_categories', 'cool_sites_categories', 'tips_categories', 'buying_guides_categories', 'charts_categories', 'newsletters_categories', 'previous_shows_categories', 'happening_now_categories', 'small_business_categories');
	$k2tax = $k2query['taxonomy'];
	$k2term = $k2query['terms']['0'];
	$k2termname = get_term_by('slug', $k2term, $k2query['taxonomy']);
	$k2_post_type = $wp_query->posts['0']->post_type;
	$k2_post_type_machine = $k2_post_type;
	$k2_post_type = get_post_type_object($k2_post_type);
	$k2_post_type = substr($k2_post_type->rewrite['slug'], 0, -10);
	$k2_get_terms = "'" . $k2termname->name . "'";

	if(in_array($k2tax, $k2taxnames)) {
		$k2tax = 'category';
		$k2ptypename = $k2_post_type->label;
	} else {
		$k2tax = 'tag';
		$k2ptypename = 'Tags';
	}
}



if(is_single()) {
	$k2_get_terms = wp_get_post_terms($id, array('post_tag', 'columns_categories', 'downloads_categories', 'apps_categories', 'cool_sites_categories', 'tips_categories', 'buying_guides_categories', 'charts_categories', 'newsletters_categories', 'previous_shows_categories', 'happening_now_categories', 'small_business_categories'));

	if(!empty($k2_get_terms)) {
		$i = 1;
		foreach ($k2_get_terms as $name) {
			if($i > 5) { break; }
			$name_array[] = $name->name;
			$i++;
		}

		$k2_get_terms = $name_array;
		$k2_get_terms = json_encode($k2_get_terms);
	} else {
		$k2_get_terms = "[]";
	}
}

if(is_front_page()) {
	$page_data = array('page_type' => 'front', '' => '');
} elseif(is_tax()) {
	$page_data = array('page_type' => 'category', 'category_name' => get_queried_object()->name);
} elseif(is_tag()) {
	$page_data = array('page_type' => 'tag', 'tag_name' => get_queried_object()->name);
} elseif(is_post_type_archive('qotd')) {
	$page_data = $id;
} elseif(is_post_type_archive()) {
	$page_data = array('page_type' => 'archive', 'post_type' => $k2_post_type);
} elseif(is_search()) {
	$page_data = array('page_type' => 'search', 'search_query' => $search_query);
} elseif(is_404()) {
	$page_data = array('page_type' => 'front', '' => '');
} else {
	$page_data = $id;
}

if (class_exists('Kom_Www_Api')) {
	$Kom_Www_Api = new Kom_Www_Api();
}

//podcastEpisode is a global object declared in page-episode php file, we used this object for get the correct meta tag in the podcast episode case, because that is not a post.
global $podcastEpisode;
global $podcastShow;
global $header_info;
if(is_null($header_info)) {
	$header_info = (new HeaderInfo())->setTitleImgUrlDesc(
		k2_meta_title($page_data),
		k2_og_image($id),
		get_sharing_permalink($id),
		k2_meta_description($page_data)
	);
}
if( is_post_type_archive('qotd') ) {
	$header_info->setMetaDescription( "Click here for the answer" );
	$header_info->setDescription( "Click here for the answer" );
}
if( class_exists('K2\Podcast\Show') ) {
	// Set the podcast meta data, if podcasts are enabled.
	if( !is_null( $podcastShow ) ) {
		// We're on an episode page
		$header_info = (new HeaderInfo())->setTitleImgUrlDesc(
      $podcastShow->get_title() . ' | Komando.com',
      $podcastShow->get_logo(),
      get_sharing_permalink($id) . "/" . $podcastShow->get_rss_id() . "/" . \K2\Podcast\Helper\Utils::urlize($podcastShow->get_title()),
      $podcastShow->get_description()
		);
	} elseif( !is_null( $podcastEpisode ) ) {
		// We're on a podcast listing page
		$header_info = (new HeaderInfo())->setTitleImgUrlDesc(
			$podcastEpisode->get_title(),
			$podcastEpisode->get_image(),
			get_sharing_permalink($id) . "/" . $podcastEpisode->get_episode_id() . "/" . \K2\Podcast\Helper\Utils::urlize($podcastEpisode->get_title()),
			$podcastEpisode->get_description()
		);
	}
}
$Kom_Www_Api->set_request_type("header")->set_content($Kom_Www_Api->get_header_wordpress());
?>

<!doctype html>
<html <?php language_attributes(); ?> xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#">
<head>
	<?php do_action('wp_angular_include'); ?>

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta name="description" content="<?php echo $header_info->getDescription(); ?>" />
	<meta http-equiv="Content-Type" content="text/html; <?php bloginfo('charset'); ?>" />

	<title><?php echo $header_info->getTitle(); ?></title>

	<link href="//www.google-analytics.com" rel="dns-prefetch" />
	<meta property="fb:pages" content="12244654978" />

	<?php
	if( 'publish' === get_post_status($id) ) {
		if( (is_single() || is_page()) ) { ?>
	<meta property="og:title" content="<?php echo $header_info->getTitle(); ?>" />
	<meta property="og:description" content="<?php echo $header_info->getDescription(); ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php echo get_sharing_permalink( $id, false ); ?>" />
	<meta property="og:image" content="<?php echo $header_info->getImg(); ?>" />
	<meta property="og:site_name" content="The Kim Komando Show" />
	<meta property="og:locale" content="en_us" />
	<meta property="fb:app_id" content="117626354992445" />

	<meta name="twitter:card" content="summary" />
	<meta name="twitter:site" content="@kimkomando" />
	<meta name="twitter:creator" content="@kimkomando" />
	<meta name="twitter:title" content="<?php echo $header_info->getMetaTitle(); ?>" />
	<meta name="twitter:description" content="<?php echo $header_info->getMetaDescription(); ?>" />
	<meta name="twitter:image" content="<?php echo $header_info->getImg(); ?>" />
	<?php }
	} ?>

	<meta name="bankrate-site-verification" content="GDAGD5G5" />

	<?php if((is_single() || is_page()) && get_post_status($id) == 'publish') { rel_share_canonical(); } ?>

	<link rel="author" href="https://plus.google.com/u/0/118019228588479629836" />

	<link rel="alternate" type="application/rss+xml" title="The Kim Komando Show - Feed" href="<?php bloginfo('url') ?>/feed" />

	<link rel="shortcut icon" href="<?php echo k2_get_static_url('v2'); ?>/icons/favicon.ico" />
	<link rel="apple-touch-icon-precomposed" href="<?php echo k2_get_static_url('v2'); ?>/icons/apple-touch-icon-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo k2_get_static_url('v2'); ?>/icons/apple-touch-icon-57x57-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo k2_get_static_url('v2'); ?>/icons/apple-touch-icon-72x72-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo k2_get_static_url('v2'); ?>/icons/apple-touch-icon-114x114-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo k2_get_static_url('v2'); ?>/icons/apple-touch-icon-144x144-precomposed.png" />
	<link rel="apple-touch-icon-precomposed" sizes="512x512" href="<?php echo k2_get_static_url('v2'); ?>/icons/apple-touch-icon-512x512-precomposed.png" />

	<link href="<?php echo k2_get_static_url('v2'); ?>/css/style<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.css?ver=<?php echo CACHE_UPDATE; ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo k2_get_static_url('v2'); ?>/owl-carousel/owl.carousel<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.css?ver=<?php echo CACHE_UPDATE; ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo k2_get_static_url('v2'); ?>/owl-carousel/owl.theme<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.css?ver=<?php echo CACHE_UPDATE; ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo k2_get_static_url('v2'); ?>/font-awesome/css/font-awesome.min.css?ver=<?php echo CACHE_UPDATE; ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo k2_get_static_url('v2'); ?>/css/k2-icons.css?ver=<?php echo CACHE_UPDATE; ?>" rel="stylesheet" type="text/css" />
	<link href="<?php echo k2_get_static_url('v2'); ?>/bxslider/jquery.bxslider.css?ver=<?php echo CACHE_UPDATE; ?>" rel="stylesheet" type="text/css" />

	<script type="text/javascript">
		/* https://github.com/withjam/jqshim-head
		 ** Defines window.jQuery and window.$ */
		(function(){"use strict";var c=[],f={},a,e,d,b;if(!window.jQuery){a=function(g){c.push(g)};f.ready=function(g){a(g)};e=window.jQuery=window.$=function(g){if(typeof g=="function"){a(g)}return f};window.checkJQ=function(){if(!d()){b=setTimeout(checkJQ,100)}};b=setTimeout(checkJQ,100);d=function(){if(window.jQuery!==e){clearTimeout(b);var g=c.shift();while(g){jQuery(g);g=c.shift()}b=f=a=e=d=window.checkJQ=null;return true}return false}}})();
	</script>

	<?php wp_head(); ?>

	<!--[if lte IE 9]>
	<script src="<?php echo k2_get_static_url('v2'); ?>/js/html5shiv.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
	<script src="<?php echo k2_get_static_url('v2'); ?>/js/jquery.placeholder<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('input, textarea').placeholder();
		})
	</script>
	<![endif]-->

	<script type="text/javascript">
		var __st_loadLate=true;
		var switchTo5x=true;
	</script>
	<script type="text/javascript" src="<?php echo k2_get_static_url('v2'); ?>/js/ads-blocker-detect.js"></script>
	<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
	<script type="text/javascript">
		stLight.options({
			publisher: 'dbb97137-f0f2-40c0-bdbb-24c542e1b944',
			doNotHash: true,
			onhover: false
		});
	</script>

	<?php if($_SERVER['HTTP_USER_AGENT'] != 'SkipAdsForTesting' && !is_search()) { ?>
		<script type="text/javascript">
			var googletag = googletag || {};
			googletag.cmd = googletag.cmd || [];
			(function() {
				var gads = document.createElement('script');
				gads.async = true;
				gads.type = 'text/javascript';
				var useSSL = 'https:' == document.location.protocol;
				gads.src = (useSSL ? 'https:' : 'http:') +
					'//www.googletagservices.com/tag/js/gpt.js';
				var node = document.getElementsByTagName('script')[0];
				node.parentNode.insertBefore(gads, node);
			})();
		</script>
		<script type="text/javascript">
			googletag.cmd.push(function() {

				// Defines the ad sizes for responsiveness
				var mapping = googletag.sizeMapping().addSize([730, 100], [728, 90]).addSize([640, 480], [320,100]).addSize([0, 0], [320,100]).build();
				var ribbon_mapping = googletag.sizeMapping().addSize([730, 100], [728, 40]).addSize([640, 480], [320,100]).addSize([0, 0], [320,100]).build();
				var content_ribbon_mapping = googletag.sizeMapping().addSize([740, 50], [728, 40]).build();
				var content_square_mapping = googletag.sizeMapping().addSize([400, 0], [336, 280]).addSize([300, 250]).build();
				var home_shop_mapping = googletag.sizeMapping().addSize([215, 350], [215, 350]);

				<?php if(is_front_page()) { // Front page ads ?>

				googletag.defineSlot('/1064811/k2-www-home-landing-ribbon', [[728, 40], [320, 100]], 'ad-leaderboard-top-ribbon').defineSizeMapping(ribbon_mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-home-landing-leaderboard', [[728, 90], [320, 100]], 'ad-leaderboard-top').defineSizeMapping(mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-home-landing-featured-1', [[300, 250], [300, 600]], 'ad-rectangle-featured-1').addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-home-landing-featured-2', [[300, 250], [300, 600]], 'ad-rectangle-featured-2').addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-home-landing-grid-1', [300, 250], 'ad-rectangle-grid-1').addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-home-landing-shop-1', [215, 350], 'ad-home-shop-1').defineSizeMapping(home_shop_mapping).addService(googletag.pubads());

				<?php } elseif(is_tag()) { // Tag ads ?>

				googletag.defineSlot('/1064811/k2-www-tag-ribbon', [[728, 40], [320, 50]], 'ad-leaderboard-top-ribbon').defineSizeMapping(ribbon_mapping).addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);
				googletag.defineSlot('/1064811/k2-www-tag-leaderboard', [[728, 90], [320, 100]], 'ad-leaderboard-top').defineSizeMapping(mapping).addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);
				googletag.defineSlot('/1064811/k2-www-tag-sidebar-1', [[300, 250], [300, 600]], 'ad-rectangle-sidebar-1').addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);
				googletag.defineSlot('/1064811/k2-www-tag-sidebar-2', [300, 250], 'ad-rectangle-sidebar-2').addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);

				<?php } elseif(is_tax()) { // Category ads ?>

				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-category-ribbon', [[728, 40], [320, 50]], 'ad-leaderboard-top-ribbon').defineSizeMapping(ribbon_mapping).addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);
				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-category-leaderboard', [[728, 90], [320, 100]], 'ad-leaderboard-top').defineSizeMapping(mapping).addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);
				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-category-sidebar-1', [[300, 250], [300, 600]], 'ad-rectangle-sidebar-1').addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);
				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-category-sidebar-2', [300, 250], 'ad-rectangle-sidebar-2').addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);

				<?php } elseif(is_post_type_archive(array('charts', 'previous_shows'))) { // Custom post type ads ?>

				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-landing-ribbon', [[728, 40], [320, 50]], 'ad-leaderboard-top-ribbon').defineSizeMapping(ribbon_mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-landing-leaderboard', [[728, 90], [320, 100]], 'ad-leaderboard-top').defineSizeMapping(mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-landing-sidebar-1', [[300, 250], [300, 600]], 'ad-rectangle-sidebar-1').addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-landing-sidebar-2', [300, 250], 'ad-rectangle-sidebar-2').addService(googletag.pubads());

				<?php } elseif(is_post_type_archive('qotd')) { // Custom post type ads ?>

				googletag.defineSlot('/1064811/k2-www-qotd-content-ribbon', [[728, 40], [320, 50]], 'ad-leaderboard-top-ribbon').defineSizeMapping(ribbon_mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-qotd-content-leaderboard', [[728, 90], [320, 100]], 'ad-leaderboard-top').defineSizeMapping(mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-qotd-content-sidebar-1', [[300, 250], [300, 600]], 'ad-rectangle-sidebar-1').addService(googletag.pubads());
				if(screen.width > 600) {
					googletag.defineSlot('/1064811/k2-www-qotd-content-sidebar-2', [300, 250], 'ad-rectangle-sidebar-2').addService(googletag.pubads());
				}
				googletag.defineSlot('/1064811/k2-www-qotd-content-content-1', [300, 250], 'ad-rectangle-content').addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-qotd-content-content-view-all-1', [300, 250], 'k2-www-content-view-all-1').addService(googletag.pubads());

				<?php } elseif(is_post_type_archive('happening_now')) { // Custom post type ads ?>

				googletag.defineSlot('/1064811/k2-www-happening_now-landing-ribbon', [[728, 40], [320, 50]], 'ad-leaderboard-top-ribbon').defineSizeMapping(ribbon_mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-happening_now-landing-leaderboard', [[728, 90], [320, 100]], 'ad-leaderboard-top').defineSizeMapping(mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-happening_now-landing-featured-1', [[300, 250], [300, 600]], 'ad-rectangle-featured-1').addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-happening_now-landing-featured-2', [[300, 250], [300, 600]], 'ad-rectangle-featured-2').addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-happening_now-landing-featured-3', [[300, 250], [300, 600]], 'ad-rectangle-featured-3').addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-happening_now-landing-grid-1', [300, 250], 'ad-rectangle-grid-1').addService(googletag.pubads());

				<?php } elseif(is_post_type_archive()) { // Custom post type ads ?>

				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-landing-ribbon', [[728, 40], [320, 50]], 'ad-leaderboard-top-ribbon').defineSizeMapping(ribbon_mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-landing-leaderboard', [[728, 90], [320, 100]], 'ad-leaderboard-top').defineSizeMapping(mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-landing-featured-1', [[300, 250], [300, 600]], 'ad-rectangle-featured-1').addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-landing-featured-2', [[300, 250], [300, 600]], 'ad-rectangle-featured-2').addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-landing-grid-1', [300, 250], 'ad-rectangle-grid-1').addService(googletag.pubads());

				<?php } elseif(is_singular('charts')) { // Charts single pages ?>

				googletag.defineSlot('/1064811/k2-www-charts-content-ribbon', [[728, 40], [320, 50]], 'ad-leaderboard-top-ribbon').defineSizeMapping(ribbon_mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-charts-content-leaderboard', [[728, 90], [320, 100]], 'ad-leaderboard-top').defineSizeMapping(mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-charts-content-leaderboard-2', [[728, 90], [320, 50]], 'k2-www-charts-1').defineSizeMapping(mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-charts-content-leaderboard-3', [[728, 90], [320, 50]], 'k2-www-charts-2').defineSizeMapping(mapping).addService(googletag.pubads());

				<?php } elseif(is_single()) { // Article pages, anything that behaves like a post ?>

				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-content-ribbon', [[728, 40], [320, 50]], 'ad-leaderboard-top-ribbon').defineSizeMapping(ribbon_mapping).addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);
				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-content-leaderboard', [[728, 90], [320, 100]], 'ad-leaderboard-top').defineSizeMapping(mapping).addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);
				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-content-sidebar-1', [[300, 250], [300, 600]], 'ad-rectangle-sidebar-1').addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);
				if(screen.width > 600){
					googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-content-sidebar-2', [300, 250], 'ad-rectangle-sidebar-2').addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);
				}
				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-content-sidebar-3', [[300, 250], [300, 600]], 'ad-rectangle-sidebar-3').addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);
				googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-content-content-1', [[300, 250], [336, 280]], 'ad-rectangle-content').defineSizeMapping(content_square_mapping).addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);
				if(window.innerWidth > 740){
					googletag.defineSlot('/1064811/k2-www-<?php echo $k2_post_type_machine; ?>-content-body-ribbon', [728, 40], 'ad-leaderboard-body-ribbon').addService(googletag.pubads()).setTargeting('keywords', <?php echo $k2_get_terms; ?>);
				}

				<?php } elseif(is_page('security-center')) { // Security center ?>

				googletag.defineSlot('/1064811/k2-www-security-center-ribbon-1', [[728, 40], [320, 50]], 'ad-leaderboard-top-ribbon').defineSizeMapping(ribbon_mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-security-center-leaderboard-1', [[728, 90], [320, 100]], 'ad-leaderboard-top').defineSizeMapping(mapping).addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-security-center-sidebar-1', [300, 250], 'ad-rectangle-sidebar-1').addService(googletag.pubads());
				googletag.defineSlot('/1064811/k2-www-security-center-sidebar-2', [300, 250], 'ad-rectangle-sidebar-2').addService(googletag.pubads());

				<?php } else { // Catch for everything else, pages ?>

				googletag.defineSlot('/1064811/k2-www-ribbon', [[728, 40], [320, 50]], 'ad-leaderboard-top-ribbon').defineSizeMapping(ribbon_mapping).addService(googletag.pubads()).setTargeting('section', '<?php echo get_post($id)->post_name; ?>');
				googletag.defineSlot('/1064811/k2-www-leaderboard-top', [[728, 90], [320, 100]], 'ad-leaderboard-top').defineSizeMapping(mapping).addService(googletag.pubads()).setTargeting('section', '<?php echo get_post($id)->post_name; ?>');
				googletag.defineSlot('/1064811/k2-www-sidebar-1', [[300, 250], [300, 600]], 'ad-rectangle-sidebar-1').addService(googletag.pubads()).setTargeting('section', '<?php echo get_post($id)->post_name; ?>');
				googletag.defineSlot('/1064811/k2-www-sidebar-2', [300, 250], 'ad-rectangle-sidebar-2').addService(googletag.pubads()).setTargeting('section', '<?php echo get_post($id)->post_name; ?>');
				googletag.defineSlot('/1064811/k2-www-sidebar-3', [[300, 250], [300, 600]], 'ad-rectangle-sidebar-3').addService(googletag.pubads()).setTargeting('section', '<?php echo get_post($id)->post_name; ?>');

				<?php } ?>

				<?php if(function_exists('k2_interstitial_ad_code')) { k2_interstitial_ad_code($k2_get_terms); } ?>
			});




	<?php
	if(class_exists('Kom_Article_Gallery') && Kom_Article_Gallery::post_has_gallery($id)){
		?>
			komando_gallery_ad_slots = {};
			googletag.cmd.push(function() {
				var gallery_leaderboard_mapping = googletag.sizeMapping().addSize([730, 100], [728, 90]).addSize([640, 480], [320,50]).addSize([0, 0], [320,50]).build();
				var gallery_right_mapping = googletag.sizeMapping().addSize([0, 0], [[300, 250], [300, 600]]).build();
				var gallery_interspersed_mapping = googletag.sizeMapping().addSize([0, 0], [300, 250]).build();
				komando_gallery_ad_slots.slot1 = googletag.defineSlot('/1064811/k2-www-gallery-leaderboard', [[728, 90], [320, 50]], 'k2-www-gallery-leaderboard')
					.addService(googletag.pubads())
					.defineSizeMapping(gallery_leaderboard_mapping);
				komando_gallery_ad_slots.slot2 = googletag.defineSlot('/1064811/k2-www-gallery-right', [[300, 600]], 'k2-www-gallery-right')
					.addService(googletag.pubads())
					.defineSizeMapping(gallery_right_mapping);
				komando_gallery_ad_slots.slot3 = googletag.defineSlot('/1064811/k2-www-gallery-interspersed', [[300, 250]], 'k2-www-gallery-interspersed')
					.addService(googletag.pubads())
					.defineSizeMapping(gallery_interspersed_mapping);
				googletag.enableServices();
				// to refresh: googletag.pubads().refresh([komando_gallery_ad_slots.slot1]);
			});
		<?php
	}
	?>


			googletag.cmd.push(function() {
				googletag.pubads().collapseEmptyDivs();
				googletag.enableServices();
			});
		</script>
	<?php } ?>


	<noscript>
		<style>.noscript { display: block; }</style>
	</noscript>
	<!--[if lte IE 8]>
	<link href="<?php echo k2_get_static_url('v2'); ?>/css/ie8.css?ver=<?php echo CACHE_UPDATE; ?>" rel="stylesheet" type="text/css" />
	<style>.ienotice { display: block; }</style>
	<![endif]-->


	<?php if (class_exists('Kom_Www_Api')) {
		// Active menu item override
		$active_class = $Kom_Www_Api->active_menu_item($url_segments[1]);
		if ($active_class) {
			?>
			<style>
				.mobile-menu ul li a.<?php echo $active_class['mobile']; ?> { color: #ffffff; background: rgba(8, 118, 227, .15); }
				.main-menu li a.<?php echo $active_class['desktop']; ?>, .main-menu li a.<?php echo $active_class['desktop']; ?> i.fa-home { color: #47c1ff; background: rgba(3, 32, 98, .95); border-radius: 2px; }
			</style>
		<?php }
	} ?>

<!-- Google Analytics -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-230639-2', 'auto');
  ga('send', 'pageview');

</script>
<!-- End Google Analytics -->

<script type="text/javascript">
	ga('require', 'displayfeatures');
	<?php echo $acctlevel; ?>

	if( window.doesNotHaveAdBlocker === undefined ){
		ga('set', 'dimension2', 'ad-blocker-yes');
	} else {
		ga('set', 'dimension2', 'ad-blocker-no');
	}
</script>

</head>
<body <?php body_class(); ?>>
<div id="fb-root"></div>
<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=117626354992445&version=v2.0";
		fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<!-- Noscript and IE7 notices -->
<div class="noscript">
	<div class="alert alert-error">
		<center>JavaScript is currently disabled in your browser. In order to watch videos and to navigate this website, JavaScript needs to be enabled. Here are the <a href="http://www.enable-javascript.com/" target="_blank">instructions how to enable JavaScript in your web browser</a>.</center>
	</div>
</div>
<div class="ienotice">
	<div class="alert alert-error">
		<center>Your browser is out of date and not supported. <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie" target="_blank">Click here</a> to download the latest version of Internet Explorer.</center>
	</div>
</div>

<?php if(function_exists('k2_interstitials')) { k2_interstitials(); } ?>

<?php ob_start(); ?>
<?php
if (is_user_logged_in()) { // user is logged in

	$user_id = get_current_user_id();
	$user_name = get_the_author_meta('cas_username', $user_id);
	$user_first_name = get_the_author_meta('first_name', $user_id);
	$user_email = get_the_author_meta('user_email', $user_id);

	if(!empty($user_name)) {
		if(strlen($user_name) > 12) {
			$display_name = substr($user_name, 0, 12) . '...';
		} else {
			$display_name = $user_name;
		}
	} elseif(!empty($user_first_name)) {
		if(strlen($user_first_name) > 12) {
			$display_name = substr($user_first_name, 0, 12) . '...';
		} else {
			$display_name = $user_first_name;
		}
	} else {
		if(strlen($user_email) > 12) {
			$display_name = substr($user_email, 0, 12) . '...';
		} else {
			$display_name = $user_email;
		}
	}

	if(current_user_can('premium_member') || current_user_can('basic_member')) { // user is a premium member
		?>
		<li class="alt-menu-account"><a href="javascript:void(0)">Hi, <?php echo $display_name; ?> <i class="fa fa-caret-down"></i></a>
			<ul class="clearfix">
				<li class="alt-menu-account"><a href="<?php echo CLUB_BASE_URI; ?>/account">My Account</a></li>
				<li><a href="<?php echo wp_logout_url(urlencode(site_url($url))); ?>">Sign Out</a></li>
			</ul>
		</li>
	<?php } else { // user is a free member ?>
		<li><a href="<?php echo CLUB_BASE_URI; ?>/products?r=<?php echo urlencode(site_url($url)); ?>" class="join-kims-club">Join Kim's Club<span><i class="fa fa-lock"></i></span></a></li>
		<li class="alt-menu-account"><a href="javascript:void(0)">Hi, <?php echo $display_name; ?> <i class="fa fa-caret-down"></i></a>
			<ul class="clearfix">
				<li><a href="<?php echo CLUB_BASE_URI; ?>/account">My Account</a></li>
				<li><a href="<?php echo wp_logout_url(urlencode(site_url($url))); ?>">Sign Out</a></li>
			</ul>
		</li>
		<?php
	}
} else { // user is not logged in
	?>
	<li><a href="<?php echo CLUB_BASE_URI; ?>/products?r=<?php echo urlencode(site_url($url)); ?>" class="join-kims-club">Join Kim's Club<span><i class="fa fa-lock"></i></span></a></li>
	<li><a href="<?php echo site_url($login_boomerang); ?>">Sign In</a></li>
<?php } ?>
<li class="search"><div class="search-container"><form method="get" action="<?php echo site_url(); ?>" role="search"><button type="submit"><i class="fa fa-search"></i></button><input type="text" class="hide-tablet" id="search" name="s" value="<?php if(!empty($search_query)) { echo $search_query; } ?>" placeholder="Search Komando.com..." x-webkit-speech="x-webkit-speech" /></form></div></li>
<?php $Kom_Www_Api->fill_replace_point('login_box_and_search', ob_get_clean()); ?>

<?php ob_start(); ?>
<?php
if (is_user_logged_in()) { // user is logged in

	$user_id = get_current_user_id();
	$user_name = get_the_author_meta('cas_username', $user_id);
	$user_first_name = get_the_author_meta('first_name', $user_id);
	$user_email = get_the_author_meta('user_email', $user_id);

	if(!empty($user_name)) {
		if(strlen($user_name) > 12) {
			$display_name = substr($user_name, 0, 12) . '...';
		} else {
			$display_name = $user_name;
		}
	} elseif(!empty($user_first_name)) {
		if(strlen($user_first_name) > 12) {
			$display_name = substr($user_first_name, 0, 12) . '...';
		} else {
			$display_name = $user_first_name;
		}
	} else {
		if(strlen($user_email) > 12) {
			$display_name = substr($user_email, 0, 12) . '...';
		} else {
			$display_name = $user_email;
		}
	}

	?>
	<li class="mobile-sub-toggle"><a href="javascript:void(0)">Hi, <?php echo $display_name; ?> <div class="toggle-wrap"><span class="icon-k2-plus mobile-sub-plus"></span><span class="icon-k2-times mobile-sub-active"></span></div></a>
		<ul class="mobile-sub-menu">
			<li><a href="<?php echo CLUB_BASE_URI; ?>/account">My Account</a></li>
			<li><a href="<?php echo wp_logout_url(urlencode(site_url($url))); ?>">Sign Out</a></li>
		</ul>
	</li>
<?php } else { ?>
	<li><a href="<?php echo site_url($login_boomerang); ?>">Sign In</a></li>
<?php } ?>
<?php $Kom_Www_Api->fill_replace_point('mobile_login_details', ob_get_clean()); ?>
<?php
echo $Kom_Www_Api->get_content();

if(is_front_page()) {
	if(function_exists('k2_special_alert')) {
		k2_special_alert();
	}
}

if(!is_404() && !is_page_template('page-advertise.php') && !is_page_template('page-kims-club.php') && !is_search()) { ?>
	<div class="ad leaderboard-ad clearfix">
		<?php /* <span>-advertisement-</span> */ ?>
		<div id="ad-leaderboard-top-ribbon" style="min-width:320px; margin:auto; text-align:center;">
			<script type="text/javascript">
				googletag.cmd.push(function() { googletag.display('ad-leaderboard-top-ribbon'); });
			</script>
		</div>
	</div>

	<div class="ad leaderboard-ad clearfix">
		<div id="ad-leaderboard-top" style="min-width:320px; min-height:50px; margin:auto; text-align:center;">
			<script type="text/javascript">
				googletag.cmd.push(function() { googletag.display('ad-leaderboard-top'); });
			</script>
		</div>
	</div>




<?php } ?>

<?php global $inject_above_body_class_div;
echo $inject_above_body_class_div; ?>

<!--######################
	Start Main Content
#######################-->

<div <?php body_class('wrapper clearfix'); ?>>
