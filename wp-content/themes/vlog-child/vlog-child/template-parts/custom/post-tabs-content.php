<?php 

	  $args = array( 
		'orderby' => 'term_order', 
		'order' => 'ASC', 
      ); 
      $types = get_categories($args);
	  foreach($types as $cat){
		$terms = get_all_wp_terms_meta($cat->term_id);
		$categories['categories'][$cat->term_id] = $cat;

	  }

    $carousel = array();

	foreach($categories['categories'] as $cat ){ 
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
	  
 
	  foreach($posts as $key => $post){
		$carousel_all[] = $post;
	  }
	  $tabstitle[$cat->term_id]['catname'] = $cat->cat_name;
	  $tabs[$cat->term_id] = $carousel_all;
	}

?> 

<div class="vlog-module module-posts post-tabs">
				
<?php foreach($tabs as $catkey => $slides ): ?>
    <?php echo do_shortcode('[tabby title="' . $tabstitle[$catkey]['catname'] . '"]'); ?>

	<div class="row vlog-posts row-eq-height vlog-posts">
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
<?php echo do_shortcode('[tabbyending]'); ?>
 </div>
				
