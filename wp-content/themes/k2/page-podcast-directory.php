<?php
    /**
     * Template Name: Podcast Directory Page
     * File: page-podcast-directory
     *   Lists free podcasts available
     * Author: Tyger Gilbert
     * Date: 6/6/2016
     * Time: 9:10 AM
     */

global $podcasts;
// Get list of all podcasts as an array of objects
$podcasts = \K2\Podcast\Listing::get_podcast_list();
get_header();

    foreach($podcasts as $podcast) {
        $podcast->get_title();
    }

    // Last Featured Episode is latest episode of all podcasts
    $last_episode = \K2\Podcast\Listing::get_latest_episode();
    $last_episode = $podcasts[0]->get_episodes()[0];
    $last_episode_view = new \K2\Podcast\Helper\EpisodeView($last_episode);

?>

<div class="post-type-banner arrow arrow-post-type">KOMANDO FREE PODCASTS</div>

<!-- Show left content column wide -->
<section class="content-left" role="main">

    <h1 class="directory-headline">Podcast 101: Are you new to podcasts?</h1>

    <p class="podcast-intro">A podcast is an audio file that you can listen to now or download to your
    computer or portable media player. You can listen to it at anytime whether you're at the gym, at
    home, on the go, or simply relaxing. It's a great way to stay informed and up-to-date the easy way.
    See my list of free podcasts below and subscribe to them all. They'll be delivered to you
    automatically, even when you're sleeping. Enjoy!</p>

    <a href="/listen/podcasts-101"><p class="podcast-101-link">Click here to learn more about how to download podcasts for FREE.</p></a>

    <h1>Podcast Directory</h1>

    <span class="sub-headline">FEATURED EPISODE</span>

    <?php
        // $last_episode is an object containing the Latest Featured Episode data
        echo $last_episode_view->display_featured_player();
    ?>

    <span class="sub-headline">FREE KOMANDO PODCASTS</span>

    <?php
        foreach( $podcasts as $podcast ) {
            $podcast_view = new \K2\Podcast\Helper\ShowView($podcast);
            // Show podcast logo graphic and caption
            echo $podcast_view->display_logo();
        }
    ?>

    <div class="podcast-column-rule"></div>

    <span class="sub-headline">Watch or listen to the Kim Komando Show</span>

    <p class="watch-text"><a href="http://www.komando.com/listen"><img class="watch-kim-image" src="<?php echo k2_get_static_url('v2') . '/img/watch-kim.jpg'; ?>"></a>Kim's Club members can <b><a href="http://www.komando.com/listen">watch or listen</a></b> to the Kim Komando Show and Digital Minutes anytime.
    <br><br>Not a Kim's Club member? <a href="https://club.komando.com"><b>Click here to join now and get a 15-day free trial</b></a>.</br>

    <div class="podcast-column-rule"></div>

</section>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
