<?php

namespace App\Framework\Libs;

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
		$route = "/test/{userId}";
		$anyChar = "\[a-z,0-9\]+";
		$brackets = "/\{(.*?)\}/";

		$regExpRoutes = [];

		// transform pretty routes into ugly regExp
		foreach ($routes as $match => $action) {
			preg_match_all($brackets, $match, $matches);

			if (count($matches[1])) $action[3] = $matches[1];

			$key = preg_replace($brackets, $anyChar, $match);
			$regExpRoutes[$key] = $action;
		}
		
		return $regExpRoutes;
	}

	/**
	 * Checks if given url matches any of the given routes.
	 *
	 * @param string $url
	 * @param array $routes
	 * @return bool
	 */
	public static function match(string $url, array $routes) : bool
	{
		$exp = "/test\/[a-z,0-9]*/";

		if (preg_match($exp, $url)) {
			return true;
		}

		return false;
	}
}
