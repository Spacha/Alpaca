<?php

/**


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

// Require Helpers
require 'helpers.php';

echo "<ol><li>Bootstrap registered";

$router = new Router(
	$_SERVER['REQUEST_URI'] ?? "",
	config('routes')
);

$callables = $router->getCallables();

$controller = new \App\Controllers\TestController();

*/

namespace App\Framework;

use App\Framework\Libs\Autoloader;
use App\Framework\Libs\Core;
use App\Framework\Libs\Router;

class Bootstrap
{
	protected $router;

	public function __construct()
	{
		$this->defineConstants();
		$this->requireVitals();
		$this->loadAutoloader();
	}

	/**
	 * Run the application
	 */
	public function run()
	{
		// Register Router
		$this->router = new Router(
			$_SERVER['REQUEST_URI'] ?? ''
		);

		$this->callController($router->getCallables());
	}

	/**
	 * Calls a controller specified by router
	 */
	public function callController($callables)
	{
		// Call specified controller
		$controller = new $callables['controller']();

		// Call controller's method
		call_user_func_array([
			$controller,
			$callables['method'],
			$callables['args'],
		);
	}

	/**
	 * Define global constants to be used anywhere in the application
	 */
	protected function defineConstants()
	{
		define('PATH_ROOT', dirname(__DIR__));
	}

	/**
	 * Require manually those files we need before autoloading
	 */
	protected function requireVitals()
	{
		require_once __DIR__ . '/Libs/Core.php';
		require_once __DIR__ . '/Libs/Autoloader.php';
	}

	/**
	  * Register autoloader to load all class dependencies for the application
	  */
	protected function loadAutoloader()
	{
		new Autoloader();
	}
}
