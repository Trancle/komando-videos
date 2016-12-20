<?php 
// finds the last URL segment
$url = strtok($_SERVER['REQUEST_URI'], '?');
$login_boomerang = '/wp-login.php?redirect_to=' . urlencode(site_url($url));
$parse_url = parse_url($url, PHP_URL_PATH);
$url_segments = explode('/', $parse_url);

if(restrictor_require_memebership() == 'true' && !(current_user_can('premium_member') || current_user_can('basic_member')) && preg_match('/^facebookexternalhit.*/', $_SERVER['HTTP_USER_AGENT']) != 1) {

	initializeCas();

	if(!is_user_logged_in() && $_GET['auth'] != 'checked') {
		status_header(401);
		$redirect = $login_boomerang . '&auth=check';
		header("Location: $redirect");
	}
}
?>
<?php get_header(); ?>
	
	<?php 
	if (have_posts()) : while (have_posts()) : the_post(); 
	global $wp_query;

	$id = get_the_ID();
	$image_id = get_post_thumbnail_id();
	$image = wp_get_attachment_image_src($image_id, 'medium');
	$post_info = get_post_type($id);
	$post_data = get_post_type_object($post_info);
	
	// finds the last URL segment  
	$urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$segments = explode('/', $urlArray);
	$numSegments = count($segments); 
	$currentSegment = $segments[$numSegments - 1];

	if ($currentSegment == 'all') {
		$no_pagination = 'yes'; 
	} else {
		$no_pagination = 'no';
	}
	?>

	
	<section class="content-full clearfix">
	
		<h1><?php the_title(); ?></h1>

		<?php
			if(restrictor_require_memebership() == 'true' && !(current_user_can('premium_member') || current_user_can('plus_member'))) {
				
				$the_excerpt = get_the_excerpt();
				if($the_excerpt != '') {
					
					echo '<p>' . the_excerpt() . '</p>';
				
				} else {

					$teaser_content = apply_filters('the_content', $post->post_content);
					$teaser_content = preg_replace('/<img[^>]+>/','', $teaser_content); // Removes any images from the content
					$teaser_content = wp_trim_words($teaser_content, $num_words = 220, '...' );
					
					echo '<p>' . $teaser_content . '</p>';
				}

				if(current_user_can('subscriber')) { ?>

					<div class="teaser-overlay">
						<div class="teaser-tag">---- To Read More You Need to Upgrade Your Kim's Club Account ----</div>
					</div>

					<div class="teaser-loginjoin clearfix">
						<a href="<?php echo CLUB_BASE_URI; ?>/products?r=<?php echo urlencode(site_url($url)); ?>" class="btn btn-large btn-success btn-block">Upgrade Kim's Club Account</a>
					</div>

				<?php } else { ?>

					<div class="teaser-overlay">
						<div class="teaser-tag">---------- To Read More Sign In or Register for Kim's Club ----------</div>
					</div>

					<div class="teaser-loginjoin clearfix">
						<div class="teaser-login"><a href="<?php echo site_url($login_boomerang); ?>" class="btn btn-large">Sign In</a></div>
						<div class="teaser-join"><a href="<?php echo CLUB_BASE_URI; ?>/products?r=<?php echo urlencode(site_url($url)); ?>" class="btn btn-large btn-success">Join Kim's Club</a></div>
					</div>

				<?php }

			} else {
				
				if($no_pagination == 'yes') { 
					echo apply_filters('the_content', $post->post_content); 
					$lastpage = 'yes';
					$k2_paginate = 'no';
				} else { 
					the_content();
					if($page == $numpages) { $lastpage = 'yes'; } else { $lastpage = 'no'; }
					$k2_paginate = 'yes';
				};

				$chart_exists = false;
				if(class_exists('K2_Comparison_Chart') && K2_Comparison_Chart::post_has_chart($id)){
					// Show Article Comparison Chart
					$chart_exists = true;
					echo K2_Comparison_Chart::display_article_comparison_chart_html($id);
				}

				$k2_post_types = array('downloads', 'apps', 'cool_sites');
				if(in_array($post_info, $k2_post_types) && $lastpage == 'yes') {
					get_download_meta();
				}

				$link_pages = custom_link_pages(array(
					'before' 			=> '<div class="article-pager"><div class="btn-group">',
					'after' 			=> '</div><a href="' . get_permalink() . '/all" id="all-button">All</a></div>',
					'next_or_number' 	=> 'next_and_number',
					'nextpagelink' 		=> 'Next <i class="icon-angle-right"></i>',
					'previouspagelink' 	=> '<i class="icon-angle-left"></i> Previous',
					'pagelink' 			=> '%',
					'echo' 				=> false
				));

				if(!empty($link_pages) && $k2_paginate == 'yes') {
					$doc = new DOMDocument();
					$doc->loadHTML($link_pages);
					$links = $doc->getElementsByTagName('a');
					foreach ($links as $link) {
						$link->setAttribute('class', 'btn');
					}
					$html = $doc->saveHTML();

					echo $html;
				}
			
			}

		?>
		
	</section>
		
	<?php endwhile; ?>
	
	<?php else: ?>
	
	<section class="content-full clearfix">
		<h1>Sorry, nothing to display.</h1>
	</section>
	
	<?php endif; ?>
	
<?php get_footer(); ?>