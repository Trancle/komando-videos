<?php
/*
Plugin Name: Publish Date DatePicker
Plugin URI: http://www.cupeltech.com/
Description: Publish Date DatePicker adds interactive calendar in publish section of post, page & custom post.
It makes adding and changing date easier by selecting it from interactive calendar using mouse.
Version: 2.0.0
Author: Vinod Dalvi
Author URI: http://www.cupeltech.com/
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  Do not call me directly.';
	exit;
}

define('PDDP_VERSION', '2.0.0');
define('PDDP_PLUGIN_URL', plugin_dir_url( __FILE__ ));

function load_pddp_admin_style_script($hook) {
	if( 'post.php' != $hook && 'post-new.php' != $hook)
		return;

	wp_enqueue_script( 'timepicker-js', PDDP_PLUGIN_URL . 'js/jquery-ui-timepicker-addon.js', array( 'jquery-ui-datepicker' ) );
	wp_enqueue_script( 'pddp-js', PDDP_PLUGIN_URL . 'js/pddp.js', array( 'timepicker-js' ) );
	wp_enqueue_style( 'pddp-css', PDDP_PLUGIN_URL . 'css/pddp.css' );
}
add_action( 'admin_enqueue_scripts', 'load_pddp_admin_style_script' );