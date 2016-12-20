<?php

trait Kom_Www_Api_Header
{
	/**
	 * This is the structure of the menu
	 *
	 * @return array
	 */
	private function menu_data()
	{
		$menu = [
			'home' =>
			[
				'name' => '<i class="fa fa-home"></i>',
				'home' => true,
				'class' =>
				[
					'desktop' => 'home-link link--home',
				],
				'active-class' =>
				[
					'desktop' => 'link--home',
				],
			],
			'shop' =>
			[
				'name' => '<span class="icon-gold-tag"></span> Shop',
				'link' => SHOP_BASE_URI,
				'external' => true,
				'class' =>
				[
					'mobile' => 'mobile-link--shop menu-shop',
					'desktop' => 'link--shop menu-shop',
				],
				'active-class' =>
				[
					'mobile' => 'mobile-link--shop menu-shop',
					'desktop' => 'link--shop menu-shop',
				],
			],
			'the-show' =>
			[
				'name' => 'The Show',
				'sponsors' => true,
				'class' =>
				[
					'mobile' => 'mobile-link--about-the-show',
					'desktop' => 'link--the-show sponsors',
				],
				'active-class' =>
				[
					'mobile' => 'mobile-link--about-the-show',
					'desktop' => 'link--the-show',
				],
				'children' =>
				[
					[
						'name' => 'About the Show',
						'slug' => 'the-show',
						'class' =>
						[
							'mobile' => 'mobile-link--about-the-show',
							'desktop' => 'link--about-the-show',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--about-the-show',
							'desktop' => 'link--the-show',
						],
					],
					[
						'name' => 'Station Finder',
						'link' => STATION_FINDER_BASE_URI,
						'external' => true,
						'class' =>
						[
							'mobile' => 'mobile-link--station-finder',
							'desktop' => 'link--station-finder',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--station-finder',
							'desktop' => 'link--the-show',
						],
					],
					[
						'name' => 'Show Picks',
						'slug' => 'show-picks',
						'class' =>
						[
							'mobile' => 'mobile-link--show-picks',
							'desktop' => 'link--show-picks',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--show-picks',
							'desktop' => 'link--the-show',
						],
					],
					[
						'name' => 'Watch Show',
						'link' => VIDEOS_BASE_URI . '/live-from-the-studio',
						'external' => true,
						'class' =>
						[
							'mobile' => 'mobile-link--watch-show',
							'desktop' => 'link--watch-show',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--watch-show',
							'desktop' => 'link--the-show',
						],
					],
                    [
                        'name' => 'Listen',
                        'slug' => 'listen',
                        'class' =>
                            [
                                'mobile' => 'mobile-link--listen',
                                'desktop' => 'link--listen',
                            ],
                        'active-class' =>
                            [
                                'mobile' => 'mobile-link--listen',
                                'desktop' => 'link--the-show',
                            ],
                    ],
                    [
                        'name' => 'Free Podcasts',
                        'slug' => 'listen/podcast-directory',
                        'class' =>
                            [
                                'mobile' => 'mobile-link--free-podcasts',
                                'desktop' => 'link--free-podcasts',
                            ],
                        'active-class' =>
                            [
                                'mobile' => 'mobile-link--free-podcasts',
                                'desktop' => 'link--the-show',
                            ],
                    ],
					[
						'name' => 'About Kim',
						'slug' => 'about-kim',
						'class' =>
						[
							'mobile' => 'mobile-link--about-kim',
							'desktop' => 'link--about-kim',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--about-kim',
							'desktop' => 'link--the-show',
						],
					],
				],
			],
			'read' =>
			[
				'name' => 'Read',
				'class' =>
				[
					'mobile' => 'mobile-link--read',
					'desktop' => 'link--read',
				],
				'active-class' =>
				[
					'mobile' => 'mobile-link--read',
					'desktop' => 'link--read',
				],
				'children' =>
				[
					[
						'name' => 'Comparison Charts',
						'slug' => 'charts',
						'class' =>
						[
							'mobile' => 'mobile-link--charts',
							'desktop' => 'link--charts',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--charts',
							'desktop' => 'link--read',
						],
					],
					[
						'name' => 'Apps',
						'slug' => 'apps',
						'class' =>
						[
							'mobile' => 'mobile-link--apps',
							'desktop' => 'link--apps',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--apps',
							'desktop' => 'link--read',
						],
					],
					[
						'name' => 'Buying Guides',
						'slug' => 'buying-guides',
						'class' =>
						[
							'mobile' => 'mobile-link--buying-guides',
							'desktop' => 'link--buying-guides',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--buying-guides',
							'desktop' => 'link--read',
						],
					],
					[
						'name' => 'Columns',
						'slug' => 'columns',
						'class' =>
						[
							'mobile' => 'mobile-link--columns',
							'desktop' => 'link--columns',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--columns',
							'desktop' => 'link--read',
						],
					],
					[
						'name' => 'Cool Sites',
						'slug' => 'cool-sites',
						'class' =>
						[
							'mobile' => 'mobile-link--cool-sites',
							'desktop' => 'link--cool-sites',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--cool-sites',
							'desktop' => 'link--read',
						],
					],
					[
						'name' => 'Downloads',
						'slug' => 'downloads',
						'class' =>
						[
							'mobile' => 'mobile-link--downloads',
							'desktop' => 'link--downloads',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--downloads',
							'desktop' => 'link--read',
						],
					],
					[
						'name' => 'Forums',
						'link' => FORUM_BASE_URI,
						'external' => true,
						'class' =>
						[
							'mobile' => 'mobile-link--forums',
							'desktop' => 'link--forums',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--forums',
							'desktop' => 'link--read',
						],
					],
					[
						'name' => 'Happening Now',
						'slug' => 'happening-now',
						'class' =>
							[
								'mobile' => 'mobile-link--happening-now',
								'desktop' => 'link--happening-now',
							],
						'active-class' =>
							[
								'mobile' => 'mobile-link--happening-now',
								'desktop' => 'link--read',
							],
					],
					[
						'name' => 'News',
						'link' => NEWS_BASE_URI,
						'external' => true,
						'class' =>
						[
							'mobile' => 'mobile-link--news',
							'desktop' => 'link--news',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--news',
							'desktop' => 'link--read',
						],
					],
                    [
                        'name' => 'New Technologies',
                        'slug' => 'new-technologies',
                        'class' =>
                            [
                                'mobile' => 'mobile-link--new-technologies',
                                'desktop' => 'link--new-technologies',
                            ],
                        'active-class' =>
                            [
                                'mobile' => 'mobile-link--new-technologies',
                                'desktop' => 'link--read',
                            ],
                    ],
					[
						'name' => 'Small Business',
						'slug' => 'small-business',
						'class' =>
						[
							'mobile' => 'mobile-link--small-business',
							'desktop' => 'link--small-business',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--small-business',
							'desktop' => 'link--read',
						],
					],
					[
						'name' => 'Tips',
						'slug' => 'tips',
						'class' =>
						[
							'mobile' => 'mobile-link--tips',
							'desktop' => 'link--tips',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--tips',
							'desktop' => 'link--read',
						],
					],
				],
			],
			'videos' =>
			[
				'name' => 'Videos',
				'class' =>
				[
					'mobile' => 'mobile-link--videos',
					'desktop' => 'link--videos',
				],
				'active-class' =>
				[
					'mobile' => 'mobile-link--videos',
					'desktop' => 'link--videos',
				],
				'children' =>
				[
					[
						'name' => 'All Videos',
						'link' => VIDEOS_BASE_URI,
						'external' => true,
						'class' =>
						[
							'mobile' => 'mobile-link--watch-videos',
							'desktop' => 'link--watch-videos',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--watch-videos',
							'desktop' => 'link--watch',
						],
					],
                    [
                        'name' => 'The Show',
                        'link' => VIDEOS_BASE_URI . '/live-from-the-studio/latest',
                        'external' => true,
                        'class' =>
                            [
                                'mobile' => 'mobile-link--watch-the-show',
                                'desktop' => 'link--watch-the-show',
                            ],
                        'active-class' =>
                            [
                                'mobile' => 'mobile-link--watch-the-show',
                                'desktop' => 'link--watch',
                            ],
                    ],
                    [
                        'name' => 'Kim\'s News Updates',
                        'link' => VIDEOS_BASE_URI . '/kims-news-updates',
                        'external' => true,
                        'class' =>
                            [
                                'mobile' => 'mobile-link--kims-news-updates',
                                'desktop' => 'link--kims-news-updates',
                            ],
                        'active-class' =>
                            [
                                'mobile' => 'mobile-link--kims-news-updates',
                                'desktop' => 'link--kims-news-updates',
                            ],
                    ],
					[
						'name' => 'Kim\'s Picks',
						'link' => VIDEOS_BASE_URI . '/kims-picks',
						'external' => true,
						'class' =>
						[
							'mobile' => 'mobile-link--watch-kims-picks',
							'desktop' => 'link--watch-kims-picks',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--watch-kims-picks',
							'desktop' => 'link--watch',
						],
					],
					[
						'name' => 'Kim\'s Reports',
						'link' => VIDEOS_BASE_URI . '/kims-reports',
						'external' => true,
						'class' =>
						[
							'mobile' => 'mobile-link--watch-kims-reports',
							'desktop' => 'link--watch-kims-reports',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--watch-kims-reports',
							'desktop' => 'link--watch',
						],
					],
					[
						'name' => 'Komando Reports',
						'link' => VIDEOS_BASE_URI . '/komando-com-reports',
						'external' => true,
						'class' =>
							[
								'mobile' => 'mobile-link--watch-the-show',
								'desktop' => 'link--watch-the-show',
							],
						'active-class' =>
							[
								'mobile' => 'mobile-link--watch-the-show',
								'desktop' => 'link--watch',
							],
					],
					[
						'name' => 'Komando Flash Tips',
						'link' => VIDEOS_BASE_URI . '/flash-tips',
						'external' => true,
						'class' =>
							[
								'mobile' => 'mobile-link--watch-the-show',
								'desktop' => 'link--watch-the-show',
							],
						'active-class' =>
							[
								'mobile' => 'mobile-link--watch-the-show',
								'desktop' => 'link--watch',
							],
					],
					[
						'name' => 'All Sections',
						'link' => VIDEOS_BASE_URI . '/shows',
						'external' => true,
						'class' =>
						[
							'mobile' => 'mobile-link--watch-shows',
							'desktop' => 'link--watch-shows',
						],
						'active-class' =>
						[
							'mobile' => 'mobile-link--watch-shows',
							'desktop' => 'link--watch',
						],
					],
				],
			],
			'win' =>
			[
				'name' => 'Win',
				'slug' => 'contests',
				'class' =>
				[
					'mobile' => 'mobile-link--contests',
					'desktop' => 'link--contests',
				],
				'active-class' =>
				[
					'mobile' => 'mobile-link--contests',
					'desktop' => 'link--contests',
				],
			],
		];

		return $menu;
	}

	public function active_menu_item($slug)
	{
		$menu = $this->menu_data();

		foreach ($menu as $menu_item) {
			if ($menu_item['slug'] == $slug) {
				return $menu_item['active-class'];
			} elseif ($menu_item['children']) {
				foreach ($menu_item['children'] as $child_item) {
					if ($child_item['slug'] == $slug) {
						return $child_item['active-class'];
					}
				}
			} elseif (is_front_page()) {
				return ['mobile' => 'mobile-link--home', 'desktop' => 'link--home'];
			}
		}

		return null;
	}

	private function build_main_header_html(){

$html ='<header id="header" class="header clearfix">
    <div class="header-alt-wrap">
        <div class="header-alt clearfix">
            <div class="find-station-widget">
                <i class="fa fa-microphone fa-lg hide-mobile"></i> <span class="tune-in"><a href="' . site_url('/the-show') . '">Listen to Kim</a></span>
                <a href="' . STATION_FINDER_BASE_URI . '">Find a Station</a></div>
            <div class="header-alt-menu hide-mobile clearfix">
                <ul class="clearfix">
                    <li style="line-height: 0;"><a class="logo-20-years" href="http://www.komando.com/the-show"></a></li>
                    <li class="alt-menu-newsletter hide-mobile hide-tablet"><a href="javascript:void(0)" onClick="ga(\'send\', \'event\', \'Homepage\', \'Click\', \'Header - Click through to subscribe modal\');" data-modal="subscribe-modal">
                            <i class="fa fa-envelope fa-lg"></i> Get Kim\'s Free Newsletter</a></li>
                    <!--HFA-POINT-H-0-->
                </ul>
            </div>
        </div>
    </div>
    <div class="header-main-wrap">
        <div class="header-main">

            <a href="' . site_url() . '" class="logo">
                <div class="logo-wrap clearfix">
                    <div class="logo-wordmark"><img src="' . k2_get_static_url('v2') . '/img/logo-wordmark.png" /></div>
                    <div class="logo-slogan"><img src="' . k2_get_static_url('v2') . '/img/logo-slogan.png" /></div>
                    <div class="logo-kim"><img src="' . k2_get_static_url('v2') . '/img/logo-kim.png" /></div>
                </div>
            </a>

			' . $this->build_mobile_html_first_half() . '
            <!--HFA-POINT-H-1-->
            ' . $this->build_mobile_html_second_half() . '

        </div>
    </div>
</header>';

		$html = preg_replace('/\t/', '', $html);
		$html = preg_replace('/(\r(\n)?|(\r)?\n)/', '', $html);

		return $html;
	}

	private function build_mobile_html_first_half()
	{
		// Begin mobile menu
		$html = '<div class="mobile-menu-wrapper hide-tablet hide-desktop">
				<div class="mobile-menu-toggle">
					<a href="javascript:void(0)" class="mobile-toggle mobile-open">
						<span class="icon-k2-hamburger"></span>
					</a>
					<a href="javascript:void(0)" class="mobile-toggle mobile-close">
						<span class="icon-k2-times"></span>
					</a>
				</div>
				<nav class="mobile-menu" role="navigation" aria-label="Main menu">
					<div class="mobile-menu-search">
						<ul>';
		// Mobile menu break for login functionality

		$html = preg_replace('/\t/', '', $html);
		$html = preg_replace('/(\r(\n)?|(\r)?\n)/', '', $html);

		return $html;
	}

	private function build_mobile_html_second_half()
	{
		$site_url = get_site_url();
		$url = strtok($_SERVER['REQUEST_URI'], '?');
		$redirect_url = urlencode(get_site_url($url));
		$search_query = stripslashes(htmlentities($_GET['s']));
		$mobile_menu_data = $this->menu_data();
		$tablet_menu_date = $this->menu_data();
		$desktop_menu_data = $this->menu_data();

		// Resuming mobile menu
		unset($mobile_menu_data['home']);
		$mm = '<li class="mobile-search"><form method="get" action="' . $site_url . '" role="search"><button type="submit"><i class="fa fa-search"></i></button><input type="text" id="mobile-search" name="s" value="' . $search_query . '" placeholder="Search Komando.com..." /></form></li>';
		foreach ($mobile_menu_data as $menu_item) {
			$mm = $mm . $this->build_parent_link('mobile', $menu_item);
		}
		$mm = $mm . '<li><a href="' . CLUB_BASE_URI . '/products?r=' . $redirect_url . '">Join Kim\'s Club</a></li>';
		$mm = $mm . '<li><a href="javascript:void(0)" data-modal="subscribe-modal">Get Email Updates</a></li>';
		$mm = $mm . '</ul>';
		$mm = $mm . '</nav>';
		$mm = $mm . '</div>';

		// Begin tablet menu
		$tm = '<nav class="tablet-menu-wrapper hide-mobile hide-desktop" role="navigation" aria-label="Main menu">';
		$tm = $tm . '<ul class="main-menu" role="menubar">';

		unset($tablet_menu_date['videos']);
		unset($tablet_menu_date['win']);
		foreach ($tablet_menu_date as $menu_item) {
			$tm = $tm . $this->build_parent_link('tablet', $menu_item);
		}
		$tm = $tm . '<li><a href="javascript:void(0)" class="link--more">More <i class="fa fa-caret-down"></i></a>
					<ul class="sub-menu sub-menu-right clearfix">
						<li><a href="' . VIDEOS_BASE_URI . '" class="link--watch-videos">Videos</a></li>
						<li><a href="' . $site_url . '/contests" class="link--contests">Win</a></li>
					</ul>
				</li>';
		$tm = $tm . '</ul>';
		$tm = $tm . '</nav>';

		// Begin desktop menu
		$dm = '<nav class="desktop-menu-wrapper hide-mobile hide-tablet" role="navigation" aria-label="Main menu">';
		$dm = $dm . '<ul class="main-menu" role="menubar">';
		foreach ($desktop_menu_data as $menu_item) {
			$dm = $dm . $this->build_parent_link('desktop', $menu_item);
		}
		$dm = $dm . '</ul>';
		$dm = $dm . '</nav>';

		$html = $mm . $tm . $dm;

		$html = preg_replace('/\t/', '', $html);
		$html = preg_replace('/(\r(\n)?|(\r)?\n)/', '', $html);

		return $html;
	}

	private function build_parent_link($menu, $item)
	{
		if ($menu == 'mobile') {

			if ($item['children']) {
				$l = '<li class="mobile-sub-toggle">';
			} else {
				$l = '<li>';
			}
			$l = $l . '<a href="' . $this->generate_link_url($item) . '" class="' . $item['class']['desktop'] . '">' . $item['name'];

			if ($item['children']) {
				$l = $l . ' <div class="toggle-wrap"><span class="icon-k2-plus mobile-sub-plus"></span><span class="icon-k2-times mobile-sub-active"></span></div>';
			}
			$l = $l . '</a>';
			if ($item['children']) {
				$l = $l . '<ul class="mobile-sub-menu">';
				foreach ($item['children'] as $child) {
					$l = $l . $this->build_child_link($menu, $child);
				}
				$l = $l . '</ul>';
			}
			$l = $l . '</li>';

		} else {

			$l = '<li><a href="' . $this->generate_link_url($item) . '" class="' . $item['class']['desktop'] . '">' . $item['name'];
			if ($item['children']) {
				$l = $l . ' <i class="fa fa-caret-down"></i>';
			}
			$l = $l . '</a>';
			if ($item['children']) {
				if ($item['sponsors']) {
					$l = $l . '<div class="sub-menu sub-menu-right sub-menu-sponsors clearfix">';
					$l = $l . $this->build_sponsors_menu_html();
					$l = $l . '<ul>';
					foreach ($item['children'] as $child) {
						$l = $l . $this->build_child_link($menu, $child);
					}
					$l = $l . '</ul>';
				} else {
					$l = $l . '<ul class="sub-menu sub-menu-right clearfix">';
					foreach ($item['children'] as $child) {
						$l = $l . $this->build_child_link($menu, $child);
					}
					$l = $l . '</ul>';
				}
			}
			$l = $l . '</li>';

		}

		return $l;
	}

	private function build_child_link($menu, $item)
	{
		if ($menu == 'mobile') {
			return '<li><a href="' . $this->generate_link_url($item) . '" class="' . $item['class']['mobile'] . '">' . $item['name'] . '</a></li>';
		} else {
			return '<li><a href="' . $this->generate_link_url($item) . '" class="' . $item['class']['desktop'] . '">' . $item['name'] . '</a></li>';
		}
	}

	private function generate_link_url($item)
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
