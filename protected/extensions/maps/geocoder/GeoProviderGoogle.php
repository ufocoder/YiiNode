<?php

class GeoProviderGoogle extends GeoProvider
{
	public $requestAddressParam = "address";

	public $requestUrl = "http://maps.googleapis.com/maps/api/geocode/xml?sensor=false&language=ru";

	public function requestAddr($address)
	{
		$requestUrl = $this->getRequestUrl($address);

		$xml = simplexml_load_string(file_get_contents($requestUrl));

		$result = $xml->result;

		$point["point"] = array(
			(float) $result->geometry->location->lng,
			(float) $result->geometry->location->lat
		);

		$point["envelope"][0] = (float) $geoitem->geometry->viewport->southwest->lng;
		$point["envelope"][1] = (float) $geoitem->geometry->viewport->southwest->lat;
		$point["envelope"][2] = (float) $geoitem->geometry->viewport->northeast->lng;
		$point["envelope"][3] = (float) $geoitem->geometry->viewport->northeast->lat;

		if (isset($geoitem->address_component))
			foreach ($geoitem->address_component as $addrcomp) {
				if ($addrcomp->type[0] == 'locality')
					$point["city"] = $addrcomp->short_name;
				if ($addrcomp->type == 'route')
					$point["street"] = $addrcomp->short_name;
				if ($addrcomp->type == 'street_number')
					$point["house"] = $addrcomp->short_name;
			}

		$point["address"] = $city . "#" . $street . '#' . $house;

		return $point;
	}
	
	
	/**
	 * Функция получения длины пути между точками через google.
	 *
	 */
	public function getDistance($d1, $s1, $d2, $s2) {
		$origin = (float) $s1 . ',' . (float) $d1;
		$dest = (float) $s2 . ',' . (float) $d2;

		$request = "http://maps.googleapis.com/maps/api/directions/json?origin=$origin&destination=$dest&sensor=false&mode=walking&language=ru";

		$res = file_get_contents($request);
		$r = json_decode($res);
		if (empty($r->routes[0]->legs[0]->distance->value))
			return false;
		$len = $r->routes[0]->legs[0]->distance->value;
		$len = round($len * 0.001, 2);
		return $len;
	}
	
}