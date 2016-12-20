<?php

/*
Template Name: Full Width
*/

	if(isset($_POST['email_address']) && !empty($_POST['email_address'])){
		$path = __DIR__.'/../../../../collection/free-weekend.csv';
		$link = fopen($path, 'a');
		fwrite($link, "\n" . date('Y-m-d') . 'T' . date('H:i:s') . ',' . $_POST['email_address']);
		fclose($link);
		header('location: /free-weekend?thanks');
	}

	$completed = false;
	if(isset($_GET['thanks'])){
		$completed = true;
	}


	add_action('wp_head','free_weekend_css');
	function free_weekend_css() {
		echo '<link href="' . k2_get_static_url('v2') . '/css/animate.css?ver=' . CACHE_UPDATE . '" rel="stylesheet" type="text/css" />';
	}

	add_action('wp_footer','free_weekend_js');
	function free_weekend_js() {
		echo '<script src="' . k2_get_static_url('v2') . '/js/free-weekend.js?ver=' . CACHE_UPDATE . '"></script>';
	}

	get_header(); ?>

<style>

</style>

<?php
if($completed){
?>

<div class="col-xs-12 free-weekend-header-heading">
	<p>Thank you for entering your email address. We'll send you a friendly reminder before your free access weekend starts.</p>
</div>

<?php
}
else{
?>
	<div class="col-xs-12 free-weekend-header">
		<div class="bs-row">
			<div class="col-xs-12">
				<div class="bs-row">
					<div class="col-xs-12 free-weekend-header-heading">
						<p>WELCOME TO KIM'S CLUB <strong>FREE SHOW WEEKEND</strong></p>
					</div>
				</div>
				<div class="bs-row">
					<div class="col-lg-6 col-xs-12 free-weekend-header-left-box">
						<img class="free-weekend-header-video-snapshot" src="<?php echo k2_get_static_url('v2') . '/img/kim-show-tablet.png'; ?>" title="FREE Show Weekend">
					</div>
					<div class="col-lg-6 col-xs-12 free-weekend-header-right-box">
						<h2 class="free-weekend-header-cd-heading" style="font-size: 450%; line-height: 1.1em; margin: 0.1em auto;"><strong>COMING<br/>SOON</strong></h2>
<!--
						<p class="free-weekend-header-cd-heading"><strong>COUNTDOWN TO YOUR FREE ACCESS WEEKEND:</strong></p>
						<div class="bs-row bs-centered free-weekend-header-cd-margin-left">
							<div class="col-xs-2"><p class="free-weekend-header-cd-number" id="free_weekend_countdown_days"><span>0</span></p></div>
							<div class="col-xs-1 free-weekend-header-colon">:</div>
							<div class="col-xs-2"><p class="free-weekend-header-cd-number" id="free_weekend_countdown_hours"><span>0</span></p></div>
							<div class="col-xs-1 free-weekend-header-colon">:</div>
							<div class="col-xs-2"><p class="free-weekend-header-cd-number" id="free_weekend_countdown_mins"><span>0</span></p></div>
							<div class="col-xs-1 free-weekend-header-colon">:</div>
							<div class="col-xs-2"><p class="free-weekend-header-cd-number" id="free_weekend_countdown_secs"><span>0</span></p></div>
						</div>
						<div class="bs-row bs-centered free-weekend-header-cd-margin-left free-weekend-header-cd-text">
							<div class="col-xs-2">Days</div>
							<div class="col-xs-1">&nbsp;</div>
							<div class="col-xs-2">Hours</div>
							<div class="col-xs-1">&nbsp;</div>
							<div class="col-xs-2">Minutes</div>
							<div class="col-xs-1">&nbsp;</div>
							<div class="col-xs-2">Seconds</div>
						</div>
-->
						<p class="free-weekend-email-heading">Enter your email address below and I'll send you a friendly reminder before
                            your free access weekend starts.</p>
						<form action="/free-weekend" method="post">
							<div class="bs-row">
								<div id="free_weekend_error" class="hidden-xs">Something isn't right with the email address you entered, please try again.</div>
								<div class="col-md-8 col-xs-10"><input class="free-weekend-email-input" placeholder="enter your email" type="email" name="email_address" title="Email Address"></div>
								<div class="col-md-4 col-xs-12"><button class="free-weekend-email-button">SEE YOU SOON!</button></div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="bs-row free-weekend-thank-you-note">
		<div class="col-xs-12"><p><strong>You're in for a treat!.</strong> For one weekend only, you can download my national radio show
                and listen to my podcast for FREE, whenever and wherever you want. You can watch the TV show, too! Each jam-packed hour
                is loaded with at least 14 different tips, security alerts, updates, insider secrets and the best callers! Listen to and
                watch original and exclusive content of the show podcasts and webcasts. All free, all weekend long!
                See you then!</p>
        </div>
	</div>
	<div class="bs-row free-weekend-learn-more">
		<div class="col-xs-12">
			<div class="bs-row">
				<div class="col-md-6 col-xs-12 left">
					<p class="free-weekend-learn-more-heading">Learn more about the<br><strong>Kim Komando Show</strong></p>
					<p>Every weekend I help listeners across the country and around the world understand and expand their digital
                        lifestyles.</p>
					<a href="//www.komando.com/the-show">
						<button class="learn-more-button">Learn more about the show</button>
					</a>
				</div>
				<div class="col-md-6 col-xs-12">
					<iframe class="free-weekend-learn-more-video" id="showVideo" src="//www.youtube.com/embed/i8WZI1fmwko" frameborder="0"
                             allowfullscreen></iframe>
				</div>
			</div>
		</div>
	</div>
<?php /*
	<div class="bs-row free-weekend-begins">
		<div class="col-xs-12">
			<div class="bs-row">
				<div class="col-md-5 col-xs-12 left">
					<p class="free-weekend-begins-left"><img src="<?php echo k2_get_static_url('v2') . '/img/circle-check.png'; ?>" title="FREE Show Weekend"></p>
				</div>
				<div class="col-md-7 col-xs-12">
					<div class="free-weekend-begins-right">
						<p class="heading">Your Kim's Club <strong>FREE Show Weekend</strong> will begin on September 16.</p>
						<p>Until then, visit <a href="//www.komando.com" title="Komando.com">Komando.com</a> to read the latest tech news and tips, videos and much more.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
*/ ?>
	<div class="bs-row free-weekend-get-access">
		<div class="col-xs-12">
			<div class="bs-row">
				<div class="col-md-7 col-xs-12 left">
					<p class="free-weekend-get-access-heading">Don't want to wait? Get access to Kim's Club now with <strong>my FREE 15-day trial</strong></p>
					<a href="//club.komando.com/" title="Kim's Club">
						<button class="learn-more-button">Start Your Free Trial</button>
					</a>
				</div>
				<div class="col-md-5 col-xs-12">
					<p class="free-weekend-get-access-key"><img src="<?php echo k2_get_static_url('v2') . '/img/unlock-key.png'; ?>" title="Unlock Kim's Club"></p>
				</div>
			</div>
		</div>
	</div>
	<div class="bs-row free-weekend-latest">
		<div class="col-xs-12">
			<div class="bs-row">
				<div class="col-md-5 col-xs-12 left">
					<p class="free-weekend-latest-left"><img src="<?php echo k2_get_static_url('v2') . '/img/newsletter-icon.png'; ?>" title="FREE Newsletter"></p>
				</div>
				<div class="col-md-7 col-xs-12">
					<div class="free-weekend-latest-right">
						<p class="heading">Get the latest tech news, security alerts and more by <strong><a href="//club.komando.com/newsletters/subscribe" title="Free Newsletter">signing up for my FREE newsletter</a></strong></p>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php get_footer(); ?>

<script type="text/javascript">

    window.onresize = resizeVideo;
    var $videoWidth = 0;
    var $videoHeight = 0;

    function resizeVideo() {
        $videoWidth = $( showVideo ).width();
        $videoHeight = ($videoWidth * 0.5625);
        $( showVideo ).height($videoHeight);
    }

</script>
