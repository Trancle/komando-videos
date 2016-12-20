<?php
/**
 * Function gets an MD5 etag using data from the
 * Title, Links, Description, and Content fields.
 *
 * Returns an MD5 string and a last modified date
 * in an array.
 */
$md5_fields = array( 'title', 'links', 'description', 'content');
$md5_array = get_unique_md5_id( $md5_fields );

if ($md5_array){
    $unique_md5_string = $md5_array[0];
    $last_build_date = $md5_array[1];
}
header('ETag: ' . $unique_md5_string, TRUE );

/**
 * RSS2 Feed Template for displaying RSS2 Posts feed
 * and specifically modified to show full content.
 *
 * @package WordPress
 */
header('Content-Type: ' . feed_content_type('rss2') . '; charset=' . get_option('blog_charset'), true);
$more = 1; 

echo '<?xml version="1.0" encoding="UTF-8"?'.'>';

/**
 * Fires between the xml and rss tags in a feed.
 *
 * @since 4.0.0
 *
 * @param string $context Type of feed. Possible values include 'rss2', 'rss2-comments',
 *                        'rdf', 'atom', and 'atom-comments'.
 */
do_action( 'rss_tag_pre', 'rss2' );
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	<?php
	/**
	 * Fires at the end of the RSS root to add namespaces.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_ns' );
	?>
>

<channel>
	<title><?php bloginfo_rss('name'); wp_title_rss(); ?></title>
	<atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
	<link><?php bloginfo_rss('url') ?></link>
	<description><?php bloginfo_rss("description") ?></description>
	<lastBuildDate><?php echo $last_build_date; ?></lastBuildDate>
	<language><?php bloginfo_rss( 'language' ); ?></language>
	<sy:updatePeriod><?php
		$duration = 'hourly';

		/**
		 * Filter how often to update the RSS feed.
		 *
		 * @since 2.1.0
		 *
		 * @param string $duration The update period. Accepts 'hourly', 'daily', 'weekly', 'monthly',
		 *                         'yearly'. Default 'hourly'.
		 */
		echo apply_filters( 'rss_update_period', $duration );
	?></sy:updatePeriod>
	<sy:updateFrequency><?php
		$frequency = '1';

		/**
		 * Filter the RSS update frequency.
		 *
		 * @since 2.1.0
		 *
		 * @param string $frequency An integer passed as a string representing the frequency
		 *                          of RSS updates within the update period. Default '1'.
		 */
		echo apply_filters( 'rss_update_frequency', $frequency );
	?></sy:updateFrequency>
	<?php
	/**
	 * Fires at the end of the RSS2 Feed Header.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_head' );

	while( have_posts()) : the_post();
	?>
	<item>
		<title><?php the_title_rss() ?></title>
		<link><?php the_permalink_rss() ?></link>
		<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false); ?></pubDate>
		<dc:creator><![CDATA[<?php the_author() ?>]]></dc:creator>
		<?php the_category_rss('rss2') ?>

		<guid isPermaLink="false"><?php the_guid(); ?></guid>
		<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
    <?php
        $id = get_the_ID();
        $content = apply_filters('the_content', $post->post_content);
        $content = remove_pagination($content);
        $post_type = get_post_type($id);
        $k2_post_types = array('downloads', 'apps', 'cool_sites');
        if(in_array($post_type, $k2_post_types)) {
            $content .= download_links();
        }
        $content = str_replace('h2>', 'h3>', $content);

        // Get splash_image and splash_video urls
        $splash_video_url = get_post_meta($id, 'article_videos_meta_url', true);
        $image_id = get_post_thumbnail_id();
        $splash_image_url = wp_get_attachment_image_src($image_id, 'large');
        $image_attr_html = get_post_meta($image_id, '_credit', true);
        $splash_div = '<div class="article-meta" style="display: none" data-splash-video-url="' . $splash_video_url . '" data-splash-image-url="' . $splash_image_url[0] . '" data-splash-image-attribution="' . $image_attr_html . '"></div>';
        $content .= $splash_div;

        if ( strlen( $content ) > 0 ) { ?>
		<content:encoded><![CDATA[<?php echo $content; ?>]]></content:encoded>
	<?php } else { ?>
		<content:encoded><![CDATA[<?php the_excerpt_rss(); ?>]]></content:encoded>
    <?php }
    rss_enclosure();
	/**
	 * Fires at the end of each RSS2 feed item.
	 *
	 * @since 2.0.0
	 */
	do_action( 'rss2_item' );
	?>
	</item>
	<?php endwhile; ?>
</channel>
</rss>
