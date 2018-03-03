<?php

/*---------------------------------------------------------
 * Alpaca helpers
 *---------------------------------------------------------
 *
 * This file contains general helper function used by Alpaca.
 * You are free to use them as well but if you wish to create
 * your own helper functions, add new one to app folder.
 */

/**
 * @todo Not permanent! Is this even ok ciz we load this every time from drive??
 * @todo config('app.locale'), config('paths.templates') etc...
 * @param string $config Config file's name matching one in app/config
 * @return array Config file as array if found
 */
function config(string $config = '') : array
{
	$configPath = PATH_ROOT . '/config/' . $config . '.php';

	$configFile = file_exists($configPath)
		? require($configPath)
		: [];

	return $configFile;
}

/**
 * Get data value from array. Checks if stuff exists for you.
 *
 * @todo Put this to main Controller
 * @param array $params parameter array which Router passed to the method
 * @return Return the value from the array or null if not found
 */
function data($params = [], $key = '')
{
	return $params[$key] ?? null;
}


/**
 * Unset element from array and return reindexed array
 *
 * @param array $array 
 * @param integer $index Which element to remove
 * @return array
 */
function array_unset(array &$array, $index = 0) : array
{
	unset($array[$index]);
	return array_values($array);
}
