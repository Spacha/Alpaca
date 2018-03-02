<?php

namespace App\Framework\Libs;

use App\Framework\Logs\ErrorLog;

class ExceptionHandler
{
	/**
	 * Set exception handler
	 */
	public function __construct()
	{
		set_exception_handler([$this, 'handler']);
	}

	/**
	 * Exception handler that catches exceptions and redirects them accordingly.
	 */
	public function handler($e)
	{
		echo "<h1>Error</h1>";
		echo "<b>Error!</b> {$e->getMessage()} (code {$e->getCode()})";

		ErrorLog::write($e);

		die();
	}
}
