<?php

namespace App\Controllers;

use App\Framework\Libs\{
	Controller,
	Request,
	View
};

use App\Models\User;

class UserController extends Controller
{
	protected $header = '<a href="/">Home</a> | <a href="/users">Users</a> | <a href="/about">About</a><hr>';

	public function __construct()
	{
		parent::__construct(new User());
	}

	public function list()
	{
		$users = $this->model->list();

		return new View('users.list', [
			'header' => $this->header,
			'users' => $users
		]);
	}

	public function view(Request $request, $userId)
	{
		$user = $this->model->view($userId);

		echo $this->header;

		// user not found
		if (!$user)
			die('User not found.');
		
		echo "<ul>";
			echo "<li> ID: {$user->id}";
			echo "<li> Name: {$user->name}";
			echo "<li> Age: {$user->age}";
		echo "</ul>";
	}

	public function create()
	{
		echo $this->header;

		echo "<h1>Create a user</h1>";

		echo "<form action='/users/add' method='POST'>";
			echo "<input type='text' name='name' placeholder='Name' />";
			echo "<input type='number' name='age' placeholder='Age' />";
			echo "<input type='text' name='phone' placeholder='Phone' />";
			echo "<button type='submit'>Add</button>";
		echo "</form>";
	}

	public function add(Request $request)
	{
		$id = $this->model->add([
			'name' => $request->data('name'),
			'age' => $request->data('age'),
			'phone' => $request->data('phone')
		]);

		header("Location: /users/{$id}");
	}
}
