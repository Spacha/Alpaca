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


/*---------------------------------------------------------
 * 		VARIABLE HELPERS
 *---------------------------------------------------------
 *
 */

/**
 * Get data value from array. Checks if stuff exists for you.
 *
 * @param array $params parameter array which Router passed to the method
 * @return Return the value from the array or null if not found
 */
function data($params = [], $key = '')
{
	return $params[$key] ?? null;
}

/**
 * Prevents unwanted errors from happening. Return $var if exist,
 * otherwise return default value which is null as default
 *
 * @param $var The variable
 * @param $default If $var is undefined, use this, null as default
 * @return Return the value or default value
 */
function opt($var = null, $default = null)
{
	return $var ?? $default;
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


/*---------------------------------------------------------
 * 		DEVELOPMENT HELPERS
 *---------------------------------------------------------
 *
 */

/**
 * Dumps given variable and kills the program.
 * 
 * @param $var Variable you want to get dumped
 **/
function dd($var = null)
{
	echo "<pre>";
	
	die(var_dump($var));

	echo "</pre>";
}

/**
 * Dumps all given variables without killing the program.
 * Also separates all of them nicely
 * 
 * @param ...$vars All the variables you want to be dumped
 * @return void
 **/
function bb(...$vars)
{
	print_r('_______________________________________________________');

	foreach ($vars as $var) {
		print_r(PHP_EOL);
		print_r($var);
		print_r(PHP_EOL);
	}

	print_r('_______________________________________________________'.PHP_EOL);
}