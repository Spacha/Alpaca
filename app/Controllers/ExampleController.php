<?php

namespace App\Controllers;

use App\Framework\Libs\{
	Controller,
	Request
};

use App\Models\Example;

class ExampleController extends Controller
{
	/**
	 * Init the model.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(new Example());
	}

	/**
	 * Display 'bar'.
	 *
	 * @param string string
	 * @return void
	 */
	public function foo()
	{
		$bar = $this->model->foo('bar');

		echo "TestController: {$bar}";
	}
}
