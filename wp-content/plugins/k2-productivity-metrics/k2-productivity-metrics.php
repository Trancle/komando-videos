<?php
/*
 * Plugin Name:       K2 Author/Editor Productivity Metrics
 * Description:       This plugin allows as an administrator or a manager be able to view statistics about how much time is spent writing articles and the time-value of each article In order to reduce costs and maximize revenue.
 * Version:           1.0
 * Author:            Lisdanay Dominguez
 */

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
    echo  'I\'m just a plugin, not much I can do when called directly.';
    exit;
}

// Require define plugin dir path
define( 'K2PM__PLUGIN_DIR', '/wp-content/plugins/k2-productivity-metrics/');

    /**
     * #REVIEW:
     * I'm not sure whether all of the following files are being loaded globally, but if they are, they should only be loaded in two places, as far as I can tell:
     * In the actual productivity metrics reporting pages and in the actual post/article creation/editing pages.
     * We should not be loading them up on pages where they are not necessary.
     */

// Require needed files
$include_files = [
    "includes/k2-productivity-metrics-controller.php",  // The controller of the plugin
    "includes/k2-productivity-metrics-core.php",        // The actual core of the plugin
    "includes/k2-productivity-metrics-settings.php"
];
foreach( $include_files as $file ) {
    require_once plugin_dir_path( __FILE__ ) . $file;
}

new ProductivityMetricsCore();