<?php
/**
 * Editor's Picks Selector
 *
 * Adds a metabox with a checkbox in the Happening Now and other post editing pages.
 * Checking it selects the article to be submitted to Google as an Editor's Pick.
 *
 * User: gilbert
 * Date: 6/5/2015
 * Time: 3:59 PM
 */
include_once(KOM_EDITORS_PICKS_DIR . '/includes/trait-kom-editors-picks-dashboard.php');
include_once(KOM_EDITORS_PICKS_DIR . '/includes/trait-kom-editors-picks-feed.php');

class Kom_Editors_Picks
{
    use Kom_Editors_Picks_Dashboard, Kom_Editors_Picks_Feed;

    private function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_editors_picks_meta_box')); // Adds the meta boxes on the editor
        add_action('save_post', array($this, 'editors_picks_meta_save_details')); // Saving the meta box data
        add_action('future_to_publish', function($post) { remove_action('save_post', array($this, 'editors_picks_meta_save_details')); }); // Prevents the post from losing its feature status when going from scheduled to published
        add_action('wp_dashboard_setup', array($this, "add_editors_picks_list")); // Adds the meta box to the Dashboard
        add_filter('query_vars', [$this, 'query_vars']);
        add_action('init', [$this, 'rewrites']);
        add_action('parse_request', [$this, 'parse_request']);
    }

    /**
     * Initialization
     *
     * @return string
     */
    public static function init()
    {
        static $instance = null;

        if (!$instance) {
            $instance = new Kom_Editors_Picks();
        }

        return $instance;
    }

    /**
     * Create the meta box in the editor
     */
    public function add_editors_picks_meta_box()
    {
        if (current_user_can('edit_posts')) {

            // Editor's Picks checkbox container
            add_meta_box('editors_picks_meta_id', "Editor's Picks on Google", array($this, 'editors_picks_meta_box'), 'post', 'side', 'high');
            add_meta_box('editors_picks_meta_id', "Editor's Picks on Google", array($this, 'editors_picks_meta_box'), 'columns', 'side', 'high');
            add_meta_box('editors_picks_meta_id', "Editor's Picks on Google", array($this, 'editors_picks_meta_box'), 'downloads', 'side', 'high');
            add_meta_box('editors_picks_meta_id', "Editor's Picks on Google", array($this, 'editors_picks_meta_box'), 'apps', 'side', 'high');
            add_meta_box('editors_picks_meta_id', "Editor's Picks on Google", array($this, 'editors_picks_meta_box'), 'cool_sites', 'side', 'high');
            add_meta_box('editors_picks_meta_id', "Editor's Picks on Google", array($this, 'editors_picks_meta_box'), 'tips', 'side', 'high');
            add_meta_box('editors_picks_meta_id', "Editor's Picks on Google", array($this, 'editors_picks_meta_box'), 'buying_guides', 'side', 'high');
            add_meta_box('editors_picks_meta_id', "Editor's Picks on Google", array($this, 'editors_picks_meta_box'), 'charts', 'side', 'high');
            add_meta_box('editors_picks_meta_id', "Editor's Picks on Google", array($this, 'editors_picks_meta_box'), 'happening_now', 'side', 'high');
            add_meta_box('editors_picks_meta_id', "Editor's Picks on Google", array($this, 'editors_picks_meta_box'), 'small_business', 'side', 'high');
            add_meta_box('editors_picks_meta_id', "Editor's Picks on Google", array($this, 'editors_picks_meta_box'), 'new_technologies', 'side', 'high');
        }
    }

    /**
     * Meta box HTML
     */
    public function editors_picks_meta_box()
    {
        global $post;
        $editors_picks = get_post_meta($post->ID, 'editors_picks_meta_id', true); ?>

        <label for="editors-picks-meta"><input type="checkbox" id="editors-picks-meta" name="editors_picks_meta_id"<?php
        if ($editors_picks == '1') {
            echo ' checked';
        } ?>> Select this as an Editor's Picks</label>
    <?php
    }

    /**
     * Save the meta box data
     */
    function editors_picks_meta_save_details($post_id)
    {
        global $post;

        // to prevent metadata or custom fields from disappearing...
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Determine if Editor's Picks box is checked and assign 1 if yes, or 0 if no.
        if (isset($_POST['editors_picks_meta_id'])) {
            $editors_picks = 1;
        } else {
            $editors_picks = 0;
        }

        // Save the data to the post.
        update_post_meta($post_id, 'editors_picks_meta_id', $editors_picks);
    }

    public function query_vars($query_vars)
    {
        $query_vars[] = 'kom_editors_picks';
        return $query_vars;
    }

    public function rewrites()
    {
        add_rewrite_rule('^feed/editors-picks$', 'index.php?kom_editors_picks=1', 'top');
    }

    public function parse_request($wp_query)
    {
        if ($wp_query->query_vars['kom_editors_picks']) {

            header('Content-Type: application/rss+xml; charset=utf-8');
            echo $this->build_feed();
            die;

        }
    }
}
