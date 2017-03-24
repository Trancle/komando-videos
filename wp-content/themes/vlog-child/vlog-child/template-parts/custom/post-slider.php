<?php 
	
	$category_detail=get_the_category(get_the_ID());//$post->ID
	foreach($category_detail as $cd){
		$category =  $cd;
	}
	/*
	
	
	$args = array(
		'posts_per_page'   => 20,
		'offset'           => 0,
		'category'         => $category->term_id,
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
		'post__not_in' => array($post->ID),

	  );
	$posts = get_posts($args); 
	  
*/
	?>
<?php $posts_slider = vlog_get_posts_slider(get_the_ID(),$category); ?>

<?php if( $posts_slider->have_posts() ) : ?>
<div class="vlog-section post-slider">  
	<div class="vlog-content">  
		<div class="carousel-sec">
			<div class="vlog-mod-head">
				<div class="vlog-mod-title">
					<h4><?php echo $category->name;?></h4>
				</div>
			</div>
			<div class="post-vlog-slider ">
			    <?php //foreach($posts as $catkey1 => $slide ): ?>
				<?php while ( $posts_slider->have_posts() ) : $posts_slider->the_post(); ?>
					<div class="items">
					    <div class="entry-image">
							<a href="<?php echo esc_url( get_permalink($posts_slider->ID) ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
								<?php echo vlog_get_featured_image('vlog-lay-g', $posts_slider->ID); ?>
								<div class="entry-header">
									<h2 class="entry-title h6"><?php echo esc_attr( get_the_title($posts_slider->ID) ); ?></h2>
								</div>
							</a>
				        </div>
					</div>
				<?php //endforeach; ?>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>

<?php wp_reset_postdata(); ?>