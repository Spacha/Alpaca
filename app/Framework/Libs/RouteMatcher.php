<?php

namespace App\Framework\Libs;

use App\Framework\Libs\Core;
use App\Framework\Exceptions\InternalException;

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
	public function __construct(string $url, array $routes)
	{
		$this->setUrl($url);
		$this->setMethod();
		$this->routes = $this->routesToRegex($routes);
	}
	public function setMethod() : void
	{
		$this->method = $_SERVER['REQUEST_METHOD'];
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
		$this->url = rtrim(parse_url($url, PHP_URL_PATH), '/');
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

			// Parse request method
			$method = $this->getMethod($url);

			$url = trim($url, "/");

			// extract parameter keys from route for later use
			$params = $this->extractParts($this->regex('brackets'), $url)[0];

			// find optional parameters
			foreach ($params as &$param) {
				if ($this->isOptional($param)) {
					// remove question mark from the route
					$param = str_replace('?', '', $param);

					// make the last slash optional
					$paramClause = $this->regex('anything');
				}
			}

			// make slashes optional
			$url = $this->optionalizeSlashes($url, count($params));

			// make expressions of brackets and make slashes literal
			$url = trim(preg_replace(
				[$this->regex('method'), $this->regex('brackets'), "/\//"],
				['', $paramClause, '\/'],
			$url));

			// add starting and ending delimeters and push to array
			$regexRoutes["/^\/{$url}$/"] = [
				'method' => $method,
				'action' => $action,
				'paramKeys' => $params
			];
		}
		
		return $regexRoutes;
	}
	protected function getMethod(string $url) : string
	{
		preg_match_all($this->regex('method'), $url, $results);

		if (isset($results[1]) && isset($results[1][0])) {	
			return  $this->getMethodByKey($results[1][0]);
		}

		throw new InternalException("Method missing from route definiton {$url}.");
	}
	protected function getMethodByKey(string $key)
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
	 * Get the last matching ocurrence from given route array.
	 *
	 * @return array Matching array from route array or empty array if no match
	 */
	protected function getCallables() : array
	{
		$url = 		$this->url;
		$method = 	$this->method;
		$routes = 	$this->routes;

		$result = 	['','',[]];

		foreach ($routes as $match => $action) {
			if (preg_match($match, $url) && $this->method == $action['method']) {

				// if we have a match, let's build an action for it
				$params = $this->extractParts($match, $url);

				// USE SMARTED METHOD!
				foreach ($params as &$param) {
					$param = $param[0];
				}

				$result = $this->buildAction($action, $params ?? []);
			}	
		}

		return $result;
	}

	/**
	 *
	 * @todo Extract this to Parser / SyntaxTransformer
	 * @param string $parts Associative array of pretty string version of the action (route syntax) and parameter keys of the route
	 * @param array $params parameters we want to pass along
	 * @return array Action array with controller, method and parameters ordered nicely
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

			$paramKey = $action['paramKeys'][$i] ?? 'unknown';
			$paramArr[$paramKey] = $params[$i];

		}

		return [
			'controller' 	=> Core::controllerNamespace($actionParts[0]),
			'method' 		=> $actionParts[1],
			'params' 		=> $paramArr
		];
	}

	/**
	 * Find and extract certain ocurrences from a string to an array
	 * 
	 * @param $match The needle
	 * @param $subject The haystack
	 * @return array Array of needles
	 */
	protected function extractParts(string $match, string $subject) : array
	{
		preg_match_all($match, $subject, $needles);
		return array_unset($needles) ?? [];
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
}
