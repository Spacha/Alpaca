<?php

namespace App\Framework\Tests;

use App\Framework\Interfaces\TestInterFace;
use App\Framework\Libs\Test;
use Closure;

class RouteTest extends Test implements TestInterFace
{
	protected $routes = [];

	/**
	 * Boot it up.
	 * Don't forget to construct parent constructor, otherwise the test won't work!
	 *
	 * @param array $routes
	 */
	public function __construct($routes = [])
	{
		parent::__construct();
		$this->routes = $routes;
	}

	/**
	 * Run the test by calling matcher.
	 * @todo Most of these should go to Test class!
	 *
	 * @param Closure $matcher Callback function we want to test.
	 * @return bool True on success, false on failure
	 */
	public function run(Closure $callback) : bool
	{
		return $this->runTests($this->tests(), $callback, [$this->routes]);
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
