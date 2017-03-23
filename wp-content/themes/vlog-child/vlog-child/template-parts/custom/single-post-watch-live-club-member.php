<div class="vlog-section dark-bg">
	<div class="container">
		<div class="vlog-single-content">
				<?php if( $breadcrumbs = vlog_breadcrumbs() ): ?>
					<?php echo $breadcrumbs; ?>
				<?php endif; ?>

			<div class="row" >
				<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 post-single-video-section1"> 
					<div class="entry-video">
						<div class="video-sections">
							<?php 
								$post_meta = komaindo_front_get_videofulldetails($post->ID); 
								$post_videos_meta = $post_meta['video'];
								?>
							<?php if(isset($post_meta['video']['vl_video_type'][0]) && $post_meta['video']['vl_video_type'][0] == 'youtube'){
							?>				
	
									<?php 
									
										preg_match("/youtube\.com\/(v\/|watch\?v=)([\w\-]+)/", $post_videos_meta['vl_url'][0], $match_id);
										$yt_id = $match_id[2]; 
		
									
										$youtubeurl="https://www.youtube.com/embed/";
										$youtubeurl .= $yt_id;
										$url .= "?autoplay=1&disablekb=1&modestbranding=1&playsinline=1"; ?>
								
										<?php echo mediaelement_youtube($youtubeurl);?>
								
								
								<?php } else if(isset($post_meta['video']['vl_video_type'][0]) && $post_meta['video']['vl_video_type'][0] == 'vimeo') { ?>
									<?php  echo mediaelement_vimeo($post_videos_meta['vl_url'][0]); ?>
								<?php } else if(isset($post_meta['video']['vl_video_type'][0]) && $post_meta['video']['vl_video_type'][0] == 'html'){ ?>
										<?php 
										
											$htmlmp4url = wp_get_attachment_url($post_videos_meta['vl_upload_video'][0]); 
											echo mediaelement_mp4($htmlmp4url);?>
								
								<?php } ?>
							
							
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
