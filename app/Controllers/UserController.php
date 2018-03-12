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
	public function __construct()
	{
		parent::__construct(new User());
	}

	public function list()
	{
		$users = $this->model->list();

		return new View('users.list', [
			'active' => 'users',
			'users' => $users
		], ['header', 'footer']);
	}

	public function view(Request $request, $userId)
	{
		$user = $this->model->view($userId);

		// user not found
		if (!$user)
			die('User not found.');
		
		return new View('users.view', [
			'active' => 'users',
			'user' => $user
		], ['header', 'footer']);
	}

	public function create()
	{
		return new View('users.add', ['active' => 'users'], ['header', 'footer']);
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
