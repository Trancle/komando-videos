<?php

trait Kom_Homepage_Helpers
{
	/**
     * Returns the latest X posts marked as featured
     * Also sets transient
     * 
     * @return string 
     */
	private function get_featured_posts($custom_links_count)
	{
		if($custom_links_count >= 1) {
			$num = 4 - $custom_links_count;
		} else {
			$num = 4;
		}

		if($num > 0) {
			// Getting the last x posts that are checked as featured
			$args = array(
				'posts_per_page' => $num,
				'post_type' => array('columns', 'downloads', 'apps', 'cool_sites', 'tips', 'buying_guides', 'small_business'),
				'meta_query' => array(
									array(
										'key' => 'feature_meta_id',
										'value' => '1',
										'compare' => '='
									)
								)
			);

			$featured_links = new WP_Query($args);
			set_transient('kom_homepage_featured_links', $featured_links, 900);
			wp_reset_query();

			return $featured_links;
		}

		return;
	}

	/**
     * Returns the latest Happening Now post marked as featured
     * Also sets transient
     * 
     * @return string 
     */
	private function get_happening_now_posts()
	{
		$args = array(
			'posts_per_page' => 1,
			'post_type' => array('happening_now'),
			'meta_query' => array(
								array(
									'key' => 'feature_meta_id',
									'value' => '1',
									'compare' => '='
								)
							)
		);

		$happening_now = new WP_Query($args);
		set_transient('kom_homepage_happening_now', $happening_now, 900);
		wp_reset_query();

		return $happening_now;
	}
	
	/**
     * Returns the placeholder image URL
     * 
     * @return string 
     */
	private function placeholder_image()
	{
		return k2_get_static_url('v2') . '/img/placeholder-image.png';
	}

	/**
     * Returns the slug from the post type
     * 
     * @return string 
     */
	private function post_type_to_slug($type) {
		return preg_replace('#_#', '-', $type);
	}

	/**
     * Returns the display name from the post type
     * 
     * @return string 
     */
	private function post_type_to_display_name($type) {
		$post_types = [
			'columns' => 'Columns',
			'downloads' => 'Downloads',
			'apps' => 'Apps',
			'cool_sites' => 'Cool Sites',
			'tips' => 'Tips',
			'buying_guides' => 'Buying Guides',
			'happening_now' => 'Happening Now',
			'new_technologies' => 'New Technologies',
			'small_business' => 'Small Business'
		];

		return $post_types[$type];
	}

	/**
     * Returns the post type link
     * 
     * @return string 
     */
	private function get_the_post_type($type) {
		return '<a href="' . get_bloginfo('url') . "/" . self::post_type_to_slug($type) . '">' . self::post_type_to_display_name($type) . "</a>";
	}

	/**
     * Returns the ID in the grid
     * 
     * @return string 
     */
	private function find_last_grid_item($grid_items, $id) {
		foreach($grid_items as $k => $v) {
			if($v['id'] == $id) {
				return $k;
			}
		}
		return false;
	}

	/**
     * Returns a custom array from WP_Query results
     * 
     * @return string 
     */
	private function convert_array($posts)
	{
		$converted_array = [];
		foreach ($posts->posts as $post) {

			$image_id = get_post_thumbnail_id( $post->ID );
			$placeholder_image = self::placeholder_image();
			$large_image = wp_get_attachment_image_src($image_id, 'large')[0];
			$medium_image = wp_get_attachment_image_src($image_id, 'medium')[0];
			if(empty($large_image) || wp_get_attachment_image_src($image_id, 'large')[1] < 970) {
				$large_image = $placeholder_image;
			}

			if(empty($medium_image) || wp_get_attachment_image_src($image_id, 'medium')[1] < 520) {
				$medium_image = $placeholder_image;
			}

			$converted_array[] = array(
				'id' => $post->ID,
				'type' => $post->post_type,
				'title' => $post->post_title,
				'url' => get_permalink($post->ID),
				'image' => array('placeholder' => $placeholder_image, 'large' => $large_image, 'medium' => $medium_image),
				'date' => strtotime($post->post_date)
			);
		}

		return $converted_array;
	}

	/**
     * Returns the HTML for each post
     * 
     * @return string 
     */
	private function get_post_html($item, $featured = null)
	{
		$app_thumb = MultiPostThumbnails::get_post_thumbnail_url($item['type'], 'app-icon', $item['id'], 'app-icon');

		?>
		<article class="grid-item <?php if($featured) { echo 'featured'; } if(!empty($app_thumb)) { echo ' app'; } ?>" data-article-url="<?php echo $item['url']; ?>" data-article-id="<?php echo $item['id']; ?>">
			<figure>
				<a href="<?php echo $item['url']; ?>">
					<img src="<?php echo $item['image']['placeholder']; ?>" data-src="<?php echo $item['image']['large']; ?>" data-src-retina="<?php echo $item['image']['large']; ?>" alt="<?php echo $item['title']; ?>" />
					<?php if(!empty($app_thumb)) { echo '<div><div><img src="' . $app_thumb . '" alt="' . $item['title'] . '" /></div></div>'; } ?>
				</a>
			</figure>
			<div class="grid-item-body">
				<header>
					<span class="grid-item-section hide-mobile"><?php echo self::get_the_post_type($item['type']); ?></span>
					<h3><a href="<?php echo $item['url']; ?>"><?php echo $item['title']; ?></a></h3>
				</header>
				<div class="grid-item-meta hide-mobile clearfix">
					<div class="grid-item-share">
						<span class="icon-k2-share"></span> Share
					</div>
					<div class="grid-item-share-icons hide-mobile">
						<div class="st_email_custom share-button" st_url="<?php echo $item['url']; ?>" st_title="<?php echo $item['title']; ?>"></div>
						<div class="st_facebook_custom share-button" st_url="<?php echo $item['url']; ?>" st_title="<?php echo $item['title']; ?>"></div>
						<div class="st_twitter_custom share-button" st_url="<?php echo $item['url']; ?>" st_title="<?php echo $item['title']; ?>"></div>
						<div class="st_googleplus_custom share-button" st_url="<?php echo $item['url']; ?>" st_title="<?php echo $item['title']; ?>"></div>
						<div class="st_pinterest_custom share-button" st_url="<?php echo $item['url'];?>" st_title="<?php echo $item['title']; ?>"></div>
					</div>
					<?php echo k2_post_view($item['id']); ?>
				</div>
			</div>
		</article>
		<?php
	}
	
	private function get_grid_posts_array($post_count, $page) {

		$featured_links = get_transient('kom_homepage_featured_links');
		$happening_now = get_transient('kom_homepage_happening_now');
		$custom_links = get_option('kom_homepage_custom_links');
		
		$custom_links_count = 0;
		foreach ($custom_links as $custom_link) {
			if($custom_link['active']) {
				$custom_links_count++;
			}
		}
		
		if(empty($featured_links)) {
			$featured_links = self::get_featured_posts($custom_links_count);
		}

		if(empty($happening_now)) {
			$happening_now = self::get_happening_now_posts();
		}
		
		$excluded_posts = [];
		foreach ($featured_links->posts as $post) {
			$excluded_posts[] = $post->ID;
		}

		$excluded_happening_now = [];
		foreach ($happening_now->posts as $post) {
			$excluded_happening_now[] = $post->ID;
		}

		$excluded = array_merge($excluded_posts, $excluded_happening_now);

		$grid_trans = 'kom_homepage_grid' . $page . $post_count;

		$grid_posts = get_transient($grid_trans);

		if(empty($grid_posts)) {

			// Getting the last x# posts
			$args = array(
				'posts_per_page' => $post_count,
				'paged' => $page,
				'post_type' => array('columns', 'downloads', 'apps', 'cool_sites', 'tips', 'buying_guides', 'small_business'),
				'post_status' => 'publish',
				'post__not_in' => $excluded
			);

			$my_query = new WP_Query($args);

			foreach ($my_query->posts as $post) {

				$image_id = get_post_thumbnail_id($post->ID);
				$placeholder_image = self::placeholder_image();
				$large_image = wp_get_attachment_image_src($image_id, 'large')[0];
				$medium_image = wp_get_attachment_image_src($image_id, 'medium')[0];
				if(empty($large_image) || wp_get_attachment_image_src($image_id, 'large')[1] < 970) {
					$large_image = $placeholder_image;
				}

				if(empty($medium_image) || wp_get_attachment_image_src($image_id, 'medium')[1] < 520) {
					$medium_image = $placeholder_image;
				}

				$grid_posts[] = array(
					'id' => $post->ID,
					'type' => $post->post_type,
					'title' => $post->post_title,
					'url' => get_permalink($post->ID),
					'image' => array('placeholder' => $placeholder_image, 'large' => $large_image, 'medium' => $medium_image),
					'date' => strtotime($post->post_date)
				);
			}

			set_transient($grid_trans, $grid_posts, 900);
		}

		return $grid_posts;
	}
}