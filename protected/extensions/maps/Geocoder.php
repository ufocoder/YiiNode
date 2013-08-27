<?php

class Geocoder extends CApplicationComponent 
{
	/** 
	 * Список подключаемых сервисов
	 */
	public $services = array();

	protected $_provider = array();

	public function init()
	{
		parent::init();

		Yii::import('ext.maps.geocoder.*');

		foreach($this->services as $service => $params)
		{
			$class = "GeoProvider".ucfirst($service);
			$this->_provider[$service] = new $class($params);
		}
	}

	public function requestAddr($service, $address)
	{
		if (!isset($this->_provider[$service]))
			throw new CHttpException(403, 'Geoprovider `'.$service.'` is not exists.');

		return $this->_provider[$service]->requestAddr($address);
	}

	public function getDistance($service , $from_lat, $from_long, $to_lat, $to_long)
	{
		if (!isset($this->_provider[$service]))
			return GeoProvider::getDistance($from_lat, $from_long, $to_lat, $to_long);
		else
			return $this->_provider[$service]->getDistance($from_lat, $from_long, $to_lat, $to_long);
	}

}
