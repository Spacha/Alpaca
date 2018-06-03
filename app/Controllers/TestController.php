<?php

namespace App\Controllers;

use App\Framework\Libs\{
	Controller,
	Request
};

use App\Models\Test;

class TestController extends Controller
{
	/**
	 * Init the model.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(new Test());
	}

	/**
	 *
	 */
	public function test()
	{
		dd($this->model->test());
	}
}
