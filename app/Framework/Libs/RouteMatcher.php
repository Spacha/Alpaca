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
		// These match to some ocurrences in the routes
		$find = [
			'any' 			=> '(.*?)',		// anything that ocurres at least once
			'brackets' 		=> '/\{(.*?)\}/',	// brackets which contain at least one character
			'slash' 		=> '/\//',
		];

		$regExpRoutes = [];

		// transform pretty routes into ugly regExp
		foreach ($routes as $match => $action) {

			$match = (strlen($match) > 1)
				? trim($match, "/")
				: $match;

			// get parameter names and save them to actions for later use
			preg_match_all($find['brackets'], $match, $matches);
			if (count($matches[1])) $action[2] = $matches[1];

			// replace brackets with matching regExp
			preg_replace(
				[$find['brackets'], $find['slash']],
				[$find['any'], "\/"],
				$match
			);

			$key = preg_replace($find['brackets'], $find['any'], $match);
			$key = preg_replace("/\//", "\/", $key);

			// add wrappers and end operator ($)
			$key = "/{$key}$/";
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
	public static function match(string $url, array $routes) : array
	{
		// THIS IS HORRIFIC! DO IT AGAIN!

		// Loop through routes and check if any of them matches the url
		foreach ($routes as $match => $action) {
			if (preg_match($match, $url)) {
				// get parameters if there are any
				preg_match_all($match, $url, $params);
				if (isset($params[1]) && count($params[1])) {
					$newParams = [];
					foreach ($params[1] as $key => $param) {
						$newParams[$action[2][$key]] = $param;
					}
					$action[2] = $newParams;
				}

				return $action;

			}
		}

		return [];
	}
}
