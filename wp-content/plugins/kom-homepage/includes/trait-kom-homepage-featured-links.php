<?php

trait Kom_Homepage_Featured_Links
{
	/**
     * Call this on the frontend
     * Builds the links and returns the HTML
     * 
     * @return string 
     */
    public function show_featured_links()
    {
		$featured_links = get_transient('kom_homepage_featured_links');
		$happening_now = get_transient('kom_homepage_happening_now');
		$custom_links = get_option('kom_homepage_custom_links');

		$custom_links_count = 0;
		foreach ($custom_links as $key => $custom_link) {
			if ($custom_link['active']) {
				$custom_links_count++;
			}
		}

		if(empty($featured_links)) {
			$featured_links = self::get_featured_posts($custom_links_count);
		}

		if(empty($happening_now)) {
			$happening_now = self::get_happening_now_posts();
		}

		$featured_posts = self::convert_array($featured_links);
		$featured_happening_now = self::convert_array($happening_now);
		// Merging the arrays together
		$featured_items = array_merge($featured_posts, $featured_happening_now);

		// Sorting the array by date
		$sort = array();
		foreach($featured_items as $k=>$v) {
			$sort['date'][$k] = $v['date'];
			$sort['id'][$k] = $v['id'];
		}

		array_multisort($sort['date'], SORT_DESC, $sort['id'], SORT_DESC, $featured_items);

		$custom_count = 0;
		foreach ($custom_links as $custom_link) {
			if($custom_link['active']) {

				$link = array(array(
					'id' => 99999,
					'type' => '',
					'title' => $custom_link['text'],
					'url' => $custom_link['link'],
					'image' => array('placeholder' => self::placeholder_image(), 'large' => $custom_link['image'], 'medium' => $custom_link['image']),
					'date' => 99999
				));

				array_splice($featured_items, $custom_count, 0, $link);
			}

			$custom_count++;
		}
			
		$i = 1;

		foreach ($featured_items as $item) { 
			
			if ($i == 1) {
				self::get_post_html($item, true);
			} else {
				self::get_post_html($item);
			}
			
			if($i >= 5) { break; }
			$i++;
		}
    }
}