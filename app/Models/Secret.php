<?php

namespace App\Models;

use App\Framework\Libs\Model;
use App\Framework\Logs\ActivityLog;
use App\Framework\Logs\ErrorLog;

class Secret extends Model
{
	public function getLog(string $log)
	{
		if ($log == 'activity') {
			return ActivityLog::read();
		} else if ($log == 'error') {
			return ErrorLog::read();
		}
	}
}
