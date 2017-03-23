<?php get_header(); ?>


<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<?php $single_cover = vlog_get_single_layout(); ?>
		<?php if( $single_cover != 'none'): ?>
			<?php get_template_part( 'template-parts/single/cover', $single_cover ); ?>
		<?php endif; ?>

		<?php get_template_part('template-parts/ads/below-header'); ?>

		<?php global $vlog_sidebar_opts; ?>
		<?php $section_class = $vlog_sidebar_opts['use_sidebar'] == 'none' ? 'vlog-single-no-sid' : '' ?>

		
		<?php global $post; ?>
		<?php $post_meta = get_post_meta($post->ID);?>

		<?php if(isset($post_meta['required_club_level'][0]) && $post_meta['required_club_level'][0] == 1): ?>
			<?php echo get_template_part('template-parts/custom/single-post-watch-live'); ?>
		<?php else : ?>
			<?php echo get_template_part('template-parts/custom/single-post'); ?>
		<?php endif; ?>

	</article>

<?php endwhile; ?>

<?php get_footer(); ?>