<?php
/**

 * Template Name: All Playlists

 */
global $wpdb;
?>

<?php get_header(); ?>

<?php
	$categories = komando_front_get_home_all_categories();

?> 


<div class="vlog-section-cat">  
	<div class="container">  
		<div class="vlog-content-cat">  
			<div class="carousel-sec">
				<div class="vlog-mod-head">
					<div class="vlog-mod-title">
						<h4><?php echo the_title();?></h4>
					</div>
				</div>

				<div class="vlog-module module-posts play-lists-page">
					<div class="row row-eq-height vlog-posts vlog-posts">				
						<?php foreach($categories as $catkey => $category ): //print_r($category);?>
						<?php 
							$class=="";
							
						if(isset($category['meta']['vcat_image'])){
						    //$imgid = vlog_get_image_id_by_url($category['meta']['category-image'][0]);
							//$html = wp_get_attachment_image( $imgid, 'vlog-lay-g', false);
							$html = wp_get_attachment_image($category['meta']['vcat_image']['id'], 'vlog-lay-b', false);
							//$html = vlog_get_featured_image('vlog-lay-e', $category['meta']['vcat_image']['id']);
							$class="category-caption ";

					
						} else {
							$imgid = 887;
							$html = wp_get_attachment_image( $imgid, 'vlog-lay-g', false);
							$class=" category-caption-noelement no-image";
												} ?>
						<!--<?php echo $category['cat']->name;?> Section-->
							
							  
								
								<article class="vlog-lay-a vlog-post col-lg-3 col-md-3 col-sm-3 col-xs-12 <?php echo $class;?>">
									<div class="video-sec">
										<div class="entry-image">
												<a href="<?php echo esc_url( get_category_link($category['cat']->term_id) ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
													<div class="entry-header ">
														<h2 class="entry-title h6"><?php echo esc_attr( $category['cat']->name ); ?></h2>
													</div>
													<?php echo $html;?>
												<div class="video-caption shadow">
													
												</div>	
												</a>
										</div>
										<div class="tooltip-wrap">
											<div class="tooltip-rectangle"></div>
											<h2><?php echo substr( esc_attr( $category['cat']->name ), 0, 50 ); ?></h2>
											<p><?php echo substr(strip_tags($category['cat']->description), 0, 250);?><?php echo strlen(strip_tags($category['cat']->description))>250 ? '...':''; ?></p>
										</div>
									</div>
								</article>
							
							
						<?php endforeach; ?>
					</div>
				</div>	
			</div>	
		</div>
	</div>
</div>	
<?php get_footer(); ?>