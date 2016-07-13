<?php

/**

TODO:	Translate url if it's localized
		Validate url

*/

Class Router
{
	const DEFAULT_CONTROLLER = 'Index';
	const DEFAULT_ACTION = 'index';
	
	protected $request = array(
		'controller' => '',
		'action' => '',
		'paramArr' => []);
	
	public function __construct($url)
	{
		$this->setRoute($url ? $url : null);
	}
	
	private function setRoute($url = null)
	{
		$urlParts = array();
		
		if (isset($url)) {
			$url = rtrim($url, "/");
			$urlParts = explode("/", $url);
		}
		
		$this->request['controller'] = isset($urlParts[0]) ? ucfirst($urlParts[0]) : self::DEFAULT_CONTROLLER;
		$this->request['action'] = isset($urlParts[1]) ? $urlParts[1] : self::DEFAULT_ACTION;
		
		for($i = 2; $i < count($urlParts); $i++) {
			array_push($this->request['paramArr'], $urlParts[$i]);
		}
	}
	
	public function getRoute()
	{
		return $this->request;
	}
}
