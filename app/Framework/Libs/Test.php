<?php

namespace App\Framework\Libs;

use Closure;

class Test
{
	protected $status = true;
	protected $results = [];

	/**
	* @todo Make more general arrays so we can have tests with multiple parameters:
	*		$tests = [
	*			[
	*				'params' => [param1, param2, ...],
	*				'expectation' => true
	*			]
	*		]
	* @param array $tests Array conatining tests we will run through
	* @param Closure $callback Function/method we want to test
	*
	*/
	protected function validator($expectation, $result)
	{
		echo $expectation ." => ". $result . PHP_EOL;
		return $expectation == $result;
	}
	protected function runTests(array $tests, Closure $callback, ...$params)
	{
		foreach ($tests as $test => $expectation) {
			$result = $callback($test, $params[0]);

			$this->saveResults(
				$test,
				$result,
				$expectation,
				$this->validator($expectation, $result[0])
			);
		}

			// $result = $callback($url, $this->routes);
			// $success = $result[0] == $expectation;

			// $this->results[$url]['result'] = $result;
			// $this->results[$url]['success'] = $success;

			// // test failed
			// if (!$result) $testSuccess = false;

		return true;
	}

	protected function saveResults(string $test, $result, $expectation, bool $status)
	{
		$this->results[$test] = [
			'result' => $result,
			'expectation' => $expectation,
			'status' => $status,
		];
	}

	/**
	 * Print out prettified results for debugging purposes
	 * @todo Require an associated view file for this (phtml).
	 *		 Clean this up!
	 */
	public function printPrettyResults()
	{
		$status = $this->status
			? "<span style='background: green; color: #fff;'>OK</span>"
			: "<span style='background: red; color: #fff;'>FAILED</span>";

		echo "<h1>Test Results {$status}</h1>";
		echo "<table style='border-collapse: collapse; padding: 1rem;'>";
		echo "<thead><tr><th>Test Url</th> <th>Result</th> <th>Expectation</th> <th>Status</th></tr></thead>";
		
		foreach ($this->results as $url => $result) {
			$background = $result['status'] ? 'green' : 'red';
			$resultLabel = $result['status'] ? 'PASSED' : 'FAILED';
			$action = $result['result'][0] ?? '';
			$params = implode(', ', $result['result'][1] ?? []);
			$expectation = $result['expectation'] ?? '';

			echo (
				"<tr style='border: 1px solid #aaa;'>
					<td style='padding-left: 0.4rem;'>{$url}</td>
					<td>{$action}({$params})</td>
					<td>{$expectation}</td>
					<td style='background: {$background}; padding: 0.4rem;'>{$resultLabel}</td>
				</tr>"
			);
		}
		echo "</table>";
	}
}
