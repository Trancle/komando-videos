<?php
/**
 * Plugin Name: Video Embed Plugin For Youtube
 * Plugin URI: http://example.com
 * Description: Embed youtube video with some additional params.
 * Version: 1.0.0
 * Author: Mahabub Alam
 * Author URI: http://example.com
 * License: GPL2
 */

require_once 'controller/embed_video_controller.php';
require_once plugin_dir_path(__FILE__) . 'install.php';

register_activation_hook(__FILE__, 'embed_video_install');

/* Add Stylesheet */

function embed_video_style()
{
    wp_enqueue_style('embed_video_style', plugin_dir_url(__FILE__) . '/css/embed_video.css');
}

add_action('admin_enqueue_scripts', 'embed_video_style');

/* Add Script */

add_action('admin_enqueue_scripts', 'embed_video_script');
function embed_video_script()
{
    wp_enqueue_script('embed_video', plugin_dir_url(__FILE__) . '/js/embed_video.js', array('jquery'), '1.0', true);
}


add_action('admin_menu', 'embed_video_menu');

function embed_video_menu()
{
    add_menu_page('Embed Video', 'Embed Video', 'administrator', 'embed_video_content', 'embed_video_page', 'dashicons-format-video');
}

function embed_video_page()
{
    $objEmbedVideo = new EmbedVideoController();
    $objEmbedVideo->handle_request();
}