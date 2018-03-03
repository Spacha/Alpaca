<?php

namespace App\Controllers;

use App\Framework\Libs\Controller;

use App\Models\Test;

class TestController extends Controller
{
	public function home()
	{
		echo "Home sweet home!";
	}

	public function list($params = [])
	{
		$users = Test::items();

		if (isset($params['userId'])) {
			echo "<h1>User {$params['userId']}</h1>";
		} else {
			echo var_dump($users);
		}
	}
}
