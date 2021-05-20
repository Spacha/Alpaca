<?php

namespace App\Controllers;

use App\Framework\Libs\{
	Controller,
	Request,
};

// Custom View, App extends the main View and has a strict Interface!
// The view has a certain layout, let's say, '_layouts/app.phtml', for example
use Views\App as View;

use App\Models\Test;

class ViewTestController extends Controller
{
	public function test()
	{
		// Get data
		$users = ['matt', 'finn', 'travis'];
		$cities = ['Monaco', 'Helsinki', 'Andorra'];

		// Handle data
		array_map(function($user) {
			return ucfirst($user)
		}, $users);

		// View(string TEMPLATE_PATH, array DATA)
		return new View('static.test', compact('users', 'cities'));
	}
}
