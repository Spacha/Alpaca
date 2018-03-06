<?php

namespace App\Framework\Libs;

use App\Framework\{
	Libs\Request,
	Libs\RouteMatcher,
	Exceptions\RoutingException
};


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
		$request = new Request();
		$request->setParams($this->params);

		if (!class_exists($this->controller))
			throw new RoutingException("Controller {$this->controller} doesn't exist.");

		// Register the controller
		$controller = new $this->controller();

		if (!method_exists($controller, $this->method))
			throw new RoutingException("Method {$this->method} doesn't exist.");

		// Call the action
		call_user_func_array([$controller, $this->method], [$request]);
	}

	/**
	 * Build the request
	 *
	 * @return Request
	 **/
	protected function buildRequest() : Request
	{
		$request = new Request();
		$request->setParams($this->params);

		return $request;
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
		
		$this->controller 	= $action['controller'] ?? '';
		$this->method 		= $action['method'] ?? '';
		$this->params 		= $action['params'] ?? [];
	}
}
