<?php

namespace App\Framework;

use App\Framework\Libs\Core;
use App\Framework\Libs\Router;
use App\Framework\Libs\ExceptionHandler;
use App\Framework\Libs\Autoloader;

class Bootstrap
{
	protected $router;

	public function __construct()
	{
		$this->defineConstants();
		$this->requireVitals();
		$this->loadAutoloader();
		$this->setExceptionHandler();
	}

	/**
	 * Run the application
	 */
	public function run()
	{
		$this->router = new Router(
			$_SERVER['REQUEST_URI'] ?? ''
		);

		// What if route changes or something? We don't need to register all again?

		$this->router->callAction();
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

		require_once __DIR__ . '/helpers.php';
	}

	/**
	 * 
	 */
	protected function setExceptionHandler()
	{
		new ExceptionHandler();
	}

	/**
	  * Register autoloader to load all class dependencies for the application
	  */
	protected function loadAutoloader()
	{
		new Autoloader();
	}
}
