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
	public static function writeLog(string $line, string $log)
	{
		$logPath = self::filePath($log);
		$file = @fopen($logPath, 'a');

		// failed to open/create file, notify
		if (!$file) {
			echo "<b>Warning:</b> Log file '{$log}' is unwritable, check permissions of the log folder.";
		} else {

			// if the file is full, do not write
			if (@filesize($logPath) < config('app')['log_max_size']) {
				fwrite($file, "\r\n[". date(config('app')['date_format']) ."] ". $line);
				fclose($file);
			}
		}
	}

	/**
	 * Read and return the contents of the file as a string. If the log file is
	 * not found or is not readable, return false.
	 *
	 * @param  string  $log
	 * @return string/false
	 */
	public static function readLog(string $log)
	{
		$logPath = self::filePath($log);

		$file = @fopen($logPath, 'r');

		if (!$file) {
			return false;
		} else {
			$contents = fread($file, filesize($logPath));
			fclose($file);
		}

		return $contents;
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
