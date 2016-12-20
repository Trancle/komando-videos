<?php get_header(); ?>
	
	<?php if(function_exists('k2_on_air')) { k2_on_air(); } ?>

	<?php if(function_exists('k2_trending_pages')) { k2_trending_pages(); } ?>

		<section class="home-feature clearfix">

			<section class="kims-desk">
				<h1 class="arrow">From Kim's Desk</h1>
				<div class="grid clearfix">
					<?php if(class_exists('Kom_Homepage')) { Kom_Homepage::show_featured_links(); } ?>
				</div>
			</section>

			<section class="the-show">

				<div class="the-show-kim"><img src="<?php echo k2_get_static_url('v2'); ?>/img/kim-home-retina.png" alt="Kim Komando" /></div>

				<h1 class="arrow">The Kim Komando Show</h1>
				<ul class="the-show-menu">
					<li><a href="<?php bloginfo('url') ?>/show-picks"><span class="the-show-icon icon-k2-trophy"></span> Show picks</a></li>
					<li><a href="<?php echo VIDEOS_BASE_URI; ?>/live-from-the-studio"><span class="the-show-icon icon-k2-play"></span> Watch the show</a></li>
					<li><a href="<?php bloginfo('url') ?>/listen"><span class="the-show-icon icon-k2-volume"></span> Listen now</a></li>
					<li><a href="<?php bloginfo('url') ?>/listen"><span class="the-show-icon icon-k2-headphones"></span> Latest podcast</a></li>
					<li><a href="<?php bloginfo('url') ?>/listen"><span class="the-show-icon icon-k2-stopwatch"></span> The Digital Minute&trade;</a></li>
					<li><a href="<?php echo STATION_FINDER_BASE_URI; ?>"><span class="the-show-icon icon-k2-radio"></span> Find your station</a></li>
				</ul>

				<div class="the-show-social">
					<span>Connect with Kim:</span>
					<div class="social-icon facebook"><a href="http://www.facebook.com/kimkomando" target="_blank"><img src="<?php echo k2_get_static_url('v2'); ?>/img/facebook-icon.png" alt="Facebook" width="45" /></a></div>&nbsp;
					<div class="social-icon twitter"><a href="http://www.twitter.com/kimkomando" target="_blank"><img src="<?php echo k2_get_static_url('v2'); ?>/img/twitter-icon.png" alt="Twitter" width="45" /></a></div>&nbsp;
					<div class="social-icon google"><a href="https://plus.google.com/u/0/118019228588479629836?rel=author" target="_blank"><img src="<?php echo k2_get_static_url('v2'); ?>/img/google-icon.png" alt="Google+" width="45" /></a></div>&nbsp;
					<div class="social-icon youtube"><a href="http://www.youtube.com/kimkomando" target="_blank"><img src="<?php echo k2_get_static_url('v2'); ?>/img/youtube-icon.png" alt="YouTube" width="45" /></a></div>&nbsp;
					<div class="social-icon youtube"><a href="http://www.pinterest.com/kimkomando" target="_blank"><img src="<?php echo k2_get_static_url('v2'); ?>/img/pinterest-icon.png" alt="Pinterest" width="45" /></a></div>
				</div>

<!--				<div class="newsletter-subscribe newsletter_subscribe_variant"></div> Removed 2016-10-20 -->
				<?php
					/**
					 * NOTE: This also appears on the search page and the Station Finder
					 */
				?>
                <div class="newsletter-sign-up-ad clearfix">
                    <?php // Display a newsletter subscription ad above the center-column ads #3552 ?>
                    <div class="newsletter-subscribe-box-<?php echo ((date('s') % 3) + 1) ?>" style="margin:auto;">
                        <form class="newsletter-subscribe-form">
                            <input class="newsletter-subscribe-email" type="text" placeholder="ENTER EMAIL HERE..." name="email_entry_field">
                            <button class="email-entry-button" type="submit">SIGN ME UP!</button>
                        </form>
                        <div class="newsletter-subscribe-response"></div>
                        <div class="newsletter-subscribe-spinner">
                            <img src="//static.komando.com/websites/common/v2/img/mini-spinner.gif" alt="Loading" title="Loading"/>
                        </div>
                    </div>
                </div>

				<div class="ad square-ad home-ad clearfix">
					<div id="ad-rectangle-featured-1" style="min-width:300px; min-height:50px; margin:auto;">
						<script type='text/javascript'>
						googletag.cmd.push(function() { googletag.display('ad-rectangle-featured-1'); });
						</script>
					</div>
				</div>

                <div class="ad square-ad home-ad clearfix">
					<div id="ad-rectangle-featured-2" style="min-width:300px; min-height:50px; margin:auto;">
						<script type='text/javascript'>
						googletag.cmd.push(function() { googletag.display('ad-rectangle-featured-2'); });
						</script>
					</div>
				</div>

		</section>

		<section class="tech-twitter clearfix">

			<h1 class="arrow">Breaking News</h1>

			<div class="tech-twitter-tabs hide-tablet">
				<ul class="clearfix">
					<li><a href="javascript:void(0)" class="news-toggle filter-active">Tech News</a></li>
					<li><a href="javascript:void(0)" class="twitter-toggle"><i class="fa fa-twitter"></i> @KimKomando</a></li>
				</ul>
			</div>

			<div class="news-wrapper">
				<div class="tech-twitter-section-header hide-mobile hide-desktop"><span>Tech News</span></div>
				<ul>
					<?php if(function_exists('home_news')) { home_news(); } ?>
				</ul>
				<div class="news-view-more"><a href="<?php echo NEWS_BASE_URI; ?>" onClick="ga('send', 'event', 'Homepage', 'Click', 'Click through to view more news');">View more news <i class="fa fa-chevron-right"></i></a></div>
			</div>

			<div class="tweets-wrapper hide-mobile hide-desktop">
				<div class="tech-twitter-section-header hide-mobile hide-desktop"><span><i class="fa fa-twitter"></i> @KimKomando</span></div>
				<?php if(function_exists('home_twitter_profile')) { home_twitter_profile(); } ?>
				<ul>
					<?php if(function_exists('home_twitter')) { home_twitter(); } ?>
				</ul>
				<div class="news-view-more"><a href="https://twitter.com/kimkomando" onClick="ga('send', 'event', 'Homepage', 'Click', 'Click through to view more tweets');" target="_blank">View more Tweets <i class="fa fa-chevron-right"></i></a></div>
			</div>

			<div class="home-shop-ad hide-mobile hide-tablet clearfix">
				<div id="ad-home-shop-1">
					<script type='text/javascript'>
					googletag.cmd.push(function() { googletag.display('ad-home-shop-1'); });
					</script>
				</div>
			</div>

		</section>

	</section>

	<section class="home-grid">

		<div class="grid-filter clearfix">
			<span class="hide-desktop"><span>Latest Everything</span><i class="fa fa-chevron-down hide-desktop"></i></span>
			<ul class="hide-mobile hide-tablet">
				<li><a href="javascript:void(0)" class="filter-everything filter-active" data-post-type="everything">Latest Everything</i></a></li>
				<li><a href="<?php echo VIDEOS_BASE_URI; ?>" class="filter-videos" data-post-type="videos">Videos</a></li>
				<li><a href="<?php bloginfo('url'); ?>/downloads" class="filter-downloads" data-post-type="downloads">Downloads</a></li>
				<li><a href="<?php bloginfo('url'); ?>/apps" class="filter-apps" data-post-type="apps">Apps</a></li>
				<li><a href="<?php bloginfo('url'); ?>/columns" class="filter-columns" data-post-type="columns">Columns</a></li>
				<li><a href="<?php bloginfo('url'); ?>/cool-sites" class="filter-cool-sites" data-post-type="cool_sites">Cool Sites</a></li>
				<li><a href="<?php bloginfo('url'); ?>/tips" class="filter-tips" data-post-type="tips">Tips</a></li>
				<li><a href="<?php echo NEWS_BASE_URI; ?>" class="filter-news" data-post-type="news">News</a></li>
			</ul>
		</div>

		<div class="grid-subscribe hide-mobile">
			<a href="javascript:void(0)" onClick="ga('send', 'event', 'Homepage', 'Click', 'Click through to subscribe modal');" data-modal="subscribe-modal"><span>Get the latest in your inbox <i class="fa fa-chevron-right"></i></span></a>
		</div>

		<div class="grid clearfix">
			<?php if(class_exists('Kom_Homepage')) { Kom_Homepage::show_grid_links(); } ?>
		</div>
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			var i = 1;
			$('.load-more').on('click', function() {

				i++;
				$('.grid-rest').hide();
				$('.grid-loading').show();

				var ajaxurl = "<?php echo get_template_directory_uri() . '/k2-ajax.php'; ?>";
				$.ajax({
					url: ajaxurl,
					data: {action: 'kom_homepage_grid', page: i},
					success: function(data, textStatus) {
						$('.grid-loading').hide();
						$('.grid-rest').show();
						if(i > 3) {
							$('.grid-load-more').remove();
						}

						$('.home-grid .grid').append(data);

						$('.home-grid .grid img').unveil();
						$(window).trigger('resize');
                        if (window.twttr != null) { twttr.widgets.load(); }
                        if (window.stButtons != null) { stButtons.locateElements(); }
						$('img').trigger('unveil');

						$('.grid .grid-item').on({
							mouseenter: function() {
								$(this).find('.grid-item-share').hide();
								$(this).find('.grid-item-share-icons').fadeIn('fast');
							},
							mouseleave: function() {
								$(this).find('.grid-item-share-icons').hide();
								$(this).find('.grid-item-share').fadeIn('fast');
							}
						});
                        if ('ontouchstart' in window || 'onmsgesturechange' in window) {
                            $('.grid .grid-item').off('mouseenter mouseleave');
                        }
					}
				});

				ga('send', 'event', 'Homepage', 'Click', 'Load Page: ' + i);

			});

		});
		</script>

		<div class="grid-load-more">
			<div class="grid-rest load-more"><i class="fa fa-plus-circle"></i> Load more content</div>
			<div class="grid-loading"><i class="fa fa-spinner fa-spin"></i> Loading...</div>
		</div>

	</section>

    <!-- ------------------------ Newsletter Subscribe Popup (triggered by JavaScript below)
    This appears in a shadowbox on Home page after specified number of seconds, and is limited
    to being shown a maximum of once every specified number of days. #3284 (revised #3469) -->
	<div class="subscribe-popup" id="popupBoxBrainAd">
		<div class="newsletter-subscribe-homepage-pop-up">
			<div class="nshpu-tag-line"><strong>FREE</strong> Newsletters <div class="nshpu-close close-x">X</div></div>
			<div class="nshpu-right-container">
				<div class="nshpu-headline">Latest Tech Trends Delivered To Your Inbox</div>
				<div class="nshpu-sub-headline">Tips, Security Alerts & more...</div>
				<div class="nshpu-form-container">
					<form class="nshpu-form newsletter-subscribe-form">
						<div class="nshpu-email-input-container">
							<input class="nshpu-subscribe-email-input newsletter-subscribe-email" type="text" placeholder="Enter your email here..." name="email_entry_field">
						</div>
						<div class="nshpu-button-opt-out-container">
							<button class="nshpu-email-entry-button email-entry-button" type="submit">SIGN ME UP!</button>
							<input title="Opt Out" class="nshpu-opt-out-box opt-out-box" type="checkbox" value="true">
							<div class="nshpu-opt-out-text">No, I don't want to stay up-to-date.</div>
						</div>
					</form>
					<div class="newsletter-subscribe-response"></div>
					<div class="newsletter-subscribe-spinner">
						<img src="//static.komando.com/websites/common/v2/img/mini-spinner.gif" alt="Loading" title="Loading"/>
					</div>
				</div>
			</div>
		</div>
	</div>
		<?php /*
    <div class="subscribe-popup" id="popupBoxBrainAd">
        <div class="newsletter-subscribe-box">
            <div class="top-attention-text"><b>FREE</b> Newsletters</div><div class="close-x">X</div><br>
            <div id="newsletter-signup-headline-small">Latest Tech Trends<br>Delivered To<br>Your Inbox</div>
            <div id="newsletter-signup-headline-large">Latest Tech Trends<br>Delivered To Your Inbox</div>
            <div id="newsletter-signup-subhead">Tips, Security Alerts & more...</div>
            <form class="newsletter-subscribe-form">
                <input class="newsletter-subscribe-email" type="text" placeholder="Enter your email here..." name="email_entry_field">
                <button class="email-entry-button" type="submit">SIGN ME UP!</button>
                <input class="opt-out-box" type="checkbox" value="true"><div class="opt-out-text">No, I don't want to
                    <br>stay up-to-date.</div>
            </form>
            <div class="newsletter-subscribe-response"></div>

            <div class="newsletter-subscribe-spinner">
                <img src="//static.komando.com/websites/common/v2/img/mini-spinner.gif" alt="Loading" title="Loading"/>
            </div>
        </div>
    </div>
 		*/
		?>

<script type="text/javascript">
    // Pops-up a newsletter subscription sign-up in a shadowbox
    $(document).ready(function() {
        // Change the parameter to be the number of seconds delay desired.
        var secs_delay = 5;
        subscribeTimer(secs_delay);
    });

</script>

<?php get_footer(); ?>
