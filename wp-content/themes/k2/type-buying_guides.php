<?php
$k2ptype = get_post_type_object('buying_guides');
$k2ptypename = $k2ptype->label;
$k2ptype = substr($k2ptype->rewrite['slug'], 0, -10);
$k2url = $k2ptype.'/';

if (http_response_code() == 404) {
	get_template_part('404-redirect');
} else {

get_header(); ?>

<section class="section-feature">

	<section class="section-feature-posts">
		<h1 class="arrow">Featured Buying Guides</h1>
		<div class="grid clearfix">
			<?php section_featured('buying_guides'); ?>
		</div>
	</section>

	<section class="section-nav-meta clearfix">
		<nav class="section-categories">
			<h1 class="arrow">Buying Guides Categories</h1>
			<ul clas="category-nav">
			<?php 
			$categories = get_terms('buying_guides_categories', array(
				'orderby' => 'name',
				'hide_empty' => 1
			));

			foreach ($categories as $category) {
				echo '<li><a href="' . get_term_link($category) . '">' . $category->name . '</a></li>';
			}
			?>
			</ul>
		</nav>

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

</section>

<section class="section-grid">

	<div class="grid-filter clearfix">
		<span class="hide-desktop"><span>Latest Buying Guides</span><i class="fa fa-chevron-down hide-desktop"></i></span>
		<ul class="hide-mobile hide-tablet">
			<li><a href="<?php bloginfo('url'); ?>" class="filter-everything" data-post-type="everything">Latest Everything</i></a></li>
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
		<a href="javascript:void(0)" onClick="ga('send', 'event', 'Buying Guides', 'Click', 'Click through to subscribe modal');" data-modal="subscribe-modal"><span>Get the latest in your inbox <i class="fa fa-chevron-right"></i></span></a>
	</div>

	<div class="grid clearfix">
		<?php section_grid(1, 'buying_guides'); ?>
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
				data: {action: 'section_grid', page: i, section: 'buying_guides'},
				success: function(data, textStatus) {
					$('.grid-loading').hide();
					$('.grid-rest').show();
					if(i > 3) {
						$('.grid-load-more').remove();
					}

					$('.section-grid .grid').append(data);

					$('.section-grid .grid img').unveil();
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

		ga('send', 'event', 'Buying Guides', 'Click', 'Load Page: ' + i);

		});

	});
	</script>

	<div class="grid-load-more">
		<div class="grid-rest load-more"><i class="fa fa-plus-circle"></i> Load more content</div>
		<div class="grid-loading"><i class="fa fa-spinner fa-spin"></i> Loading...</div>
	</div>

</section>

<?php get_footer(); } ?>