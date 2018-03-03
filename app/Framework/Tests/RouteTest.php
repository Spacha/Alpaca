<?php

namespace App\Framework\Tests;

use App\Framework\Interfaces\TestInterFace;
use App\Framework\Libs\Test;
use Closure;

class RouteTest extends Test implements TestInterFace
{
	protected $routes = [];
	protected $results = [];

	public function __construct($routes = [])
	{
		$this->routes = $routes;
	}

	/**
	 * Run the test by calling matcher.
	 *
	 * @param Closure $matcher Callback function we want to test.
	 * @return bool True on success, false on failure
	 */
	public function run(Closure $matcher) : bool
	{
		$strings = $this->tests();
		$testSuccess = true;
		echo "<li>Running tester...";

		// Run the test strings
		foreach ($strings as $url => $expectation) {
			$result = $matcher($url, $this->routes);
			$success = $result == $expectation;

			$this->results[$url]['result'] = $result;
			$this->results[$url]['success'] = $success;

			// test failed
			if (!$result) $testSuccess = false;
		}

		return $testSuccess;
	}

	/**
	 * Get array of test results
	 *
	 * @return array Results
	 */
	public function getResults() : array
	{
		return $this->results;
	}

	/**
	 * Array of test string. Feel free to add more lines if you need further testing
	 *
	 * @return array Array of test strings
	 */
	protected function tests() : array
	{

		// Test-string						// Expected Result

		return [
			'/'							=> 'TestController@home',
			'/tes'						=> '',
			'/test'						=> 'TestController@test',
			'/testi'					=> '',
			'/test/a'					=> 'TestController@list',
			'/test/penis/paska'			=> '',
			'/test/12'					=> 'TestController@list',
			'/test/penis'				=> 'TestController@list',
			'/test/12/uloste/pieru'		=> '',
			'/test/12/kakka'			=> 'TestController@kakka',
			'/test/penis/kakka'			=> 'TestController@kakka',
			'/test/penis/kakka/ulostaminen'	=> ''
		];
	}
}
