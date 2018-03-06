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

	public function home()
	{
		echo $this->header;
		echo "<h2>Home</h2>";
	}

	public function papers(Request $request)
	{
		$paperId = $request->paperId;

		echo "I'm a controller and I got: {$paperId} safely!";
	}

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

	public function posts($userId, $postId = 0, $pageId = 0)
	{

		$route = "/users/{$userId}/posts";

		echo $this->header;
		echo "<h2><a href='{$route}'><</a> Posts</h2>";

		if ($postId) {
			$route = $route."/".$postId;

			// View a post

			//$post = Test::post($postId);

			echo "<h3>Post title</h3>";
			echo "<p>Post id: {$postId}</p>";
			echo "<p>Post content</p>";

			echo "<p><a href='{$route}/1'>Show extra 1</a></p>";
			echo "<p><a href='{$route}/2'>Show extra 2</a></p>";

			if ($pageId) {
				echo "<p><a href='{$route}'>Close extra</a></p>";
				echo "<div style='padding: 5rem; background: linear-gradient(#999, #333)'>
					EXTRAAA NUMBEER {$pageId}!!!
				</div>";
			}

		} else {

			// Show user's post listing

			//$posts = Test::posts($postId);

			for ($id=0; $id < 10; $id++) {
				echo "<li><a href='{$route}/{$id}'>Post id {$id}</li>";
			}
		}
	}
}
