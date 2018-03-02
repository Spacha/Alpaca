<?php

namespace App\Framework\Libs;

Class Core
{
	/**
	 * Translates class namespace to storage path
	 *
	 * @param string $className Class name we want to translate
	 */
	public static function classRoute($className) : string
	{
		$className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
		$className = strstr($className, DIRECTORY_SEPARATOR);

		return $className;
	}
}
