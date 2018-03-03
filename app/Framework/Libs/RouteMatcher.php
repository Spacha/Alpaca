<?php

namespace App\Framework\Libs;

/**
 * @todo Maybe this should have SyntaxTransformer or something
 */
class RouteMatcher
{
	/**
	 * Converts beautified route strings into regular expressions
	 *
	 * @param array $routes Array of beautified routes
	 * @return array
	 */
	public static function routesToRegExp(array $routes) : array
	{
		/*
		// Better method name maybe (HOX: this is called from Router)?
		// also #-_?&
		*/

		// Explode route syntax to callables
		array_walk($routes, function(&$action) {
			$action = explode('@', $action);
		});

		$find = [
			'any' 		=> '([a-zA-Z0-9åäö]+)',
			'brackets' 	=> '/\{(.*?)\}/',
			'literals'	=> '/\//'
		];

		$formattedRoutes = [];

		foreach ($routes as $url => $action) {

			$url = trim($url, "/");

			// extract parameted keys from route for later use
			preg_match_all($find['brackets'], $url, $params);

			// replace brackets with matching exceptions
			$url = preg_replace($find['brackets'], $find['any'], $url);

			// add backslash in front of literals
			$url = preg_replace($find['literals'], "\/", $url);

			// add starting and ending delimeters and push to array
			$formattedRoutes["/^\/{$url}$/"] = [$action, $params[1]];
		}

		return $formattedRoutes;
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
	 * Get the last matching ocurrence from given route array.
	 *
	 * @param string $url
	 * @param array $routes
	 * @return array Matching array from route array or empty array if no match
	 */
	protected static function match(string $url, array $routes) : array
	{
		$result = ['',[]];

		array_walk($routes, function($action, $match) use ($url, &$result) {
			if (preg_match($match, $url)) {
				$result = $action;
			}
		});
		
		return $result;
	}
}
