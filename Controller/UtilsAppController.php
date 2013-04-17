<?php
/**
 * Copyright 2007-2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2007-2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Utils Plugin
 *
 * Utils App Controller
 *
 * @package utils
 */
class UtilsAppController extends AppController {
	
	/**
	 * Get the lat/lng of passed address
	 * @param string $address The business/place address
	 * @return array The location lat/lng/country, etc.
	 */
	public function geocodeAddress($address) {
		
		$url = 'http://maps.google.com/maps/api/geocode/json?address=' . urlencode($address) . '&sensor=false';
		
		$response = @file_get_contents($url);

		if($response === false) {
			return false;
		}

		$response = json_decode($response);

		if($response->status != 'OK') {
			return false;
		}

		foreach ($response->results['0']->address_components as $data) {
			if($data->types['0'] == 'country') {
				$country = $data->long_name;
				$country_code = $data->short_name;
			}
		}

		$result = array(
			'latitude'  => $response->results['0']->geometry->location->lat,
			'longitude' => $response->results['0']->geometry->location->lng,
			'country' => $country,
			'country_code' => $country_code,
			'googleaddress' => $response->results['0']->formatted_address,
		);
		return $result;
	}
		
}
