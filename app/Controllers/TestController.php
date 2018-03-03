<?php

namespace App\Controllers;

use App\Framework\Libs\Controller;

use App\Models\Test;

class TestController extends Controller
{
	protected $header = "
		<a href='/'>Home</a> |
		<a href='/users'>Users</a> |
		<a href='/error'>Cause Error</a>";

	public function home(array $data)
	{
		echo $this->header;
		echo "<h2>Home</h2>";
	}

	public function user(array $data)
	{
		$users = Test::users();
		$userId = data($data, 'userId');

		echo $this->header;
		echo "<h2><a href='/users'><</a> Users</h2>";

		if (isset($userId)) {
			
			// Show user page

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

	public function posts(array $data)
	{
		// @todo maybe $data should be this object's property so we can simply use: $this->data('userId'); 
		$userId = data($data, 'userId');
		$postId = data($data, 'postId');

		echo $this->header;
		echo "<h2><a href='/users/{$userId}'><</a> Posts</h2>";

		if (isset($postId)) {

			// Show post page

			//$post = Test::post($postId);

			echo "<h3>Post title</h3>";
			echo "<p>Post id: {$postId}</p>";
			echo "<p>Post content</p>";

		} else {

			// Show user's post listing

			//$posts = Test::posts($postId);

			for ($id=0; $id < 10; $id++) {
				echo "<li><a href='/users/{$userId}/posts/{$id}'>Post id {$id}</li>";
			}
		}
	}
}
