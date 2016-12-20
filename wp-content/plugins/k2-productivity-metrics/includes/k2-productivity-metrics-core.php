<?php
/**
 * Description of Productivity Metrics
 * @author Lisdanay Dominguez
 */

class ProductivityMetricsCore
{
	private $ctrl;
	public function __construct()
	{
        /**
         * #REVIEW: why are we setting $angular_nvd3_is_loaded to true before actually loading anything
         */
		global $angular_nvd3_is_loaded;
		$angular_nvd3_is_loaded = true;
		$this->action_dispatcher( );
	}

	public function add_managing_editor_role(){
    $success = add_role( 'managing_editor', 'Managing Editor', [
        'delete_others_pages'         => true,
        'delete_others_posts'         => true,
        'delete_pages'                => true,
        'delete_posts'                => true,
        'delete_private_pages'        => true,
        'delete_private_posts'        => true,
        'delete_published_pages'      => true,
        'delete_published_posts'      => true,
        'edit_others_pages'           => true,
        'edit_others_posts'           => true,
        'edit_pages'                  => true,
        'edit_posts'                  => true,
        'edit_private_pages'          => true,
        'edit_private_posts'          => true,
        'edit_published_pages'        => true,
        'edit_published_posts'        => true,
        'manage_categories'           => true,
        'manage_links'                => true,
        'moderate_comments'           => true,
        'publish_pages'               => true,
        'publish_posts'               => true,
        'read'                        => true,
        'read_private_pages'          => true,
        'read_private_posts'          => true,
        'unfiltered_html'             => true,
        'upload_files'                => true,
        'view_productivity_metrics'   => true,
        'edit_productivity_metrics'   => true,
        'delete_productivity_metrics'   => true,
    ] );

    $role = get_role('administrator');
    $role->add_cap('view_productivity_metrics');
    $role->add_cap('edit_productivity_metrics');
    $role->add_cap('delete_productivity_metrics');

    $role = get_role('managing_editor');
    $role->add_cap('level_7');
    $role->add_cap('level_6');
    $role->add_cap('level_5');
    $role->add_cap('level_4');
    $role->add_cap('level_3');
    $role->add_cap('level_2');
    $role->add_cap('level_1');
    $role->remove_cap('level_0');
    $role->add_cap('ef_view_calendar');
    $role->add_cap('edit_post_subscriptions');
    $role->add_cap('ef_view_story_budget');
    $role->add_cap('kom_showpicks_rw');
    $role->add_cap('premium_member');

    return $success;
  }

	private function action_dispatcher( )
	{
    register_activation_hook('k2-productivity-metrics/k2-productivity-metrics.php', [$this, 'create_time_spent_post_table']);
    register_activation_hook('k2-productivity-metrics/k2-productivity-metrics.php', [$this, 'add_managing_editor_role']);

		//this should only be loaded on the actual plugin settings page, otherwise it breaks things.
		if('k2pm_settings_page' == $_GET['page']){
			add_action( 'language_attributes',   [$this, 'add_module_to_header'] );
		}

		add_action( 'admin_enqueue_scripts', [$this, 'load_admin_scripts'] );
		add_action( 'admin_enqueue_scripts', [$this, 'load_admin_style'] );

		add_action( 'rest_api_init', function () {
			register_rest_route( 'wp/v2', '/k2pm_settings_page(?P<id>\d+)', ['methods' => WP_REST_Server::READABLE] );
			$results = ProductivityMetricsController::do_process($_GET);

			exit( json_encode( $results ) );
		});

		add_action( 'load-post.php',     [$this, 'author_content_edit'] );
		add_action( 'load-post-new.php', [$this, 'author_content_edit']);
		add_action( 'save_post',  [$this,'author_save_post'], 10, 1  );
	}

	public function load_admin_scripts()
	{
		// onw js
		wp_enqueue_script('app-tool', K2PM__PLUGIN_DIR.'js/app-tool.js', ['angular-app'], null, false);

		$angularjs_for_wp_localize = [
			'site' => get_bloginfo('wpurl'),
			'nonce' => wp_create_nonce( 'wp_rest' ),
			'start_timer_author' => 0,
			'template_directory' =>array(
				'configure_url_partial'   => K2PM__PLUGIN_DIR.'partials'
			)
		];
		if( function_exists( 'json_url' ) ) {
			$angularjs_for_wp_localize['base'] = json_url();
		}
		if( function_exists( 'rest_get_url_prefix' ) ) {
			$angularjs_for_wp_localize['base'] = get_bloginfo( 'wpurl') . '/' . rest_get_url_prefix() . '/wp/v2';
		}
		// Localize Variables
		wp_localize_script(
			'angular-core',
			'wpAngularVars',
			$angularjs_for_wp_localize
		);
	}

	public function load_admin_style() {
        /** @var \WP_Screen $screen */
        $screen = get_current_screen();
        if ( 'settings_page_k2pm_settings_page' !== $screen->base )  return;

		wp_register_style( 'bootstrap.min', K2PM__PLUGIN_DIR . 'css/bootstrap.min.css', false,'1.1','all' );
		wp_register_style( 'dataTables.bootstrap', K2PM__PLUGIN_DIR . 'css/dataTables.bootstrap.min.css', false,'1.1','all' );
		wp_register_style( 'font-awesome', K2PM__PLUGIN_DIR . 'css/font-awesome.min.css', false,'1.1','all' );
		wp_register_style( 'ionicons', K2PM__PLUGIN_DIR . 'css/ionicons.min.css', false,'1.1','all' );
		wp_register_style( 'style', K2PM__PLUGIN_DIR . 'css/style.min.css', false,'1.1','all' );

		wp_enqueue_style( 'bootstrap.min' );
		wp_enqueue_style( 'dataTables.bootstrap' );
		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_style( 'ionicons' );
		wp_enqueue_style( 'style' );
	}

	public function add_module_to_header( $output )
	{
		return $output . ' ng-app="wpAngularApp" ng-controller="metricsCtrl"';
	}

	public function create_time_spent_post_table( ) {
		ProductivityMetricsController::create_k2_author_log_table();
	}

	function author_content_edit() {
		add_action('get_angular_for_production_metrics', [$this,'embed_function'] );
	}

	function embed_function()
	{
		echo <<<JS_PRODUCTIVITY_METRICS
		<script>
		    angular.element(document).ready(function() {		     
		    	document.getElementById("post-body-content").innerHTML = document.getElementById("post-body-content").innerHTML + '<span style="display:none;">{{startTimer = true}}</span>' ;
		    	document.getElementById("post-body-content").setAttribute("ng-controller", "metricsCtrl"); 
                angular.bootstrap(document.getElementById("post-body-content"), ['wpAngularApp']);
            }); 
		</script>
JS_PRODUCTIVITY_METRICS;

	}

	function author_save_post( $post_id ) {
		if( 0 < intval($post_id) && isset($_COOKIE['num_changed_author']) && 0 < $_COOKIE['num_changed_author']){
            $_COOKIE['timer_author'] = ( isset($_COOKIE['timer_author']) && 0 < intval($_COOKIE['timer_author']) ) ?  intval($_COOKIE['timer_author']) : 1;
			if ( $parent_id = wp_is_post_revision( $post_id ) ){
				$parameters['post_id'] = $parent_id;
			}else{
				$parameters['parent'] = 0;
			}

			$parameters['author_id']      = get_current_user_id();
			$parameters['time_in_second'] = $_COOKIE['timer_author'];
			$parameters['num_changed']    = $_COOKIE['num_changed_author'];
			ProductivityMetricsController::insert_author_log($parameters);
		}

		unset($_COOKIE['timer_author']);
		unset($_COOKIE['num_changed_author']);
	}

}