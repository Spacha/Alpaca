<?php

require("Libs/Model.php");
require("Libs/View.php");
require("Libs/Controller.php");
require("Libs/Router.php");

// TODO: Use an autoloader

require("Controllers/Index.php");

$url = $_GET['url'];

$router = new Router($url);
$route = $router->getRoute();

$controller = new $route['controller']();
$controller->$route['action']($route['param1'] ? $route['param1'] : null,
	$route['param2'] ? $route['param2'] : null);
