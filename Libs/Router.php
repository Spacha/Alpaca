<?php

/**

	URL: http://localhost/index.php?route=news/economics/param1/param2
		
		Controller: NewsController.php
		Action method: EconomicsAction
		Parameters: param1, param2
		
	Router Class:
	
	1. GET the URL
	2. Validate it
	3. Translate it if localized
	4. Call specified class, method etc.

*/

Class Router
{
	const DEFAULT_CONTROLLER = 'Index';
	const DEFAULT_ACTION = 'index';
	
	protected $request = array(
		'controller' => '',
		'action' => '',
		'param1' => '',
		'param2' => '');
	
	public function __construct($url)
	{
		$this->setRoute($url ? $url : null);
	}
	
	private function setRoute($url = null)
	{
		if (isset($url)) {
			$url = rtrim($url, "/");
			$url_parts = explode("/", $url);
		}
		
		// Look for YBoard Bootstrap
		$this->request['controller'] = isset($url_parts[0]) ? $url_parts[0] : self::DEFAULT_CONTROLLER;
		$this->request['action'] = isset($url_parts[1]) ? $url_parts[1] : self::DEFAULT_ACTION;
		$this->request['param1'] = isset($url_parts[2]) ? $url_parts[2] : null;
		$this->request['param2'] = isset($url_parts[3]) ? $url_parts[3] : null;
	}
	
	public function getRoute()
	{
		return $this->request;
	}
}