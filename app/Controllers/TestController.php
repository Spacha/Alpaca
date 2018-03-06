<?php

namespace App\Controllers;

use App\Framework\Libs\{
	Controller,
	Request
};

use App\Models\Test;

class TestController extends Controller
{
	protected $header = '';

	public function __construct()
	{
		$this->header = "
		<a href='/'>Home</a> |
		<a href='/users'>Users</a> |
		<a href='/error'>Cause Error</a> |
		<a href='/secret/". rand(0,1000) ."'>Secret</a> |";
	}


	public function posts(Request $request)
	{
	  // Getting a single parameter
	  $userId = $request->data('userId');
	  $postId = $request->data('postId');

	  var_dump("User's id: {$userId} and post's id: {$postId}");

	  // Getting all parameters
	  $allParams = $request->data();
	  
	  var_dump($allParams);
	}

	public function home(Request $request)
	{
		echo public_root('img/miika.jpg');
		echo $this->header;
		echo "<h2>Home</h2>";
		echo "<form action='/ripuli/send' method='POST'>";

		echo "<p><input type='text' name='file_name' placeholder='File name' /></p>";
		echo "<p><textarea name='content' placeholder='Content...'></textarea></p>";
		echo "<button type='submit'>Submit</button>";

		echo "</form>";
	}

	public function papers(Request $request, $subject)
	{
		$content = opt($request->data('content'), 'No content provided.');

		$success = Test::insert($request->data('file_name'), $request->data('content'), true);

		$route = $success
			? "/"
			: "/error";

		header("Location: {$route}");
	}

	public function users(Request $request)
	{
		$userId = $request->data('userId');
		echo "Moikka. Tässä on sulle vähän dataa: {$userId}".PHP_EOL;

		print_r($request->data());
	}

	// OLD STUFF

	public function user($userId = 0, $postId = 0, $pageId = 0)
	{
		$users = Test::users();

		echo $this->header;
		echo "<h2><a href='/users'><</a> Users</h2>";

		if ($userId) {
			
			// View a user

			$user = Test::user($userId);

			echo "<h3></h3>";
			echo "<p><b>User: {$userId}</b></p>";

			echo "<p><b>Name: {$user['name']}</b></p>";

			echo "<p><a href='/users/{$userId}/posts'>Show User Posts</a></p>";

		} else {

			// Show user listing

			echo "<h3>Select user:</h3>";

			foreach ($users as $user) {
				echo "<li><a href='/users/{$user['id']}'>User id {$user['id']}</a></li>";
			}
		}
	}
}
