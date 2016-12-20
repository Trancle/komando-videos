<?php
/*
Plugin Name: K2 Podcasts List
Plugin URI: http://www.komando.com
Description: Show the podcasts list.
Author: Lisdanay Dominguez
Version: 0.1
Author URI: http://www.komando.com
*/

add_shortcode( 'podcast_list_player', 'k2_podcast_list_player_short_code' );
function k2_podcast_list_player_short_code($atts) {
    extract(shortcode_atts(array(
        'num' => 10,
        'offset' => 1
    ), $atts));

    $total    = ($num + $offset - 1);
    $show     = 'http://podcast.komando.com/show/5.xml';
    $xml      = k2_grab_podcasts($show);
    $elements = $xml->channel->item;
    $total    = (count($elements) >= $total) ? $total : count($elements);
  //If modifying this section, ensure that it does not break the replacePodcastPlayer() function in app/wp-content/plugins/publish-to-apple-news/includes/apple-exporter/class-exporter-content.php
    $podcast  = '<div class="featured-title">MOST RECENT EPISODES</div><div class="featured-player-wrapper clearfix"><ul>';

    $url = get_site_url().'/listen/episode/';

    for ($i = ($offset-1); $i < $total; $i++) {
        $id = k2_grab_podcast_uuid($elements[$i]);
       // $title = urlencode(preg_replace('/[^A-Za-z0-9 !?@#$%^&*().]/u','',strip_tags(html_entity_decode($elements[$i]->title))));
        $title =  htmlspecialchars(urlencode(html_entity_decode($elements[$i]->title, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
        $podcast .= '<li class="listen-on-demand-player-root">
        <div class="featured-player-share">
            <div class="featured-player-share__facebook hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=facebook&amp;url='.urlencode($url.$id).'&amp;title='.$title.'&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-facebook-square"></i></a></div>
            <div class="featured-player-share__twitter hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=twitter&amp;url='.urlencode($url.$id).'&amp;title='.$title.'&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-twitter-square"></i></a></div>
            <div class="featured-player-share__google hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=googleplus&amp;url='.urlencode($url.$id).'&amp;title='.$title.'&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-google-plus-square"></i></a></div>
        </div>
        
        <a name="id-' . $id . '" class="header-offset-anchor"></a>
        <div class="rf-image"><img src="' . $elements[$i]->xpath("itunes:image")[0]['href'] . '"></div>
        <div class="rf-info">
        <div class="featured-player-date hide-mobile">' . date('F d, Y', strtotime($elements[$i]->pubDate)) . '</div>
            <h3 class="featured-player-text">' . $elements[$i]->title . '</h3>
            <span class="featured-player-content">' . $elements[$i]->description . '</span>
        </div>
        <div class="featured-download"><a href="' . $elements[$i]->enclosure['url'] . '">
            <span class="featured-player-download-button"></span></a>
        </div>
        <div class="rf-listen">
            <div class="featured-player audio-player">
            <audio id="podcast' . $i . '" class="featured-player-source" preload="none"><source src="' . $elements[$i]->enclosure['url'] . '" type="'. $elements[$i]->enclosure['type'] . '"></audio>
            <span class="featured-player-play-button audio-player-play"><i class="fa fa-play"></i></span>
            <span class="featured-player-stop-button audio-player-stop"><i class="fa fa-pause"></i></span>
            <span class="featured-player-spinner audio-player-spinner"><i class="fa fa-spinner fa-spin"></i></span>
        </div>
        <span class="featured-player-listen">Listen</span>
        <div class="default-bar">
            <div class="progress-bar"></div>
        </div>
        <span class="featured-player-runtime"><span class="current-index">0:00</span> / <span class="duration">' . sprintf("%02d",floor(( $elements[$i]->xpath("itunes:duration")[0]/60) % 60)) . ':' . sprintf("%02d",floor( $elements[$i]->xpath("itunes:duration")[0] % 60)) . '</span></span>
        </div>
        </li>';
    }
    $podcast .= '</ul></div>';
    // Display one 728x90 leaderboard ad beneath Featured Players
    $podcast .= '<div class="leaderboard-ad">' . show_728x90_ad() . '</div><br>&nbsp;';
    return $podcast;
}