<?php

namespace App\Models;

use App\Framework\Libs\Model;
use App\Framework\Logs\ErrorLog;

class Secret extends Model
{
	public function getErrorLog()
	{
		return ErrorLog::read();
	}
}
