<?php

namespace App\Framework\Libs;

use App\Framework\Logs\ErrorLog;

use Throwable;

class ExceptionHandler
{
	protected $dump = false;

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
	public function handler(Throwable $e)
	{
		$trace = "
			<div style='padding: 1rem;'>
				In File <span style='font-size: 110%; font-weight: bold;'>".$e->getFile()."</span> on line <span style='font-size: 110%; font-weight: bold;'>".$e->getLine()."</span>
			</div>
		";
		//print_r($e->getTrace());
		echo "<h1>Error</h1>";
		echo "<div style='background: #666; color: #fff; padding: 1rem;'>{$e->getMessage()} (code {$e->getCode()})</div>";
		echo "{$trace}<hr>";

		ErrorLog::write($e);

		die();
	}
}
