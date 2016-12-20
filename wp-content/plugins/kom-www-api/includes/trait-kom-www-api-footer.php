<?php

trait Kom_Www_Api_Footer
{
	private function footer_menu_data()
	{
		$footer_menu = [
			'col-one' =>
			[
				'name' => 'The site',
				'class' => false,
				'children' =>
				[
					[
						'name' => 'The Shop',
						'link' => SHOP_BASE_URI,
						'external' => true,
						'class' => false,
					],
					[
						'name' => 'The Show',
						'slug' => 'the-show',
						'class' => false,
					],
					[
						'name' => 'Downloads',
						'slug' => 'downloads',
						'class' => false,
					],
					[
						'name' => 'Tech News',
						'link' => NEWS_BASE_URI,
						'external' => true,
						'class' => false,
					],
					[
						'name' => 'Tips',
						'slug' => 'tips',
						'class' => false,
					],
                    [
                        'name' => 'Apps',
                        'slug' => 'apps',
                        'class' => false,
                    ],
                    [
                        'name' => 'New Technologies',
                        'slug' => 'new-technologies',
                        'class' => false,
                    ],
				],
			],
			'col-two' =>
			[
				'name' => '&nbsp;',
				'class' => 'hide-mobile',
				'children' =>
				[
					[
						'name' => 'Happening Now',
						'slug' => 'happening-now',
						'class' => false,
					],
					[
						'name' => 'Small Business',
						'slug' => 'small-business',
						'class' => false,
					],
					[
						'name' => 'Videos',
						'link' => VIDEOS_BASE_URI,
						'external' => true,
						'class' => false,
					],
          [
						'name' => 'Station Finder',
            'link' => STATION_FINDER_BASE_URI,
						'external' => true,
						'class' => false,
					],
					[
						'name' => 'Contests',
						'slug' => 'contests',
						'class' => false,
					],
					[
						'name' => 'Forum',
						'link' => FORUM_BASE_URI,
						'external' => true,
						'class' => false,
					],
					[
						'name' => '&nbsp;',
						'class' => 'spacer hide-mobile',
					],
				],
			],
			'col-three' =>
			[
				'name' => 'Backstage',
				'class' => false,
				'children' =>
				[
					[
						'name' => 'Contact Us',
						'slug' => 'contact-us',
						'class' => false,
					],
					[
						'name' => 'About Kim',
						'slug' => 'about-kim',
						'class' => false,
					],
					[
						'name' => 'FAQ\'s',
						'slug' => 'faqs',
						'class' => false,
					],
					[
						'name' => 'Manage your account',
						'link' => CLUB_BASE_URI . '/account',
						'external' => true,
						'class' => false,
					],
					[
						'name' => 'Manage newsletters',
						'link' => CLUB_BASE_URI . '/newsletters',
						'external' => true,
						'class' => false,
					],
					[
						'name' => 'Advertise with us',
						'slug' => 'advertise',
						'class' => false,
					],
					[
						'name' => 'Flash Drives for Freedom',
						'link' => 'http://www.komando.com/freedom',
						'external' => true,
						'class' => false,
					]
				],
			],
			'col-four' =>
			[
				'name' => '&nbsp;',
				'class' => 'hide-mobile',
				'children' =>
				[
					[
						'name' => 'Affiliates Center',
						'link' => AFFILIATES_BASE_URI,
						'external' => true,
						'class' => false,
					],
					[
						'name' => 'Careers',
						'slug' => 'careers',
						'class' => false,
					],
					[
						'name' => 'Operation Komando',
						'slug' => 'operation-komando',
						'class' => false,
					],
					[
						'name' => 'Returns &amp; Exchanges',
						'slug' => 'terms-conditions#kims-shop',
						'class' => false,
					],
					[
						'name' => 'Terms &amp; Conditions',
						'slug' => 'terms-conditions',
						'class' => false,
					],
					[
						'name' => 'Privacy Policy',
						'slug' => 'privacy-policy',
						'class' => false,
					],
				],
			],
			'col-five' =>
			[
				'name' => 'Connect with Kim',
				'class' => false,
				'children' =>
				[
					[
						'name' => 'Like on Facebook',
						'link' => 'http://www.facebook.com/kimkomando',
						'external' => true,
						'class' => false,
					],
					[
						'name' => 'Follow on Twitter',
						'link' => 'http://www.twitter.com/kimkomando',
						'external' => true,
						'class' => false,
					],
					[
						'name' => 'Add on Google+',
						'link' => 'https://plus.google.com/u/0/118019228588479629836?rel=author',
						'external' => true,
						'class' => false,
					],
					[
						'name' => 'Follow on Pinterest',
						'link' => 'http://www.pinterest.com/kimkomando',
						'external' => true,
						'class' => false,
					],
					[
						'name' => 'Subscribe on YouTube',
						'link' => 'http://www.youtube.com/kimkomando',
						'external' => true,
						'class' => false,
					],
					[
						'name' => 'Subscribe to Free Podcasts',
						'slug' => 'listen/podcast-directory',
						'external' => false,
						'class' => false,
					],
				],
			],
		];

		return $footer_menu;
	}

	private function build_footer_menu_html()
	{
		$footer_menu_data = $this->footer_menu_data();

		$html = '<footer class="footer">
			<nav class="footer-nav clearfix">';

		foreach ($footer_menu_data as $menu_key => $menu_item) {
			$html = $html . '<div class="' . $menu_key . '">
					<h4' . ($menu_item['class'] ? ' class="' . $menu_item['class'] . '"' : '') . '>' . $menu_item['name'] . '</h4>
					<ul>';

			foreach ($menu_item['children'] as $menu_child) {
				$html = $html . '<li' . ($menu_child['class'] ? ' class="' . $menu_child['class'] . '"' : '') . '>' . ($menu_child['link'] || $menu_child['slug'] ? '<a href="' . $this->generate_footer_link_url($menu_child) . '"' . ( ( 'col-five' == $menu_key && isset($menu_child['external']) && $menu_child['external'] ) ? ' target="_blank"' : '') . '>' . $menu_child['name'] . '</a>' : $menu_child['name'] ) . '</li>';
			}

			$html = $html . '</ul>
			</div>';
		}

		$html = $html . '</nav>
		<div class="footer-meta">
				<div class="footer-logos">
					<img src="' . k2_get_static_url('v2') . '/img/copyright-logos.png" alt="[Logo] The Kim Komando Show - [Logo] WestStar Multimedia Entertainment, Inc." />
				</div>
				<div class="copyright cleafix">
					The Kim Komando Show &reg; and all material pertaining thereto is a Registered Trademark / Servicemark: No. 2,281,044. America\'s Digital Goddess &reg; and all material pertaining thereto is a Registered Trademark / Servicemark: No. 3,727,509. Digital Diva &reg; and all material pertaining thereto is a Registered Trademark / Servicemark: No, 2,463,516. Any and all other material herein is protected by Copyright &copy; 1995 - ' . date('Y', strtotime('+1 month')) . ' WestStar MultiMedia Entertainment, Inc. All Rights Reserved.
				</div>
			</div>';
			$html = $html . '</footer>';

		return $html;
	}

	private function generate_footer_link_url($item)
	{
		if ($item['link'] && $item['external']) {
			return $item['link'];
		} elseif ($item['slug']) {
			return get_site_url() . '/' . $item['slug'];
		} elseif ($item['home']) {
			return get_site_url();
		} else {
			return 'javascript:void(0)';
		}
	}
}
