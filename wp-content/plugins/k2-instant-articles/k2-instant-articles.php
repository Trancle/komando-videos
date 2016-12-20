<?php
/*
 * Plugin Name:       K2 Facebook Instant Articles
 * Description:       This plugin allows you to easily publish and manage your content directly from WordPress for Facebook Instant Articles (FBIA).
 * Version:           1.0
 * Author:            Lisdanay Dominguez
 */

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo  'I\'m just a plugin, not much I can do when called directly.';
    exit;
}

// Require define plugin dir path
define( 'K2IA__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

// Require needed files
$include_files = array(
        "includes/k2-instant-article-tools.php",
        "includes/k2-instant-articles-core.php",         // The actual core of the plugin
        "includes/k2-instant-articles-settings.php",     // The settings options needed for the plugin
        "includes/k2-instant-articles-filters.php",      // The filters needed to morph the code for instant articles
        "includes/k2-instant-articles-options.php"
        ); 
foreach( $include_files as $file ) {
    require_once plugin_dir_path( __FILE__ ) . $file;
}

K2\InstantArticlesCore::initialize();