<?php 

	  $args = array( 
		'orderby' => 'term_order', 
		'order' => 'ASC', 
      ); 
      $types = get_categories($args);
	  foreach($types as $cat){
		$terms = get_all_wp_terms_meta($cat->term_id);
		
		if(isset($terms['is-come-in-three-section-carousel'][0])){
		  $categories['categories'][$cat->term_id] = $cat;
		  $categories[$cat->term_id]['is_come_in_three_section'] = true;
		}
	  }

    $carousel = array();

	foreach($categories['categories'] as $cat ){ 
		$carousel_three = array();
		$carousel_all = array();

		$args = array(
		'posts_per_page'   => 20,
		'offset'           => 0,
		'category'         => $cat->term_id,
		'category_name'    => '',
		'orderby'          => 'menu_order',
		'order'            => 'ASC',
		'include'          => '',
		'exclude'          => '',
		'meta_key'         => '',
		'meta_value'       => '',
		'post_type'        => 'post',
		'post_mime_type'   => '',
		'post_parent'      => '',
		'author'	   => '',
		'author_name'	   => '',
		'post_status'      => 'publish',
		'suppress_filters' => true,

	  );
	  $posts = get_posts($args); 
	  
	  $index=1;
	  if(isset($categories[$cat->term_id]['is_come_in_three_section']) && $categories[$cat->term_id]['is_come_in_three_section'] ){
		  foreach($posts as $key => $post){
			$carousel_three[] = $post;
			if($key==2)break;
		  }
		  $carousel[$cat->term_id]['first'] = $carousel_three;

		  foreach($posts as $key => $post){
			unset($posts[$key]);
			if($key==2)break;
		  }
		  $index++;  
	  }
	  $counter=1;
  
	  foreach($posts as $key => $post){
		$carousel_all[$index][] = $post;
		if($counter%6 == 0){
		   $index++;
		}
		$counter++;
	  }
	  $carousel[$cat->term_id]['catname'] = $cat->cat_name;
	  $carousel[$cat->term_id]['rest'] = $carousel_all;
	}

?> 

<?php foreach($carousel as $catkey => $slides ): ?>
<!--<?php echo $slides['catname'];?> Section-->
<?php $backaddcss = ""; ?>
<?php if($catkey % 2 == 0): ?>
<?php $backaddcss = "style=background:#ebeeef"; ?>
<?php endif; ?>
<div class="vlog-section-cat" <?php echo $backaddcss; ?>>  
	<div class="container">  
		<div class="vlog-content-cat">  
			<div class="carousel-sec">
				<div class="vlog-mod-head">
					<div class="vlog-mod-title">
						<h4><a href="<?php echo get_category_link( $catkey ); ?>"><?php echo $slides['catname'];?></a></h4>
					</div>
					<div class="vlog-mod-actions">
						<a class="vlog-all-link" href="<?php echo get_category_link( $catkey ); ?>">View All Videos <i class="fa fa-angle-right" aria-hidden="true"></i></a>
					</div>
				</div>
				<div class="home-vlog-slider">
					<?php if(isset($slides['first']) && count($slides['first']) > 0): ?>
						<div class="row">
							<article class="vlog-lay-e vlog-post col-lg-8  col-sm-8 col-md-8  col-xs-12 bigimage"> 
								<article class="vlog-lay-a">
										<div class="video-sec">
											<div class="entry-image">
													<a href="<?php echo esc_url( get_permalink($slides['first'][0]->ID) ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
														<div class="play small"><span>&nbsp;</span></div>
													
														<?php echo vlog_get_featured_image('vlog-lay-e', $slide->ID); ?>
													<div class="video-caption">
														<div class="entry-header">
															<h2 class="entry-title h6 post-clock"><i class="fa fa-clock-o" aria-hidden="true"></i> 4:45</h2>
															<h2 class="entry-title h2"><?php echo esc_attr( get_the_title($slides['first'][0]->ID) ); ?></h2>
														</div>
														<div class="entry-meta">
															<h2 class="entry-title h6"><?php echo substr(strip_tags($slides['first'][0]->post_content), 0, 300);?>
															</h2>
														</div>
													</div>	
													</a>
											</div>
										</div>
									</article>
							
							
								
							</article>
							<article class="vlog-lay-e vlog-post col-lg-4  col-sm-4 col-md-4  col-xs-12">
								<?php foreach($slides['first'] as $key => $slide ): if($key>0):?>
								<article class="vlog-lay-a">
									<div class="video-sec">
										<div class="entry-image">
												<a href="<?php echo esc_url( get_permalink($slide->ID) ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
													<div class="play small"><span>&nbsp;</span></div>
												
													<?php echo vlog_get_featured_image('vlog-lay-e', $slide->ID); ?>
												<div class="video-caption">
													<div class="entry-header">
														<h2 class="entry-title h6 post-clock"><i class="fa fa-clock-o" aria-hidden="true"></i> 4:45</h2>
														<h2 class="entry-title h6"><?php echo esc_attr( get_the_title($slide->ID) ); ?></h2>
													</div>
												</div>	
												</a>
										</div>
									</div>
								</article>
								 <?php endif; endforeach; ?>
							</article>
						
					
						</div>
					<?php endif; ?>
					
					<?php if(isset($slides['rest']) && count($slides['rest']) > 0): ?>
					  <?php foreach($slides['rest'] as $key => $slidesrestall ): ?>

						<div class="row">
							<?php foreach($slidesrestall as $key1 => $slide ): ?>
								
									<article class="vlog-lay-e vlog-post col-lg-4  col-sm-4 col-md-4  col-xs-12">
		
										<div class="video-sec">
											<div class="entry-image">
													<a href="<?php echo esc_url( get_permalink($slide->ID) ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
														<div class="play small"><span>&nbsp;</span></div>
													
														<?php echo vlog_get_featured_image('vlog-lay-g', $slide->ID); ?>
													<div class="video-caption">
														<div class="entry-header">
															<h2 class="entry-title h6 post-clock"><i class="fa fa-clock-o" aria-hidden="true"></i> 4:45</h2>
															<h2 class="entry-title h6"><?php echo esc_attr( get_the_title($slide->ID) ); ?></h2>
														</div>
													</div>	
													</a>
											</div>
										</div>
									</article>
								
							<?php endforeach; ?>
						</div>	
					  <?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php endforeach; ?>
