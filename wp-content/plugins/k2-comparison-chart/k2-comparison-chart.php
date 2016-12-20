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

// Set up the defines
define('K2_COMPARISON_CHART_DIR', dirname(__FILE__));
define('K2_COMPARISON_CHART_URL', plugins_url(null, __FILE__));

// For Activation and deactivation of the plugin
register_activation_hook(__FILE__, array('K2_Comparison_Chart', 'activation'));
register_deactivation_hook(__FILE__, array('K2_Comparison_Chart', 'deactivation'));

// Init the plugin
if (class_exists('K2_Comparison_Chart') || include_once(K2_COMPARISON_CHART_DIR . '/includes/class-k2-comparison-chart.php')) {
    K2_Comparison_Chart::init();
}

// Kills plugin update lookup todo: find out why this is here
function hidden_plugin_k2_comparison_chart($r, $url) {
    if (0 !== strpos($url, 'http://api.wordpress.org/plugins/update-check'))
        return $r; // Not a plugin update request. Bail immediately.
    $plugins = unserialize($r['body']['plugins']);
    unset($plugins->plugins[plugin_basename(__FILE__)]);
    unset($plugins->active[array_search(plugin_basename(__FILE__), $plugins->active)]);
    $r['body']['plugins'] = serialize($plugins);
    return $r;
}

add_filter('http_request_args', 'hidden_plugin_k2_comparison_chart', 5, 2); //todo: find out why this line is here
