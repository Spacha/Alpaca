<?php

namespace App\Framework\Libs;

use App\Framework\{
	Libs\Request,
	Libs\Controller,
	Libs\RouteMatcher,
	Libs\Auth\Authenticator,
	Interfaces\Middleware,
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
		$this->setAction();
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

		// Check controller middleware if there is one
		$this->runMiddleware($controller);

		$arguments = $this->getArguments();

		// Call the action
		call_user_func_array([$controller, $this->method], $arguments);
	}

	/**
	 * Check controller middleware if it has any.
	 * @author Miika Sikala <miikasikala96@gmail.com>
	 *
	 * @param \App\Framework\Libs\Controller $controller
	 * @return void
	 */
	protected function runMiddleware(Controller $controller)
	{
		Authenticator::startSession();

		if ($this->controllerHasMiddleware($controller))
			$controller->middleware->check($this->method);
	}

	/**
	 * Check if given controller has a middleware.
	 * @author Miika Sikala <miikasikala96@gmail.com>
	 *
	 * @param \App\Framework\Libs\Controller $controller
	 * @return bool
	 */
	protected function controllerHasMiddleware(Controller $controller)
	{
		return property_exists($controller, 'middleware') &&
			($controller->middleware instanceof Middleware);
	}

	/**
	 * Build the request.
	 *
	 * @return Request
	 **/
	protected function initRequest() : Request
	{
		$request = new Request();
		$request->injectPostData($this->params);

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
	 * Set the action or 'callables' based on the current url
	 *
	 * @return void
	 */
	protected function setAction()
	{
		$action = $this->matcher->getAction();
		
		$this->controller 	= $action['controller'] ?? '';
		$this->method 		= $action['method'] ?? '';
		$this->params 		= $action['params'] ?? [];
	}

	/**
	 * Get all arguments based on current request method.
	 *
	 * @return array
	 */
	public function getArguments() : array
	{
		$request = $this->initRequest();

		$arguments = [$request];
		$arguments += $this->params;

		return $arguments;
	}
}
