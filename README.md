# Alpaca
Alpaca is light but scalable PHP framework for those who like Rapid and Eloquent Web Development.

# Using Tests


First, we want to define a new test:
```php
namespace App\Framework\Tests;

use App\Framework\Interfaces\TestInterFace;
use App\Framework\Libs\Test;
use Closure;

class RouteTest extends Test implements TestInterFace
{
  protected $names = [];
	protected $results = [];

	public function __construct($routes = [])
	{
		$this->routes = $routes;
	}

	public function run(Closure $testMethod) : bool
	{
		$testSuccess = true;

		// Run the test strings
		foreach ($this->names as $name) {
      $this->results[$name]['result'] = $testMethod($name);

			// test failed
			if (!$this->results[$name]['result'])
        $testSuccess = false;
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

```

Then we use our test in this generic Example class:
```php
use App\Framework\Tests\ExampleTest;

class Example
{
  public function test($testParams)
  {
    // declare test and pass needed parameters
    $test = ExampleTest($testParams);
    
    // run the test
    $test->run(function($name) {
      return $this->isValidName($name);
    });
    
    // this line gives us html view of results so we can debug them
    $test->getPrettyResults();
  }
  
  /**
  * This is the method we want to test
  */
  protected function isValidName($name = '') : bool
  {
    return $name == 'Alpaca';
  }
}

$names = [
  'Alpaca',
  'Monkey',
  'Giraffe',
  1.2
];

$app = new Example();
$app->test($names);

```

This test prints pretty html-table of results to the page. Optionally you can get raw array of results with ```getResults()``` method. and save it to a separate log for example.
