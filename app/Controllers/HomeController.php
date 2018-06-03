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
	public function home()
	{
		return new View('home.home', ['active' => 'home'], ['header', 'footer']);
	}

	public function about()
	{
		return new View('about.about', ['active' => 'about'], ['header', 'footer']);
	}
}
