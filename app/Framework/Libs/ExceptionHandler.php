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
		@set_exception_handler([$this, 'handler']);

		if (Core::inProduction())
			ini_set('display_errors', 'Off');
	}

	/**
	 * Exception handler that catches exceptions and redirects them accordingly.
	 *
	 * @param Throwable $e 	Throwable caught by the error handler.
	 *                      Contains the exception.
	 * @return 
	 */
	public function handler(Throwable $e)
	{
		$env = config('app')['env'] ?? 'production';

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

		if (config('app')['log_errors'] && $this->isLoggable($errCode))
			ErrorLog::write($e);

		if ($env == 'production') {

			// show an error view
			return new View("_errors.default", [
				'title' 			=> "Error {$errCode}",
				'errorCode' 		=> $errCode,
				'errorName' 		=> $errName,
				'errorDescription' 	=> $errDescription
			], ['header', 'footer']);

		}

		die();
	}

	/**
	 * Returns whether the error should be logged or not. Generally only
	 * server errors (500 - 599) should be logged.
	 * 
	 * @param int $errCode 	The HTTP error code.
	 * @param bool
	 */
	protected function isLoggable(int $errCode) : bool
	{
		return (500 <= $errCode) && ($errCode <= 599);
	}
}
