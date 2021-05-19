<?php

namespace App\Framework\Libs;

use App\Framework\Libs\Core;
use App\Framework\Libs\View;
use App\Framework\Logs\ErrorLog;
use App\Framework\Libs\HttpResponse;

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

		if (Core::inProduction())
			ini_set('display_errors', 'Off');
	}

	/**
	 * Exception handler that catches exceptions and redirects them accordingly.
	 */
	public function handler(Throwable $e)
	{
		$env = config('app')['env'];

		$errCode = $e->getCode();
		$errName = $e->name ?? "Internal Server Error";
		$errDescription = $e->description ?? "If the problem persists, please contact the administrator.";

		// TODO: make a class that contains all http errors
		// fall back to generic server error
		if ($errCode == 0) {
			$errCode = 500;
		}

		HttpResponse::setStatus($errCode);

		// Show details if in development environment
		if ($env == 'development') {
			$trace = "
				<div style='padding: 1rem;'>
					In File <span style='font-size: 110%; font-weight: bold;'>".$e->getFile()."</span> on line <span style='font-size: 110%; font-weight: bold;'>".$e->getLine()."</span>
				</div>
			";
			//print_r($e->getTrace());
			echo "<h1>Error</h1>";
			echo "<div style='background: #666; color: #fff; padding: 1rem;'>{$e->getMessage()} (code {$e->getCode()})</div>";
			echo "{$trace}<hr>";
		}

		if (config('app')['log_errors'])
			ErrorLog::write($e);

		if ($env == 'production') {

			// show an error view
			return new View("_errors.default", [
				'title' 			=> "Error {$errCode}",
				'errorCode' 		=> $errCode,
				'errorName' 		=> $errName,
				'errorDescription' 	=> $errDescription
			], ['header', 'footer']);

			/*
			if (View::exists("_errors.{$code}")) {
				return new View("_errors.{$code}");
			} else {
				return new View("_errors.default");
			}
			*/
		}

		die();
	}
}
