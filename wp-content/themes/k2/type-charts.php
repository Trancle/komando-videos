<?php get_header(); 

function kom_charts_teaser($the_excerpt) {
	$the_excerpt = strip_tags($the_excerpt);

	if(str_word_count($the_excerpt, 0) < 24) {
		return $the_excerpt;
	} else {
		$the_excerpt = wp_trim_words($the_excerpt, $num_words = 24, '...');
		return $the_excerpt;
	}
}

$categories_list = array(
	'tablets' => array('name' => 'tablets', 'title' => 'Tablets'),
	'phones' => array('name' => 'phones', 'title' => 'Phones'),
	'ereaders' => array('name' => 'e-readers', 'title' => 'E-Readers'),
	'streaming' => array('name' => 'streaming-media', 'title' => 'Streaming Media'),
	'watch' => array('name' => 'smart-watch', 'title' => 'Smart Watch'),
	'gameconsoles' => array('name' => 'video-game-consoles', 'title' => 'Video Game Consoles'),
	'cloudstorage' => array('name' => 'cloud-storage', 'title' => 'Cloud Storage')
); 
?>

<div class="content-left">

	<?php foreach ($categories_list as $category) {
		$args = array(
			'post_type' => 'charts',
			'charts_categories' => $category['name']
		);
		$query = new WP_Query($args);

		if ( $query->have_posts() ) {
			echo '<div class="charts-list">';
			echo '<h2>' . $category['title'] . '</h2>';
			while ( $query->have_posts() ) {
				$query->the_post();
				$thumb_id = get_post_thumbnail_id(get_the_ID());
				$thumbnail = wp_get_attachment_image_src($thumb_id, 'thumbnail')[0];
				echo '<div class="charts-list__chart clearfix">';
				if(!empty($thumbnail)) {
					echo '<a href="' . get_permalink(get_the_ID()) . '" class="chart-list-thumbnail"><img src="' . $thumbnail . '" /></a>';
				}
				echo '<h3><a href="' . get_the_permalink() . '">'  . get_the_title() . '</a></h3>';

				if(get_the_excerpt()) {
					echo kom_charts_teaser(get_the_excerpt());
				} else {
					echo kom_charts_teaser(the_content());
				}
				echo '</div>';
			}
			echo '</div>';
		}
		wp_reset_postdata();
	} ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>