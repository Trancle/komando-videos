<?php 
$k2ptype = get_post_type_object('previous_shows');
$k2ptypename = $k2ptype->label;
$k2ptype = substr($k2ptype->rewrite['slug'], 0, -10);
$k2url = $k2ptype.'/';

if (http_response_code() == 404) {
	get_template_part('404-redirect');
} else {

get_header();
	
?>

<div class="content-left">

	<h1>Previous Shows Picks</h1>

<?php 
if (have_posts()): while (have_posts()) : the_post(); 

$thumb_id = get_post_thumbnail_id(get_the_ID());
$thumbnail = wp_get_attachment_image_src($thumb_id, 'thumbnail')[0];

$meta_link = '<a href="' . get_bloginfo('url') . '/' . $k2ptype . '">' . get_post_type(get_the_ID()) . '</a>';

?>
			
<div class="search-result clearfix" data-article-url="<?php the_permalink(); ?>" data-article-id="<?php echo get_the_ID(); ?>">

	<?php if(!empty($thumbnail)) {
		echo '<a href="' . get_permalink(get_the_ID()) . '" class="search-image"><img src="' . $thumbnail . '" /></a>';
	} ?>

	<div class="search-results-text">       
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php 
		$the_excerpt = get_the_excerpt();

		if($the_excerpt != '') {
			$the_excerpt = strip_tags($the_excerpt);

			if(str_word_count($the_excerpt, 0) < 24) {
				echo $the_excerpt;
			} else {
				$the_excerpt = wp_trim_words($the_excerpt, $num_words = 24, '...');
				echo $the_excerpt;
			}
		} else {
			$teaser_content = apply_filters('the_content', $post->post_content);
			$teaser_content = preg_replace('/<img[^>]+>/', '', $teaser_content); // Removes any images from the content
			$teaser_content = strip_tags($teaser_content);
			$teaser_content = wp_trim_words($teaser_content, $num_words = 24, '...');
			echo $teaser_content;
		} ?>

	</div>
</div>
			
<?php endwhile; endif; ?>

<?php k2wp_pagination($k2url); ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); } ?>