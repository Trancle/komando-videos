<?php
namespace K2\Podcast\Helper;

class Utils {

    public static function urlize($s) {
        return trim(preg_replace('/(-)\\1+/', '$1', preg_replace('/[^a-z0-9!_-]/','-', strtolower($s) ) ), "-");
    }
}

// ==========================================================================
// page-podcast-episodes and page-podcast-directory

class ShowView {

    private $podcast;

    public function __construct( $podcast ) {
        $this->podcast = $podcast;
    }

    public function display_logo(){
        $title = htmlentities($this->podcast->get_title());
        $slug = Utils::urlize($this->podcast->get_title());
        $logo_url = $this->podcast->get_logo();
        $logo_caption = htmlentities($this->podcast->get_logo_caption());
        $show_id = $this->podcast->get_rss_id();
        return <<<EOD
        <div class="podcast-logo-wrapper">
            <a class="podcast-episodes-page-link" href="/listen/show/$show_id/$slug">
            <img src="$logo_url" class="podcast-logo" alt="$title" /></a><br/>
            <div class="logo-caption">$logo_caption</div>
        </div>
EOD;
    }

    protected function podcast() { return $this->podcast; }
}

class EpisodeView {
    
    private $episode;
    
    public function __construct( $episode ) {
        if($episode instanceof \K2\Podcast\Episode){
            $this->episode = $episode;
            return $this;
        }
        return null;
    }

    protected function episode() { return $this->episode; }

    function episode_exists(){
        return !is_null($this->episode());
    }

// ==========================================================================
    /**
     * @return string The Featured Player html to be echoed on page.
     */
    public function display_featured_player(){
        if(!$this->episode_exists()){
            return "";
        }

        $is_free_weekend = $this->isFreeWeekend();
        if(current_user_can('premium_member') || (isset($_COOKIE["k2-free-weekend-already-subscribed"]) && $_COOKIE["k2-free-weekend-already-subscribed"])){
            $is_free_weekend = false;
        }

       /* $title = htmlentities($this->episode->get_title());
        $title = urlencode(preg_replace('/[^A-Za-z0-9 !?@#$%^&*().]/u','',strip_tags(html_entity_decode($title))));*/
        $title =  htmlspecialchars(urlencode(html_entity_decode($this->episode->get_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');
        $description = htmlentities($this->episode->get_description());
        $episode_id = $this->episode->get_episode_id();
        $episode_url_slug = Utils::urlize($this->episode->get_title());
        $description = $this->episode->get_description();
        $image_url = $this->episode->get_image();
        $download_url = $this->episode->get_download_url();
        $player_url = $download_url;
        $audio_type = $this->episode->get_audio_type();
        $duration = $this->episode->get_duration();
        $pub_date = $this->episode->get_pub_date();

        $show = $this->episode->get_show();
        $podcast_name = $show->get_title();
        $podcast_title = $this->episode->get_title();
        $show_id = $show->get_rss_id();
        $show_title = strtolower(str_replace(' ', '-', $podcast_name));
        $podcast_show_link = "/listen/show/" . $show_id . '/' . $show_title;
        $podcast_url = 'http%3A%2F%2Fwww.komando.com%2Flisten%2Fepisode%2F' . $episode_id;
        $podcast_episode_url = '/listen/episode/' . $episode_id . "/" . $episode_url_slug;
      //If modifying this section, ensure that it does not break the replacePodcastPlayer() function in app/wp-content/plugins/publish-to-apple-news/includes/apple-exporter/class-exporter-content.php
        $featured = '<div class="featured-player-wrapper clearfix"><ul>
        <li class="listen-on-demand-player-root">
        <div class="player-body">
            <div class="featured-player-share">
                <div class="featured-player-share__facebook hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=facebook&url=' . $podcast_url . '&title=' . $title . '&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-facebook-square"></i></a></div>
                <div class="featured-player-share__twitter hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=twitter&url=' . $podcast_url . '&title=' . $title . '&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-twitter-square"></i></a></div>
                <div class="featured-player-share__google hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=googleplus&url=' . $podcast_url . '&title=' . $title . '&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-google-plus-square"></i></a></div>
                <div class="featured-player-share__linkedin hide-mobile"><a href="http://rest.sharethis.com/v1/share/share?destination=linkedin&url=' . $podcast_url . '&title=' . $title . '&api_key=8712971d5b41b8a10aa3703a0fbd4e72" target="_blank"><i class="fa fa-linkedin-square"></i></a></div>
            </div>
            
            <a name="id-' . $episode_id . '" class="header-offset-anchor"></a>
            <div class="rf-image"><a href="' . $podcast_episode_url . '"><img src="' . $image_url . '" /></a></div>
            <div class="rf-info">
                <div class="featured-player-date hide-mobile">' . date('F d, Y', strtotime($pub_date)) . '
                &nbsp;&nbsp;&bullet;&nbsp;&nbsp;<a href="' . $podcast_show_link . '">
                <span class="featured-player-podcast-name"><b>' . $podcast_name . '</b></span></a></div>
                
                <h3 class="featured-player-text">' . $podcast_title . '</h3>
                <span class="featured-player-content">' . $description . '</span>
    
            </div>
            <div class="featured-download ' . ($is_free_weekend ? 'hide' : '') . '"><a href="' . $download_url . '">
                <span class="featured-player-download">DOWNLOAD <i class="fa fa-download hide-mobile"></i></span></a>
            </div>
        </div>
        ' .
        ($is_free_weekend ? $this->getFreeWeekendEmailCollector() : '')
        . '
        <div class="rf-listen ' . ($is_free_weekend ? 'hide' : '') . '"><!-- themes/k2/includes/functions-podcasts.php -->
            <div class="featured-player audio-player">
                <audio id="latest-podcast" class="featured-player-source" preload="none"><source src="' . $download_url . '" type="'. $audio_type . '"></audio>
                <span class="featured-player-play-button audio-player-play"><i class="fa fa-play"></i></span>
                <span class="featured-player-stop-button audio-player-stop"><i class="fa fa-pause"></i></span>
                <span class="featured-player-spinner audio-player-spinner"><i class="fa fa-spinner fa-spin"></i></span>
            </div>
            <span class="featured-player-listen">Listen</span>
            <div class="default-bar">
                <div class="progress-bar"></div>
            </div>
            <span class="featured-player-runtime"><span class="current-index">0:00</span> / <span class="duration">' . sprintf("%02d",floor(( $this->episode->get_duration()/60) % 60)) . ':' . sprintf("%02d",floor( $this->episode->get_duration() % 60)) . '</span></span>
        </div>
        </li></ul></div>';

        return $featured;
    }

    public function isFreeWeekend(){
        $free_weekend_episodes = get_option("k2_free_weekend_podcast_ids");
        if(empty($free_weekend_episodes)){
            $free_weekend_episodes = array();
        }
        else{
            $free_weekend_episodes = explode(',', $free_weekend_episodes);
        }
        return in_array($this->episode->get_episode_id(), $free_weekend_episodes);
    }

    public function getFreeWeekendEmailCollector(){

        $gray_bar_image = k2_get_static_url('v2') . '/img/kims-club-dont-miss-the-fun.jpg';

        $html = <<<HTML
<div id="subscribe_free_weekend_box">
<div class="inner">
    <span class="title">Listen to My Show for Free Now!</span>
    <div class="email-error hide"></div>
    <div class="success-message hide"></div>
    <div class="loading-message hide">Please wait while we verify your email address...</div>
    <div class="free-weekend-email-subscribe-input"><input type="email" name="email_free_weekend_podcast" id="email_free_weekend_podcast" placeholder="example@komando.com" title="Enter your email address"></div>
    <div class="free-weekend-email-subscribe-button"><button id="subscribe_free_weekend_button">Listen for Free!</button></div>
</div>
<div class="free-weekend-gray-bar"><img src="$gray_bar_image"></div>
</div>
HTML;

        return $html;
    }

// ==========================================================================
    function display_feed_player(){
        if(!$this->episode_exists()){
            return "";
        }

        // Receives an object with all necessary episode data in it
        // Returns one feed player html to be echoed on page

        $episode = $this->episode();

        $player = '<li><div class="feed-player audio-player">
        <audio class="feed-player-source" preload="none"><source src="' . $episode->get_download_url() . '" type="' . $episode->get_audio_type() . '"></audio>
        <span class="feed-player-play-button audio-player-play"><i class="fa fa-play"></i></span>
        <span class="feed-player-stop-button audio-player-stop"><i class="fa fa-pause"></i></span>
        <span class="feed-player-spinner audio-player-spinner"><i class="fa fa-spinner fa-spin"></i></span></div>
        <a name="id-' . $episode->get_episode_id() . '" class="header-offset-anchor"></a>
        <div class="feed-player-text">' . $episode->get_title() . '</div>
        <div class="feed-player-runtime">' . sprintf("%02d",floor(( $episode->get_duration()/60) % 60)) . ':' . sprintf("%02d",floor( $episode->get_duration() % 60)) . ' </div>
        <div class="feed-player-date">' . date('m/d/y', strtotime($episode->get_pub_date())) . '</div>
        <div class="feed-download"><a href="' . $episode->get_download_url() . '">
        <span class="feed-player-download-button"><i class="fa fa-download"></i></span></a>
        </div></li>';

        return $player;
    }
}


// ==========================================================================

