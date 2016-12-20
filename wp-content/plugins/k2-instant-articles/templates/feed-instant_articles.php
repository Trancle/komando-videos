<?php
/**
 * Instant Articles Feed Template for displaying Instant Articles Posts feed.
 * @package k2ia_ia
 */

header('Content-Type: ' . feed_content_type('rss2') . '; charset=' . get_option('blog_charset'), true);
$more = 1;

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';

/**
 * Fires between the xml and rss tags in a feed. 
 * @param string $context Type of feed. Possible values include 'rss2', 'rss2-comments', 'rdf', 'atom', and 'atom-comments'.
 */
do_action( 'rss_tag_pre', 'rss2' );
?>
<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
    <?php
    /**
     * Fires at the end of the RSS root to add namespaces.
     */
    do_action( 'k2ia_ns' );

    ?>
>

    <channel>
        <title><?php bloginfo_rss('name'); ?></title>
        <link><?php bloginfo_rss('url') ?></link>
        <description><?php bloginfo_rss("description") ?></description>
        <lastBuildDate><?php echo mysql2date('c', get_lastpostmodified('GMT'), false); ?></lastBuildDate>
        <language><?php bloginfo_rss( 'language' ); ?></language>
        <?php
        /**
         * Fires at the end of the RSS2 Feed Header.
         */
        do_action( 'k2ia_head' );

        while( have_posts()) : the_post();

            /**
             * Note: we don't yet have a solution for charts on FBIA, disable them
             */
            if('charts' == get_post_type()){
                continue;
            }

            $guid = get_the_guid();
            $permalink = get_the_permalink();
            $pubDate = mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false);

            $the_content = strval(get_the_content( '' ));
            if(empty($the_content)){
                continue;
            }

            ?>
            <item>
                <title><?php the_title_rss() ?></title>
                <link><?php echo $permalink; ?></link>
                <pubDate><?php echo $pubDate; ?></pubDate>
                <author><![CDATA[<?php the_author() ?>]]></author>
                <guid isPermaLink="false"><?php echo $guid; ?></guid>
                <description><![CDATA[<?php the_excerpt(); ?>]]></description>
                <content:encoded><![CDATA[
                    <?php
                    $the_template = K2IA__PLUGIN_DIR . 'templates/instant_article.php';
                    load_template($the_template, false);
                    ?>
                    ]]></content:encoded>
                <?php
                /**
                 * Fires at the end of each Instant Article feed item.
                 */
                do_action( 'k2ia_item' );
                ?>
            </item>
        <?php endwhile; ?>
    </channel>
</rss>
