<?php

class GeoProvider
{
	/**
	 * Имя параметра ключа в запросе к провайдеру
	 */
	protected $requestKeyParam = null;

	/**
	 * Имя параметра адреса в запросе к провайдеру
	 */
	protected $requestAddressParam = null;

	/**
	 * Адрес запроса к провайдеру
	 */
	protected $requestUrl = null;

	/**
	 * Ключ для подключения к провайдеру данных
	 */
	public $key = null;

	public function __construct($params)
	{
		foreach($params as $param => $value)
			$this->$param = $value;
	}

	/**
	 * Получить URL строчку запроса
	 */
	public function getRequestUrl($address)
	{
		$url = $this->requestUrl."&".$this->requestAddressParam."=".urlencode($address);
		if (!empty($this->requestKeyParam))
			$url .= "&".$this->requestKeyParam."=".$this->key;

		return $url;
	}

	/**
	 * Послать запрос на получение адреса
	 */
	public function requestAddr($address)
	{
		return $address;
	}

	/**
	 * Определить расстояние между точками по заданным координатам
	 *
	 * @param type $from_lat - широта первой точки
	 * @param type $from_long - долгота первой точки
	 * @param type $to_lat - ширина второй точки
	 * @param type $to_long - долгота второй точки
	 * @return type
	 */
	public function getDistance($from_lat, $from_long, $to_lat, $to_long)
	{
		return 111.2 * acos(sin($from_lat) * sin($to_lat) + cos($from_lat) * cos ($to_lat) * cos ($from_long-$to_long));
	}
}