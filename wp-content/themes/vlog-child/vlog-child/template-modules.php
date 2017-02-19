<?php

/**

 * Template Name: Modules

 */

?>

<?php get_header(); ?>



<?php $vlog_meta = vlog_get_page_meta( get_the_ID() ); ?>

	<?php $args = array(

	'posts_per_page'   => 10,

	'offset'           => 0,

	'category'         => '',

	'category_name'    => '',

	'orderby'          => 'date',

	'order'            => 'DESC',

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

	'meta_query' => array(

      // meta query takes an array of arrays, watch out for this!

      array(

         'key'     => 'post_is_post_show_in_home_carousal',

         'value'   => array('1'),

         'compare' => 'IN'

      )

   )

);

$posts_array = get_posts( $args ); 

unset($vlog_meta['fa']['manual']);

//print_r(unset($vlog_meta['fa']['manual']);

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
<?php include(locate_template('template-parts/custom/home-cat-section.php')); ?>

<?php get_footer(); ?>