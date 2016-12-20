<?php
/*
Plugin Name: K2 Podcasts more on demand
Plugin URI: http://www.komando.com
Description: Show more podcasts on demand.
Author: Lisdanay Dominguez
Version: 0.1
Author URI: http://www.komando.com
*/

add_shortcode( 'podcast-more-on-demand', 'k2_podcast_more_on_demand_short_code' );
function k2_podcast_more_on_demand_short_code($atts) {
    extract(shortcode_atts(array(
        'num' => 10,
        'offset' => 1
    ), $atts));

    $total    = ($num + $offset - 1);
    $show     = 'http://podcast.komando.com/show/5.xml';
    $xml      = k2_grab_podcasts($show);
    $elements = $xml->channel->item;
    $total    = (count($elements) >=$total) ? $total : count($elements);

    $podcasts = '<div class="feed-player-header">More Komando On Demand Podcasts</div>
    <div class="feed-player-sub-header hide-mobile">
        <div class="sub-header-play">Play</div>
        <div class="sub-header-title">Episode Title</div>
        <div class="sub-header-runtime"> Runtime</div>
        <div class="sub-header-date">Date</div>
        <div class="sub-header-download">Download</div>
    </div>
    <div class="feed-player-wrapper clearfix"><ul>';

    for ($i = ($offset-1); $i < $total; $i++) {
        $podcasts .= '<li><div class="feed-player audio-player">
                <audio class="feed-player-source" preload="none"><source src="' . $elements[$i]->enclosure['url'] . '" type="'. $elements[$i]->enclosure['type'] . '"></audio>
                <span class="feed-player-play-button audio-player-play"><i class="fa fa-play"></i></span>
                <span class="feed-player-stop-button audio-player-stop"><i class="fa fa-pause"></i></span>
                <span class="feed-player-spinner audio-player-spinner"><i class="fa fa-spinner fa-spin"></i></span>
		    </div>
            <a name="id-' . k2_grab_podcast_uuid($elements[$i]) . '" class="header-offset-anchor"></a>
		    <div class="feed-player-text">' . $elements[$i]->title . '</div>
		    <div class="feed-player-runtime">' . sprintf("%02d",floor(( $elements[$i]->xpath("itunes:duration")[0]/60) % 60)) . ':' . sprintf("%02d",floor( $elements[$i]->xpath("itunes:duration")[0] % 60)) . ' </div>
		    <div class="feed-player-date">' . date('m/d/y', strtotime($elements[$i]->pubDate)) . '</div>
		    <div class="feed-download"><a href="' . $elements[$i]->enclosure['url'] . '">
		        <span class="feed-player-download-button"><i class="fa fa-download"></i></span></a>
		    </div></li>';
    }
    $podcasts .= '</ul></div>';

    return $podcasts;
}

