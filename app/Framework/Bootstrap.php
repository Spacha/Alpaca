<?php

namespace App\Framework;

require 'Model.php';
require 'View.php';
require 'Controller.php';
require 'Router.php';
require 'Autoloader.php';

define('PATH_FILE_ROOT', dirname(__DIR__));

echo "<ol><li>Bootstrap registered";


spl_autoload_register(function($className) {
	$className = str_replace('\\', DIRECTORY_SEPARATOR, $className);

	// this should be in: getClassRoute($className);
	// Get rid off this App\ part in front of every namespace
	$appPart = 'App\\';
	$className = substr($className, strpos($className, $appPart) + strlen($appPart));
	
	if (is_file(PATH_FILE_ROOT . '/' . $className . '.php')) {
        require(PATH_FILE_ROOT . '/' . $className . '.php');
    }
});

$router = new Router($_SERVER['REQUEST_URI'] ?? "");

require PATH_FILE_ROOT.'/Controllers/TestController.php';
require PATH_FILE_ROOT.'/Models/Test.php';

$controller = new \App\Controllers\TestController();

echo "</ol>";
