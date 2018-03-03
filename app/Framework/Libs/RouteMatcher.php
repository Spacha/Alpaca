<?php

namespace App\Framework\Libs;

use App\Framework\Exceptions\RoutingException;

/**
 * @todo Maybe this should have SyntaxTransformer or something
 */
class RouteMatcher
{
	/**
	 * Return handled routes array
	 *
	 * @param array $routes Array of beautified routes
	 * @return array Handled routes
	 */
	public static function handleRoutes(array $routes) : array
	{
		return self::routesToRegex($routes);
	}

	/**
	 * Checks if given url matches any of the given routes.
	 *
	 * @param string $url
	 * @param array $routes
	 * @return bool
	 */
	public static function getCallables(string $url, array $routes) : array
	{
		return self::match($url, $routes);
	}

	/**
	 * Converts beautified route strings into regular expressions
	 *
	 * @param array $routes Array of beautified routes
	 * @return array
	 */
	public static function routesToRegex(array $routes) : array
	{
		$find = [
			'any' 		=> '([a-zA-Z0-9åäö_-]+)',
			'brackets' 	=> '/\{(.*?)\}/',
			'literals'	=> '/\//'
		];

		$formattedRoutes = [];

		// @todo Group and separate these into own methods
		foreach ($routes as $url => $action) {

			$url = trim($url, "/");

			// extract parameter keys from route for later use
			preg_match_all($find['brackets'], $url, $paramKeys);

			// replace brackets with matching exceptions
			$url = preg_replace($find['brackets'], $find['any'], $url);

			// add backslash in front of literals
			$url = preg_replace($find['literals'], "\/", $url);

			// add starting and ending delimeters and push to array
			$formattedRoutes["/^\/{$url}$/"] = [
				'action' 	=> $action,
				'paramKeys' => $paramKeys[1]
			];
		}

		return $formattedRoutes;
	}

	/**
	 * Get the last matching ocurrence from given route array.
	 *
	 * @param string $url
	 * @param array $routes
	 * @return array Matching array from route array or empty array if no match
	 */
	protected static function match(string $url, array $routes) : array
	{
		$result = ['',[]];

		foreach ($routes as $match => $action) {
			if (preg_match($match, $url)) {

				// if we have a match, build an action for it

				preg_match_all($match, $url, $params);

				$result = self::buildAction($action, $params[1] ?? []);
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
	protected static function buildAction(array $parts, $params = []) : array
	{
		// Explode route syntax to callables
		$action = explode('@', $parts['action']) ?? [];

		if (count($action) !== 2)
			throw new RoutingException("Invalid route definition: {$parts['action']}.");

		$paramArr = [];

		// Make key value pairs of parameters
		// For example:
		// route: test/{userId} and url: test/12 => "usedId" => 12
		for($i = 0; $i < count($params); $i++) {
			$paramArr[$parts['paramKeys'][$i] ?? 'unknown'] = $params[$i];
		}

		$action[2] = $paramArr;

		return $action;
	}
}
