<?php

namespace App\Framework\Libs;

use App\Framework\Libs\Core;

Class Router
{
	protected $url = '';
	protected $routes = [];
	
	protected $controller;
	protected $method;
	protected $params = [];

	public function __construct($url = '')
	{
		$this->controller = Core::controllerNamespace('TestController');
		$this->method = 'home';
		$this->params = [];
	}

	/**
	 * Calls the specified controller
	 */
	public function callAction()
	{
		// Call the specified controller
		$controller = new $this->controller();

		// Call the controller's method
		call_user_func_array(
			[$controller, $this->method],
			$this->params
		);	
	}
}
