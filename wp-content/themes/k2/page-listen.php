<?php 

/*
Template Name: Listen
*/

// finds the last URL segment
$url = strtok($_SERVER['REQUEST_URI'], '?');
$login_boomerang = '/wp-login.php?redirect_to=' . urlencode(site_url($url));
$parse_url = parse_url($url, PHP_URL_PATH);
$url_segments = explode('/', $parse_url);

if(!(current_user_can('premium_member') || current_user_can('basic_member'))) {

	initializeCas();

	if(!is_user_logged_in() && $_GET['auth'] != 'checked') {
		status_header(401);
		$redirect = $login_boomerang . '&auth=check';
		header("Location: $redirect");
	}
}

get_header(); ?>
	
<div class="content-left">
	<?php if (have_posts()) : while (have_posts()) : the_post(); 

		if(!(current_user_can('premium_member') || current_user_can('basic_member'))) { ?>
						
			<div class="listen-page-login">
				<div class="listen-page-login-inner">
				<?php if(current_user_can('subscriber')) { ?>
					<h1>Join Kim's Club to Listen Now!</h1>
					<p>To listen to the latest show you need to upgrade you Kim's Club membership.</p>

					<a href="<?php echo CLUB_BASE_URI; ?>/products?r=<?php echo urlencode(site_url($url)); ?>" class="btn btn-xwide btn-white btn-listen listen-join-btn-white"><span>+</span> Upgrade Kim's Club</a>
				<?php } else { ?>
					<h1>Join Kim's Club to Listen Now!</h1>
					<p>Kim's Club members can watch or listen to the Kim Komando Show anytime.<br />If you're a member, click below to sign in.<br />Want to sign up? Click on the <strong>Join Kim's Club</strong> button below.</p>

					<a href="<?php echo CLUB_BASE_URI; ?>/products?r=<?php echo urlencode(site_url($url)); ?>" class="btn btn-xwide btn-white btn-listen listen-join-btn-white"><span>+</span> Join Kim's Club</a>
					<a href="<?php echo site_url($login_boomerang); ?>" class="btn btn-xwide btn-blue btn-listen listen-signin-btn">Sign In</a>
				<?php } ?>
				</div>
			</div>

			<p>A few more reasons to join Kim's Club:</p>

			<div class="join-table-listen">

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell basic premium">
						<div class="header-cell">
							+ Instant Access to Shows and Podcasts - on Your Schedule <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell">
							Watch or listen to Kim's shows, when and where you want, on just about any device. Or download the podcasts and take them with you.
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell basic premium">
						<div class="header-cell">
							+ Automatic Entry in My Contests <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell">
							We give away everything from tablets to vacations. Members are entered into our contests automatically, every day, without doing a thing!
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell basic premium">
						<div class="header-cell">
							+ Free Downloads, How-To Guides, Buying Recommendations and More <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell">
							Be in the know the easy way with all of my unbiased advice and guides for members only!
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell basic premium">
						<div class="header-cell">
							+ Answers on the Members-Only Forums <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell">
							Post questions, get answers and make friends among my fun and active community.
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell basic premium">
						<div class="header-cell">
							+ Crystal-Clear Audio and HD Video <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell clearfix" style="display:block;">
							<iframe class="club-products-sizzle-video" width="300" height="208" src="//www.youtube.com/embed/i6MSDrHrLLg?showinfo=0&modestbranding=1&rel=0&autohide=1&vq=hd1080" frameborder="0" allowfullscreen></iframe>No static and no commercials! Members get the highest-quality audio and video and no long commercial breaks.
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell premium">
						<div class="header-cell">
							+ The Live Production - on Your Tablet, Computer, Phone or TV <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell">
							We film every Kim Komando Show with eight HD cameras - it's a blast to watch! Members of Kim's Club can see as well as hear the action - and when the world goes to commercials, members don't! During the breaks, when Kim and her crew chat and horse around, you're in on the action.
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell premium">
						<div class="header-cell">
							+ Chat during the Live Production <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell">
							Become a Premium Member and you will enjoy the banter of others watching the show while you do. Be sure to join in the conversation! It's fun!
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell premium">
						<div class="header-cell">
							+ Priority Email to Speak with Kim <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell">
							Your email will be Kim's priority! Kim will review emails and use the questions from Kim's Club members to develop segments and information in newsletters. When a Kim's Club member email question is answered by Kim on the air, we'll recognize the member by calling out their first name and city.
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell premium">
						<div class="header-cell">
							+ Exclusive Discounts <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell">
							Get instant special savings in Kim's Shop and more! We extend discounts to Kim's Club Premium members only from time to time!
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell premium">
						<div class="header-cell">
							+ Free E-Guides from Kim Komando <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell">
							More than $100 in free E-Guides published by me, Kim Komando, are available for you to download and use instantly. Get all of the latest tech guides from my shop, free of charge!
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell premium">
						<div class="header-cell">
							+ Invitation to VIP-Only Events to Meet Kim in Person <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell">
							Only Kim's Club members receive special tickets to come see the show when we open our studios or hold events throughout the year! <em>Subject to availability.</em>
						</div>
					</div>
				</div>

			</div>

			<a href="<?php echo CLUB_BASE_URI; ?>/products?r=<?php echo urlencode(site_url($url)); ?>" class="btn btn-xwide btn-blue btn-listen listen-join-btn">Join Now</a>

		<?php } else { ?>

			<div class="listen-page-players">
				<h1>Listen Now</h1>
				<div class="listen-page-need-help"><a href="#download-help">Need help<span class="hide-mobile"> downloading</span>?</a></div>

				<div class="listen-page-player-container">
					<div class="listen-page-player-wrapper">
						<?php if(function_exists('k2_podcast_listen_latest')) { $podcast = k2_podcast_listen_latest(); ?>
						<header class="listen-page-player-header">
							<div class="listen-page-player-icon"><i class="fa fa-microphone"></i></div>
							<div class="listen-page-player-text-wrapper">
								<div class="listen-page-player-text-show">The Kim Komando Show</div>
								<div class="listen-page-player-text-title"><?php echo $podcast['title']; ?></div>
							</div>
						</header>
						<div class="listen-page-player-audio"><?php echo $podcast['player']; ?></div>
						<div class="listen-page-player-description"><p><?php echo $podcast['description']; ?></p></div>
						<div class="listen-page-player-download"><a href="<?php echo $podcast['url']; ?>" class="btn btn-blue podcast-download-btn"><span><i class="fa fa-download"></i></span><span>Download this Episode</span></a></div>
						<div class="listen-page-player-showtime">New Show posted weekly at 7:00 pm Sundays, Pacific Time</div>
						<?php } ?>
					</div>

					<div class="listen-page-player-wrapper">
						<?php if(function_exists('k2_digital_minute_listen_latest')) { $podcast = k2_digital_minute_listen_latest(); ?>
						<header class="listen-page-player-header">
							<div class="listen-page-player-icon"><span class="icon-k2-stopwatch"></span></div>
							<div class="listen-page-player-text-wrapper">
								<div class="listen-page-player-text-show">The Kim Komando Digital Minute</div>
								<div class="listen-page-player-text-title"><?php echo $podcast['title']; ?></div>
							</div>
						</header>
						<div class="listen-page-player-audio"><?php echo $podcast['player']; ?></div>
						<div class="listen-page-player-description"><p><?php echo $podcast['description']; ?></p></div>
						<div class="listen-page-player-download"><a href="<?php echo $podcast['url']; ?>" class="btn btn-blue podcast-download-btn"><span><i class="fa fa-download"></i></span><span>Download this Digital Minute</span></a></div>
						<div class="listen-page-player-showtime">New Digital Minute posted daily at 1:00 pm, Pacific Time</div>
						<?php } ?>
					</div>
				</div>
			</div>

			<div class="listen-page-tabs">
				<ul class="clearfix">
					<li><a href="#" class="listen-page-show filter-active">Previous Shows</a></li>
					<li><a href="#" class="listen-page-minute">Previous Digital Minutes</a></li>
				</ul>
			</div>

			<?php if(function_exists('k2_digital_minutes')) { echo k2_digital_minutes(15); } ?>
			<?php if(function_exists('k2_podcasts')) { echo k2_podcasts(15); } ?>
					
			<a href="<?php echo site_url(); ?>/listen/podcast">Click here</a> to get the Kim Komando Show or Digital Minutes on podcast.

			<div class="listen-page-help-wrapper">
				<a id="download-help"></a><div class="listen-page-help-title">Need help downloading the episodes? <i class="fa fa-caret-down"></i></div>
				<div class="listen-page-help-content" class="hide-mobile"><?php the_content(); ?></div>
			</div>

	<?php } endwhile; endif; ?>
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>