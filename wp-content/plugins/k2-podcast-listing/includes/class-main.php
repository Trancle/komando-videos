<?php

namespace K2\Podcast;

include_once(K2_PODCAST_LISTING_CONFIG_DIR . '/includes/trait-getter-setter.php');
include_once(K2_PODCAST_LISTING_CONFIG_DIR . '/includes/class-podcast-rss-lookup-exception.php');
include_once(K2_PODCAST_LISTING_CONFIG_DIR . '/includes/class-long-term-data.php');
include_once(K2_PODCAST_LISTING_CONFIG_DIR . '/includes/class-listing.php');
include_once(K2_PODCAST_LISTING_CONFIG_DIR . '/includes/class-show.php');
include_once(K2_PODCAST_LISTING_CONFIG_DIR . '/includes/class-episode.php');
include_once(K2_PODCAST_LISTING_CONFIG_DIR . '/includes/class-admin.php');

class Main
{
    public function __construct()
    {
		add_action('admin_menu', [$this, 'admin_page_menu']);
		add_action('admin_enqueue_scripts', [$this, 'admin_page_scripts']);
	}

    /**
     * When the plugin is activated we need to do stuff
     */
    public function activation()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }
    }

    /**
     * Clean up after deactivation
     */
    public function deactivation()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }
    }

    /**
     * Create the menu item in the admin
     */
    public function admin_page_menu()
    {
       	add_menu_page('Podcast Listing Configuration', 'Podcast Listing Configuration', 'edit_posts', 'k2-podcast-listing', array(new \K2\Podcast\Admin(), 'admin_init'), '', 41);
    }

    /**
     * Admin page support scripts
     */
    public function admin_page_scripts() {
        if (isset($_GET['page']) && $_GET['page'] == 'k2-podcast-listing') {
            wp_enqueue_media();
            wp_enqueue_script('jquery-ui-core');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('jquery-ui-slider');
            wp_enqueue_script('jquery-effects-core');
            wp_enqueue_script('jquery-effects-highlight');
            wp_register_style('jquery-ui', '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/smoothness/jquery-ui.css');
            wp_enqueue_style('jquery-ui');
            wp_enqueue_script('jquery-ui-sortable');
        }
    }

}
