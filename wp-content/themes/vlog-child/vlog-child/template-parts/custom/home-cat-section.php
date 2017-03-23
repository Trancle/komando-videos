<article class="vlog-lay-g vlog-post col-lg-4 col-md-4 col-sm-4 col-xs-6 smallimage-cat">
	<div class="video-sec">
		<div class="entry-image">
				<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
					<div class="play small"><span>&nbsp;</span></div>
				
					<?php echo $fimg = vlog_get_featured_image('vlog-lay-e'); ?>
				<div class="video-caption">
					<div class="entry-header">
						<h2 class="entry-title h6 post-clock"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo komaindo_front_get_video_time($post->ID); ?></h2>
						<h2 class="entry-title h6"><?php echo esc_attr( get_the_title() ); ?></h2>
					</div>
				</div>	
				</a>
		</div>
		<div class="tooltip-wrap">
			<div class="tooltip-rectangle"></div>
			<h2><?php echo substr( esc_attr( get_the_title() ), 0, 50 ); ?></h2>
			<p><?php echo substr(strip_tags($post->post_content), 0, 250);?><?php echo strlen(strip_tags($post->post_content))>250 ? '...':''; ?></p>
		</div>
	</div>
</article>

				
