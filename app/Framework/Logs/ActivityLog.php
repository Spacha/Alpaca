<?php

namespace App\Framework\Logs;

use App\Framework\Libs\Log;

class ActivityLog extends Log
{
	/**
	 * Write new entry to 'activity' log
	 *
	 * @param string $line String want to be appended to the log
	 */
	public static function write($line) {
		parent::writeLog($line, 'activity');
	}

	public static function read() {
		return parent::readLog('activity');
	}
}
