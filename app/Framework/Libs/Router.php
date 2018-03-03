<?php

namespace App\Framework\Libs;

use App\Framework\Libs\Core;
use App\Framework\Libs\RouteMatcher;
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
		$this->setUrl($url);
		$this->getRoutes();
		$this->setCallables();
	}

	/**
	 * Calls the specified controller
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
	 * Trim and set the url. Empty url is converted to a slash: '/'
	 */
	protected function setUrl(string $url)
	{
		$url = rtrim($url, '/');
		$this->url = strlen($url) ? $url : '/';
	}

	/**
	 * Set the callables based on url
	 */
	protected function setCallables()
	{
		//var_dump($this->routes);
		$action = RouteMatcher::getCallables($this->url, $this->routes);

		die(var_dump($action));
		
		$this->controller = Core::controllerNamespace($action[0] ?? '');
		$this->method = $action[1] ?? '';
		$this->params = $action[2] ?? [];
	}

	/**
	 * Load route config and explode it into route-callable pairs
	 */
	protected function getRoutes()
	{
		$routes = config('routes');
		$this->routes = RouteMatcher::routesToRegExp($routes);
	}
}
