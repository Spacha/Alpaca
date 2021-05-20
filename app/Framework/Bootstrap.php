<?php
/**
 * This is the entry point of the whole application.
 * This boots up everything.
 */

namespace App\Framework;

use App\Framework\Libs\{
	Core,
	Router,
	Request,
	Autoloader,
	ExceptionHandler
};

/**
*
*/
class Bootstrap
{
	protected $router;

	/**
	 * Call initializing methods
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->defineConstants();
		$this->requireVitals();
		$this->loadAutoloader();
		$this->setExceptionHandler();
	}

	/**
	 * Run the application
	 *
	 * @return void
	 */
	public function run()
	{
		// TODO: Maybe this is not the place?
		date_default_timezone_set(config('app')['timezone']);

		$this->router = new Router(
			Request::uri(),
			Request::method()
		);

		// What if route changes or something? We don't need to register all again?
		$this->router->callAction();
	}

	/**
	 * Define global constants to be used anywhere in the application.
	 *
	 * @return void
	 */
	protected function defineConstants()
	{
		define('PATH_ROOT', dirname(__DIR__));
	}

	/**
	 * Require manually those files we need before autoloading.
	 *
	 * @return void
	 */
	protected function requireVitals()
	{
		require_once __DIR__ . '/Libs/Core.php';
		require_once __DIR__ . '/Libs/Autoloader.php';

		require_once __DIR__ . '/helpers.php';
		// Require vital Alpaca parts 
		//Core::requireVitals();
		// Require others, like files user has configured to be loaded (app/helper.php...)
		//Core::requireExtras();
	}

	/**
	  * Register autoloader to load all class dependencies for the application.
	  *
	 * @return void
	  */
	protected function loadAutoloader()
	{
		new Autoloader();
	}

	/**
	 * Set exception handler for the application.
	 *
	 * @return void
	 */
	protected function setExceptionHandler()
	{
		new ExceptionHandler();
	}
}
