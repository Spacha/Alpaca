<?php

namespace App\Controllers;

use App\Framework\Libs\Controller;

use App\Models\Test;

class TestController extends Controller
{
	protected $header = "
		<a href='/'>Home</a> |
		<a href='/users'>Users</a> |
		<a href='/error'>Error</a>";

	public function home()
	{
		echo $this->header;
		echo "<h2>Home</h2>";
	}

	public function user($data = [])
	{
		$users = Test::items();

		echo $this->header;
		echo "<h2><a href='/users'><</a> Users</h2>";

		if (count($data)) {
			echo "<h3></h3>";
			echo "User: ". $data['userId'];
		} else {
			echo "<h3>Select user:</h3>";

			for ($id=0; $id < 10; $id++) { 
				echo "<li><a href='/users/{$id}'>User id {$id}</a></li>";
			}
		}

		if (isset($data['userId'])) {
			// echo "<h1>User {$params['userId']}</h1>";
		} else {
			// echo var_dump($users);
		}
	}
}
