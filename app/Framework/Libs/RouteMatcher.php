<?php

namespace App\Framework\Libs;

use App\Framework\Exceptions\RoutingException;

class RouteMatcher
{
	protected $routes = [];
	protected $url = '';

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
		$this->routes = $this->routesToRegex($routes);
	}

	/**
	 * Gives ready-to-use action based on the current route
	 *
	 * @return array The callables in an array
	 */
	public function getAction() : array
	{
		$test = new \App\Framework\Tests\RouteTest($this->routes);
		$test->run(function($url) {
			return $this->getCallables($url);
		});
		$test->printPrettyResults();
return [];
		//return $this->getCallables();
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
			'brackets' 	=> '/\{(.*?)\}/',
			'something' => '([a-zA-Z0-9åäö_-]+)',
			'anything' 	=> '([a-zA-Z0-9åäö_-]*)'
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
		$url = rtrim($url, '/');
		$this->url = strlen($url) ? $url : '/';
	}

	/**
	 * Converts beautified route strings into regular expressions
	 *
	 * @param array $routes Array of beautified routes
	 * @return array
	 */
	public function routesToRegex(array $routes) : array
	{
		$regexRoutes = [];

		foreach ($routes as $url => $action) {

			//$paramClause = $this->regex('something');
			$paramClause = '([a-zA-Z0-9åäö_-]+)';

			$url = trim($url, "/");

			// extract parameter keys from route for later use
			$params = $this->extractParts($this->regex('brackets'), $url)[0];

			// Find optional parameters and
			// remove question mark from the route and 
			// make the last slash optional
			foreach ($params as &$param) {
				if ($this->isOptional($param)) {

					$param = str_replace('?', '', $param);
					$paramClause = $this->regex('anything');
					//$paramClause = '([a-zA-Z0-9åäö_-]*)';
				} else {

				}
			}

			// make slashes optional
			$url = $this->optionalizeSlashes($url, count($params));

			// make expressions of brackets and make slashes literal
			$url = preg_replace(
				[$this->regex('brackets'), "/\//"],
				[$paramClause, "\/"],
			$url);

			// add starting and ending delimeters and push to array
			$regexRoutes["/^\/{$url}$/"] = [
				'action' => $action,
				'paramKeys' => $params
			];
		}

		return $regexRoutes;
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
	 * Get the last matching ocurrence from given route array.
	 *
	 * @return array Matching array from route array or empty array if no match
	 */
	protected function getCallables(string $url) : array
	{
		$result = 	['','',[]];
		$routes = $this->routes;

		foreach ($routes as $match => $action) {
			if (preg_match($match, $url)) {

				// if we have a match, let's build an action for it
				$params = $this->extractParts($match, $url);

				// USE SMARTED METHOD!
				foreach ($params as &$param) {
					$param = $param[0];
				}

				//bb($url, $match, $params);

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
	protected function buildAction(array $parts, $params = []) : array
	{
		// Explode route syntax to callables
		$action = explode('@', $parts['action']) ?? [];

		if (count($action) !== 2)
			throw new RoutingException("Invalid route definition: {$parts['action']}.");

		$paramArr = [];

		// Make key value pairs of parameters
		for($i = 0; $i < count($params); $i++) {

			$paramKey = $parts['paramKeys'][0][$i] ?? 'unknown';
			$paramArr[$paramKey] = $params[$i];

		}

		$action[2] = $paramArr;

		return $action;
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
}
