<?php

namespace App\Framework\Libs;

use App\Framework\Libs\Core;
use App\Framework\Libs\RouteMatcher;
use App\Framework\Exceptions\RoutingException;

class Router
{
	protected $matcher;
	
	protected $controller;
	protected $method;
	protected $params = [];

	public function __construct($url = '')
	{
		// @todo think about the name, 'matcher'...
		$this->initMatcher($url);
		$this->setCallables();
	}

	/**
	 * Calls the specified controller with methods and parameters.
	 *
	 * @return void
	 */
	public function callAction()
	{
		if (!class_exists($this->controller)) {
			throw new RoutingException("Controller {$this->controller} doesn't exist.");
		}

		// Register the controller
		$controller = new $this->controller();

		if (!method_exists($controller, $this->method)) {
			throw new RoutingException("Method {$this->method} doesn't exist.");
		}

		// Call the controller's method
		call_user_func_array([$controller, $this->method], [$this->params]);
	}

	/**
	 * Register the matcher class and set routes to it
	 * 
	 * @param string $url
	 * @return void
	 */
	protected function initMatcher(string $url)
	{
		$this->matcher = new RouteMatcher($url, config('routes'));
	}

	/**
	 * Set the callables based on the url
	 *
	 * @return void
	 */
	protected function setCallables()
	{
		$action = $this->matcher->getAction();
		
		$this->controller = Core::controllerNamespace($action[0] ?? '');
		$this->method = $action[1] ?? '';
		$this->params = $action[2] ?? [];
	}
}
