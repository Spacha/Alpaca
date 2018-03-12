<?php

namespace App\Controllers;

use App\Framework\Libs\{
	Controller,
	Request
};

use App\Models\Test;

class HomeController extends Controller
{
	protected $header = '<a href="/">Home</a> | <a href="/users">Users</a> | <a href="/about">About</a>';

	public function home()
	{
		echo $this->header;

		echo "<h1>Home</h1>";
		echo "<p>This is home. Home sweet home!</p>";
		echo "<img src='images/logo_blue.svg' style='width: 5rem;' />";
	}

	public function about()
	{
		echo $this->header;

		echo "<h1>About</h1>";
		echo "<p>Contains documentation from here and there… About everything for now, until I care to split different subjects into their own files.</p>";
		echo "<p>– Spacha</p>";
	}
}
