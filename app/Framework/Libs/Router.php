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
	protected $postData = [];

	public function __construct(string $url, string $method)
	{
		$this->initMatcher($url, $method);
		$this->setCallables();
	}

	/**
	 * Calls the specified controller with methods and parameters.
	 *
	 * @return void
	 */
	public function callAction()
	{
		if (!class_exists($this->controller))
			throw new RoutingException("Controller {$this->controller} doesn't exist.");

		// Register the controller
		$controller = new $this->controller();

		if (!method_exists($controller, $this->method))
			throw new RoutingException("Method {$this->method} doesn't exist.");

		$arguments = $this->getMethodArguments();

		// Call the action
		call_user_func_array([$controller, $this->method], $arguments);
	}

	public function getMethodArguments() : array
	{
		$request = $this->initRequest();	

		$arguments = [];
		$arguments[] = $request;
		$arguments[] = $this->params;

		return $arguments;
	}

	/**
	 * Build the request
	 *
	 * @return Request
	 **/
	protected function initRequest() : Request
	{
		$request = new Request();
		$request->setData($this->params);

		return $request;
	}

	/**
	 * Register the matcher class and set routes to it
	 * 
	 * @param string $url
	 * @return void
	 */
	protected function initMatcher(string $url, string $method)
	{
		$this->matcher = new RouteMatcher($url, $method, config('routes'));
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
