<?php 

/*
Template Name: Meet Kim
*/

// finds the last URL segment  
$urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$segments = explode('/', $urlArray);
$numSegments = count($segments); 
$currentSegment = $segments[$numSegments - 1];

get_header(); ?>

<script>
googletag.cmd.push(function() {
googletag.defineSlot('/1064811/trip.komando.com-2013-05-left-column', [160, 600], 'meet-kim-left-ad').addService(googletag.pubads());
googletag.defineSlot('/1064811/trip.komando.com-2013-05-right-column', [160, 600], 'meet-kim-right-ad').addService(googletag.pubads());
googletag.pubads().enableSingleRequest();
googletag.enableServices();
});
</script>
	
<div class="meet-kim-contest-wrapper clearfix">

	<div class="meet-kim-left-ad">
		<span>- Advertisement -</span>
		<div id="meet-kim-left-ad"></div>
		<script type='text/javascript'>googletag.cmd.push(function() { googletag.display('meet-kim-left-ad'); });</script>
	</div>

	<div class="meet-kim-contest clearfix" role="main">
		<div class="meet-kim-header">

			<div class="meet-kim-share">
				<span class="st_sharethis" displayText="ShareThis"></span>
				<span class="st_facebook" displayText="Facebook"></span>
				<span class="st_twitter" displayText="Tweet"></span>
				<span class="st_pinterest" displayText="Pinterest"></span>
				<span class="st_email" displayText="Email"></span>
			</div>

			<div class="meet-kim-profile"><img src="<?php echo k2_get_static_url('v2'); ?>/img/meet-kim/meet-kim-profile.png" alt="Kim" /></div>

			<nav class="meet-kim-nav">
				<ul>
					<li><a href="<?php echo site_url(); ?>/contests/meet-kim">Home</a></li>
					<li><a href="<?php echo site_url(); ?>/contests/meet-kim/prizes">Prizes</a></li>
					<li><a href="<?php echo site_url(); ?>/contests/meet-kim/winners">Winners</a></li>
					<li><a href="<?php echo site_url(); ?>/contests/meet-kim/rules">Rules</a></li>
				</ul>
			</nav>
		</div>

		<div class="meet-kim-content">
			<?php 
				if (have_posts()): while (have_posts()) : the_post();
					the_content();		
				endwhile;
				else: 
			?>
				<h2>Sorry, nothing to display.</h2>

			<?php endif; ?>

			<?php if(is_page('meet-kim')) { ?>
			<div class="meet-kim-club-header"><img src="<?php echo k2_get_static_url('v2'); ?>/img/meet-kim/meet-kim-kims-club.jpg" alt="Kim's Club" /></div>
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
							+ Automatic Entry in Our Contests <i class="fa fa-caret-down"></i>
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
							Be in the know the easy way with all of Kim's unbiased advice and guides for members only!
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell basic premium">
						<div class="header-cell">
							+ Answers on the Members-Only Message Board <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell">
							Post questions, get answers and make friends among Kim's fun and active community.
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell basic premium">
						<div class="header-cell">
							+ Crystal-Clear Audio and HD Video <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell clearfix" style="display:block;">
							<iframe class="club-products-sizzle-video" width="300" height="208" src="//www.youtube.com/embed/ie1aqPyumnk?showinfo=0&modestbranding=1&rel=0&autohide=1&vq=hd1080" frameborder="0" allowfullscreen></iframe>No static and no commercials! Members get the highest-quality audio and video along with no long commercial breaks.
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell premium">
						<div class="header-cell">
							+ The Live Show - on Your Tablet, Computer, Phone or TV <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell">
							We film every Kim Komando Show with eight HD cameras - it's a blast to watch! Members of Kim's Club can see as well as hear the action - and when the world goes to commercials, members don't! During the breaks, when Kim and her crew chat and horse around, you're in on the action.
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell premium">
						<div class="header-cell">
							+ Chat during the Live Show <i class="fa fa-caret-down"></i>
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
							+ Exclusive Discounts at Kim's Store <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell">
							Get instant special savings in Kim's Store. We extend discounts to Kim's Club members only from time to time!
						</div>
					</div>
				</div>

				<div class="join-table-listen-row">
					<div class="join-table-listen-cell premium">
						<div class="header-cell">
							+ Invitation to VIP-Only Events to Meet Kim in Person <i class="fa fa-caret-down"></i>
						</div>

						<div class="description-cell">
							Only Kim's Club members receive special tickets to come see the show when we open our studios or hold events throughout the year!
						</div>
					</div>
				</div>

			</div>

			<?php } ?>
		
		</div>

	</div>

	<div class="meet-kim-right-ad">
		<span>- Advertisement -</span>
		<div id="meet-kim-right-ad"></div>
		<script type='text/javascript'>googletag.cmd.push(function() { googletag.display('meet-kim-right-ad'); });</script>
	</div>

</div>

<?php get_footer(); ?>