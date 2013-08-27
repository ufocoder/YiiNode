<?php

class GeoIP extends CApplicationComponent 
{
	private $_instance = null;
	private $_cache = array();

	public function init()
	{
		parent::init();

		Yii::import('ext.maps.geoip.*');

		$ds = DIRECTORY_SEPARATOR;
		$this->_instance = new SxGeo(__DIR__ . $ds . "geoip" . $ds . "data". $ds. "SxGeoCity.dat");
	}

	public function get($ip)
	{
		if (!isset($this->_cache[$ip]))
			$this->_cache[$ip] = $this->_instance->getCity($ip);

		$result = null;

		if (!empty($this->_cache[$ip]['country']))
			$result .= $this->_cache[$ip]['country'];

		if (!empty($this->_cache[$ip]['city']))
			$result .= !empty($result)?", ".$this->_cache[$ip]['city']:$this->_cache[$ip]['city'];

		return $result;
	}

}