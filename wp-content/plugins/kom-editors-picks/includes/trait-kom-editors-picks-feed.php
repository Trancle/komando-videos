<?php

/**
 * Class Kom_Editors_Picks_Feed
 * trait Kom_Editors_Picks_Feed
 *
 * Created to conform to Google's Editors' Picks feeds specifications
 * See https://support.google.com/news/publisher/answer/1407682
 *
 * June 24, 2015
 */
trait Kom_Editors_Picks_Feed
{
    private function build_feed()
    {
        $aFeed = $this->feed_loop();
        $aPicks = $aFeed['List'];

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom"></rss>');
        $channel = $xml->addChild('channel');
        $channel->addChild('link', get_site_url());
        $channel->addChild('description', 'Top Articles from Kim Komando');
        $channel->addChild('title', 'Komando.com Tech News');
        $channel->addChild('lastBuildDate', $aFeed['Date']);
        $atomLink = $channel->addChild('atom:link', 'http://www.komando.com/feeds/editors-picks', 'atom');
        $atomLink->addAttribute("rel","self");
        $atomLink->addAttribute("type","application/rss+xml");
        $image = $channel->addChild('image');
        $image->addChild('url', k2_get_static_url("v2") . '/img/logo-editors-picks.png');
        $image->addChild('title', 'Komando.com Tech News');
        $image->addChild('link', get_site_url());

        $nPickNo = 1;
        while ($nPickNo <= count($aPicks)) {
            $this->build_item($channel, $aPicks, $nPickNo);
            $nPickNo++;
        }

        return $xml->asXML();
    }

    private function build_item( $channel, $aPicks, $nPickNo )
    {
        $item = $channel->addChild('item');
        $item->addChild('title', strip_tags(addslashes($aPicks[$nPickNo]['title'])));
        $item->addChild('link', $aPicks[$nPickNo]['permalink']);
        $item->addChild('guid', $aPicks[$nPickNo]['permalink']);
        $item->addChild('description', strip_tags(addslashes($aPicks[$nPickNo]['excerpt'])));
        $item->addChild('dc:creator', get_the_author(), 'dc' );
        $item->addChild('pubDate', $aPicks[$nPickNo]["datetime"]);

        return $item;
    }
    // Changed htmlspecialchars() to addslashes() and removed ENT_XML1 as second parameter in above function. 02-05-2016

    private function feed_loop()
    {
        /**
         * Output a list of recent Editor's Picks records
         */

        // Set query parameters for list of current posts
        $maximum_life_of_editor_pick = (3600 * 48);  // The length of time a Pick stays on Google. 48 hours total in seconds. 172800
        $tStart = current_time( 'timestamp' ) - $maximum_life_of_editor_pick;   // Start at 48 hours ago.

        // Run a query on the database to get a list of the relevant post records.
        $args = array(
            'date_query' => array(
                array(
                    'after' => date('Y-m-d H:i:s',$tStart),
                    'before' => date('Y-m-d H:i:s',current_time( 'timestamp' )),
                    'inclusive' => true,
                ),
            ),
            'orderby' => 'post_modified_gmt',
            'order' => 'desc',
            'posts_per_page' => -1,
            'post_type' => array('post', 'columns', 'downloads', 'apps', 'cool_sites', 'tips', 'charts', 'happening_now', 'small_business', 'new_technologies'),
            'post_status' => array('publish'),
            'meta_query' => array(
                array(
                    'key' => 'editors_picks_meta_id',
                    'value' => 1,
                    'compare' => '=',
                ),
            ),
        );

        // Get the list of Editor's Picks records.
        $picks_list = new WP_Query($args);

        // Select Editor's Picks from last 48 hours.
        if ($picks_list->have_posts()) {
            $start_times = array();
            $aPicks = array();
            $nPickNo = 0;
            // Go through the records found one at a time.
            while ($picks_list->have_posts()) {
                // Increment the list of posts.
                $picks_list->the_post();

                // $status will be either 'publish' or 'future'.
                $nPickNo++;
                // Add record to Picks array.
                $aPicks[$nPickNo]['post_id'] = get_the_ID();
                $aPicks[$nPickNo]['permalink'] = get_permalink();
                $aPicks[$nPickNo]['title'] = ucwords(get_the_title());
                $aPicks[$nPickNo]['excerpt'] = get_the_excerpt();
                $aPicks[$nPickNo]['author'] = ucwords(get_the_author());
                $aPicks[$nPickNo]['post_type'] = str_replace('_', '-', get_post_type());
                $aPicks[$nPickNo]['datetime'] = get_the_date('D, d M Y H:i:s') . ' MST';
            }
            $cLastDate = get_the_modified_date('D, d M Y H:i:s') . ' MST';
            $aReturn = array(
                'List' => $aPicks,
                'Date' => $cLastDate,
            );
        } else {
            // No Editor's Picks exist NOW
            // --------------------------------------------------- What needs to happen if no Picks are found
        }
        return $aReturn;
    }
}

