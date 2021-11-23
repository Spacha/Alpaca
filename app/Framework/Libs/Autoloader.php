<?php

namespace App\Framework\Libs;

use App\Framework\Libs\Core;

class Autoloader
{
	/**
	 * Registers class loader for the spl autoloader
	 */
	public function __construct()
	{
		if (config('app')['use_vendors'])
			require app_path('vendor', 'autoload.php');

		spl_autoload_register([$this, 'loader']);
	}

	/**
	 * Loader method which handles loading every class for the application's needs
	 *
	 * @param string $className Class name the spl-registerer injects
	 */
	private function loader($className)
	{
		$file = PATH_ROOT . Core::classRoute($className) . '.php';

		if (is_file($file))
			require_once($file);
	}
}
