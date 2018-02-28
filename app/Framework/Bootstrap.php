<?php

namespace App\Framework;

class Bootstrap
{
	public function __construct()
	{
		// Define root path
		define('PATH_FILE_ROOT', dirname(__DIR__));

		// Autoload
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
	}

	public function run()
	{
		// Require Helpers
		require 'helpers.php';

		echo "<ol><li>Bootstrap registered";

		$router = new Router(
			$_SERVER['REQUEST_URI'] ?? "",
			config('routes')
		);

		$callables = $router->getCallables();

		require PATH_FILE_ROOT.'/Controllers/TestController.php';
		require PATH_FILE_ROOT.'/Models/Test.php';

		$controller = new \App\Controllers\TestController();
	}
}
