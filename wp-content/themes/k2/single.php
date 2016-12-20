<?php 
// finds the last URL segment
$url = strtok($_SERVER['REQUEST_URI'], '?');
$login_boomerang = '/wp-login.php?redirect_to=' . urlencode(site_url($url));
$parse_url = parse_url($url, PHP_URL_PATH);
$url_segments = explode('/', $parse_url);

$post_id = get_queried_object_id();
$post_type = get_post_type($post_id);
$post_views = k2_post_view($post_id);

$k2_post_type = $wp_query->query['post_type'];
$k2_post_type_machine = $k2_post_type;

if(restrictor_require_memebership() == 'true' && !(current_user_can('premium_member') || current_user_can('basic_member')) && preg_match('/^facebookexternalhit.*/', $_SERVER['HTTP_USER_AGENT']) != 1) {

	initializeCas();

	if(!is_user_logged_in() && $_GET['auth'] != 'checked') {
		status_header(401);
		$redirect = $login_boomerang . '&auth=check';
		header("Location: $redirect");
	}

}
// Allows checking for plugin being active.
include_once( ABSPATH . 'wp-admin/includes/plugin.php');

get_header();

?>
	
<div class="post-type-banner arrow arrow-post-type"><a href="<?php echo substr(get_post_type_archive_link($post_type), 0, -10); ?>"><?php echo get_post_type_object($post_type)->labels->name; ?></a></div>

<section class="content-left" role="main">

	<article id="post-<?php echo get_the_ID(); ?>" <?php post_class('clearfix'); ?>>
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
		$image_attr_html = get_post_meta($image_id, '_html', true);
        $splash_url = get_post_meta($id, 'article_videos_meta_url', true);

        // finds the last URL segment
		$urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$segments = explode('/', $urlArray);
		$numSegments = count($segments); 
		$currentSegment = $segments[$numSegments - 1];

		if (isset($_GET['page'])) {
			$preview_page = $_GET['page'];
		}

		if ($currentSegment == 'all' || $preview_page == 'all') {
			$no_pagination = 'yes'; 
		} else {
			$no_pagination = 'no';
		}
		?>

        <header>
            <div class="clearfix">
                <div class="article-date"><?php the_time('F j, Y'); ?></div>
                <div class="article-comments"><a href="#comments">Leave a comment</a> <span class="comment-count"><fb:comments-count href="<?php echo get_sharing_permalink($post_id); ?>"></fb:comments-count></span></div>
            </div>

            <h1><?php the_title(); ?></h1>
            <div class="article-author">By <span><?php the_author(); ?>, Komando.com</span></div>

            <div class="article-meta clearfix">
                <?php if (!empty($post_views)) { echo '<div class="article-meta-views hide-mobile">' . $post_views . '</div>'; } ?>
            </div>
        </header>

        <?php

			if ( has_splash_on_current_page($page, $splash_url, $image) ) {
				?>
                <div class="article-media">

            <?php
			if(has_gallery($id)){
				echo Kom_Article_Gallery::display_article_image_gallery_html($id);
			}

			if ( has_splash_video($splash_url) ) {
				// Determine what type of video is requested and show it.
				switch (splash_type($splash_url)) {
					case 'youtube': ?>
						<div class="article-youtube">
							<iframe class="video-player" src="https://www.youtube.com/embed/<?php echo extract_youtube_video_id($splash_url); ?>" frameborder="0" allowfullscreen></iframe>
						</div>
						<?php break;

					case 'bitgravity':
						// Show one of our own videos.
						echo "<!-- Bitgravity Video<br> -->";
						break;

					case 'vimeo': ?>
						<div class="article-vimeo">
							<iframe class="video-player" src="https://player.vimeo.com/video/<?php echo extract_vimeo_video_id($splash_url); ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
						</div>
						<?php break;
					
					default:
						// Some other type of video.
						echo "<!-- Unspecified Video<br> -->";
						break;
				}
			}
			elseif(!has_gallery($id)){
			// Show the Shutterstock image. ?>
				<img src="<?php echo $image[0]; ?>" alt="<?php the_title_attribute(); ?>" />
				<?php if(!empty($image_caption)) { echo '<div class="article-media-caption">' . $image_caption . '</div>'; } ?>
				<?php if(!empty($image_author)) { echo '<div class="article-media-attribute"><a href="' . $image_author_link . '" target="_blank" rel="nofollow">' . $image_author . '</a></div>'; } ?>
				<?php if(!empty($image_attr_html)) { echo '<div class="article-media-attribute">' . $image_attr_html . '</div>'; } ?>
				
			<?php } ?>
		</div>
        <?php }

        if (class_exists('Kom_Presented_By') && Kom_Presented_By::presented_by_is_active($post_id)) {

            // Display the Presented By Client Ad
            echo Kom_Presented_By::show_presented_by_ad($post_id);

        } else { ?>

            <div class="ad leaderboard-ribbon-ad clearfix">
                <div id="ad-leaderboard-body-ribbon" style="min-width:320px; margin:auto; text-align:center;">
                    <script type="text/javascript">
                        googletag.cmd.push(function() { googletag.display('ad-leaderboard-body-ribbon'); });
                    </script>
                </div>
            </div>

        <?php } ?>

		<div class="article-content clearfix">

			<?php
				// If this is membership restricted AND the user is not a member:
				if (restrictor_require_memebership() == 'true' && !(current_user_can('premium_member') || current_user_can('basic_member'))) {

                    $teaser_content = apply_filters('the_content', $post->post_content);
                    $teaser_content = truncateHtml($teaser_content, 220);

                    echo $teaser_content;

                    if (current_user_can('subscriber')) { ?>

                        <div class="teaser-wrapper">
                            <div class="teaser-overlay">
                                <div class="teaser-tag">To continue reading you need to upgrade your Kim's Club
                                    account
                                </div>
                            </div>

                            <div class="teaser-loginjoin clearfix">
                                <a href="<?php echo CLUB_BASE_URI; ?>/products?r=<?php echo urlencode(site_url($url)); ?>"
                                   class="btn btn-large btn-gold btn-block">Upgrade Kim's Club Account</a>
                            </div>
                        </div>

                    <?php } else { ?>

                        <div class="teaser-wrapper">
                            <div class="teaser-overlay">
                                <div class="teaser-tag">To continue reading sign in or join Kim's Club</div>
                            </div>

                            <div class="teaser-loginjoin clearfix">
                                <div class="teaser-login"><a href="<?php echo site_url($login_boomerang); ?>"
                                                             class="btn btn-large">Sign In</a></div>
                                <div class="teaser-join"><a
                                        href="<?php echo CLUB_BASE_URI; ?>/products?r=<?php echo urlencode(site_url($url)); ?>"
                                        class="btn btn-large btn-gold">Join Kim's Club</a></div>
                            </div>
                        </div>

                    <?php }

				} else {
                    // Content is not restricted OR user is a Member who is not logged-in
                    if ($no_pagination == 'yes') {
                        echo apply_filters('the_content', $post->post_content);
                        $lastpage = 'yes';
                        $k2_paginate = 'no';
                    } else {
                        the_content();
                        if($page == $numpages) { $lastpage = 'yes'; } else { $lastpage = 'no'; }
                        $k2_paginate = 'yes';
                    }

					if($lastpage == 'yes') {

						$k2_post_types = array('downloads', 'apps', 'cool_sites');
						if(in_array($post_info, $k2_post_types)) {
							get_download_meta();
						}
                        // Social sharing buttons ?>
                        <div class="share-button-insert">
                            <p>Please share this information with everyone. Just click on any of these social media buttons.</p>
                            <div class="st_email_custom share-button" st_url="<?php echo get_sharing_permalink($post_id); ?>">&nbsp;<span>Email</span></div>
                            <div class="st_facebook_custom share-button" st_url="<?php echo get_sharing_permalink($post_id); ?>">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Facebook</span></div>
                            <div class="st_twitter_custom share-button" st_url="<?php echo get_sharing_permalink($post_id); ?>">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Twitter</span></div>
                            <div class="st_googleplus_custom share-button" st_url="<?php echo get_sharing_permalink($post_id); ?>">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Google+</span></div>
                            <div class="st_pinterest_custom share-button" st_url="<?php echo get_sharing_permalink($post_id); ?>">&nbsp;<span class="hide-mobile hide-tablet hide-small-desktop">Pinterest</span></div>
                        </div>
                        <?php
						$source_attribution = get_post_meta($id, 'source_attribution_data')[0];
						if($source_attribution['source_title']) {
							echo '<div class="source clearfix">Source: <a href="' . $source_attribution['source_url'] . '" target="_blank">' . $source_attribution['source_title'] . '</a></div>';
						}

						$post_terms = wp_get_post_terms($post_id, array('post_tag', $k2_post_type_machine . '_categories'));
						if ($post_terms) {
								
							$post_cats = wp_get_post_terms($post_id, $k2_post_type_machine . '_categories');
							$post_tags = wp_get_post_terms($post_id, 'post_tag');

							echo '<ul class="article-tags clearfix">';
							
							if ($post_cats) {
								foreach ($post_cats as $cat) {
									echo '<li><a href="' . get_term_link($cat->term_id, $cat->taxonomy) . '"><i class="fa fa-circle tag-bullet"></i> ' . $cat->name . '</a>';
								}
							}

							if ($post_tags) {
								foreach ($post_tags as $tag) {
									echo '<li><a href="' . get_term_link($tag->term_id, $tag->taxonomy) . '"><i class="fa fa-circle tag-bullet"></i> ' . $tag->name . '</a>';
								}
							}
							
							echo '</ul>';
						}
					}
				}
			?>

			<div class="article-content-share-wrapper-mini">
				<div class="article-content-share-buttons-mini clearfix">
					<div class="st_facebook_custom article-content-share-button-mini" st_url="<?php echo get_sharing_permalink($post_id); ?>" data-toggle="tooltip" data-placement="right" title="Share this <?php echo get_post_type_object($post_type)->labels->singular_name; ?>">&nbsp;</div>
					<div class="st_twitter_custom article-content-share-button-mini" st_url="<?php echo get_sharing_permalink($post_id); ?>" data-toggle="tooltip" data-placement="right" title="Tweet this <?php echo get_post_type_object($post_type)->labels->singular_name; ?>">&nbsp;</div>
					<div class="st_googleplus_custom article-content-share-button-mini" st_url="<?php echo get_sharing_permalink($post_id); ?>" data-toggle="tooltip" data-placement="right" title="Share this <?php echo get_post_type_object($post_type)->labels->singular_name; ?>">&nbsp;</div>
					<div class="st_linkedin_custom article-content-share-button-mini" st_url="<?php echo get_sharing_permalink($post_id); ?>" data-toggle="tooltip" data-placement="right" title="LinkedIn this <?php echo get_post_type_object($post_type)->labels->singular_name; ?>">&nbsp;</div>
					<div class="st_pinterest_custom article-content-share-button-mini" st_url="<?php echo get_sharing_permalink($post_id); ?>" data-toggle="tooltip" data-placement="right" title="Pin this <?php echo get_post_type_object($post_type)->labels->singular_name; ?>">&nbsp;</div>
					<div class="st_email_custom article-content-share-button-mini" st_url="<?php echo get_sharing_permalink($post_id); ?>" data-toggle="tooltip" data-placement="right" title="Email this <?php echo get_post_type_object($post_type)->labels->singular_name; ?>">&nbsp;</div>
                    <a href="<?php echo VIDEOS_BASE_URI; ?>" class="sharebar-video article-content-share-button-mini" data-toggle="tooltip" data-placement="right" title="Watch Kim's Picks">&nbsp;</a>
				</div>
			</div>
		</div>

        <div class="article-footer clearfix">
		<?php 
			if($page == $numpages) { $lastpage = 'yes'; } else { $lastpage = 'no'; }

			if(is_preview()) {
				$after_links_code = esc_url( add_query_arg('page', 'all', get_permalink()) );
			} else {
				$after_links_code = get_permalink() . '/all';
			}

			$link_pages = custom_link_pages(array(
				'before' 			=> '<div class="article-pager"><div class="btn-group">',
				'after' 			=> '</div><a href="' . $after_links_code . '" id="all-button">View All</a></div>',
				'next_or_number' 	=> 'next_and_number',
				'nextpagelink' 		=> 'Next <i class="fa fa-angle-right"></i>',
				'previouspagelink' 	=> '<i class="fa fa-angle-left"></i> Previous',
				'pagelink' 			=> '%',
				'echo' 				=> false
			));

			if($lastpage != 'yes' && !empty($link_pages) && $k2_paginate == 'yes') {
				echo '<span class="article-continues hide-mobile">Continued on next page</span>';
			}

			if(!empty($link_pages) && $k2_paginate == 'yes') {
				$doc = new DOMDocument();
				$doc->loadHTML($link_pages);
				$doc->removeChild($doc->firstChild); // remove <!DOCTYPE>
				$doc->replaceChild($doc->firstChild->firstChild->firstChild, $doc->firstChild); // remove <html><body></body></html> 
				$links = $doc->getElementsByTagName('a');

				$last_link = $doc->getElementsByTagName('a')->length;
				$i = 1;

				foreach ($links as $link) {
					$cur_link = $link->getAttribute('href');

					if($i == 1 || $i == $last_link || $i == ($last_link - 1)) {
						if($link->hasAttribute('disabled')) {
							$link->setAttribute('class', 'btn disabled hide-mobile hide-tablet');
						} else {
							$link->setAttribute('class', 'btn');
						}
					} else {
						if($link->hasAttribute('disabled')) {
							$link->setAttribute('class', 'btn disabled hide-mobile hide-tablet');
						} else {
							$link->setAttribute('class', 'btn hide-mobile hide-tablet');
						}
					}

					$i++;
				}
				$html = $doc->saveHTML();

				echo $html;
			}

            // Display a Newsletter Subscribe Ad at the bottom of the article #3285 
            // Moved to below page selector #3846 ?>
            <div class="newsletter-subscribe-box" id="popupBox">
                <div class="top-blue-banner">BREAKING NEWS, TIPS, AND MORE</div><div class="blue-triangle"></div><br>
                <div id="newsletter-signup-headline">LIKED WHAT YOU READ? GET MORE IN YOUR INBOX FREE.</div>
                <div id="newsletter-signup-subhead">Stay up-to-date the easy way.</div>
                <div class="newsletter-subscribe-response"></div>
                <form class="newsletter-subscribe-form">
                    <input class="newsletter-subscribe-email" type="text" placeholder="Enter your email here..." name="email_entry_field">
                    <button class="email-entry-button" type="submit">SIGN ME UP!</button>
                    <div class="newsletter-subscribe-spinner">
                        <img src="//static.komando.com/websites/common/v2/img/mini-spinner.gif" alt="Loading" title="Loading"/>
                    </div>
                </form>
            </div>

        <?php
            if($numpages <= 5 && !empty($link_pages) && $k2_paginate == 'yes') { ?>

			<div class="article-comments article-comments-bottom hide-mobile hide-tablet">
                <a href="#comments">Leave a comment</a> <span class="comment-count">
                    <fb:comments-count href="<?php echo get_permalink(); ?>"></fb:comments-count>
                </span>
            </div>

		<?php } ?>
		</div>

		<div id="taboola-below-article-thumbs-mix"></div>

		<?php endwhile; endif; ?>

		<?php if(restrictor_require_memebership() == 'true' && !(current_user_can('premium_member') || current_user_can('basic_member'))) { } else { ?>
			
			<div class="article-prev-next clearfix">

				<?php 
				$prev_post_id = get_previous_post()->ID;
				if($prev_post_id) {
					$image_id = get_post_thumbnail_id($prev_post_id);
					$image = wp_get_attachment_image_src($image_id, 'small')[0];
					if(empty($image)) {
						$image = k2_get_static_url('v2') . '/img/placeholder-image.png';
					}
				?>
					<div class="article-prev-wrapper clearfix">
						<a href="<?php echo get_permalink($prev_post_id); ?>">
							<div class="article-prev-image">
								<img src="<?php echo $image; ?>" alt="<?php echo get_the_title($prev_post_id); ?>" width="130" height="73" />
								<div class="article-prev-image-overlay"><span class="icon-k2-skinny-left"></span></div>
							</div>
							<div class="article-prev-text">
								<span>Previous <?php echo get_post_type_object($post_type)->labels->singular_name; ?></span>
								<h4><?php echo get_the_title($prev_post_id); ?></h4>
							</div>
						</a>
					</div>
				<?php } else { 
					$rand_post = get_posts(array('orderby' => 'rand', 'numberposts' => '1', 'post_type' => $post_type))[0];
					$image_id = get_post_thumbnail_id($rand_post->ID);
					$image = wp_get_attachment_image_src($image_id, 'small')[0];
					if(empty($image)) {
						$image = k2_get_static_url('v2') . '/img/placeholder-image.png';
					}
				?>
					<div class="article-prev-wrapper clearfix">
						<a href="<?php echo get_permalink($rand_post->ID); ?>">
							<div class="article-prev-image">
								<img src="<?php echo $image; ?>" alt="<?php $rand_post->post_title; ?>" width="130" height="73" />
								<div class="article-prev-image-overlay"><span class="icon-k2-skinny-left"></span></div>
							</div>
							<div class="article-prev-text">
								<span>Random <?php echo get_post_type_object($post_type)->labels->singular_name; ?></span>
								<h4><?php $rand_post->post_title; ?></h4>
							</div>
						</a>
					</div>
				<?php wp_reset_postdata(); }

				$next_post_id = get_next_post()->ID;
				if($next_post_id) {
					$image_id = get_post_thumbnail_id($next_post_id);
					$image = wp_get_attachment_image_src($image_id, 'small')[0];
					if(empty($image)) {
						$image = k2_get_static_url('v2') . '/img/placeholder-image.png';
					}
				?>
					<div class="article-next-wrapper clearfix">
						<a href="<?php echo get_permalink($next_post_id); ?>">
							<div class="article-next-image">
								<img src="<?php echo k2_get_static_url('v2') . '/img/placeholder-image.png'; ?>" data-src="<?php echo $image; ?>" alt="<?php echo get_the_title($next_post_id); ?>" width="130" height="73" />
								<div class="article-next-image-overlay"><span class="icon-k2-skinny-right"></span></div>
							</div>
							<div class="article-next-text">
								<span>Next <?php echo get_post_type_object($post_type)->labels->singular_name; ?></span>
								<h4><?php echo get_the_title($next_post_id); ?></h4>
							</div>
						</a>
					</div>
				<?php } else { 
					$rand_post = get_posts(array('orderby' => 'rand', 'numberposts' => '1', 'post_type' => $post_type))[0];
					$image_id = get_post_thumbnail_id($rand_post->ID);
					$image = wp_get_attachment_image_src($image_id, 'small')[0];
					if(empty($image)) {
						$image = k2_get_static_url('v2') . '/img/placeholder-image.png';
					}
				?>
					<div class="article-next-wrapper clearfix">
						<a href="<?php echo get_permalink($rand_post->ID); ?>">
							<div class="article-next-image">
								<img src="<?php echo k2_get_static_url('v2') . '/img/placeholder-image.png'; ?>" data-src="<?php echo $image; ?>" alt="<?php echo $rand_post->post_title; ?>" width="130" height="73" />
								<div class="article-next-image-overlay"><span class="icon-k2-skinny-right"></span></div>
							</div>
							<div class="article-next-text">
								<span>Random <?php echo get_post_type_object($post_type)->labels->singular_name; ?></span>
								<h4><?php echo $rand_post->post_title; ?></h4>
							</div>
						</a>
					</div>
				<?php wp_reset_postdata(); } ?>

			</div>

			<?php
			// Related articles
			$tags = wp_get_post_tags($id);
			$tag_article_count = 0;

			// Checking to see if there are any tags on the article to get related
			if($tags) {
				$tagcount = count($tags);
				for($i = 0; $i < $tagcount; $i++) {
					$tagIDs[$i] = $tags[$i]->term_id;
				}

				$tag_args = array(
					'tag__in' => $tagIDs,
					'post__not_in' => array($post->ID),
					'posts_per_page' => 4,
					'ignore_sticky_posts' => 1,
					'post_type' => array('posts', 'columns', 'downloads', 'apps', 'cool_sites', 'tips', 'small_business')
				);

				$tag_query = new WP_Query($tag_args);
				$tag_article_count = $tag_query->found_posts;

			}

			// If there are not enough related articles we're pulling in randos from the last month
			if($tag_article_count < 4) {
				$fetch_num = 4 - $tag_article_count;
				$date = getdate();

				$rand_args = array(
					'post__not_in' => array($post->ID),
					'posts_per_page' => $fetch_num,
					'ignore_sticky_posts' => 1,
					'date_query' => array(
						array(
							'column' => 'post_date_gmt',
							'after'  => '7 days ago',
						)
					),
					'post_type' => array('posts', 'columns', 'downloads', 'apps', 'cool_sites', 'tips', 'small_business'),
					'orderby' => 'rand'
				);

				$rand_query = new WP_Query($rand_args);
			}
			
			// Building the new query for the foreach
			$final_query = new WP_Query();
			
			if($tags) {

				if($tag_article_count >= 4) {
					$final_query->posts = $tag_query->posts;
					$final_query->post_count = $tag_query->post_count;
				} else {
					$final_query->posts = array_merge($tag_query->posts, $rand_query->posts);
					$final_query->post_count = $tag_query->post_count + $rand_query->post_count;
				}

			} else {

				$final_query->posts = $rand_query->posts;
				$final_query->post_count = $rand_query->post_count;
			}

			if($final_query->have_posts()) {
				?>
				<div class="related-posts clearfix">
					<div class="related-header arrow arrow-post-type">Related Articles</div>
					<div class="related-posts-wrapper clearfix">
					<?php
						foreach($final_query->posts as $post) {
							$thumbnail_image_id = get_post_thumbnail_id($post->ID);
							$thumbnail_image = wp_get_attachment_image_src($thumbnail_image_id, 'thumbnail')[0];
							if(empty($thumbnail_image)) {
								$thumbnail_image = k2_get_static_url('v2') . '/img/placeholder-image.png';
							}
						?>
							<div class="related-post clearfix" data-article-url="<?php the_permalink() ?>">
								<a href="<?php the_permalink(); ?>" onclick="ga('send', 'event', 'Article', 'Click', 'Related articles link');" class="related-post-image" title="<?php the_title_attribute(); ?>"><img src="<?php echo k2_get_static_url('v2') . '/img/placeholder-image.png'; ?>" data-src="<?php echo $thumbnail_image; ?>" alt="<?php the_title_attribute(); ?>" /></a>
								<a href="<?php the_permalink(); ?>" onclick="ga('send', 'event', 'Article', 'Click', 'Related articles link');"><h4><?php the_title(); ?></h4></a>
							</div>
						<?php } ?>
					</div>
				</div>
			<?php }
			wp_reset_query();
		
		} ?>
			
	</article>

    <?php /* -------------------------------- Newsletter Subscribe Popup (#3233 -- Triggered by JavaScript below)
          This displays an ad with a newsletter subscription form which appears in a shadowbox on an Article page
          after the .article-footer becomes visible. It is limited to being shown a maximum of once every 7 days. */ ?>

    <div class="subscribe-popup" id="popupBoxBrainAd">

        <?php // Rotates graphic based on the modulus of current seconds divided by 2
        if ((date('s') % 2) > 0) { $ver = '1'; } else { $ver = '2'; } ?>

        <div class="popup-container">
            <div class="newsletter-subscribe-box-brain-<?php echo $ver; ?>">

                <div class="close-x">X</div>
                <form class="newsletter-subscribe-form">
                    <input class="newsletter-subscribe-email" type="text" placeholder="Enter your email here..." name="email_entry_field">
                    <button class="email-entry-button" type="submit">SUBSCRIBE</button>
                </form>

                <div class="newsletter-subscribe-spinner">
                    <img src="//static.komando.com/websites/common/v2/img/mini-spinner.gif" alt="Loading" title="Loading"/>
                </div>
            </div>
            <div class="newsletter-subscribe-response"></div>
        </div>
    </div>

    <?php if (($lastpage == 'yes') OR ($currentSegment == 'all') OR ($currentSegment + 1 == $numSegments) OR ($preview_page == 'all')) {
    // Display only if this is an All page or the last page in a multiple segment article ?>

    <script type="text/javascript">
        subscribe_box_shown = false;
        // Check to be sure page (and required JavaScipt file) has loaded.
        $(document).ready(function() {
            // Checks to see when the article-footer div at the end of the
            // article is in view and triggers subscribePopup when it shows.
            $(document).scroll(function() {
                var inView = isInView($('.article-footer'));
                if (!subscribe_box_shown && inView) {
                    // Pops-up a newsletter subscription form in a shadowbox.
                    // Change the parameter to be the number of days before the popup appears again.
                    var days_delay = 7;
                    subscribe_box_shown = subscribePopup( days_delay );

                    // Use this INSTEAD of the previous three lines if a time delay before popup display is preferred.
                    // Change the parameter to be the number of seconds to delay.
                    //var secs_delay = 5;
                    //subscribeTimer( secs_delay );
                }
            });
        });
    </script>

    <?php } ?>

	<?php if(get_post_status($id) != 'publish' || (restrictor_require_memebership() == 'true' && !(current_user_can('premium_member') || current_user_can('basic_member')))) { } else { ?>
	<div class="comments" id="comments">
		<div class="load-comments">View Comments (<fb:comments-count href="<?php echo get_sharing_permalink($post_id); ?>"></fb:comments-count>)</div>
	</div>

	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('.load-comments, .article-comments a').on('click', function() {
			$('#comments').html('<div class="comments-header arrow arrow-post-type">User Comments</div><div class="fb-comments" data-href="<?php echo get_sharing_permalink($post_id); ?>" data-width="100%" data-num-posts="10"></div>');
			FB.XFBML.parse(document.getElementById('comments'));

			ga('send', 'event', 'Article', 'Click', 'Load Facebook comments')
		});
	});
	</script>
	<?php } ?>

	<section class="more-articles hide-mobile">
		<div class="more-articles-header arrow arrow-post-type">More Articles</div>
		<?php
		$args = array(
				'post_type' => array('columns', 'downloads', 'apps', 'cool_sites', 'tips', 'buying_guides', 'small_business', 'happening_now', 'new_technologies'),
				'posts_per_page' => 20,
				'ignore_sticky_posts' => 1,
				'post__not_in' => array($post->ID)
			);

		$more_articles = new WP_Query($args);

		if($more_articles->have_posts()) {

			while($more_articles->have_posts()) : $more_articles->the_post(); 
			$more_article_id = get_the_ID();
			$more_article_post_type = get_post_type($more_article_id);
			$more_article_image_id = get_post_thumbnail_id();
			$more_article_image = wp_get_attachment_image_src($more_article_image_id, 'medium')[0];
			if(empty($more_article_image)) {
				$more_article_image = k2_get_static_url('v2') . '/img/placeholder-image.png';
			}
			?>
			<article class="more-articles-item clearfix" data-article-url="<?php the_permalink(); ?>" data-article-id="<?php echo $more_article_id; ?>">
				<figure>
					<a href="<?php the_permalink(); ?>" onclick="ga('send', 'event', 'Article', 'Click', 'More articles link');" title="<?php the_title_attribute(); ?>"><img src="<?php echo k2_get_static_url('v2') . '/img/placeholder-image.png'; ?>" data-src="<?php echo $more_article_image; ?>" alt="<?php the_title_attribute(); ?>" /></a>
				</figure>
				<header>
					<h3><a href="<?php the_permalink(); ?>" onclick="ga('send', 'event', 'Article', 'Click', 'More articles link');"><?php the_title(); ?></a></h3>
				</header>
			</article>
		<?php
		endwhile; }
		wp_reset_query();
		?>
	</section>
 
</section>

<?php get_sidebar(); ?>
	
<?php get_footer(); ?>
