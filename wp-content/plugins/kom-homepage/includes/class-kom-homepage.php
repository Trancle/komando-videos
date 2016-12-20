<?php

// Including traits
include_once(KOM_HOMEPAGE_DIR . '/includes/trait-kom-homepage-admin.php');
include_once(KOM_HOMEPAGE_DIR . '/includes/trait-kom-homepage-featured-links.php');
include_once(KOM_HOMEPAGE_DIR . '/includes/trait-kom-homepage-grid-links.php');
include_once(KOM_HOMEPAGE_DIR . '/includes/trait-kom-homepage-helpers.php');

class Kom_Homepage
{
    use Kom_Homepage_Admin, Kom_Homepage_Featured_Links, Kom_Homepage_Grid_Links, Kom_Homepage_Helpers;

    private function __construct()
    {
        date_default_timezone_set(get_option('timezone_string'));
        add_action('kom_home_featured_link_hook', array($this, 'cron'));
        add_action('admin_menu', array($this, 'admin_page_menu'));
        add_action('admin_enqueue_scripts', array($this, 'admin_page_scripts'));

	    add_action('k2_ajax_kom_homepage_fetch_post_data', array($this, 'fetch_post_data')); // Allows AJAX for fetching post data on the admin page
        add_action('k2_ajax_nopriv_kom_homepage_fetch_post_data', array($this, 'fetch_post_data')); // Allows AJAX for fetching post data on the admin page

	    add_action('k2_ajax_kom_homepage_grid', array($this, 'show_grid_links')); // This handles the home grid ajax
	    add_action('k2_ajax_nopriv_kom_homepage_grid', array($this, 'show_grid_links')); // This handles the home grid ajax for non-admin users

	    add_action('k2_ajax_kom_homepage_clear_cache', array($this, 'clear_cache')); // This handles the home page cache clear
	    add_action('k2_ajax_nopriv_kom_homepage_clear_cache', array($this, 'clear_cache')); // This handles the home page cache clear for non-admin users

	    add_action('add_meta_boxes', array($this, 'add_feature_meta')); // Adds the meta boxes on the editor
	    add_action('save_post', array($this, 'feature_meta_save_details')); // Saving the meta box data
	    add_action('future_to_publish', function($post) { remove_action('save_post', array($this, 'feature_meta_save_details')); }); // Prevents the post from losing its feature status when going from scheduled to published
    }

    /**
     * Vroom, vroom
     * 
     * @return string 
     */
    public static function init()
    {
        static $instance = null;

        if (!$instance) {
            $instance = new Kom_Homepage();
        }

        return $instance;
    }

    /**
     * When the plugin is activated we need to do stuff
     */
    public static function activation()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }
        wp_schedule_event('1412092800', 'fiver', 'kom_home_featured_link_hook');

        // Creates the data for the text boxes if they don't exist yet.
        $text_boxes = get_option('kom_homepage_custom_links');
        if (empty($text_boxes)) {
            for ($i = 0; $i < 5; $i++) {
                $link = [
                    'text' => '',
                    'link' => '',
                    'image' => '',
                    'start' => '',
                    'end' => '',
                    'scheduled' => '',
                    'active' => '',
                    'position' => '',
                ];

                $blank_links[] = $link;
            }
            update_option('kom_homepage_custom_links', $blank_links);
        }
    }

    /**
     * Clean up after deactivation
     */
    public static function deactivation() 
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }
        $featured_next = wp_next_scheduled('kom_home_featured_link_hook');
        wp_unschedule_event($featured_next, 'kom_home_featured_link_hook');
    }

    /**
     * Create the menu item in the admin
     */
    public function admin_page_menu() 
    {
        add_menu_page('Home Featured Links', 'Home Link Boxes', 'edit_others_posts', 'kom-homepage-admin', array($this, 'admin_init'), '', 40);
    }

	/**
	 * Create the meta boxes in the editor
	 */
	public function add_feature_meta()
	{
		if(current_user_can('edit_posts')) {
			add_meta_box('feature_meta_id', 'Feature this Article?', array($this, 'feature_meta_box'), 'post', 'side', 'high');
			add_meta_box('feature_meta_id', 'Feature this Article?', array($this, 'feature_meta_box'), 'columns', 'side', 'high');
			add_meta_box('feature_meta_id', 'Feature this Article?', array($this, 'feature_meta_box'), 'downloads', 'side', 'high');
			add_meta_box('feature_meta_id', 'Feature this Article?', array($this, 'feature_meta_box'), 'apps', 'side', 'high');
			add_meta_box('feature_meta_id', 'Feature this Article?', array($this, 'feature_meta_box'), 'cool_sites', 'side', 'high');
			add_meta_box('feature_meta_id', 'Feature this Article?', array($this, 'feature_meta_box'), 'tips', 'side', 'high');
			add_meta_box('feature_meta_id', 'Feature this Article?', array($this, 'feature_meta_box'), 'buying_guides', 'side', 'high');
			add_meta_box('feature_meta_id', 'Feature this Article?', array($this, 'feature_meta_box'), 'charts', 'side', 'high');
			add_meta_box('feature_meta_id', 'Feature this Article?', array($this, 'feature_meta_box'), 'happening_now', 'side', 'high');
            add_meta_box('feature_meta_id', 'Feature this Article?', array($this, 'feature_meta_box'), 'small_business', 'side', 'high');
            add_meta_box('feature_meta_id', 'Feature this Article?', array($this, 'feature_meta_box'), 'new_technologies', 'side', 'high');
		}
	}

	/**
	 * Meta box HTML
	 */
	public function feature_meta_box()
	{
		global $post;
		$feature_data = get_post_meta($post->ID, 'feature_meta_id', true);
		?>
		<label for="featured-meta"><input type="checkbox" id="featured-meta" name="feature_meta_id" <?php if($feature_data == '1') { echo 'checked'; } ?>> Make this featured</label>
		<?php
	}

	/**
	 * Save the meta box data
	 */
	function feature_meta_save_details($post_id)
	{
		global $post;

		// to prevent metadata or custom fields from disappearing...
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return;
		}

		if(isset($_POST['feature_meta_id'])) {
			$feature_data = 1;
		} else {
			$feature_data = 0;
		}

		update_post_meta($post_id, 'feature_meta_id', $feature_data);
	}

    /**
     * Runs every five minutes to unschedule things that need unschedulin'
     */
    public function cron()
    {
        $clear_cache = $updated_links = null;
        $time = new DateTime('now', new DateTimeZone('America/Phoenix'));
        $time = strtotime($time->format('m/d/Y H:i'));

        $links = get_option('kom_homepage_custom_links');

        foreach ($links as $link) {
            
            if($link['scheduled'] && empty($link['active']) && $time >= $link['start'] && $time < $link['end']) {

                $link['scheduled'] = 1;
                $link['active'] = 1;

                $clear_cache = true;

            } else if($link['scheduled'] && empty($link['active']) && $time <= $link['start']) {

	            $link['scheduled'] = 1;
	            $link['active'] = 0;

	            $clear_cache = true;

            } else if($link['active'] && $time >= $link['end']) {
                
                $link['scheduled'] = 0;
                $link['active'] = 0;

                $clear_cache = true;

            } else if($link['active']) {

                $link['scheduled'] = 1;
                $link['active'] = 1;

            } else {
                
                $link['scheduled'] = 0;
                $link['active'] = 0;
            }

            $updated_links[] = $link;
        }

        update_option('kom_homepage_custom_links', $updated_links);

        if($clear_cache) {
            self::clear_cache();
        }
    }

    /**
     * Clear the cache on the home page
     */
    public function clear_cache()
    {
        $transients = ['kom_homepage_featured_links', 'kom_homepage_happening_now', 'news_stories', 'komando_tweets', 'kom_homepage_grid16', 'kom_homepage_grid26', 'kom_homepage_grid36', 'kom_homepage_grid46'];

        foreach ($transients as $transient) {
            delete_transient($transient);
        }
    }
}