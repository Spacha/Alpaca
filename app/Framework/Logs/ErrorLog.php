<?php

namespace App\Framework\Logs;

use App\Framework\Libs\Log;

class ErrorLog extends Log
{
	/**
	 * Write new entry to 'error' log
	 *
	 * @param string $line String want to be appended to the log
	 */
	public static function write($line) {
		parent::writeLog($line, 'error');
	}

	public static function read() {
		return parent::readLog('error');
	}
}
