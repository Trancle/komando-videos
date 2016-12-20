<?php
/*
Plugin Name: Kom Article Videos
Plugin URI: http://www.komando.com
Description: Allows supstituting a video for a splash image in an article.
Version: 1.0
Author: Tyger Gilbert
Author URI: http://www.tygergilbert.com

Date: 11/30/2015
Time: 4:44 PM

Defines the class that adds a field to the Article Edit page
which will contain the URL to a video that replaces the image
from Shutterstock or wherever at the top of the article.
*/

//include_once(KOM_ARTICLE_VIDEOS_DIR . '/includes/trait-kom-editors-picks-dashboard.php');

class Kom_Article_Videos
{
    private function __construct() {
        add_action('add_meta_boxes', array($this, 'add_article_videos_meta_box')); // Adds the meta boxes on the editor
        add_action('save_post', array($this, 'article_videos_meta_save_details')); // Saving the meta box data
        add_filter('get_youtube_embed_url', array($this, 'article_videos_get_youtube_embed_url')); // Saving the meta box data
        add_filter('get_vimeo_embed_url', array($this, 'article_videos_get_vimeo_embed_url'));
        add_action('future_to_publish', function($post) { remove_action('save_post', array($this, 'article_videos_meta_save_details')); }); // Prevents the post from losing its feature status when going from scheduled to published
        add_filter('query_vars', [$this, 'query_vars']);
        add_action('init', [$this, 'rewrites']);
    }

    /**
     * Initialization
     *
     * @return string
     */
    public static function init() {

        static $instance = null;

        if (!$instance) {
            $instance = new Kom_Article_Videos();
        }

        return $instance;
    }

    /**
     * Create the meta box in the editor page
     */
    public function add_article_videos_meta_box() {

        if (current_user_can('edit_posts')) {

            // Editor's Picks checkbox container
            add_meta_box('article_videos_meta_id', "Add Video to Article", array($this, 'article_videos_meta_box'), 'post', 'side', 'low');
            add_meta_box('article_videos_meta_id', "Add Video to Article", array($this, 'article_videos_meta_box'), 'columns', 'side', 'low');
            add_meta_box('article_videos_meta_id', "Add Video to Article", array($this, 'article_videos_meta_box'), 'downloads', 'side', 'low');
            add_meta_box('article_videos_meta_id', "Add Video to Article", array($this, 'article_videos_meta_box'), 'apps', 'side', 'low');
            add_meta_box('article_videos_meta_id', "Add Video to Article", array($this, 'article_videos_meta_box'), 'cool_sites', 'side', 'low');
            add_meta_box('article_videos_meta_id', "Add Video to Article", array($this, 'article_videos_meta_box'), 'tips', 'side', 'low');
            add_meta_box('article_videos_meta_id', "Add Video to Article", array($this, 'article_videos_meta_box'), 'buying_guides', 'side', 'low');
            add_meta_box('article_videos_meta_id', "Add Video to Article", array($this, 'article_videos_meta_box'), 'charts', 'side', 'low');
            add_meta_box('article_videos_meta_id', "Add Video to Article", array($this, 'article_videos_meta_box'), 'happening_now', 'side', 'low');
            add_meta_box('article_videos_meta_id', "Add Video to Article", array($this, 'article_videos_meta_box'), 'small_business', 'side', 'low');
            add_meta_box('article_videos_meta_id', "Add Video to Article", array($this, 'article_videos_meta_box'), 'new_technologies', 'side', 'low');
        }
    }

    /**
     * Meta box HTML
     * Displays the meta box
     */
    public function article_videos_meta_box() {

        global $post;
        $VideoURL = get_post_meta($post->ID, 'article_videos_meta_url', true); ?>

        <label for="article-videos-meta-url">Link to Video URL:</label><br><input type="text" id="article-videos-meta-url" name="article_videos_meta_url" value="<?php echo $VideoURL; ?>" size="25">
        <a href="https://www.komando.com/wp-admin/post.php?post=<?php the_ID(); ?>&action=edit#"><i class="fa fa-trash-o"></i></a><br>(Minimum 720p)
        <p>This video will replace the Shutterstock image on the main article page only.</<p>
        <p>May be a YouTube.com (right-click and use "Copy video URL"), a Vimeo or a BitGravity.com (Komando) video URL. Leave empty if no video will be shown.</p>
        <p>A Shutterstock image (or other source) is still required.</p>
        <?php
    }

    /**
     * Get a YouTube embed url and make it an embed url if it is not already one
     */
    public function article_videos_get_youtube_embed_url($url){
        $youtube_url = 'https://www.youtube.com/embed';
        if(strpos($url, "watch?v=") !== false){
            $vid = parse_url($url, PHP_URL_QUERY);
            $vid =  str_replace("v=", "/", $vid);
            $url = $youtube_url . $vid;
        }
        elseif(strpos($url, "youtu.be") !== false){
            $vid = parse_url($url, PHP_URL_PATH);
            $url = $youtube_url . $vid;
        }

        return $url;
    }


    /**
     * Get a Vimeo embed url and make it an embed url if it is not already one
     */
    public function article_videos_get_vimeo_embed_url($url){
        $vimeo_url = "https://player.vimeo.com/video/";
        $id = extract_vimeo_video_id($url);
        return !empty($id) ? $vimeo_url . $id : $url;
    }

    /**
     * Save the meta box data
     */
    public function article_videos_meta_save_details($post_id) {

        global $post;

        // to prevent metadata or custom fields from disappearing...
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Save the data to the post.
        update_post_meta($post_id, 'article_videos_meta_url', $_POST['article_videos_meta_url']);
    }

    /**
     * Retrieve the $query_vars array
     * @param $query_vars
     * @return array
     */
    public function query_vars($query_vars) {

        $query_vars[] = 'article_videos_meta_url';
        return $query_vars;
    }

    public function rewrites() {
        add_rewrite_rule('^article-videos$', 'index.php?kom_article-videos=1', 'top');
    }
}

/**
 * Determine if an image or a video is supposed
 * to be the splash for the article
 */
function splash_type( $splash_url ) {

    // Return the source of the video, or default to image
    if ((substr_count($splash_url, 'youtube') > 0) OR (substr_count($splash_url, 'youtu.be') > 0)) {
        return 'youtube';
    } elseif (substr_count($splash_url, 'bitgravity') > 0) {
        return 'bitgravity';
    } elseif (substr_count($splash_url, 'vimeo') > 0) {
        return 'vimeo';
    } else {
        return 'image';
    }
}

