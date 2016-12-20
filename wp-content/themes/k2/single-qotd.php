<?php get_header(); ?>
	
<div class="post-type-banner arrow arrow-post-type">Question of the Day</div>
<section class="content-left" role="main">

	<article id="post-<?php echo get_the_ID(); ?>" <?php post_class('clearfix'); ?>>
		<header>
			<h1><?php the_title(); ?></h1>
		</header>

		<?php 
		if (have_posts()) : while (have_posts()) : the_post(); 
		global $wp_query;

		$id = get_the_ID();
		$image_id = get_post_thumbnail_id();
		$image = wp_get_attachment_image_src($image_id, 'large');
		$image_data = get_post($image_id);
		$image_caption = $image_data->post_excerpt; 
		$post_info = get_post_type($id);
		$post_data = get_post_type_object($post_info);
		$image_author = get_post_meta($image_id, '_credit', true);
		$image_author_link = get_post_meta($image_id, '_link', true);
		?>

		<?php if(!empty($image[0]) && $page < 2) { ?>
		<div class="article-media">
			<img src="<?php echo $image[0]; ?>" alt="<?php the_title_attribute(); ?>" />
			<?php if(!empty($image_caption)) { echo '<div class="article-media-caption">' . $image_caption . '</div>'; } ?>
			<?php if(!empty($image_author)) { echo '<div class="article-media-attribute"><a href="' . $image_author_link . '" target="_blank" rel="nofollow">' . $image_author . '</a></div>'; } ?>
		</div>
		<?php } ?>
		
		<div class="article-content clearfix">
			
			<?php 

			echo apply_filters('the_content', $post->post_content); 
			
			$prev_post = get_previous_post();
		
			if(!empty($prev_post)) {
				echo '<div class="article-qotd-previous"><strong>Previous question:</strong> <a href="' . get_permalink($prev_post->ID) . '">' . $prev_post->post_title . '</a></div>';
			}
			?>

			<div class="article-content-share-wrapper hide-mobile hide-tablet">
				<div class="article-content-share-buttons clearfix">
					<div class="article-content-share-buttons-toggle"><span>Share</span></div>
					<div class="st_facebook_hcount article-content-share-button" st_url="<?php echo get_permalink(); ?>"></div>
					<div class="st_twitter_hcount article-content-share-button" st_url="<?php echo get_permalink(); ?>"></div>
					<div class="st_plusone_hcount article-content-share-button" st_url="<?php echo get_permalink(); ?>"></div>
					<div class="st_pinterest_hcount article-content-share-button" st_url="<?php echo get_permalink(); ?>"></div>
					<div class="st_email_hcount article-content-share-button" st_url="<?php echo get_permalink(); ?>"></div>
				</div>
				<div id="ad-content-sharebar-1" style="min-width:130px; min-height:80px; text-align:center;">
					<script type="text/javascript">
					googletag.cmd.push(function() { googletag.display('ad-content-sharebar-1'); });
					</script>
				</div>
			</div>
		</div>

		<?php endwhile; endif; ?>

		<div class="article-meta clearfix">
			<div class="article-meta-share-buttons">
				<div class="article-meta-post-type hide-mobile hide-tablet hide-small-desktop">Share this Question:&nbsp;</div>
				<div class="st_email_custom share-button" st_url="<?php echo get_permalink(); ?>">&nbsp;<span>Email</span></div>
				<div class="st_facebook_custom share-button" st_url="<?php echo get_permalink(); ?>">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Facebook</span></div>
				<div class="st_twitter_custom share-button" st_url="<?php echo get_permalink(); ?>">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Twitter</span></div>
				<div class="st_googleplus_custom share-button" st_url="<?php echo get_permalink(); ?>">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Google+</span></div>
				<div class="st_pinterest_custom share-button" st_url="<?php echo get_permalink();?>">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Pinterest</span></div>
			</div>
		</div>
			
	</article>

</section>

<?php get_sidebar(); ?>
	
<?php get_footer(); ?>