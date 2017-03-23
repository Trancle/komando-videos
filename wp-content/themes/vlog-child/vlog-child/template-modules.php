<?php

/**

 * Template Name: Modules

 */

?>

<?php get_header(); ?>



<?php $vlog_meta = vlog_get_page_meta( get_the_ID() ); ?>

	<?php $posts_array = komando_front_get_home_slider_posts(); 
		unset($vlog_meta['fa']['manual']);
		foreach($posts_array as $post){
			$vlog_meta['fa']['manual'][] = $post->ID;
		}
	?>

	



<?php if( isset( $vlog_meta['fa'] ) && $vlog_meta['fa']['layout'] != 'none' ) : ?>



    <?php $fa = vlog_get_featured_area_query( $vlog_meta['fa'] ); ?>

    <?php include( locate_template('template-parts/cover/area-2.php') ); ?>

    <?php wp_reset_postdata(); ?>



<?php endif; ?>



<?php get_template_part('template-parts/ads/below-header'); ?>


<!--KIMS-->


<?php include(locate_template('template-parts/custom/home-cat-three-piece-section.php')); ?>

<?php get_footer(); ?>