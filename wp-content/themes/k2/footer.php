<?php
global $wp_query;
$id = $wp_query->post->ID;
$k2_post_type = $wp_query->query['post_type'];
$k2_post_type_machine = $k2_post_type;

// Define the var for the fixed ad slot
if (is_front_page()) {
	$fixed_ad_slot = 'k2-www-home-fixed-bottom';
} elseif (is_tag()) {
	$fixed_ad_slot = 'k2-www-tag-fixed-bottom';
} elseif (is_tax()) {
	$fixed_ad_slot = 'k2-www-tax-fixed-bottom';
} elseif (is_post_type_archive()) {
	$fixed_ad_slot = 'k2-www-' . $k2_post_type_machine . '-fixed-bottom';
} elseif (is_search()) {
	$fixed_ad_slot = 'k2-www-search-fixed-bottom';
} elseif (is_single()) {
	$fixed_ad_slot = 'k2-www-' . $k2_post_type_machine . '-content-fixed-bottom';
} else {
	$fixed_ad_slot = 'k2-www-fixed-bottom';
}
?>

</div>

<?php if(is_singular('charts')) { ?>
<div class="ad leaderboard-ad clearfix">
	<?php /* <span>-advertisement-</span> */ ?>
	<div id="k2-www-charts-2" style="max-width:728px; max-height:90px; margin:auto; text-align:center">
		<script type="text/javascript">
			googletag.cmd.push(function() { googletag.display('k2-www-charts-2'); });
		</script>
	</div>
</div>
<?php } ?>

<!--######################
	Start Footer
#######################-->

<?php

if (class_exists('Kom_Www_Api')) {
	$Kom_Www_Api = new Kom_Www_Api();
}

echo $Kom_Www_Api->get_footer_wordpress();

?>
	<!-- Taboola start -->
	<script type="text/javascript">
		window._taboola = window._taboola || [];
		!function (e, f, u) {
			e.async = 1;
			e.src = u;
			f.parentNode.insertBefore(e, f);
		}(document.createElement('script'), document.getElementsByTagName('script')[0], '//cdn.taboola.com/libtrc/komando/loader.js');

		_taboola.push({ mode: 'thumbs-2r-2nd', container: 'taboola-below-article-thumbs-mix', placement: '<?php echo $k2_post_type; ?>-below-article-thumbs', target_type: 'mix' });
		_taboola.push({ mode: 'thumbnails-b', container: 'taboola-sidebar-thumbnails', placement: 'Sidebar Thumbnails - <?php echo $k2_post_type; ?>', target_type: 'mix' });
		_taboola.push({ article: 'auto' });
		_taboola.push({ flush: true });
	</script>
	<!-- Taboola end -->
<?php

// Determine whether user is logged-in and if so, with what level of membership
if (is_user_logged_in()) {
	if (current_user_can('premium_member')){
		$acctlevel = "ga('set', 'dimension1', 'account-premium');";
	} elseif (current_user_can('basic_member')){
		$acctlevel = "ga('set', 'dimension1', 'account-basic');";
	} else {
		$acctlevel = "ga('set', 'dimension1', 'account-free');";
	}
} else {
	$acctlevel = "ga('set', 'dimension1', 'account-no');";
}
echo('<script type="text/javascript">'.$acctlevel.'</script>'); // Do not remove, this is required for this to work!
?>

<!-- Google Mobile Ad -->
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
	(adsbygoogle = window.adsbygoogle || []).push({
		google_ad_client: "ca-pub-1581871527628066",
		enable_page_level_ads: true
	});
</script>
<!-- End Google Mobile Ad -->

<?php if(SERVER_ENVIRONMENT == 'production') { ?>

	<script src="<?php echo k2_get_static_url('v2'); ?>/js/all-site-scripts.min.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>

<?php } else { ?>

	<script src="<?php echo k2_get_static_url('v2'); ?>/js/jquery-1.10.2.min.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
	<script src="<?php echo k2_get_static_url('v2'); ?>/js/jquery.detectmobilebrowser.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
	<script src="<?php echo k2_get_static_url('v2'); ?>/js/jquery.unveil<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
	<script src="<?php echo k2_get_static_url('v2'); ?>/js/bootstrap.tooltip<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
	<script src="<?php echo k2_get_static_url('v2'); ?>/js/sticky-pagination-fixer<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
	<script src="<?php echo k2_get_static_url('v2'); ?>/js/jquery.validate.min.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
	<script src="<?php echo k2_get_static_url('v2'); ?>/js/jquery.cookie.min.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
	<script src="<?php echo k2_get_static_url('v2'); ?>/js/jquery.komaccordion.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
	<script src="<?php echo k2_get_static_url('v2'); ?>/js/site<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>

<?php } ?>

<script src="<?php echo k2_get_static_url('v2'); ?>/jwplayer/jwplayer.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
<script type="text/javascript">jwplayer.key='PAEWEC6MDy4jAXkKcAt6W8Gfg1b9gzjCl5G77efO5G0=';</script>
<script src="<?php echo k2_get_static_url('v2'); ?>/nouislider/jquery.nouislider.min.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
<script src="<?php echo k2_get_static_url('v2'); ?>/owl-carousel/owl.carousel<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
<?php /* <script src="<?php echo k2_get_static_url('v2'); ?>/js/jquery.komshopnewslettersubscribe<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script> */ ?>
<script src="<?php echo k2_get_static_url('v2'); ?>/widgets/newsletter-subscribe/newsletter-subscribe<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>

<script src="<?php echo k2_get_static_url('v2'); ?>/widgets/newsletter-subscribe/subscribe-popup<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>

<script src="<?php echo k2_get_static_url('v2'); ?>/widgets/gallery-images/gallery-images-v2<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
<script src="<?php echo k2_get_static_url('v2'); ?>/widgets/comparison-chart/comparison-chart<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
<script src="<?php echo k2_get_static_url('v2'); ?>/bxslider/jquery.bxslider.min.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
<script src="<?php echo k2_get_static_url('v2'); ?>/js/listen-on-demand<?php if(SERVER_ENVIRONMENT == 'production') { echo '.min'; } ?>.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>

<!-- For on air plugin -->
<script src="<?php echo k2_get_static_url('v2'); ?>/js/jquery.plugin.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>
<script src="<?php echo k2_get_static_url('v2'); ?>/js/jquery.countdown.js?ver=<?php echo CACHE_UPDATE; ?>" type="text/javascript"></script>

<?php if(is_post_type_archive('downloads')) {
	## For ticket 1585 - Carbonite impression tracker - downloads home only
	echo '<script type="text/javascript" src="//tags.mediaforge.com/js/2572"></script>';
} ?>

<!-- iTunes Auto Link Maker -->
<script type='text/javascript'>var _merchantSettings=_merchantSettings || [];_merchantSettings.push(['AT', '1l3vbEM']);(function(){var autolink=document.createElement('script');autolink.type='text/javascript';autolink.async=true; autolink.src= ('https:' == document.location.protocol) ? 'https://autolinkmaker.itunes.apple.com/js/itunes_autolinkmaker.js' : 'http://autolinkmaker.itunes.apple.com/js/itunes_autolinkmaker.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(autolink, s);})();</script>

<!-- AdRoll script -->
<script type="text/javascript">
adroll_adv_id = "7WN7DQNHXJHMZNO4SQADYB";
adroll_pix_id = "RIVHYEDCLBFOTA5B6W5JU4";
/* OPTIONAL: provide email to improve user identification */
/* adroll_email = "username@example.com"; */
(function () {
var _onload = function(){
if (document.readyState && !/loaded|complete/.test(document.readyState)){setTimeout(_onload, 10);return}
if (!window.__adroll_loaded){__adroll_loaded=true;setTimeout(_onload, 50);return}
var scr = document.createElement("script");
var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
scr.setAttribute('async', 'true');
scr.type = "text/javascript";
scr.src = host + "/j/roundtrip.js";
((document.getElementsByTagName('head') || [null])[0] ||
document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
};
if (window.addEventListener) {window.addEventListener('load', _onload, false);}
else {window.attachEvent('onload', _onload)}
}());
</script>
<!-- Facebook Pixel Code -->
	<script>
		!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
			n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
			n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
			t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
			document,'script','https://connect.facebook.net/en_US/fbevents.js');

		fbq('init', '1780734305496646');
		fbq('track', "PageView");
	</script>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1780734305496646&ev=PageView&noscript=1"/></noscript>
<!-- End Facebook Pixel Code -->

<?php wp_footer();?>

<!-- Twitter embed platform code -->
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<!-- /Twitter -->

</body>
</html>
