<?php 


	$categories = komando_front_get_home_all_categories();
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
				
<?php foreach($tabs as $catkey => $slides ): ?>
    <?php 
	$open="";
	if($category1 == $catkey){
		$open='open="yes"';
		echo $tabstitle[$catkey]['catname'];
		echo $open;
		echo $catkey;
	} else {
	  $open='';
	}
	
	?>
    <?php echo do_shortcode('[tabby ' . $open . ' title="' . $tabstitle[$catkey]['catname'] . '"]'); ?>

	<div class="row row-eq-height vlog-posts vlog-posts">
	<?php foreach($slides as $catkey1 => $slide ): ?>
		<!--<?php echo $slides['catname'];?> Section-->
       <article class="vlog-lay-g vlog-post col-lg-3 col-md-3 col-sm-3 col-xs-6 ">
		
			<div class="video-sec">
				<div class="entry-image">
						<a href="<?php echo esc_url( get_permalink($slide->ID) ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
							<div class="play small"><span>&nbsp;</span></div>
						
							<?php echo vlog_get_featured_image('vlog-lay-g', $slide->ID); ?>
						<div class="video-caption">
							<div class="entry-header">
							    <h2 class="entry-title h6 post-clock"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo komaindo_front_get_video_time($slide->ID); ?></h2>
								<h2 class="entry-title h6"><?php echo esc_attr( get_the_title($slide->ID) ); ?></h2>
							</div>
						</div>	
						</a>
				</div>
				<div class="tooltip-wrap">
					<div class="tooltip-rectangle"></div>
					<h2><?php echo substr( esc_attr( get_the_title($slide->ID) ), 0, 50 ); ?></h2>
					<p><?php echo substr(strip_tags($slide->post_content), 0, 250);?><?php echo strlen(strip_tags($slide->post_content))>250 ? '...':''; ?></p>
				</div>
			</div>
		</article>
	<?php endforeach; ?>
	</div>
<?php endforeach; ?>
<?php echo do_shortcode('[tabbyending]'); ?>
 </div>
				
