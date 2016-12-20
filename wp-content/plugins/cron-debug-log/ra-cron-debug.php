<?php
/*
Plugin Name: Cron Debug Log
Plugin URI: http://wpmututorials.com/
Description: A rudimentary plugin for debugging cron.
Author: Ron Rennick
Version: 0.1
Author URI: http://ronandandrea.com/

This plugin is a collaboration project with contributions from the CUNY Acedemic Commons (http://dev.commons.gc.cuny.edu/)
*/
/* Copyright:   (C) 2011 Ron Rennick, All rights reserved.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
function ra_cron_http_debug( $response, $type, $transport = false ) {
	if( !$transport || 'response' != $type )
		return;

	if( is_wp_error( $response ) )
		$response = $response->get_error_message();

	if( is_array( $response ) && is_array( $response['response'] ) && !empty( $response['response']['code'] ) && '200' == $response['response']['code'] )
		return;

	$meta_key = 'ra_http_' . $transport . time();
	update_option( $meta_key, stripslashes_deep( $response ) );
}
add_action( 'http_api_debug', 'ra_cron_http_debug', 10, 3 );
