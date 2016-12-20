<?php
    /**
     * Template Name: Podcast Episodes Page
     * File: page-podcast-episodes
     *   Lists free episodes for a specified podcast
     *   The podcast ID is pulled from the end of the URL
     * Author: Tyger Gilbert
     * Date: 6/6/2016
     * Time: 9:10 AM
     */


function get_podcast_episode() {

    if(!empty(get_query_var( "podcast_id", ""))){
        $podcast_id = get_query_var( "podcast_id", "");
    }
    elseif(strpos($_SERVER['REQUEST_URI'], "listen/show/") !== false){
        // Get podcast id from URL
        $urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $segments = explode('/', $urlArray);
        $numSegments = count($segments);
        $podcast_id = $segments[$numSegments - 1];
    }
    else{
        header("location: /podcast-directory");
        die();
    }

    global $podcastShow;
    $podcastShow = \K2\Podcast\Listing::get_show_by_id($podcast_id);
    if(is_null($podcastShow)){
        header("location: /podcast-directory");
        die();
    }
}

add_action('get_header','get_podcast_episode');
get_header();

$episode_list = $podcastShow->get_episodes();
//$show = $podcastShow->get_show();
$itunes_url = $podcastShow->get_itunes_url();
$google_play_url = $podcastShow->get_google_play_url();
$tracked_rss_feed_url = $podcastShow->get_tracked_rss_feed_url();
$podcast_name = $podcastShow->get_title();
$show_title = strtolower(str_replace(' ', '-', $podcast_name));
$podcast_url = 'http%3A%2F%2Fwww.komando.com%2Flisten%2F' . $show_title;

?>

<div class="post-type-banner arrow arrow-post-type"><?php echo $podcastShow->get_title(); ?></div>

<?php // Show left content column wide ?>

<section class="content-left" role="main">

    <div class="episode-listing">
        <div class="top-line">
            <h1>Podcast Episodes</h1>
            <div class="align-right">
                <a class="btn btn-blue" href="/listen/podcast-directory">
                <span><i class="fa fa-reply"></i>&nbsp;&nbsp;Back to Podcast Directory</span>
                </a>
            </div>
        </div>
        <div class="podcast-info-outside">
            <div class="inside-info">
                <div class="podcast-page-logo">
                    <img class="podcast-logo-episode" width="250" height="250" src="<?php echo $podcastShow->get_logo(); ?>" />
                    <div class="podcast-information">
                        <span class="sub-headline"><?php echo $podcastShow->get_title(); ?></span>
                        <p style="line-height: 20px;"><?php echo $podcastShow->get_description(); ?></p>
                        <?php if (!empty($itunes_url) OR !empty($google_play_url) OR !empty($tracked_rss_feed_url)) { ?>
                        <div class="subscribe-links">
                            <div class="subscribe-text">SUBSCRIBE:&nbsp;&nbsp;&nbsp;</div>
                            <?php if (!empty($itunes_url)) { ?>
                            <div class="subscribe-iTunes"><a href="<?php echo $itunes_url; ?>"><img src="<?php echo k2_get_static_url('v2'); ?>/img/podcasts/get-it-on-itunes-logo-83x30.png"></a></div>
                            <?php } if (!empty($google_play_url)) { ?>
                            <div class="subscribe-google"><a href="<?php echo $google_play_url; ?>"><img src="<?php echo k2_get_static_url('v2'); ?>/img/podcasts/google-play-logo-96x30.png"></a></div>
                            <?php } if (!empty($tracked_rss_feed_url)) { ?>
                            <div class="subscribe-rss"><a href="<?php echo $tracked_rss_feed_url; ?>"><img src="<?php echo k2_get_static_url('v2'); ?>/img/podcasts/rss-feed-logo-30x30.png"></a></div>
                            <?php } ?>
                        </div>
                        <?php } ?>
                        <div class="podcast-share">
                            <div class="podcast-share-facebook hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=facebook&url=<?php echo $podcast_url; ?>&title=<?php echo urlencode($podcast_name); ?>&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-facebook-square"></i></a></div>
                            <div class="podcast-share-twitter hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=twitter&url=<?php echo $podcast_url; ?>&title=<?php echo urlencode($podcast_name); ?>&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-twitter-square"></i></a></div>
                            <div class="podcast-share-google hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=googleplus&url=<?php echo $podcast_url; ?>&title=<?php echo urlencode($podcast_name); ?>&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-google-plus-square"></i></a></div>
                            <div class="podcast-share-linkedin hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=linkedin&url=<?php echo $podcast_url; ?>&title=<?php echo urlencode($podcast_name); ?>&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-linkedin-square"></i></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="after-intro">
            <div class="sub-headline">Podcast 101: Are you new to podcasts?</div>
            <div class="learn-how-link"><a href="/listen/podcasts-101">Click here to learn more about what they are, and how to download them for FREE.</a></div>
        </div>

        <span class="sub-headline">MOST RECENT EPISODES</span>

        <?php
            // Display Featured Player for the first X episodes in the list.
            foreach(array_slice($episode_list, 0, $podcastShow->get_featured_player_display_amount()) as $episode){
                // Show feed player for this podcast
                $episode_view = new K2\Podcast\Helper\EpisodeView($episode);
                echo $episode_view->display_featured_player();
            }

        ?>

        <?php //<div class="feed-player-headline">+ Click here for more episodes</div> ?>

        <?php // Display header for feed player chart. ?>

        <div class="feed-player-header">More <?php echo $podcast_name; ?> Episodes</div>
        <div class="feed-player-sub-header hide-mobile">
            <div class="sub-header-play">Play</div>
            <div class="sub-header-title">Episode Title</div>
            <div class="sub-header-runtime"> Runtime</div>
            <div class="sub-header-date">Date</div>
            <div class="sub-header-download">Download</div>
        </div>
        <div class="feed-player-wrapper clearfix">
            <ul>
                <?php
                    // Display Feed Player for the next X episodes in the list.
                    foreach(array_slice($episode_list, $podcastShow->get_featured_player_display_amount(), $podcastShow->get_feed_player_display_amount()) as $episode){
                        // Show feed player for this podcast
                        $episode_view = new K2\Podcast\Helper\EpisodeView($episode);
                        echo $episode_view->display_feed_player();
                    }
                    // End of feed players chart
                ?>
            </ul>
        </div>
    </div>
</section>

<?php get_sidebar(); ?>

<?php get_footer(); ?>

<?php
?>
