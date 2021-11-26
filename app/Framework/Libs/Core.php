<?php

namespace App\Framework\Libs;

Class Core
{
	/**
	 * Translates class namespace to storage path
	 *
	 * @param string $className Class name we want to translate
	 * @return string
	 */
	public static function classRoute($className) : string
	{
		$className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
		$className = strstr($className, DIRECTORY_SEPARATOR);

		return $className;
	}

	/**
	 * Translates controller name to full namespaced class
	 *
	 * @param string $controller Controller we want to translate
	 * @return string
	 */
	public static function controllerNamespace($controller) : string
	{
		return sprintf('App\Controllers\%s', $controller);
	}

	/**
	 * Tells if the app is currently set to run in production environment.
	 * For security reasons, this is true "more often than it should".
	 * The application is assumed to be in production unless
	 * it specifically is in 'development' mode.
	 *
	 * @return bool
	 */
	public static function inProduction() : bool
	{
		return !self::inDevelopment();
	}

	/**
	 * Tells if the app is currently set to run in development environment.
	 *
	 * @return bool
	 */
	public static function inDevelopment() : bool
	{
		return config('app')['env'] == 'development';
	}
}
