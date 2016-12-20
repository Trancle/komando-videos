<?php 

/*
Template Name: Exclusive Discounts
*/

// finds the last URL segment
$url = strtok($_SERVER['REQUEST_URI'], '?');
$login_boomerang = '/wp-login.php?redirect_to=' . urlencode(site_url($url));

initializeCas();

if(!is_user_logged_in()) {
	status_header(401);
	header("Location: $login_boomerang");
}

get_header(); ?>
	
	<section class="content-full eguide-downloads clearfix" role="main">
		
	<?php if(current_user_can('premium_member')) { ?>

		<div class="kims-club-dashboard premium">
			<header class="kcd-header">
				<div class="kcd-left">
					<div class="kcd-logo"><img src="<?php echo k2_get_static_url('v2'); ?>/img/kims-club-logo.png" alt="[LOGO] Kim's Club"> <div class="kcd-badge premium">Premium Member</div></div>
					<div class="kcd-welcome">Exclusive Discounts</div>
				</div>
			</header>
		
			As a Kim's Club Premium member, you have access to exclusive discounts so check back often for updates. -Kim

		</div>

		<div class="discounts">

			<?php if (have_posts()): while (have_posts()) : the_post(); the_content(); endwhile; endif; ?>
			
		</div>

	<?php } elseif(current_user_can('basic_member')) { ?>

		<div class="kims-club-dashboard premium">
			<header class="kcd-header">
				<div class="kcd-left">
					<div class="kcd-logo"><img src="<?php echo k2_get_static_url('v2'); ?>/img/kims-club-logo.png" alt="[LOGO] Kim's Club"> <div class="kcd-badge basic">Basic Member</div></div>
					<div class="kcd-welcome">Exclusive Discounts</div>
				</div>
			</header>
		
			You must be a premium member to access Exclusive Discounts. 

		</div>

		<div class="kims-club-dashboard-upgrade">
			<h3><div class="kcd-badge basic">Basic Member</div> Upgrade your membership now and receive:</h3>
			<ul class="blue-arrow">
				<li>Watch the show Live!</li>
				<li>Exclusive Discounts</li>
				<li>Live chat during the Kim Komando Show on the <a href="<?php echo VIDEOS_BASE_URI; ?>/live-from-the-studio">Watch the Show</a> page</li>
				<li>Priority Email to ask Kim your tech questions</li>
			</ul>
			<a href="<?php echo CLUB_BASE_URI; ?>/account/membership/upgrade" class="kcdu-button">Upgrade Now</a>
		</div>

	<?php } else { ?>

		<div class="kims-club-dashboard free">
			<header class="kcd-header">
				<div class="kcd-left">
					<div class="kcd-welcome">Exclusive Discounts</div>
				</div>
			</header>
		
			You must be a premium member to access Exclusive Discounts. 

		</div>

		<div class="kims-club-dashboard-upgrade">
			<h3>Join Kim's Club to get these great membership benefits:</h3>
			<ul class="blue-arrow">
				<li>Watch the show live - on Your Tablet, Computer, Phone or TV</li>
				<li>Instant Access to Shows and Podcasts - on Your Schedule</li>
				<li>Automatic Entry in Our Contests</li>
				<li>Free Downloads, How-To Guides, Buying Recommendations</li>
				<li>Answers on the Members-Only Message Board</li>
				<li>Exclusive Discounts</li>
				<li>Crystal clear audio and video in HD Quality</li>
				<li>Live chat during the Kim Komando Show</li>
				<li>Priority Email to ask Kim your tech questions</li>
			</ul>
			<a href="<?php echo CLUB_BASE_URI; ?>/account/membership/upgrade" class="kcdu-button">Upgrade Now</a>
		</div>

	<?php } ?>

	</section>
	
<?php get_footer(); ?>