<?php

namespace App\Framework\Libs;

use App\Framework\Libs\Core;
use App\Framework\Exceptions\RoutingException;

class Router
{
	protected $url = '';
	protected $routes = [];
	
	protected $controller;
	protected $method;
	protected $params = [];

	public function __construct($url = '')
	{
		$this->getRoutes();

		$this->controller = Core::controllerNamespace('TestController');
		$this->method = 'home';
		$this->params = [];
	}

	/**
	 * Calls the specified controller
	 */
	public function callAction()
	{
		if (!class_exists($this->controller))
			throw new RoutingException("Contoller {$this->controller} doesn't exist.");

		$controller = new $this->controller();

		if (!method_exists($controller, $this->method))
			throw new RoutingException("Method {$this->method} doesn't exist.");

		// Call the controller's method
		call_user_func_array(
			[$controller, $this->method],
			$this->params
		);	
	}

	protected function getRoutes()
	{

	}
}
