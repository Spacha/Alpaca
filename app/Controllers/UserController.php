<?php

namespace App\Controllers;

use App\Framework\Exceptions\RoutingException as NotFound;
use App\Framework\Libs\{
	Auth\AuthMiddleware,
	Auth\Authenticator,
	Controller,
	Request,
	View
};

use App\Models\User;

class UserController extends Controller
{
	protected $requiresAuth = ['create', 'add', 'delete', 'logout'];

	public function __construct()
	{
		parent::__construct(
			new User(),
			new AuthMiddleware($this->requiresAuth)
		);
	}

	public function list()
	{
		$users = $this->model->list();

		return new View('users.home', [
			'active' => 'users',
			'users' => $users
		], ['header', 'footer']);
	}

	public function view(Request $request, $userId)
	{
		$user = $this->model->view($userId);

		// user not found
		if (!$user)
			throw new NotFound("User id '{$userId}' not found");
		
		return new View('users.view', [
			'active' => 'users',
			'user' => $user
		], ['header', 'footer']);
	}

	public function create()
	{
		return new View('users.create', ['active' => 'users'], ['header', 'footer']);
	}

	public function add(Request $request)
	{
		$id = $this->model->add([
			'name' => $request->data('name'),
			'email' => $request->data('email'),
			'active' => 1
		]);

		redirect("/users/{$id}");
	}

	public function delete(Request $request, $userId)
	{	
		$this->model->delete($userId);

		redirect("/users");
	}

	// Authentication

	public function login()
	{
		return new View('auth.login', [], ['header', 'footer']);
	}

	public function register()
	{
		return new View('auth.register', [], ['header', 'footer']);
	}

	public function logout()
	{
		Authenticator::logout();

		redirect("/");
	}

	public function tryLogin(Request $request)
	{
		$success = Authenticator::authenticate(
			$request->data('email'),
			$request->data('password')
		);

		if ($success)
			redirect("/secret");
		else
			redirect("/login");
	}
}
