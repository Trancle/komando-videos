<?php
/**
 * Plugin Name: K2 Comparison Chart
 * Plugin URI: http://www.komando.com
 * Description: Allows creation of a comparison chart within an article or page.
 * Version 0.1
 * Author: Yossi Wolfe
 * Date: 5/19/2016
 * Time: 9:42 AM
 *
 * File: k2-comparison-chart.php
 * Class to add a section in the Edit Article page (post.php) for adding
 * a comparison chart on the article page itself
 */

// Including traits
include_once(K2_COMPARISON_CHART_DIR . '/includes/trait-k2-comparison-chart-display.php');
include_once(K2_COMPARISON_CHART_DIR . '/includes/trait-k2-comparison-chart-admin.php');

class K2_Comparison_Chart
{
    use K2_Comparison_Chart_Admin;
    use K2_Comparison_Chart_Display;
    

    private function __construct() {
        add_action('add_meta_boxes', array($this, 'add_comparison_chart_meta_box')); // Adds the meta box on the editor
        add_action('save_post', array($this, 'comparison_chart_meta_save_details')); // Saving the meta box data

        /* todo: check what this is for */
//        add_image_size('gallery_index_image', 60, 45);
//        add_filter( 'image_size_names_choose', array($this, 'add_image_name') );

        // Prevents the post from losing its comparison chart status when going from scheduled to published
        add_action('future_to_publish', function($post) { remove_action('save_post', array($this, 'comparison_chart_meta_save_details')); });
        add_action('init', [$this, 'rewrites']);
    }

    /* todo: check what this is for */
/*    public function add_image_name($sizes)
    {
        return array_merge( $sizes, array(
            'gallery_index_image' => __('Gallery Index Image'),
        ) );
    }
*/
    /**
     * Initialization
     *
     * @return string
     */
    public static function init() {

        static $instance = null;

        if (!$instance) {
            $instance = new K2_Comparison_Chart();
        }

        return $instance;
    }

    /**
     * Create the meta box in the editor
     */
    public function add_comparison_chart_meta_box()
    {
        if (current_user_can('edit_posts')) {

            // Editor's Picks checkbox container
            add_meta_box('comparison_chart_meta_id', 'Article Comparison Chart', array($this, 'comparison_chart_meta_box'), 'post', 'normal', 'default');
            add_meta_box('comparison_chart_meta_id', 'Article Comparison Chart', array($this, 'comparison_chart_meta_box'), 'columns', 'normal', 'default');
            add_meta_box('comparison_chart_meta_id', 'Article Comparison Chart', array($this, 'comparison_chart_meta_box'), 'downloads', 'normal', 'default');
            add_meta_box('comparison_chart_meta_id', 'Article Comparison Chart', array($this, 'comparison_chart_meta_box'), 'apps', 'normal', 'default');
            add_meta_box('comparison_chart_meta_id', 'Article Comparison Chart', array($this, 'comparison_chart_meta_box'), 'cool_sites', 'normal', 'default');
            add_meta_box('comparison_chart_meta_id', 'Article Comparison Chart', array($this, 'comparison_chart_meta_box'), 'tips', 'normal', 'default');
            add_meta_box('comparison_chart_meta_id', 'Article Comparison Chart', array($this, 'comparison_chart_meta_box'), 'buying_guides', 'normal', 'default');
            add_meta_box('comparison_chart_meta_id', 'Article Comparison Chart', array($this, 'comparison_chart_meta_box'), 'happening_now', 'normal', 'default');
            add_meta_box('comparison_chart_meta_id', 'Article Comparison Chart', array($this, 'comparison_chart_meta_box'), 'small_business', 'normal', 'default');
            add_meta_box('comparison_chart_meta_id', 'Article Comparison Chart', array($this, 'comparison_chart_meta_box'), 'charts', 'normal', 'default');
            add_meta_box('comparison_chart_meta_id', 'Article Comparison Chart', array($this, 'comparison_chart_meta_box'), 'new_technologies', 'normal', 'default');
        }
    }

    public function rewrites() {
        add_rewrite_rule('^comparison-chart$', 'index.php?k2_comparison_chart=1', 'top'); //todo: what is this for?
    }
}
