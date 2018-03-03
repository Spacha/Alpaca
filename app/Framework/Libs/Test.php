<?php

namespace App\Framework\Libs;

use Closure;

class Test
{
	protected $status = true;
	protected $results = [];
	protected $validator;

	/**
	 * Boot up the Test Engine
	 *
	 * @param $validator Array or String of user defined validator
	 */
	public function __construct($validator = null)
	{
		$this->setValidator($validator ?? [$this, 'validator']);
	}

	/**
	 * Run the tests user provided by calling the callback for each one
	 *
	 * @param array $tests Array containing test rows
	 * @param Closure $callback Function/method we will test
	 * @param array $params Array of parameters to pass be passed to the callable
	 * @return bool True on success, false on failure
	 */
	protected function runTests(array $tests, Closure $callback, array $params = []) : bool
	{
		foreach ($tests as $test => $expectation) {

			// call the function we want to test
			$result = $callback($test, $params[0]);

			// validate test result
			$status = call_user_func_array($this->validator, [$expectation, $result[0]]);

			// test failed
			if (!$status) $this->status = false;

			$this->saveResults($test, $result, $expectation, $status);
		}

		return $this->status;
	}

	/**
	 * Save test results to object state
	 *
	 * @param string $test
	 * @param $result
	 * @param $expectation
	 * @param bool $status
	 */
	protected function saveResults(string $test, $result, $expectation, bool $status)
	{
		$this->results[$test] = [
			'result' => $result,
			'expectation' => $expectation,
			'status' => $status,
		];
	}

	/**
	 * Set user-defined validator
	 *
	 * @param $validator String or Array of callable function
	 */
	protected function setValidator($validator = null)
	{
		$this->validator = $validator;
	}

	/**
	 * The default validator which simply compares if two of them are the same
	 *
	 * @param $expectation Value the testable function should return to be successful
	 * @param $result Result we actually got from the function
	 */
	protected function validator($expectation, $result)
	{
		return $expectation == $result;
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
