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
 * 
 * This is the front-end display code
 */

trait K2_Comparison_Chart_Display
{
    /* todo: remove below and replace with actual functions */

    public function post_has_chart($post_id = '')
    {
        // See if a chart is specified for this article.
        // Returns TRUE if there is, FALSE if not.
        // Used in single-charts.php and in next function below.

        if (empty($post_id)) {
            // No Article ID No. was passed.
            return false;
        }
        $custom_fields = get_post_custom($post_id);

        return !is_null($custom_fields['comparison_chart']) && //Not null
        !empty($custom_fields['comparison_chart']) && // Not empty
        isset($custom_fields['comparison_chart'][0]) && // has 0th element
        "[]" != $custom_fields['comparison_chart'][0]; // 0th element not empty brackets

    }

    public function display_article_comparison_chart_html($post_id = '')
    {
        // Creates html to display the chart and Returns it.
        // Used in single-charts.php where an a chart would be inserted.
        if (empty($post_id) || !self::post_has_chart($post_id)) {
            // No Article ID No. was passed.
            return false;
        }
        // Get the chart information

        $custom_fields = get_post_custom($post_id); // Array of all custom fields
        $comparison_chart_json = base64_decode($custom_fields['comparison_chart'][0]);
        // JSON decode the array
        $comparison_chart = json_decode($comparison_chart_json, true);
        $comparison_chart_json = addslashes(json_encode($comparison_chart, JSON_UNESCAPED_UNICODE));

        if(isset($comparison_chart["options"]) && (!isset($comparison_chart["options"]["chart_enabled"]) || !$comparison_chart["options"]["chart_enabled"])){
            return false;
        }

        if(isset($comparison_chart["rows"]) && 1 == sizeof($comparison_chart["rows"]) && isset($comparison_chart["rows"][0]) && 1 == sizeof($comparison_chart["rows"][0]["cols"])){
            return false;
        }

        // Compose the HTML for the chart
        $chart = '<div class="comparison-chart-outer-wrapper">
            <script type="text/javascript">
                var komando_comparison_chart_data_json = \'' . str_replace("'", "&#39;", $comparison_chart_json) . '\';
                var komando_comparison_chart_data = JSON.parse(komando_comparison_chart_data_json);
            </script>
            <div class="comparison-chart-inner-wrapper">
              <div class="horz-gesture gesture"><img src="' . k2_get_static_url('v2') . '/img/horz_scroll_gesture.png" alt="Scroll left and right"></div>
              <div class="vert-gesture gesture"><img src="' . k2_get_static_url('v2') . '/img/vert_scroll_gesture.png" alt="Scroll up and down"></div>
              <div class="comparison-chart-left-column">
                <div class="comparison-chart-left-header-cell"></div> 
                <div class="comparison-chart-left-cells"></div> 
              </div>            
              <div class="comparison-chart-right-column">
                <div class="comparison-chart-header-row-container">
                  <div class="comparison-chart-right-header-row"></div> 
                </div>           
                <div class="comparison-chart-right-row-container"></div>            
              </div>            
            </div>
        ';

        return $chart;
    }

}