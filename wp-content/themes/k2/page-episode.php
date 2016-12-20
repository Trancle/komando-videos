<?php
/**
 * Displays a single podcast episode.
*/
global $podcastEpisode;

$currentSegment = $wp_query->query_vars["episode_id"];

// Must be done above the headers to pull in meta data
if(class_exists("\\K2\\Podcast\\Helper\\EpisodeView")){
    $podcastEpisode = \K2\Podcast\Episode::get_episode_by_id($wp_query->query_vars["episode_id"]);
    $featured_player = (new \K2\Podcast\Helper\EpisodeView($podcastEpisode))->display_featured_player();
}
get_header();
?>

<style>
    .wrapper ul li .rf-info {
        min-height: auto;
    }
    .default-bar {
        cursor:pointer;
    }
    .featured-player-wrapper ul li {
        border-top: none;
    }
    .featured-player-wrapper ul li .rf-listen .featured-player-listen {
        margin: 5px 0;
    }
    .featured-player-wrapper ul li .rf-listen .featured-player-runtime {
        margin: 5px 8px;
    }
    .clear {
        clear: both;
    }
    .wrapper ul li .rf-info {
        min-height: 200px;
    }
</style>

<section class="content-left episode-komando clearfix" role="main">
    <div class="episode-main-column clearfix">
        <?php
        /*
                    if($podcast_show_id == 5){
                        $podcast_rss = 'http://feeds.podtrac.com/zcaOvK7EN_jN';
                    }else if($podcast_show_id == 3){
                        $podcast_rss = 'http://feeds.podtrac.com/qjDxovCnSaSq';
                    }
        */
        ?>
        <?php //note: If modifying this section, ensure that it does not break the replacePodcastPlayer() function in app/wp-content/plugins/publish-to-apple-news/includes/apple-exporter/class-exporter-content.php ?>
        <?php if(!empty($featured_player)): ?>

            <div class="featured-player-wrapper clearfix">
                <?php echo $featured_player ?>
            </div>
            <?php if($podcast_rss): ?>
                <div class="podcast-subscribe">
                    <span class="podcast-subscribe__title hide-mobile">Subscribe to Podcasts</span>
                    <a href="<?php echo $podcast_rss ?>"><span class="podcast-subscribe__rss-button">Subscribe by RSS</span></a>
                </div>
            <?php endif ?>
        <?php  else:
            ?>
            <h2>Sorry, nothing to display.</h2>
        <?php endif; ?>
    </div>
</section>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
