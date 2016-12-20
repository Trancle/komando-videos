<?php
/**
 * Plugin Name: Cab Channel Finder
 * Plugin URI: http://www.cablegone.com
 * Description: This returns JSON for the Channel Finder.
 * Author: Kelly Karnetsky
 * Version: 0.1
 * Author URI: http://www.komando.com
 */

function wp_cab_channel_finder_url_encode($zip){
	return urlencode($zip);
}

// Allows AJAX for fetching post data on the admin page
add_action('wp_ajax_cab_channel_finder', function() {
	echo (new Cab_Channel_Finder(ANTENNAWEB_HOST))->get_channels_by_zip( wp_cab_channel_finder_url_encode($_GET['zip']) );
	die();
});
// Allows AJAX for fetching post data on the admin page
add_action('wp_ajax_nopriv_cab_channel_finder', function() {
	echo (new Cab_Channel_Finder(ANTENNAWEB_HOST))->get_channels_by_zip( wp_cab_channel_finder_url_encode($_GET['zip']) );
	die();
});

include_once('lib/class-cab-channel-finder-html.php');

class Cab_Channel_Finder
{
	private $_hostname;
	private $_height = '9';

	public function __construct( $hostname )
	{
		$this->_hostname = $hostname;
	}

	public function hostname() { return $this->_hostname; }

	public function get_channels_by_zip( $zip )
	{
		$sxe = $this->api_get_call( "/service.asmx/GetAddressPredictions?street=&city=&state=&zip=$zip&receiveHeight=$this->_height" );

		if( strpos($sxe->Location, 'Error') !== false ) {
			return json_encode(['error' => '1']);
		}

		$channel_list = (new Cab_Channel_Finder_Html())->channels_to_html( $sxe );
		$location = ['lat' => (string)$sxe->Latitude, 'lon' => (string)$sxe->Longitude];
		$station_locations = $this->stations_to_array( $sxe );

		return $this->build_json_object( $channel_list, $location, $station_locations );
	}

	public function stations_to_array( $sxe )
	{
		$station_locations = [];
		foreach ( $sxe->Station as $station ) {
			$station_locations[] = [
				'callsign' => explode( '-', $station->CallSign )[0],
				'lat' => (string)$station->Latitude,
				'lon' => (string)$station->Longitude
			];
		}
		return $station_locations;
	}

	public function build_json_object( $channel_list, $location, $station_locations )
	{
		$data = (object)[
			'html' => $channel_list,
			'location' => $location,
			'stations' => $station_locations
		];

		return json_encode( $data );
	}

	private function api_get_call( $req_path )
	{
		return $this->api_call( 'GET', $req_path );
	}

	private function api_call( $verb, $req_path ) {
		$ch = curl_init();

		curl_setopt( $ch, CURLOPT_URL, $this->hostname() . $req_path );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, $verb );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );

		$file_contents = curl_exec( $ch );
		$xml_data = new SimpleXMLElement( $file_contents );
		curl_close( $ch );

		if ( is_object( $xml_data ) ) {
			return $xml_data;
		} else {
			return false;
		}
	}
}

/**
 * Removes the update notification
 */
add_filter('site_transient_update_plugins', 'remove_update_notification_cab_channel_finder');
function remove_update_notification_cab_channel_finder($value) {
	if (isset($value) && is_object($value)) {
		unset($value->response[plugin_basename(__FILE__)]);
	}
}