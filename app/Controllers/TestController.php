<?php

namespace App\Controllers;

use App\Framework\Controller;
use App\Framework\Model;

class TestController extends Controller
{
	protected $model;

	public function __construct()
	{
		parent::__construct();
		$this->model = new Model();

		echo "<li> Test Controller";
	}
}
