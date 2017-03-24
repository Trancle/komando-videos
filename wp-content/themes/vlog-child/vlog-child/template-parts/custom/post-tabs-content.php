<?php 


	$categories = komando_front_get_home_all_categories();
	
	$category_detail=get_the_category(get_the_ID());//$post->ID
	foreach($category_detail as $cd){
		$category =  $cd;
	}
	
	
    $carousel = array();
	foreach($categories as $cat ){ 
		$carousel_all = array();

		$posts = komando_front_get_post_tabs_posts_category_based($cat['cat']->term_id);
	  
 
	  foreach($posts as $key => $post){
		$carousel_all[] = $post;
	  }
	  $tabstitle[$cat['cat']->term_id]['catname'] = $cat['cat']->name;
	  $tabs[$cat['cat']->term_id] = $carousel_all;
	  $tabs[$cat['cat']->term_id]['details'] = $cat;
	}

?> 
<div class="vlog-module module-posts post-tabs">
	<?php foreach($categories as $cat ): ?>
		<?php $catid = $cat['cat']->term_id; ?>
		<?php $catname = $cat['cat']->name; ?>
		<?php 
			$open="";
			if($catid == $category->term_id){
				$open='open="yes"';
			} else {
			  $open='';
			}
	
		?>
		<?php echo do_shortcode('[tabby ' . $open . ' title="' . $catname . '"]'); ?>

	
		<?php //$posts_slider = vlog_get_posts_slider(get_the_ID(),$cat['cat']);
		$posts_slider = vlog_get_posts_more_videos($cat['cat']);
			
			?>

		<?php if( $posts_slider->have_posts() ) : ?>
			<div class="row row-eq-height vlog-posts vlog-posts">
			   
				<?php while ( $posts_slider->have_posts() ) : $posts_slider->the_post(); ?>
					<article class="vlog-lay-g vlog-post col-lg-3 col-md-3 col-sm-3 col-xs-6 ">
					
						<div class="video-sec">
							<div class="entry-image">
									<a href="<?php echo esc_url( get_permalink($posts_slider->ID) ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
										<div class="play small"><span>&nbsp;</span></div>
									
										<?php echo vlog_get_featured_image('vlog-lay-g', $posts_slider->ID); ?>
									<div class="video-caption">
										<div class="entry-header">
											<h2 class="entry-title h6 post-clock"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo komaindo_front_get_video_time($posts_slider->ID); ?></h2>
											<h2 class="entry-title h6"><?php echo esc_attr( get_the_title($posts_slider->ID) ); ?></h2>
										</div>
									</div>	
									</a>
							</div>
							<div class="tooltip-wrap">
								<div class="tooltip-rectangle"></div>
								<h2><?php echo substr( esc_attr( get_the_title($posts_slider->ID) ), 0, 50 ); ?></h2>
								<p><?php echo substr(strip_tags($posts_slider->post_content), 0, 250);?><?php echo strlen(strip_tags($posts_slider->post_content))>250 ? '...':''; ?></p>
							</div>
						</div>
					</article>

				<?php endwhile; ?>
				
			</div>
	<?php else: ?>
						<article class="vlog-lay-g vlog-post col-lg-3 col-md-3 col-sm-3 col-xs-6 ">
						
							<div class="video-sec">
								<h2 class="entry-title h6">Not enough video.</h2>
							</div>
								
						</article>
	<?php endif; ?>


	
<?php endforeach; ?>
<?php wp_reset_postdata(); ?>
<?php echo do_shortcode('[tabbyending]'); ?>

 </div>

