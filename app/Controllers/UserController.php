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

		// user not found
		if (!$user)
			die('User not found.');
		
		return new View('users.view', [
			'header' => $this->header,
			'user' => $user
		]);
	}

	public function create()
	{
		return new View('users.add', [
			'header' => $this->header
		]);
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
