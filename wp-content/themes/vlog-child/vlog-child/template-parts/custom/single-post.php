<div class="vlog-section dark-bg">
	<div class="container">
		<div class="vlog-single-content">
				<?php if( $breadcrumbs = vlog_breadcrumbs() ): ?>
					<?php echo $breadcrumbs; ?>
				<?php endif; ?>

			<div class="row1" >
				<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 post-single-video-section"> 
					<div class="entry-video">
						<div class="video-sections">
							<?php 
								$post_meta = get_post_meta($post->ID); ?>
							<?php if(isset($post_meta) && isset($post_meta['videos'][0])):
								$post_videos = get_post((int)$post_meta['videos'][0]);
								$post_videos_meta = get_post_meta((int)$post_videos->ID);
							?>				
							
								<?php if(isset($post_videos_meta) && ($post_videos_meta['vl_video_type'][0] == 'youtube' || $post_videos_meta['vl_video_type'][0] == 'vimeo123')): ?>
									<?php //echo do_shortcode('[embed width="100%" height="490"]' . str_replace('watch?v=','embed/',$post_videos_meta['vl_url'][0]) . '[/embed]'); https://www.youtube.com/watch?v=KBFkr277f9k ?>
								<?php 
								
									preg_match("/youtube\.com\/(v\/|watch\?v=)([\w\-]+)/", $post_videos_meta['vl_url'][0], $match_id);
									$yt_id = $match_id[2]; 
	
								
									$url="https://www.youtube.com/embed/";
									$url .= $yt_id;
									$url .= "?autoplay=1&disablekb=1&modestbranding=1&playsinline=1"; ?>
								<iframe width="100%"  src="<?php echo $url; ?>" frameborder="0" allowfullscreen></iframe>
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
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
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
