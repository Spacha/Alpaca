<?php

// TODO: config('app.locale'), config('paths.templates') etc...
// no need to relaod the whole thing every time, right?
function config($config = '')
{
	$configPath = PATH_FILE_ROOT . '/config/' . $config . '.php';

	$configFile = file_exists($configPath)
		? require($configPath)
		: [];

	return $configFile;
}