<?php

include_once(KOM_WWW_API_DIR . '/includes/trait-kom-www-api-admin.php');
include_once(KOM_WWW_API_DIR . '/includes/trait-kom-www-api-header.php');
include_once(KOM_WWW_API_DIR . '/includes/trait-kom-www-api-footer.php');

class Kom_Www_Api
{
	use Kom_Www_Api_Admin, Kom_Www_Api_Header, Kom_Www_Api_Footer;

	protected $request_type;
	protected $content;
	protected $replace_point_map = array(
		"login_box_and_search" => 0,
		"mobile_login_details" => 1,
	);

	private $show_picks_role_name = array("role" => "kom_showpicks_rw",
					"display_name" => "Komando Show Picks Updater",
					"capabilities" => array(
						"kom_showpicks_rw" => true,
						"read" => true
					) );

    public function __construct()
    {
	    add_filter('query_vars', [$this, 'query_vars']);
	    add_action('init', [$this, 'rewrites']);
	    add_action('parse_request', [$this, 'parse_request']);
	    add_action('admin_menu', [$this, 'admin_page_menu']);
	    add_action('admin_enqueue_scripts', [$this, 'admin_page_scripts']);

	    $this->_menu = $this->menu_data();
    }

	function admin_page_scripts() {
		if (isset($_GET['page']) && $_GET['page'] == 'kom-www-api-admin') {
			wp_enqueue_media();
		}
	}

    /**
     * When the plugin is activated we need to do stuff
     */
    public function activation()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }
	$this->add_roles_and_caps();
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


	public function add_roles_and_caps() {
		// Adds the role if it doesn't exist
		add_role(
		    $this->show_picks_role_name['role'],
		    __( $this->show_picks_role_name['display_name'] ),
		    $this->show_picks_role_name['capabilities']
		);
		$roles = array( get_role('editor'), get_role('author'), get_role('administrator'), get_role($this->show_picks_role_name['role']) );
		foreach( $roles as $role ) {
			foreach( $this->show_picks_role_name["capabilities"] as $name => $en ) {
				if( $en ) {
					$role->add_cap( $name );
				}
			}
		}
	}


	public function query_vars($query_vars)
	{
		$query_vars[] = 'kom_www_api';
		$query_vars[] = 'kom_www_section';
		$query_vars[] = 'kom_www_file_type';
		$query_vars[] = 'kom_www_post';
		$query_vars[] = 'action';
		return $query_vars;
	}

	public function rewrites()
	{
		add_rewrite_rule('^api\/k2\/([a-z_-]+)$', 'index.php?kom_www_api=1&kom_www_section=$matches[1]', 'top');
		add_rewrite_rule('^api\/k2\/([a-z_-]+)[.\/]?(json|html)?$', 'index.php?kom_www_api=1&kom_www_section=$matches[1]&kom_www_file_type=$matches[2]', 'top');
		add_rewrite_rule('^api\/k2\/post-action\/([a-zA-Z_-]+)$', 'index.php?kom_www_api=1&kom_www_post=1&action=$matches[1]', 'top');
	}

	public function postFreeWeekendEmail($post){
		$success = false;
		$message = "";

		if(isset($post['email_address']) && filter_var($post['email_address'], FILTER_VALIDATE_EMAIL)){
			$filename = __DIR__."/../../../../../collection/free-weekend.csv";
			if(!file_exists($filename)){
				$link = fopen($filename, "w");
				fputcsv($link, array("TIME_OF_CAPTURE","EMAIL_ADDRESS"));
			}
			else{
				$link = fopen($filename, "a");
			}
			fputcsv($link, array(date("Y-m-d\Th:i:s"), $post['email_address']));
			fclose($link);
			$message = "Thank you! Please enjoy the podcast.";
			$success = true;
			//setcookie("k2-free-weekend-already-subscribed", true, time()+(3600*24*3), '/');
		}
		else{
			$message = "Invalid email address - please try again.";
		}

		$res = (object) array(
			"success" => $success,
			"message" => $message,
		);

		die(json_encode($res));
	}

	public function parse_request($wp_query)
	{
		if ($wp_query->query_vars['kom_www_api']) {

			if($wp_query->query_vars['kom_www_post'] && !empty($wp_query->query_vars['action'])){
				$method = "post" . $wp_query->query_vars['action'];
				if(method_exists($this, $method)){
					$this->$method($_POST);
					die();
				}
			}

			$map = [
				'topbar',
				'header',
				'footer',
				'sponsors',
				'all'
			];

			if (in_array($wp_query->query_vars['kom_www_section'], $map)) {
				// Valid endpoint, let's call the method
				$method = 'get_' . $wp_query->query_vars['kom_www_section'];
				$this->$method((($wp_query->query_vars['kom_www_file_type']) ? $wp_query->query_vars['kom_www_file_type'] : 'json'));
			} else {
				// Invalid endpoint, set status and redirect to homepage
				status_header(400);
				header('Location: ' . get_site_url());
			}

			die();
		}
	}

    /**
     * Create the menu item in the admin
     */
    public function admin_page_menu()
    {
       	add_menu_page('Show Sponsors', 'Show Sponsors', 'kom_showpicks_rw', 'kom-www-api-admin', array($this, 'admin_init'), '', 41);
    }

	/**
	 * @param $file_type
	 * @TODO figure out how to have the login status persist across the sites
	 */
	public function get_topbar($file_type)
	{
		return;
	}

	public function get_header()
	{
		header("Content-type: application/json; charset=utf-8");
		echo '{"header":[{"main-header": ' . json_encode($this->build_main_header_html()) . '}]}';
	}

	public function get_footer($file_type)
	{
		$footer = $this->build_footer_menu_html();
		if ($file_type == 'html') {
			echo $footer;
		} else {
			header("Content-type: application/json; charset=utf-8");
			echo '{"footer":' . json_encode($footer) . '}';
		}
	}

	public function get_sponsors($file_type)
	{
		$sponsors = $this->build_sponsors_page_html();
		if ($file_type == 'html') {
			echo $sponsors;
		} else {
			header("Content-type: application/json; charset=utf-8");
			echo '{"sponsors":' . json_encode($sponsors) . '}';
		}
	}

	public function get_sponsors_wordpress()
	{
		return $this->build_sponsors_page_html();
	}

	public function get_all()
	{
		header("Content-type: application/json; charset=utf-8");
		echo '{"header":[{"main-header": ' . json_encode($this->build_main_header_html()) . '}], "footer": ' . json_encode($this->build_footer_menu_html()) . '}';
	}

	public function get_header_wordpress()
	{
		return $this->build_main_header_html();
	}

	public function get_footer_wordpress()
	{
		return $this->build_footer_menu_html();
	}

	/**
	 * @return string
	 */
	public function build_sponsors_page_html()
	{
		$sponsors = get_option('kom_sponsors');

		if ($sponsors) {

			$html = '<div class="show-picks-sponsors-grid">';
			foreach ($sponsors as $sponsor) {
				$html = $html . '<div class="sponsor-grid-item"><a href="' . $sponsor['link'] . '" target="_blank" rel="nofollow"><img src="' . $sponsor['image'] . '" alt="' . $sponsor['name'] . '" /><div class="sgii-wrapper"><div class="sgii-website">' . $sponsor['promo-website'] . '</div><div class="sgii-promo-code">' . $sponsor['promo-code'] . '</div><div class="sgii-promo-details">' . $sponsor['promo-details'] . '</div></div></a></div>';
			}
			foreach ($sponsors as $sponsor) {
				if($sponsor['html-code']) {
					if (preg_match('/(\${time})/', $sponsor['html-code'])) {
						$html = $html . preg_replace('/(\${time})/', time(), $sponsor['html-code']);
					} else {
						$html = $html . $sponsor['html-code'];
					}
				}
			}
			$html = $html . '</div>';

			return $html;

		} else {

			return '';

		}
	}

	public function build_sponsors_menu_html()
	{
		$sponsors = get_option('kom_sponsors');

		if ($sponsors) {

			shuffle($sponsors);

			$html = '<div class="menu-sponsors">';
			$html = $html . '<div class="menu-sponsors__header">Kim\'s show sponsors</div>';
			$html = $html . '<div class="menu-sponsors__sponsors">';
			foreach ($sponsors as $sponsor) {
				$html = $html . '<a href="' . get_site_url() . '/show-picks#sponsors"><img src="' . $sponsor['image'] . '" alt="' . $sponsor['name'] . '" /></a>';
			}
			$html = $html . '</div></div>';

			return $html;

		} else {

			return '';
			
		}
	}

	public function fill_replace_point($num, $replacement){

		if(!is_integer($num) && isset($this->replace_point_map[$num])){
			$num = $this->replace_point_map[$num];
		}

		$replace_point = '<!--HFA-POINT-' . ("header" == $this->request_type ? "H" : "F") . '-' . $num . '-->';
		if(strpos($this->content, $replace_point) !== false){
			$this->content = str_replace($replace_point, $replacement, $this->content);
		}
		return $this;
	}

	public function set_request_type($request_type){
		$this->request_type = $request_type;
		return $this;
	}

	public function set_content($content){
		$this->content = $content;
		return $this;
	}

	public function get_content(){
		return $this->content;
	}

}
