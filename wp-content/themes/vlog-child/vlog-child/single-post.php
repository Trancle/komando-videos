<?php get_header(); ?>


<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php $single_cover = vlog_get_single_layout(); ?>
		<?php if( $single_cover != 'none'): ?>
			<?php get_template_part( 'template-parts/single/cover', $single_cover ); ?>
		<?php endif; ?>

		<?php get_template_part('template-parts/ads/below-header'); ?>

		<?php global $vlog_sidebar_opts; ?>
		<?php $section_class = $vlog_sidebar_opts['use_sidebar'] == 'none' ? 'vlog-single-no-sid' : '' ?>

		<div class="vlog-section dark-bg">
			<div class="container">
				<div class="vlog-single-content">
						<?php if( $breadcrumbs = vlog_breadcrumbs() ): ?>
							<?php echo $breadcrumbs; ?>
						<?php endif; ?>

					<div class="row" >
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 post-single-video-section"> 
							<div class="entry-video">
								
								<div class="video-sections">
									<?php global $post; $post_meta = get_post_meta($post->ID); ?>
									<?php if(isset($post_meta) && isset($post_meta['videos'][0])):
										$post_videos = get_post((int)$post_meta['videos'][0]);
										$post_videos_meta = get_post_meta((int)$post_videos->ID);
									?>							
										<?php if(isset($post_videos_meta) && ($post_videos_meta['vl_video_type'][0] == 'youtube' || $post_videos_meta['vl_video_type'][0] == 'vimeo123')): ?>
											<?php //echo do_shortcode('[embed width="100%" height="490"]' . str_replace('watch?v=','embed/',$post_videos_meta['vl_url'][0]) . '[/embed]'); ?>
										<iframe width="100%"  src="<?php echo str_replace('watch?v=','embed/',$post_videos_meta['vl_url'][0]); ?>" frameborder="0" allowfullscreen></iframe>
										<?php else: ?>
										
											<?php  //HTML
											//echo do_shortcode('[embed width="100%" height="490"]' . $post_videos_meta['vl_url'][0] . '[/embed]'); ?>
										<?php endif; ?>
									<?php endif; ?>
									
								</div>
							</div>
						<div class="entry-carsoul">
							<?php get_template_part( 'template-parts/custom/post-slider' ); ?>
						</div>


						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
							<div class="post-content"> 
								<div class="entry-title post-title">
									 <?php the_title( sprintf( '<h2 class="entry-title h1">', esc_url( get_permalink() ) ), '</h2>' ); ?>
								</div>
								<div class="entry-meta description" style="color:#FFF;"><?php 	echo substr(strip_tags($post->post_content), 0, 300);?>
								</div>
							</div>
							<div class="entry-share">
								<?php get_template_part( 'template-parts/single/share' ); ?>
							</div>
							<div class="entry-ads">
								<?php get_template_part('template-parts/ads/below-single'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>	
					
					
					
		<div class="vlog-section more-videos" >
			<div class="container">
					<div class="vlog-tab-title">More Video on  komando.com</div>
					<div class="vlog-single-content">
						<?php get_template_part( 'template-parts/custom/post-tabs-content' ); ?>
					</div>
			</div>
		</div>
		
		<div class="vlog-section related" >
			<div class="container">
				<div class="vlog-content-related vlog-single-content">	
					<?php if( vlog_get_option('single_related') ) : ?>
						<?php get_template_part( 'template-parts/single/related'); ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</article>

<?php endwhile; ?>

<?php get_footer(); ?>