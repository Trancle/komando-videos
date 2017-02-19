<?php
namespace W3TC;

class Cdn_CloudFrontFsd_Page {
	// called from plugin-admin
	static public function admin_print_scripts_w3tc_cdn() {
		wp_enqueue_script( 'w3tc_cdn_cloudfront_fsd',
			plugins_url( 'Cdn_CloudFrontFsd_Page_View.js', W3TC_FILE ),
			array( 'jquery' ), '1.0' );
	}



	static public function w3tc_settings_cdn() {
		$config = Dispatcher::config();
		include  W3TC_DIR . '/Cdn_CloudFrontFsd_Page_View.php';
	}
}
