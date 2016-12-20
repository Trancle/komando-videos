<aside class="content-right sidebar-grid" role="complementary">

	<div class="grid">

		<?php

			/**
			 * NOTE: This also appears on the home page and the Station Finder
			 */
			if(is_post_type_archive('newsletters') || is_search()) {

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
		<?php } ?>

		<?php if(!is_search()) { ?>
		<div class="grid-item-ad">
			<div class="ad-container clearfix">
				<div id="ad-rectangle-sidebar-1" style="min-width:300px; min-height:50px;">
					<script type='text/javascript'>
					googletag.cmd.push(function() { googletag.display('ad-rectangle-sidebar-1'); });
					</script> 
				</div>
			</div>
		</div>
		<?php } ?>

		<?php 
		if(is_post_type_archive() || is_tax()) {
			global $wp_query;
			$id = $wp_query->post->ID;
			$k2_post_type = $wp_query->query['post_type'];

			if(isset($k2_post_type)) {
				$k2_tax_term = $k2_post_type . '_categories';
				$terms = get_terms($k2_tax_term);
			} else {
				$k2_post_type = substr(get_queried_object()->taxonomy, 0, -11);
				$k2_tax_term = $k2_post_type . '_categories';
				$terms = get_terms($k2_tax_term);
			}

			if(count($terms) > 1) {
				$post_type_obj = get_post_type_object($k2_post_type);

				echo '<div class="sidebar-categories">';
				echo '<h2 class="arrow arrow-gray">' . $post_type_obj->labels->singular_name . ' Categories</h2>';
				echo '<ul>';
				foreach ($terms as $term) {
					$term_link = get_term_link( $term, $k2_tax_term );
					if( is_wp_error( $term_link ) )
					    continue;
					echo '<li><a href="' . $term_link . '">' . $term->name . '</a></li>';
				}
				echo '</ul>';
				echo '</div>';
			}
		} 
		?>

		<?php if(!is_search()) { ?>
		<div class="grid-item-ad grid-item-ad-rect-2">
			<div class="ad-container clearfix">
				<div id="ad-rectangle-sidebar-2" style="min-width:300px; min-height:50px;">
					<script type='text/javascript'>
					googletag.cmd.push(function() { googletag.display('ad-rectangle-sidebar-2'); });
					</script> 
				</div>
			</div>
		</div>
		<?php } ?>

		<?php if(is_single()) { ?>
		<div class="grid-item-ad">
			<div class="ad-container clearfix">
				<?php /* <span>-advertisement-</span> */ ?>
				<div id="ad-rectangle-sidebar-3" style="min-width:300px; min-height:50px;">
					<script type='text/javascript'>
						googletag.cmd.push(function() { googletag.display('ad-rectangle-sidebar-3'); });
					</script>
				</div>
			</div>
		</div>
		<?php } ?>

		<?php
		// List the days content
		$today = getdate();

		$args = array(
			'year' => $today["year"],
			'monthnum' => $today["mon"],
			'day' => $today["mday"],
			'ignore_sticky_posts' => 1,
			'post_type' => array('columns', 'downloads', 'apps', 'cool_sites', 'tips', 'buying_guides', 'small_business')
		);

		$my_query = new WP_Query($args);

		$i = 1;
		echo '<h2 class="arrow">Trending Now</h2>';
		/*if($my_query->have_posts() && !is_post_type_archive('qotd') && !is_singular('qotd')) {

			echo '<h2 class="arrow">Trending Now</h2>';
			while($my_query->have_posts()) : $my_query->the_post(); 
				$id = get_the_ID();
				$post_type = get_post_type($id);
				$post_data = get_post_type_object($post_type);
				$sidebar_image_id = get_post_thumbnail_id();
				$sidebar_image = wp_get_attachment_image_src($sidebar_image_id, 'medium')[0];
				if(empty($sidebar_image)) {
					$sidebar_image = k2_get_static_url('v2') . '/img/placeholder-image.png';
				}

				$app_thumb = MultiPostThumbnails::get_post_thumbnail_url($post_type, 'app-icon', $id, 'app-icon');
				?>
				<article class="grid-item<?php if($post_type == 'viral_video') { echo ' video'; } if(!empty($app_thumb)) { echo ' app'; } ?>" data-article-url="<?php the_permalink(); ?>" data-article-id="<?php echo $id; ?>">
					<figure>
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><img src="<?php echo k2_get_static_url('v2') . '/img/placeholder-image.png'; ?>" data-src="<?php echo $sidebar_image; ?>" alt="<?php the_title_attribute(); ?>" /></a>
						<?php if($post_type == 'viral_video') { echo '<div><img src="' . k2_get_static_url('v2') . '/img/play-icon-circle.png" alt="Play" /></div>'; } if(!empty($app_thumb)) { echo '<div><div><img src="' . $app_thumb . '" alt="' . the_title_attribute() . '" /></div></div>'; } ?>
					</figure>
					<div class="grid-item-body">
						<header>
							<span class="grid-item-section hide-mobile"><?php echo $post_data->labels->singular_name; ?></span>
							<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
						</header>
						<div class="grid-item-meta hide-mobile clearfix">
							<div class="grid-item-share">
								<span class="icon-k2-share"></span> Share
							</div>
							<div class="grid-item-share-icons hide-mobile">
								<div class="st_email_custom share-button" st_url="<?php the_permalink(); ?>" st_title="<?php the_title(); ?>"></div>
								<div class="st_facebook_custom share-button" st_url="<?php the_permalink(); ?>" st_title="<?php the_title(); ?>"></div>
								<div class="st_twitter_custom share-button" st_url="<?php the_permalink(); ?>" st_title="<?php the_title(); ?>"></div>
								<div class="st_googleplus_custom share-button" st_url="<?php the_permalink(); ?>" st_title="<?php the_title(); ?>"></div>
								<div class="st_pinterest_custom share-button" st_url="<?php the_permalink();?>" st_title="<?php the_title(); ?>"></div>
							</div>
							<?php echo k2_post_view($id); ?>
						</div>
					</div>
				</article>
			<?php $i++; endwhile; }

		wp_reset_query(); */ ?>

		<?php
        if(!is_post_type_archive('qotd') && !is_singular('qotd')) {

			//disabled on 05/16/2016 as per the instructions of Taboola (ticket 2956)
            //k2_sidebar_trending_pages();

			?>

			<div id="taboola-right-rail-thumbnails"></div>
			<script type="text/javascript">
				window._taboola = window._taboola || [];
				_taboola.push({
					mode: 'alternating-thumbnails-rr',
					container: 'taboola-right-rail-thumbnails',
					placement: 'Right Rail Thumbnails',
					target_type: 'mix'
				});
			</script>

			<?php

            echo '<div id="taboola-sidebar-thumbnails"></div>';

        }
        ?>

	</div>

</aside>