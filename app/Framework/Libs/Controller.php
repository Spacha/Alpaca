<?php

namespace App\Framework\Libs;

use App\Framework\Libs\Database;
use App\Framework\Interfaces\Middleware;

abstract class Controller
{
	protected $model;
	public $middleware;

	public function __construct($model = null, Middleware $middleware = null)
	{
		$this->model = $model;
		$this->middleware = $middleware;
	}
}
