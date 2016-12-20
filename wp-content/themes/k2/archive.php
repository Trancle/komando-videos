<?php get_header(); 

$k2query = $wp_query->tax_query->queries['0'];

$k2taxnames = array('columns_categories', 'downloads_categories', 'apps_categories', 'cool_sites_categories', 'tips_categories', 'buying_guides_categories', 'charts_categories', 'newsletters_categories', 'news_categories', 'previous_shows');
$k2tax = $k2query['taxonomy'];

if(in_array($k2tax, $k2taxnames)) {
	$k2tax = 'category';
	$k2term = $k2query['terms']['0'];
	$k2termname = get_term_by('slug', $k2term, $k2query['taxonomy']);
	$k2ptype = $wp_query->posts['0']->post_type;
	$k2ptype = get_post_type_object($k2ptype);
	$k2ptypename = $k2ptype->label;
	$k2ptype = substr($k2ptype->rewrite['slug'], 0, -10);
	$k2url = $k2ptype.'/category/'.$k2term.'/';

	$page_title = $k2termname->name . ' in ' . $k2ptypename;
} else {
	$k2tax = 'tag';
	$k2term = $k2query['terms']['0'];
	$k2termname = get_term_by('slug', $k2term, $k2query['taxonomy']);
	$k2ptype = $wp_query->posts['0']->post_type;
	$k2ptype = get_post_type_object($k2ptype);
	$k2ptypename = 'Tags';
	$k2ptype = substr($k2ptype->rewrite['slug'], 0, -10);
	$k2url = 'tag/'.$k2term.'/';

	$page_title = 'Other stories about ' . $k2termname->name;
}
?>

<div class="content-left">

<h2><?php echo $page_title; ?></h2>

<?php 
if (have_posts()): while (have_posts()) : the_post(); 

$thumb_id = get_post_thumbnail_id(get_the_ID());
$thumbnail = wp_get_attachment_image_src($thumb_id, 'thumbnail')[0];
$ptype_name = get_post_type_object(get_post_type(get_the_ID()));

$meta_link = '<a href="' . get_bloginfo('url') . '/' . $k2ptype . '">' . $ptype_name->labels->name . '</a>';

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

		<div class="result-meta">
			<span class="search-result-post-type"><?php echo $meta_link; ?></span>
			<span class="search-result-date"><?php echo get_the_date(); ?></span>
		</div>
	</div>
</div>
			
<?php endwhile; ?>

<div class="pagination-wrapper clearfix">
	<div class="article-pager btn-group">
	<?php	
	global $wp_query;
	$big = 999999999; // need an unlikely integer

	echo k2wp_pagination( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages
	) );
	?>
	</div>
</div>

<?php else: ?>
<h2>Sorry, nothing to display.</h2>
<?php endif; ?>	

</div>

<?php get_sidebar(); ?>



<?php get_footer(); ?>
