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
	 * Find and extract certain ocurrences from a string to an array
	 * 
	 * @param $match The needle
	 * @param $subject The haystack
	 * @return array Array of needles
	 */
	protected static function extractParts($match, $subject) : array
	{
		preg_match_all($brackets, $url, $params);
		return array_unset($params)[0];
	}

	/**
	 * Converts beautified route strings into regular expressions
	 *
	 * @param array $routes Array of beautified routes
	 * @return array
	 */
	public static function routesToRegex(array $routes) : array
	{
		// Regular expressions
		$brackets = '/\{(.*?)\}/';
		$optional = '/^\?(.*?)/';
		$any = '([a-zA-Z0-9åäö_-]+)';
		$anyOrNothing = '([a-zA-Z0-9åäö_-]*)';

		$formattedRoutes = [];

		foreach ($routes as $url => $action) {

			$paramClause = $any;
			$url = trim($url, "/");

			// extract parameter keys from route for later use
			$params = self::extractParts($brackets, $url);

			// find optional parameters
			foreach ($params as $$param) {
				if (preg_match($optional, $param)) {

					// remove the question mark from param name
					str_replace($url, '[/]?', strrpos($url, '/'), 1);

					// make last slash optional
					$url = substr_replace($url, '[/]?', strrpos($url, '/'), 1);
					$param = str_replace('?', '', $param);
					$paramClause = $anyOrNothing;
				}
			}

			// make expressions of brackets and make slashes literal
			$url = preg_replace([$brackets, "/\//"], [$paramClause, "\/"], $url);

			// add starting and ending delimeters and push to array
			$formattedRoutes["/^\/{$url}$/"] = [
				'action' => $action,
				'paramKeys' => $paramKeys
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
		$result = ['','',[]];

		foreach ($routes as $match => $action) {
			if (preg_match($match, $url)) {

				// if we have a match, let's build an action for it
				preg_match_all($match, $url, $params);
				$params = array_unset($params);

				$result = self::buildAction($action, $params ?? []);
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
		for($i = 0; $i < count($params); $i++) {

			$paramKey = $parts['paramKeys'][$i] ?? 'unknown';
			$paramArr[$paramKey] = $params[$i][0];

		}

		$action[2] = $paramArr;

		return $action;
	}
}
