<?php

namespace App\Framework\Libs;

use App\Framework\Libs\Database;

abstract class Controller
{
	protected $model;

	public function __construct($model = null)
	{
		$this->model = $model;
	}
}
