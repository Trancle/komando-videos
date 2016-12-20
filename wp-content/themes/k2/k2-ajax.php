<?php
/**
 * WordPress AJAX Process Execution.
 *
 * @package WordPress
 * @subpackage Administration
 *
 * @link http://codex.wordpress.org/AJAX_in_Plugins
 */

/**
 * Executing AJAX process.
 *
 * @since 2.1.0
 */
define( 'DOING_AJAX', true );

/** Load WordPress Bootstrap */
require_once( '../../../wp-load.php' );

/** Allow for cross-domain requests (from the frontend). */
send_origin_headers();

// Require an action parameter
if ( empty( $_GET['action'] ) )
	die();

@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
@header( 'X-Robots-Tag: noindex' );

send_nosniff_header();
header('Cache-Control: max-age=900, public');
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 900));
header('Access-Control-Allow-Origin: *');

add_action( 'wp_ajax_nopriv_heartbeat', 'wp_ajax_nopriv_heartbeat', 1 );

if ( is_user_logged_in() ) {
	/**
	 * Fires authenticated AJAX actions for logged-in users.
	 *
	 * The dynamic portion of the hook name, $_REQUEST['action'],
	 * refers to the name of the AJAX action callback being fired.
	 *
	 * @since 2.1.0
	 */
	do_action( 'k2_ajax_' . $_REQUEST['action'] );
} else {
	/**
	 * Fires non-authenticated AJAX actions for logged-out users.
	 *
	 * The dynamic portion of the hook name, $_REQUEST['action'],
	 * refers to the name of the AJAX action callback being fired.
	 *
	 * @since 2.8.0
	 */
	do_action( 'k2_ajax_nopriv_' . $_REQUEST['action'] );
}
// Default status
die();
