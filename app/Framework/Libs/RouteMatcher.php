<?php

namespace App\Framework\Libs;

use App\Framework\Libs\Core;
use App\Framework\Exceptions\InternalException;

/**
 * Takes care of parsing and matching urls and routes.
 * Contains also some grammar stuff.
 */
class RouteMatcher
{
	protected $routes = [];
	
	protected $url = '';
	protected $method = '';

	/**
	 * Set the url and routes
	 *
	 * @param string $url
	 * @param array $routes
	 * @return void
	 */
	public function __construct(string $url, string $method, array $routes)
	{
		$this->setUrl($url);
		$this->method = $method;
		$this->routes = $this->routesToRegex($routes);
	}

	/**
	 * Gives ready-to-use action based on the current route
	 *
	 * @return array The callables in an array
	 */
	public function getAction() : array
	{
		return $this->getCallables();
	}

	/**
	 * Get regular expressions
	 *
	 * @param string $key The regex key
	 * @return strign The regex
	 */
	protected function regex($key) : string
	{
		$regex = [
			'optional' 	=> '/^\?(.*?)/',
			'method'	=> '/^(\&|\$)\:/',
			'brackets' 	=> '/\{(.*?)\}/',
			'something' => '([a-zA-Z0-9åäö_-]+)',
			'anything' 	=> '([a-zA-Z0-9åäö_-]*)',
		];

		return $regex[$key] ?? '';
	}

	/**
	 * Trim trailing slash and set the url. Empty url is converted to a slash.
	 *
	 * @param string $url
	 * @return void
	 */
	protected function setUrl(string $url)
	{
		$url = rtrim(parse_url($url, PHP_URL_PATH), '/');
		$this->url = strlen($url) ? $url : '/';
	}

	/**
	 * Converts beautified route strings into regular expressions
	 *
	 * @todo These regex things should be in their own Object!
	 * (public) routesToRegex
	 * (public) match
	 * (protected) extractParts
	 * (protected) isOptional
	 *
	 * @param array $routes Array of beautified routes
	 * @return array
	 */
	public function routesToRegex(array $routes) : array
	{
		$regexRoutes = [];

		foreach ($routes as $url => $action) {

			$paramClause = $this->regex('something');

			// Get the request method
			$method = $this->getMethodFromRoute($url);

			// Trim method keys and spaces and slashes
			$url = trim(preg_replace($this->regex('method'), '', $url), '/ ');
			$url = str_replace(' ', '', $url);

			// Extract parameter keys from the route for later use
			$params = $this->extractParts($this->regex('brackets'), $url)[0];

			// Find optional parameters
			foreach ($params as &$param) {
				if ($this->isOptional($param)) {
					// remove question mark from the route
					$param = str_replace('?', '', $param);

					// make the last slash optional
					$paramClause = $this->regex('anything');
				}
			}

			// Make slashes optional
			$url = $this->optionalizeSlashes($url, count($params));

			// Make expressions of brackets and make slashes literal
			$url = trim(preg_replace(
				[$this->regex('brackets'), "/\//"],
				[$paramClause, '\/'],
			$url));

			// Add starting and ending delimeters and push to array
			$regexRoutes[] = [
				'url' => "/^\/{$url}$/",
				'method' => $method,
				'action' => $action,
				'paramNames' => $params
			];
		}
		
		return $regexRoutes;
	}

	/**
	 * Get the last matching ocurrence from given route array.
	 *
	 * @return array Matching array from route array or empty array if no match
	 */
	protected function getCallables() : array
	{
		$result = 	['','',[]];

		foreach ($this->routes as $action) {
			
			if ($this->isMatchingUrl($action['url'], $this->url, $action['method'])) {
				// if we have a match, let's build an action for it
				$params = $this->extractParts($action['url'], $this->url);
				// @todo USE SMARTED METHOD!
				foreach ($params as &$param) {
					$param = $param[0];
				}

				// the first matching route is always taken as an action
				$result = $this->buildAction($action, $params ?? []);
				break;
			}
		}

		return $result;
	}

	/**
	 * Builds an action based on routes and current url
	 *
	 * @todo Extract this to Parser / SyntaxTransformer
	 * @param string $parts 	Associative array of pretty string version of the
	 *							action (route syntax) and parameter keys of the route
	 * @param array $params 	Parameters we want to pass along
	 * @return array 			Action array with controller, method and
	 *							parameters ordered nicely
	 */
	protected function buildAction(array $action, $params = []) : array
	{
		// Explode route syntax to callables
		$actionParts = explode('@', $action['action']) ?? [];

		if (count($actionParts) !== 2)
			throw new InternalException("Invalid route definition: {$action['action']}.", 500);

		$paramArr = [];

		// Make key value pairs of parameters
		for($i = 0; $i < count($params); $i++) {

			// filter empty parameters
			if (!isset($params[$i]) || empty($params[$i])) continue;

			$paramKey = $action['paramNames'][$i] ?? 'unknown';
			$paramArr[$paramKey] = $params[$i];

		}

		return [
			'controller' 	=> Core::controllerNamespace($actionParts[0]),
			'method' 		=> $actionParts[1],
			'params' 		=> $paramArr
		];
	}


	/**---------------------------------------------
	 * Methods From Here are REGEX and GRAMMAR stuff
	 *--------------------------------------------*/


	/**
	 * Find and extract certain ocurrences from a string to an array.
	 * Also removes the 'full-match' array.
	 * 
	 * @param $match 	The needle
	 * @param $subject 	The haystack
	 * @return array 	Array of the picked needles
	 */
	protected function extractParts(string $match, string $subject) : array
	{
		preg_match_all($match, $subject, $needles);
		return array_unset($needles) ?? [];
	}

	/**
	 * Returns the method defined in the given route string
	 *
	 * @param string $url
	 * @return string
	 */
	protected function getMethodFromRoute(string $route) : string
	{
		preg_match_all($this->regex('method'), $route, $results);

		if (isset($results[1]) && isset($results[1][0])) {	
			return  $this->getMethodName($results[1][0]);
		}

		throw new InternalException("Method missing from route definiton {$route}.");
	}

	/**
	 * Returns the method name by key.
	 *
	 * @param string $key Key of the method
	 * @return mixed 
	 */
	protected function getMethodName(string $key)
	{
		$methods = [
			'&' => 'GET',
			'$' => 'POST'
		];

		if(array_key_exists($key, $methods))
			return $methods[$key];
		else
			return false;
	}

	/**
	 * Return true if given parameter is optional
	 *
	 * @param string $param
	 * @return 
	 **/
	protected function isOptional(string $param) : bool
	{
		return preg_match($this->regex('optional'), $param);
	}

	/**
	 * Make slashes in front of optional parameters also optional by matching
	 * ocurrences of pattern '/{?' which simply means that there is a slash and
	 * after that, a parameter starting by question mark, which means optional.
	 *
	 * @param string $url
	 * @return string
	 */
	protected function optionalizeSlashes(string $url) : string
	{
		return preg_replace('/(\/)(?=\{\?)/', '[/]?', $url);
	}

	/**
	 * Checks if two urls are matching
	 *
	 * @param string $match 	The first url we will compare to another one
	 * @param string $url 		The another url
	 * @param string $method 	The method of the url
	 * @return bool
	 */
	public function isMatchingUrl(string $match, string $url, string $method) : bool
	{
		return preg_match($match, $url) && $this->method == $method;
	}
}
