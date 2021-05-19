<?php

namespace App\Framework\Libs;

use Exception;

class Log
{
	/**
	 * Write a new line to specified log.
	 *
	 * @param string $line String want to be appended to the log
	 * @param string $log Name of the log file we want to write to
	 */
	public static function writeLog($line, $log)
	{
		$logPath = self::filePath($log);

		$file = @fopen($logPath, 'a');

		// failed to open/create file, notify
		if (!$file) {
			echo "<b>Warning:</b> Log file '{$log}' is unwritable, check permissions of the log folder.";
		} else {
			fwrite($file, "\r\n\r\n[". date(config('app')['date_format']) ."] ". $line);
			fclose($file);
		}
	}
	
	/**
	 * Return full path to the log file of given name.
	 *
	 * @param  string $log
	 * @return string
	 */
	public static function filePath(string $log) : string
	{
		return dirname(PATH_ROOT).'/'.config('paths')['logs'].'/'.$log.'.log';
	}
}
