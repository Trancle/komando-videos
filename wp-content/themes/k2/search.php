<?php get_header(); ?>
	
	<!-- section -->
	<section class="content-left" role="main">
	
		<h1>Search <input type="text" placeholder="Search Komando.com..." value="<?php echo get_search_query(); ?>" /></h1>
		
		<?php get_template_part('loop'); ?>
		
		<?php get_template_part('pagination'); ?>
	
	</section>
	<!-- /section -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>