<?php

namespace App\Controllers;

use App\Framework\Libs\{
	Controller,
	Request,
	View
};

use App\Models\Test;

class HomeController extends Controller
{
	protected $header = '<a href="/">Home</a> | <a href="/users">Users</a> | <a href="/about">About</a>';

	public function home()
	{
		return new View('home.home', ['header' => $this->header]);
	}

	public function about()
	{
		return new View('about.about', ['header' => $this->header]);
	}
}
