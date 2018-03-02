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
		call_user_func_array([$controller, $this->method], $this->params);
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
		// /test/{userId} => /test\/[a-z,0-9]*/	
		$route = "/test/{userId}";
		$anyChar = "\[a-z,0-9\]+";
		$brackets = "/\{(.*?)\}/";
		$url = $this->url;

		$regExpRoutes = [];

		// transform pretty routes into ugly regExp
		foreach ($this->routes as $match => $action) {
			preg_match_all($brackets, $match, $matches);

			if (count($matches[1])) $action[3] = $matches[1];

			$key = preg_replace($brackets, $anyChar, $match);
			$regExpRoutes[$key] = $action;
		}
		var_dump($regExpRoutes);

		$exp = "/test\/[a-z,0-9]*/";
		// '^\/test\/[0-9]+$/'
		if (preg_match($exp, $this->url)) {
			var_dump('woa');
		}

		// $this->controller = Core::controllerNamespace($route[0] ?? '');
		// $this->method = $route[1] ?? '';
		// $this->params = $route[2] ?? [];
	}

	/**
	 * Load route config and explode it into route-callable pairs
	 */
	protected function getRoutes()
	{
		$routes = config('routes');

		// Explode route syntax to callables
		array_walk($routes, function(&$action, $match) {
			$action = explode('@', $action);
		});

		$this->routes = $routes;
	}
}
