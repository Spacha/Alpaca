<?php

namespace App\Framework\Libs;

use App\Framework\Libs\Core;
use App\Framework\Exceptions\RoutingException;

Class Router
{
	protected $url = '';
	protected $routes = [];
	
	protected $controller;
	protected $method;
	protected $params = [];

	public function __construct($url = '')
	{
		$this->controller = Core::controllerNamespace('TestCaontroller');
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

		// Call the specified controller
		$controller = new $this->controller();

		// Call the controller's method
		call_user_func_array(
			[$controller, $this->method],
			$this->params
		);	
	}
}
