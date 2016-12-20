<?php
/*
Plugin Name: Kom Article Gallery
Plugin URI: http://www.komando.com
Description: Allows supstituting an image gallery for a splash image in an article.
Version: 1.0
Author: Tyger Gilbert
Author URI: http://www.tygergilbert.com

Date: 4/22/2016
Time: 10:23 AM

Defines a class that adds a section to the Article Edit page
which will contain the data for a gallery that replaces the
image from Shutterstock or wherever at the top of the article.
*/

// Including traits
include_once(KOM_ARTICLE_GALLERY_DIR . '/includes/trait-kom-image-gallery-display.php');
include_once(KOM_ARTICLE_GALLERY_DIR . '/includes/trait-kom-image-gallery-admin.php');

class Kom_Article_Gallery
{
    use Kom_Image_Gallery_Admin;
    use Kom_Image_Gallery_Display;

    private function __construct() {
        add_action('add_meta_boxes', array($this, 'add_image_gallery_meta_box')); // Adds the meta box on the editor
        add_action('save_post', array($this, 'image_gallery_meta_save_details')); // Saving the meta box data

        add_image_size('gallery_index_image', 60, 45);
        add_filter( 'image_size_names_choose', array($this, 'add_image_name') );

        // Prevents the post from losing its gallery status when going from scheduled to published
        add_action('future_to_publish', function($post) { remove_action('save_post', array($this, 'image_gallery_meta_save_details')); });
        add_action('init', [$this, 'rewrites']);
    }

    public function add_image_name($sizes)
    {
        return array_merge( $sizes, array(
            'gallery_index_image' => __('Gallery Index Image'),
        ) );
    }

    /**
     * Initialization
     *
     * @return string
     */
    public static function init() {

        static $instance = null;

        if (!$instance) {
            $instance = new Kom_Article_Gallery();
        }

        return $instance;
    }

    /**
     * Create the meta box in the editor
     */
    public function add_image_gallery_meta_box()
    {
        if (current_user_can('edit_posts')) {

            // Editor's Picks checkbox container
            add_meta_box('image_gallery_meta_id', 'Article Image Gallery', array($this, 'image_gallery_meta_box'), 'post', 'normal', 'default');
            add_meta_box('image_gallery_meta_id', 'Article Image Gallery', array($this, 'image_gallery_meta_box'), 'columns', 'normal', 'default');
            add_meta_box('image_gallery_meta_id', 'Article Image Gallery', array($this, 'image_gallery_meta_box'), 'downloads', 'normal', 'default');
            add_meta_box('image_gallery_meta_id', 'Article Image Gallery', array($this, 'image_gallery_meta_box'), 'apps', 'normal', 'default');
            add_meta_box('image_gallery_meta_id', 'Article Image Gallery', array($this, 'image_gallery_meta_box'), 'cool_sites', 'normal', 'default');
            add_meta_box('image_gallery_meta_id', 'Article Image Gallery', array($this, 'image_gallery_meta_box'), 'tips', 'normal', 'default');
            add_meta_box('image_gallery_meta_id', 'Article Image Gallery', array($this, 'image_gallery_meta_box'), 'buying_guides', 'normal', 'default');
            add_meta_box('image_gallery_meta_id', 'Article Image Gallery', array($this, 'image_gallery_meta_box'), 'happening_now', 'normal', 'default');
            add_meta_box('image_gallery_meta_id', 'Article Image Gallery', array($this, 'image_gallery_meta_box'), 'small_business', 'normal', 'default');
            add_meta_box('image_gallery_meta_id', 'Article Image Gallery', array($this, 'image_gallery_meta_box'), 'new_technologies', 'normal', 'default');
        }
    }

    public function rewrites() {
        add_rewrite_rule('^article-gallery$', 'index.php?kom_article_gallery=1', 'top');
    }
}
