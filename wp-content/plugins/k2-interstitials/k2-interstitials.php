<?php
/*
Plugin Name: K2 Interstitials
Plugin URI: http://www.komando.com
Description: Creates the interstitial for content
author: Kelly Karnetsky
Version: 0.1
Author URI: http://www.komando.com
*/

function k2_interstitials() {
	
	global $wp_query;
	$post_id = get_queried_object_id();
	$post_type = get_post_type($post_id);
	$post_type = get_post_type_object($post_type)->labels->singular_name;

	// finds the last URL segment  
	$urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$segments = explode('/', $urlArray);
	$numSegments = count($segments); 
	$currentSegment = $segments[$numSegments - 1];

	if(is_single() && $currentSegment == 'all' && !is_user_logged_in()) { ?>
		
		<script src="<?php echo k2_get_static_url('v2'); ?>/js/k2-interstitial.min.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>

		<div class="interstitial-wrapper">
			<div class="interstitial-header clearfix">
				<div class="interstitial-progress-bar"></div>
				<div class="interstitial-header-inner">
					<div class="interstitial-logo">
						<img src="<?php echo k2_get_static_url('v2'); ?>/img/interstitial-logo.png" alt="[LOGO] Kim Komanado" />
					</div>
					<div class="interstitial-countdown hide-mobile hide-tablet">
						You will be taken to the content automatically in <span>15</span> seconds
					</div>
					<div class="interstitial-skip"><div class="before">Skip in <span>5</span></div><div class="after">Skip to <?php echo $post_type; ?></div></div>
				</div>

				<div class="insterstitial-unit-wrapper">
					<div class="interstitial-dec">Advertisement</div>
					<div id="interstitial-unit" class="interstitial-unit">
						<script type='text/javascript'>
						googletag.cmd.push(function() { googletag.display('interstitial-unit'); });
						</script> 
					</div>
				</div>
			</div>
		</div>



	<?php }
	
}

function k2_interstitial_ad_code($terms) {
	global $wp_query;
	$post_id = get_queried_object_id();
	$post_type = get_post_type($post_id);

	// finds the last URL segment  
	$urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$segments = explode('/', $urlArray);
	$numSegments = count($segments); 
	$currentSegment = $segments[$numSegments - 1];

	if(is_single() && $currentSegment == 'all' && !is_user_logged_in()) { ?>
		googletag.defineSlot('/1064811/k2-www-<?php echo $post_type; ?>-content-interstitial-view-all-1', [[300, 250]], 'interstitial-unit').addService(googletag.pubads()).setTargeting('keywords', <?php echo $terms; ?>);
	<?php }
}

// Kills plugin update lookup
add_filter('http_request_args', 'hidden_plugin_k2_interstitials', 5, 2);
function hidden_plugin_k2_interstitials($r, $url) {
	if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
		return $r; // Not a plugin update request. Bail immediately.
	$plugins = unserialize($r['body']['plugins']);
	unset($plugins->plugins[plugin_basename(__FILE__)]);
	unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
	$r['body']['plugins'] = serialize($plugins);
	return $r;
}
?>
