<?php

Class Index extends Controller
{
	public function __construct()
	{
		parent::__construct();
		echo "<li>Index Controller";
	}
	
	public function index($param1 = null, $param2 = null)
	{
		echo "<li>Index method";
		if (isset($param1)) {
			 echo "(param1 = '" . $param1 . "')";
		}
		if (isset($param2)) {
			 echo ", (param2 = '" . $param2 . "')";
		}
	}
}
