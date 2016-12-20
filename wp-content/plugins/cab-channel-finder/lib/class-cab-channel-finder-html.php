<?php

class Cab_Channel_Finder_Html
{
	private $_logo_path;
	private $_location;
	private $_lat_o;
	private $_lon_o;

	public function channels_to_html( $sxe )
	{
		$channels = null;
		$this->_logo_path = $sxe->LogoRootPath;
		$this->_location = $sxe->Location;
		$this->_lat_o = (string)$sxe->Latitude;
		$this->_lon_o = (string)$sxe->Longitude;

		foreach ($sxe->Station as $station) {
			$channels = $channels . $this->station_to_channel( $station );
		}

		return $this->channels_html_wrapper( $channels );
	}

	private function station_to_channel( $station )
	{
		$callsign = $station->CallSign;
		$distance = (string)$station->Distance;
		$rf = $station->RFChannel;

		$origin = ['lat' => $this->_lat_o, 'lon' => $this->_lon_o];
		$destination = ['lat' => (string)$station->Latitude, 'lon' => (string)$station->Longitude];
		$bearing = $this->get_bearing( $origin, $destination );

		foreach ($station->Channel as $channel) {
			return $this->channel_line_item( $callsign, $rf, $bearing, $distance, $channel );
		}
	}

	private function channels_html_wrapper( $channels )
	{
		$html = '
			<div class="channel-list">
				<div class="channel-list-item channel-list-header">
					<div class="channel-list-item__network">Network</div>
					<div class="channel-list-item__station">Station</div>
					<div class="channel-list-item__channel">Channel</div>
					<div class="channel-list-item__band">Band</div>
					<div class="channel-list-item__distance">Distance</div>
					<div class="channel-list-item__heading">Heading</div>
					<div class="channel-list-item__strength">Strength</div>
				</div>
				' . $channels . '
			</div>
		';

		return trim(preg_replace(['/\t+/', '/\r+/', '/\n+/'], '', $html));
	}

	private function get_channel_strength( $distance )
	{
		$strength = null;

		if ( $distance < 40 ) {
			$strength = 'strong';
		} else if ( $distance >= 40 && $distance <= 65 ) {
			$strength = 'moderate';
		} else if ( $distance >= 66 && $distance <= 80 ) {
			$strength = 'weak';
		} else {
			$strength = 'no';
		}

		$strength = '<div class="strength-wrapper ' . $strength . '"><div class="strength-wrapper__bar"></div><div class="strength-wrapper__bar"></div><div class="strength-wrapper__bar"></div><div class="strength-wrapper__bar"></div></div>';

		return $strength;
	}

	private function get_channel_band( $rf )
	{
		if ( $rf <= 13 ) {
			return 'VHF';
		} else {
			return 'UHF';
		}
	}

	private function get_bearing( $origin, $destination )
	{
		//difference in longitudinal coordinates
		$dLon = deg2rad($destination['lon']) - deg2rad($origin['lon']);

		//difference in the phi of latitudinal coordinates
		$dPhi = log(tan(deg2rad($destination['lat']) / 2 + pi() / 4) / tan(deg2rad($origin['lat']) / 2 + pi() / 4));

		//we need to recalculate $dLon if it is greater than pi
		if(abs($dLon) > pi()) {
			if($dLon > 0) {
				$dLon = (2 * pi() - $dLon) * -1;
			} else {
				$dLon = 2 * pi() + $dLon;
			}
		}

		//return the angle, normalized
		return (rad2deg(atan2($dLon, $dPhi)) + 360) % 360;
	}

	private function get_compass_heading( $bearing )
	{
		static $cardinals = ['North', 'North East', 'East', 'South East', 'South', 'South West', 'West', 'North West', 'North'];
		return $cardinals[(int)round( $bearing / 45 )];
	}

	private function get_network_logo( $network_logo, $network )
	{
		if ($network_logo) {
			return '<img src="' . $this->_logo_path . '/' . $network_logo . '" title="' . $network . '" alt="' . $network . '" />';
		} else {
			return $network;
		}
	}

	private function channel_line_item( $callsign, $rf, $bearing, $distance, $channel )
	{
		$callsign = explode( '-', $callsign )[0];
		$strength = $this->get_channel_strength( $distance );
		$channel_num = $rf;
		$band = $this->get_channel_band( $rf );

		if (!empty($channel->MajorChannelNumber) || !empty($channel->MinorChannelNumber)) {
			$channel_num = $channel->MajorChannelNumber . '-' . $channel->MinorChannelNumber;
		}

		$html = '
			<div class="channel-list-item" data-callsign="' . $callsign . '">
				<div class="channel-list-item__network">' . $this->get_network_logo($channel->NetworkLogo, $channel->Network) . '</div>
				<div class="channel-list-item__station">' . $callsign . '</div>
				<div class="channel-list-item__channel">' . $channel_num . '</div>
				<div class="channel-list-item__band">' . $band . '</div>
				<div class="channel-list-item__distance">' . round(($distance * 0.62137119), 1) . ' miles</div>
				<div class="channel-list-item__heading">' . $this->get_compass_heading($bearing) . ' (' . $bearing .'&deg;)</div>
				<div class="channel-list-item__strength">' . $strength . '</div>
			</div>
		';

		return $html;
	}
}