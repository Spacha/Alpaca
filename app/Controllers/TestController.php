<?php

namespace App\Controllers;

use App\Framework\Libs\Controller;

use App\Models\Test;

class TestController extends Controller
{
	public function home()
	{
		echo "<li>HOME";
	}

	public function test()
	{
		echo "<li>JUST TEST";
	}

	public function list($data = [])
	{
		$users = Test::items();

		if (count($data)) {
			echo "<li>LIST WITH PARAMS";
			echo "User: ". $data['userId'];
		} else {
			echo "<li>EMPTY LIST";
		}

		if (isset($data['userId'])) {
			// echo "<h1>User {$params['userId']}</h1>";
		} else {
			// echo var_dump($users);
		}
	}

	public function kakka($params = [])
	{
		echo "<li>KAKKA";
	}
}
