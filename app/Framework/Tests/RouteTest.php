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

		// Test-string									// Expected Result

		return [
			'/' 										=> 'TestController@home',
			'/users' 									=> 'TestController@user',
			'/users/123' 								=> 'TestController@user',
			'/users/1224/posts'	 						=> 'TestController@posts',
			'/users/33/posts/asa' 						=> 'TestController@posts',
			'/users/asas/posts/as/sas'					=> 'TestController@posts',
			'/users/433/posts/7/12'						=> 'TestController@posts',

			'/secret/asas'								=> 'AnotherController@test',
			'/secret'									=> 'AnotherController@test',
			'/secret/123'								=> 'AnotherController@test',
			'/secret/11/122'							=> 'AnotherController@test',
			'/secret/0/sas'								=> 'AnotherController@test',

			'/secret/123/rg/porkkana'					=> '',
			'/users/11/aa'								=> '',
		];
	}
}
