<?php

class GeoProviderYandex extends GeoProvider
{

	protected $requestKeyParam = "key";

	protected $requestAddressParam = "geocode";

	protected $requestUrl = "http://geocode-maps.yandex.ru/1.x/?results=1";


	//@TODO: refactor
	public function requestAddr($address)
	{

		$requestUrl = $this->getRequestUrl($address);

		// $xml = new SimpleXMLElement(file_get_contents($requestUrl));
		$xml = simplexml_load_string(file_get_contents($requestUrl));

		if (!$xml)
			throw new CHttpException(403, "Bad xml request");

		$point = array();

		if (isset($xml->GeoObjectCollection->featureMember))
		{

			$geoitem = $xml->GeoObjectCollection->featureMember;
			$point["point"] = explode(" ", $geoitem->GeoObject->Point->pos);

			$point["envelope"] = array_merge(
				explode(" ", $geoitem->GeoObject->boundedBy->Envelope->lowerCorner),
				explode(" ", $geoitem->GeoObject->boundedBy->Envelope->upperCorner)
			);

			$addressdetails = (isset($geoitem->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->AdministrativeArea))
			? $geoitem->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->AdministrativeArea->Locality
			: $geoitem->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->Locality;

			$point['country'] = $geoitem->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->CountryName
				? (string)$geoitem->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->CountryName
				: '';

			$point["region"] = isset($geoitem->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->AdministrativeArea->AdministrativeAreaName)
				? (string)$geoitem->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->AdministrativeArea->AdministrativeAreaName
				: '';

			$point["city"] = isset($addressdetails->LocalityName) ? (string) $addressdetails->LocalityName : '';
			$point["street"] = isset($addressdetails->Thoroughfare->ThoroughfareName) ? (string) $addressdetails->Thoroughfare->ThoroughfareName : '';
			$point["house"] = isset($addressdetails->Thoroughfare->Premise->PremiseNumber) ? (string) $addressdetails->Thoroughfare->Premise->PremiseNumber : '';

			// если не нашли в стандартной структуре, будем искать перебором
			$xml = new SimpleXMLElement($xml->asXML());
			$xml->registerXPathNamespace('y', 'urn:oasis:names:tc:ciq:xsdschema:xAL:2.0');

			if (empty($point["country"])) {
				$node = $xml->xpath('//y:CountryName');
				if (!empty($node[0]))
					$point["country"] = strip_tags($node[0]->asXML());
			}

			if (empty($point["city"])) {
				$node = $xml->xpath('//y:LocalityName');
				if (!empty($node[0]))
					$point["city"] = strip_tags($node[0]->asXML());
			}

			if (empty($point["region"])) {
				$node = $xml->xpath('//y:AdministrativeAreaName');
				if (!empty($node[0]))
					$point["region"] = strip_tags($node[0]->asXML());
			}
			if (empty($point["street"])) {
				$node = $xml->xpath('//y:ThoroughfareName');
				if (!empty($node[0]))
					$point["street"] = strip_tags($node[0]->asXML());
				else {
					//если улицы нет, копаем район
					$node = $xml->xpath('//y:DependentLocalityName ');
					if (!empty($node[0]))
						$point["street"] = strip_tags($node[0]->asXML());
				}
			}
			if (empty($point["house"])) {
				$node = $xml->xpath('//y:PremiseNumber');
				if (!empty($node[0]))
					$point["house"] = strip_tags($node[0]->asXML());
			}
		}

		return $point;
	}

		/**
	 * Функция получения длины пути между точками через яндекс.
	 * Основано на недокументированных функциях
	 */
	public function getDistance($d1, $s1, $d2, $s2) {
		$coord = (float) $d1 . ',' . (float) $s1 . '~' . (float) $d2 . ',' . (float) $s2;
		$request = "http://api-maps.yandex.ru/services/route/1.1/route.xml?callback=id_1&lang=ru_RU&rll=$coord&sco=longlat&mode=human";
		$res = substr(file_get_contents($request), 3); // убрали BOM
		$res = str_replace("id_1(", "", file_get_contents($request)); // убрали обрамляющую функцию
		$res = str_replace(");", "", $res);

		$r = json_decode($res);
		if (empty($r->response->properties->RouterRouteMetaData->humanLength->value))
			return false;
		$len = $r->response->properties->RouterRouteMetaData->humanLength->value;
		if ($r->response->properties->RouterRouteMetaData->humanLength->unit == "meters")
			$len = round($len * 0.001, 2);
		return $len;
	}


	/**
	 * Функция получения длины пути между точками через google.
	 *
	 */
	public function googleDistance($d1, $s1, $d2, $s2) {
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

	/*	 * **
	 * Альтернативный вариант. Выдаёт несколько станций метро с расстояниями до них (массив: точка, название, расстояние в км)
	 * Параметры:
	 * 				$long - долгота
	 * 				$lat - широта
	 * 				$count - кол-во возвращаемых саун
	 * * */

	public function geoReachmetro($long, $lat, $count = 6) {
		$coord = (float) $long . ',' . (float) $lat;
		$count *= 2;
		$request = "http://geocode-maps.yandex.ru/1.x/?geocode=$coord&kind=metro&results=$count&key=" . $this->key;
		$xml = simplexml_load_string(file_get_contents($request));
		$stations = $points = array();
		$point = array("point" => array(0, 0), "name" => "", "dist" => 0);

		// проверяем кол-во. если 2 и более, то будет массив объектов
		if ($xml->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->results > 1)
			$stations = $xml->GeoObjectCollection->featureMember;
		else
			$stations[] = $xml->GeoObjectCollection->featureMember;


		$station_names = array();

		foreach ($stations as $geoitem) {
			$point["point"] = explode(" ", $geoitem->GeoObject->Point->pos);

			$addressdetails = (isset($geoitem->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->AdministrativeArea)) ? $geoitem->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->AdministrativeArea->Locality : $geoitem->GeoObject->metaDataProperty->GeocoderMetaData->AddressDetails->Country->Locality;

			if (isset($addressdetails->Thoroughfare))
				$point["name"] = (string) $addressdetails->Thoroughfare->Premise->PremiseName;
			else
				$point["name"] = isset($addressdetails->Premise->PremiseName) ? (string) $addressdetails->Premise->PremiseName : '';

			// проверяем, нет ли уже такой станции
			if (!in_array($point["name"], $station_names)) {
				$station_names[] = $point["name"];
				$point["dist"] = $this->googleDistance($point["point"][0], $point["point"][1], $long, $lat);
				if (!$point["dist"])
					$point["dist"] = $this->geoDistance($point["point"][0], $point["point"][1], $long, $lat);
				$points[] = $point;
			}
			$points = array_slice($points, 0, $count / 2);
		}
		return $points;
	}
}