<?php $args=array( 
		'orderby' => 'term_order', 
		'order' => 'ASC', 
      ); 
      $types = get_categories($args);
	  foreach($types as $cat){
		$terms = get_all_wp_terms_meta($cat->term_id);
		if(isset($terms['is-come-in-three-section-carousel'][0])){
		  $categories[$cat->term_id] = $cat;
		}
	  }

	?>
<?php 
$carousel = array();

	foreach($categories as $cat ): 
	$carousel_three = array();
$carousel_all = array();
	?>
	
	
<?php 
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
  $counter=1;
  
  foreach($posts as $key => $post){
	$carousel_all[] = $post;
	if($counter%6 == 0){
	   $index++;
	}
	$counter++;
  }
  
  $carousel[$cat->term_id]['rest'] = $carousel_all;
  
?>


<?php endforeach;   ?>
<!--HTML PART-->

<?php $slideno=1; ?>
<?php foreach($carousel as $catkey => $slides ): ?>
<div class="row vlog-cats row-eq-height vlog-slider vlog-module module-cats col-lg-12 col-md-12 col-sm-12 <?php echo esc_attr( $module['css_class'] ); ?>" id="vlog-module-<?php echo $catkey; ?>" data-col="12">
    <?php $first=true; ?>
	<?php foreach($slides['first'] as $sfirst => $post ): setup_postdata( $post ); ?>
			
			<?php if($first && $sfirst == 0): ?> <!--Close123345345-->
				<?php $first=false; ?>
				<div class="vlog-module module-cats col-lg-12 col-md-12 col-sm-12">
					<div class="row vlog-cats col-md-8 col-sm-8">
					<div class="entry-image">
						<a class="vlog-cover" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
							 <?php echo vlog_get_featured_image('vlog-lay-c', $post->ID, false, true ); ?>
							</a>
					</div>
					<?php the_title( sprintf( '<h2 class="entry-title h1"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
								
					<div class="entry-meta description" style="color:#FFF;"><?php echo substr(strip_tags($post->post_content), 0, 400);?>
						<div class="entry-category video-time">5:59</div>
					</div>
					</div>
					<div class="row vlog-cats col-md-4 col-sm-4">
			<?php else: ?>
			        <div class="row vlog-cats col-md-12 col-sm-12">
					<div class="entry-image">
						<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
							 <?php echo vlog_get_featured_image('vlog-lay-e', $post->ID, false, true ); ?>
						</a>
					</div>
					<div class="entry-category video-time">5:59</div>
					<?php the_title( sprintf( '<h2 class="entry-title h1"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							
					
					</div>
			<?php endif; ?>
			<!--Close-->
			<?php if(!$first && $sfirst == 2): ?>
			</div></div>
			<?php endif; ?>
	<?php endforeach; ?>
	
	<?php foreach($slides['rest'] as $srest => $sections ): ?>
		<?php if($srest == 0): ?> <!--Close123-->
		<div class="col-lg-12 col-md-12 col-sm-12">
		<?php endif; ?>

		<div class="row vlog-cats col-md-4 col-sm-4">
			<div class="entry-image">
						<a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
							  <?php echo vlog_get_featured_image('vlog-lay-e', $post->ID, false, true ); ?>
						</a>
			</div> 
			<div class="entry-category video-time">5:59</div>
					<?php the_title( sprintf( '<h2 class="entry-title h1"><a href="%s">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
							
					
		</div> 
		
		<!--Close-->
		<?php if($srest == count($slides['rest'])): ?>
		</div>
		<?php endif; ?>
	
	<?php endforeach; ?>
		
<?php endforeach; ?>
</div>
<?php //die; ?>

	