<?php $slider_class = isset($fa->post_count) && $fa->post_count > 1 ? 'vlog-featured-slider' : ''; ?>

<div class="vlog-featured-2 <?php echo esc_attr($slider_class); ?>">
	<?php if($fa->have_posts()): ?>
		<?php while( $fa->have_posts()): $fa->the_post();?>
			<div class="vlog-featured-item">
				<div class="vlog-cover-bg">
					<a class="vlog-cover" href="<?php echo esc_url( get_permalink($fa->ID) ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
						 <?php echo vlog_get_featured_image('vlog-cover-full', false, false, true ); ?>
					</a>
				</div>
				<div class="vlog-featured-info-2 container vlog-pe-n vlog-active-hover vlog-f-hide">
					<div class="vlog-fa-item">
						<div class="entry-header vlog-pe-a">
			                <?php the_title( sprintf( '<h2 class="entry-title h1">', esc_url( get_permalink() ) ), '</h2>' ); ?>

							<div class="entry-meta description" style="color:#FFF;">
								<div class="entry-meta-description visible-lg">
									<?php echo substr(strip_tags($post->post_content), 0, 400);?>
								</div>
								<div class="entry-category video-time"><h2 class="entry-title h6 post-clock"><i class="fa fa-clock-o" aria-hidden="true"></i>  <?php echo komaindo_front_get_video_time($post->ID); ?></h2></div>
								<div class="play"><a href="<?php echo esc_url( get_permalink($fa->ID) ); ?>">&nbsp;</a></div>
							</div>
			             </div>	
		             </div>
				</div>
			</div>
		<?php endwhile; ?>
	<?php endif; ?>
</div>