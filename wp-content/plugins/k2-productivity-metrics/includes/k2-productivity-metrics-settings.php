<?php

class K2_productivity_metrics_settings {

    /**
     * __construct function.
     */
    public function __construct(){
        // Actions
        add_action( 'admin_menu'    , array($this, 'add_theme_settings_page') );
        add_action( 'admin_notices' , array($this, 'theme_settings_admin_notices') );
    }

    /**
     * Register the Theme Settings Page. add_theme_settings_page function.
     * @return void
     */
    public function add_theme_settings_page(){
        $theme_page = add_options_page( __("K2 Productivity Metrics", "PM"), __("K2 Productivity Metrics", "PM"), 'view_productivity_metrics', 'k2pm_settings_page', array($this, 'lh_settings_page') );
    }

    /**
     * lh_settings_page function.
     * @return void
     */
    public function lh_settings_page(){
        // $myListTable = new K2\ProductivityMetricsCore();
        //ng-view
        /**
         * #REVIEW: ng-controller ="metricsCtrl" would be better as ng-controller="metricsCtrl"
         */
        ?>
        <div ng-controller ="metricsCtrl">
            <ng-include src="'/wp-content/plugins/k2-productivity-metrics/partials/metrics.html'"> </ng-include>
        </div>
        <?php
    }

    /**
     * theme_settings_admin_notices function.
     * @return void
     */
    public function theme_settings_admin_notices(){
        if(isset($_GET['page']) && $_GET['page'] != "lh_theme_settings"){
            return;
        }

        if(isset($_GET['settings-updated']) && $_GET['settings-updated'] == true){
            add_settings_error('k2pm_settings_page', 'k2pm_settings_page', __("Successfully updated.", 'PM') , 'updated');
        }

        settings_errors('k2pm_settings_page');

    }

}
new K2_productivity_metrics_settings();