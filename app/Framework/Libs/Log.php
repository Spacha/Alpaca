<?php

namespace App\Framework\Libs;

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
		$logPath = self::logPath($log);
		
		$file = fopen($logPath, 'a');
		fwrite($file, "\r\n\r\n[". date(config('app')['date_format']) ."] ". $line);
		fclose($file);
	}
	
	/**
	 * Return full path to the log file of given name.
	 *
	 * @param  string $log
	 * @return string
	 */
	public static function logPath(string $log) : string
	{
		return dirname(PATH_ROOT).'/'.config('paths')['logs'].'/'.$log.'.log';
	}
}
