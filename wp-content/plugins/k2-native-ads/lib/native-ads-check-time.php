<?php

add_action('k2sectionfeaturedadshook', 'k2_section_featured_ads_check_time');

function k2_section_featured_ads_check_time() {

	$time = new DateTime('now', new DateTimeZone('America/Phoenix'));
	$time = strtotime($time->format('m/d/Y H:i'));

	// Section ads
	$section_ads = get_option('k2_section_ads');

	foreach($section_ads as $section_ad) {

		$section_update = array(
			'section' => $section_ad['section'],
			'section_pretty' => $section_ad['section_pretty'],
			'advertiser' => $section_ad['advertiser'],
			'text' => $section_ad['text'],
			'link' => $section_ad['link'],
			'image' => $section_ad['image'],
			'start' => $section_ad['start'],
			'end' => $section_ad['end']
		);

		if($section_ad['toggle'] && empty($section_ad['active']) && $time >= $section_ad['start']) {

			$section_update['toggle'] = 1;
			$section_update['active'] = 1;

		} elseif($section_ad['toggle'] && $section_ad['active'] && $time >= $section_ad['end']) {

			$section_update['toggle'] = 0;
			$section_update['active'] = 0;

		} elseif($section_ad['toggle'] && $section_ad['active']) {

			$section_update['toggle'] = 1;
			$section_update['active'] = 1;

		} elseif($section_ad['toggle']) {

			$section_update['toggle'] = 1;
			$section_update['active'] = 0;

		} else {
		
			$section_update['toggle'] = 0;
			$section_update['active'] = 0;

		}

		$section_data[] = $section_update;
	}

	update_option('k2_section_ads', $section_data);

	// Trending ads
	$trending_ads = get_option('k2_trending_ads');

	foreach($trending_ads as $trending_ad) {

		$trending_update = array(
			'advertiser-name' => $trending_ad['advertiser-name'],
			'presented-by-text' => $trending_ad['presented-by-text'],
			'presented-by-link' => $trending_ad['presented-by-link'],
			'ad-text' => $trending_ad['ad-text'],
			'ad-link' => $trending_ad['ad-link'],
			'ad-html' => $trending_ad['ad-html'],
			'trending-image' => $trending_ad['trending-image'],
			'start' => $trending_ad['start'],
			'end' => $trending_ad['end'],
		);

		if($trending_ad['toggle'] && empty($trending_ad['active']) && $time >= $trending_ad['start']) {
		
			$trending_update['toggle'] = 1;
			$trending_update['active'] = 1;

		} elseif($trending_ad['toggle'] && $trending_ad['active'] && $time >= $trending_ad['end']) {

			$trending_update['toggle'] = 0;
			$trending_update['active'] = 0;

		} elseif($trending_ad['toggle'] && $trending_ad['active']) {

			$trending_update['toggle'] = 1;
			$trending_update['active'] = 1;

		} elseif($trending_ad['toggle']) {

			$trending_update['toggle'] = 1;
			$trending_update['active'] = 0;
		
		} else {

			$trending_update['toggle'] = 0;
			$trending_update['active'] = 0;
			
		}

		$trending_data[] = $trending_update;
	}

	update_option('k2_trending_ads', $trending_data);

} ?>