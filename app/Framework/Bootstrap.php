<?php

require("Libs/Model.php");
require("Libs/View.php");
require("Libs/Controller.php");
require("Libs/Router.php");

spl_autoload_register(function($className) {
	require("Controllers/" . $className . ".php");
});

// Define global constants
define('ROOT_PATH', dirname(__DIR__));

// Url handling
$url = !empty($_GET["url"]) ? $_GET["url"] : null;
$router = new Router($url);
$route = $router->getRoute();

// Finally call a proper method
$controller = new $route['controller']();
call_user_func_array([$controller, $route['action']], $route['paramArr']);

// Add exceptions!
