<?php

/**
 *
 * 'addressId' => array(
 * 		'countryId' => 'countryId',
 * 		'regionId' => 'regionId',
 * 		'cityId'    => 'cityId',
 * 		'streetId'  => 'streetId',
 * 		'houseId'   => 'houseId'
 * )
 */
class YandexMapPoint extends CWidget
{
	// контейнер
	public $containerId = 'ymap';

	// элемент с адресом
	public $addressId = null;

	// элемент с адресом
	public $latId = null;

	// элемент с адресом
	public $lonId = null;

	// центр карты [по умолчанию г. Красноярск]
	protected $mapCenter = array(
		56.010646,  // широта - Latitude
		92.870412   // долгота - Longitude
	);

	// центр карты
	public $mapPoint = array();

	// коэффициент приближения
	public $mapZoom = 15;

	// элементы управления карты
	public $mapControls = array(
		'zoomControl',
		'mapTools'
	);

	// параметры карты
	public $mapParams = array();

	// язык карты
	public $mapLang = "ru-RU";

	// опции html тэга
	public $htmlOptions = array();

	private $runUpdate = null;

	// запуск виджета
	public function run()
	{
		// register js
		$mapUrl = "http://api-maps.yandex.ru/2.0-stable/?load=package.standard";
		if (!empty($this->mapLang))
			$mapUrl .= "&lang=".$this->mapLang;

		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerScriptFile($mapUrl);

		// параметры карты
		if (!empty($this->mapPoint))
			$this->mapParams['center'] = $this->mapPoint;
		elseif (!empty($this->mapCenter))
			$this->mapParams['center'] = $this->mapCenter;

		if (!empty($this->mapZoom))
			$this->mapParams['zoom'] = $this->mapZoom;

		// создаем карту
		$suffix = substr(md5($this->containerId),0, 4);
		$script = "var ymap_params_".$suffix." = ".CJSON::encode($this->mapParams).",\n"
				 ."    ymap_object_".$suffix;

		if (!empty($this->mapPoint))
			$script .= ",\n    ymap_point_".$suffix.";\n";
		 else
			$script .= ";\n";

		$script .="\n"
				 ."ymaps.ready(function(){\n"
				 ."    ymap_object_".$suffix." = new ymaps.Map('".$this->containerId."', ymap_params_".$suffix.");\n";

		// подключаем элементы управления
		if (!empty($this->mapControls))
			foreach($this->mapControls as $control)
				$script .="    ymap_object_".$suffix.".controls.add('".$control."');\n";


		if (isset($this->mapPoint[0]) && isset($this->mapPoint[1]))
			$script .="    ymap_point_".$suffix." = new ymaps.Placemark([".$this->mapPoint[0].", ".$this->mapPoint[1]."]);\n"
					 ."    ymap_object_".$suffix.".geoObjects.add(ymap_point_".$suffix.");\n";

		// обрабатываем клик [если существуют необходимые сущности]
		if (!empty($this->addressId) || !empty($this->latId) && !empty($this->lonId))
		{
			$script .="    ymap_object_".$suffix.".events.add('click', function(e){\n"
					 ."    ymap_object_".$suffix.".geoObjects.each(function(mark){\n"
					 ."        ymap_object_".$suffix.".geoObjects.remove(mark);\n"
					 ."    });\n";

			if (!empty($this->addressId)){
				$script .="        ymaps.geocode(e.get('coordPosition'), { results: 1 }).then(function (result) {\n";
				if (!is_array($this->addressId)){
					$script .="            var address_text = result.geoObjects.get(0).properties.get('text');\n"
							 ."            $('#".$this->addressId."').val(address_text);\n";
				}
				else{
					$script .="            var geoObject = result.geoObjects.get(0),\n"
							 ."                addrObject = geoObject.properties.get('metaDataProperty.GeocoderMetaData.AddressDetails'),\n"
							 ."                objCountry = null,\n"
							 ."                objRegion = null,\n"
							 ."                objCity = null,\n"
							 ."                objStreet = null,\n"
							 ."                objHouse = null,\n"
							 ."                nameCountry = null,\n"
							 ."                nameRegion = null,\n"
							 ."                nameCity = null,\n"
							 ."                nameStreet = null,\n"
							 ."                nameHouse = null;\n"
							 ."\n"
							 ."            if (addrObject && 'Country' in addrObject){\n"
							 ."                objCountry = addrObject.Country;\n"
							 ."                nameCountry = objCountry.CountryName;\n"
							 ."            }\n"
							 ."\n"
							 ."            if (objCountry && 'AdministrativeArea' in objCountry){\n"
							 ."                objRegion = objCountry.AdministrativeArea;\n"
							 ."                nameRegion = objRegion.AdministrativeAreaName;\n"
							 ."            }\n"
							 ."\n"
							 ."            if (objRegion && 'Locality' in objRegion){\n"
							 ."                objCity = objRegion.Locality;\n"
							 ."                nameCity = objCity.LocalityName;\n"
							 ."            }else if ('Locality' in objCountry){\n"
							 ."                objCity = objCountry.Locality;\n"
							 ."                nameCity = objCity.LocalityName;\n"
							 ."            }\n"
							 ."\n"
							 ."            if (objCity && 'Thoroughfare' in objCity){\n"
							 ."                objStreet = objCity.Thoroughfare;\n"
							 ."                nameStreet = objStreet.ThoroughfareName;\n"
							 ."            }\n"
							 ."\n"
							 ."            if (objStreet && 'Premise' in objStreet){\n"
							 ."                objHouse = objStreet.Premise;\n"
							 ."                nameHouse = objHouse.PremiseNumber;\n"
							 ."            }\n";

					if (!empty($this->addressId['countryId']))
						$script .="            $('#".$this->addressId['countryId']."').val(nameCountry);\n";
					if (!empty($this->addressId['regionId']))
						$script .="            $('#".$this->addressId['regionId']."').val(nameRegion);\n";
					if (!empty($this->addressId['cityId']))
						$script .="            $('#".$this->addressId['cityId']."').val(nameCity);\n";
					if (!empty($this->addressId['streetId']))
						$script .="            $('#".$this->addressId['streetId']."').val(nameStreet);\n";
					if (!empty($this->addressId['houseId']))
						$script .="            $('#".$this->addressId['houseId']."').val(nameHouse);\n";
				}
				$script .="        });\n";
			}

			$script .="        ymap_point_".$suffix." = new ymaps.Placemark(e.get('coordPosition'));\n"
					 ."        ymap_object_".$suffix.".geoObjects.add(ymap_point_".$suffix.");\n";

			if (!empty($this->latId) && !empty($this->lonId))
				$script .="        $('#".$this->latId."').val(e.get('coordPosition')[0]);\n"
						 ."        $('#".$this->lonId."').val(e.get('coordPosition')[1]);\n";

			$script .="    });\n";
		}

		// если указан адрес обрабатываем его изменение
		if (!empty($this->addressId))
		{
			if (!is_array($this->addressId))
				$script .="    $('#".$this->addressId."').bind('change keyup', function (e) {\n"
						 ."        var code = (e.keyCode ? e.keyCode : e.which);\n"
						 ."        if (code == 13){\n"
						 ."            updateMapAddr($('#".$this->addressId."').val());\n"
						 ."            return false;\n"
						 ."        }else\n"
						 ."            return true;\n"
						 ."    });\n"
						 ."    $('#".$this->addressId."').bind('change', function (e){\n"
						 ."            updateMapAddr($('#".$this->addressId."').val());\n"
						 ."    });\n";
			else
				$script .="    $('#".implode(", #", $this->addressId)."').bind('change keyup', function (e) {\n"
						 ."        var address = '';\n"
						 ."        $('#".implode(", #", $this->addressId)."').each(function(){\n"
						 ."            address += ', ' + $(this).val().toString();\n"
						 ."        });\n"
						 ."        var code = (e.keyCode ? e.keyCode : e.which);\n"
						 ."        if (code == 13){\n"
						 ."            updateMapAddr(address);\n"
						 ."            return false;\n"
						 ."        }else\n"
						 ."            return true;\n"
						 ."    });\n"
						 ."    $('#".implode(", #", $this->addressId)."').bind('change', function (e){\n"
						 ."        var address = ''; "
						 ."        $('#".implode(", #", $this->addressId)."').each(function(){"
						 ."            address  += ', ' + $(this).val();"
						 ."        });"
						 ."        updateMapAddr(address);\n"
						 ."    });\n";

			if ($this->runUpdate === null)
			{
				$script .="function updateMapAddr(address){\n"
						 ."    ymaps.geocode(address, { results: 1 }).then(function (result) {\n"
						 ."        ymap_object_".$suffix.".geoObjects.each(function(mark){\n"
						 ."            ymap_object_".$suffix.".geoObjects.remove(mark);\n"
						 ."        });\n"
						 ."        var firstGeoObject = result.geoObjects.get(0);\n"
						 ."        ymap_point_".$suffix." = new ymaps.Placemark(firstGeoObject.geometry.getCoordinates());\n"
						 ."        ymap_object_".$suffix.".geoObjects.add(ymap_point_".$suffix.");\n"
						 ."        ymap_object_".$suffix.".panTo(firstGeoObject.geometry.getCoordinates(), { flying: true, duration: 500 });\n"
						 ."    });\n"
						 ."};\n";
				$this->runUpdate = true;
			}
		}

		$script .= "});";

		Yii::app()->clientScript->registerScript("widget-yandex-map-".md5($this->containerId), $script, CClientScript::POS_READY);

		// container
		$this->htmlOptions['id'] = $this->containerId;
		echo CHtml::tag('div', $this->htmlOptions, null, true);

	}

}