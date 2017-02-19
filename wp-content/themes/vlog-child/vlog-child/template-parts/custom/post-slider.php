<?php 
	$post =  get_post(get_the_ID());
	$category_detail=get_the_category(get_the_ID());//$post->ID
	$catid = 0;
	foreach($category_detail as $cd){
		$category =  $cd;
	}
	
	
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

	  );
	$posts = get_posts($args); 
	  

	?>

<div class="vlog-section post-slider">  
	<div class="vlog-content">  
		<div class="carousel-sec">
			<div class="vlog-mod-head">
				<div class="vlog-mod-title">
					<h4><?php echo $category->name;?></h4>
				</div>
			</div>
			<div class="post-vlog-slider ">
			    <?php foreach($posts as $catkey1 => $slide ): ?>
					<div class="items">
					    <div class="entry-image">
							<a href="<?php echo esc_url( get_permalink($slide->ID) ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
								<?php echo vlog_get_featured_image('vlog-lay-g', $slide->ID); ?>
								<div class="entry-header">
									<h2 class="entry-title h6"><?php echo esc_attr( get_the_title($slide->ID) ); ?></h2>
								</div>
							</a>
				        </div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
