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

	public function list($userId = 0)
	{
		$users = Test::items();

		if ($userId) {
			echo 'User: '.$userId;
		} else {
			echo var_dump($users);
		}
	}
}
