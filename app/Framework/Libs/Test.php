<?php

namespace App\Framework\Libs;

class Test
{
	/**
	 * Print out prettified results for ebugging purposes
	 * @todo Require an associated view file for this (phtml)
	 */
	public function printPrettyResults()
	{
		echo "<h1>Test Results</h1>";
		echo "<table style='border-collapse: collapse; padding: 1rem;'>";
		echo "<thead><tr><th>Test Url</th> <th>Result</th> <th>Status</th></tr></thead>";
		
		foreach ($this->results as $url => $result) {
			$background = $result['success'] ? 'green' : 'red';
			$resultLabel = $result['success'] ? 'PASSED' : 'FAILED';

			echo (
				"<tr style='border: 1px solid #aaa;'>
					<td>{$url}</td>
					<td>{$result['result']}</td>
					<td style='background: {$background};'>{$resultLabel}</td>
				</tr>"
			);
		}
		echo "</table>";
	}
}
