<?php

namespace App\Controllers;

use App\Framework\Libs\Auth\RequiresAuth;
use App\Framework\Libs\Auth\AuthMiddleware;
use App\Framework\Libs\{
	Controller,
	Request,
	View
};

use App\Models\Secret;

class SecretController extends Controller
{
	protected $requiresAuth = ['*'];

	public function __construct()
	{
		parent::__construct(
			new Secret(),
			new AuthMiddleware($this->requiresAuth)
		);
	}

	public function home()
	{
		return new View('secret.home', [
			'title' => 'Spacha — Secret area'
		], ['header', 'footer', 'secret-toolbar']);
	}


	//
	// Sub-pages
	//

	public function errorLog()
	{
		return new View('secret.error-log', [
			'title' 	=> 'Spacha — Error Log',
			'errorLog' 	=> $this->model->getErrorLog()
		], ['header', 'footer', 'secret-toolbar']);
	}

	public function todoList()
	{
		return new View('secret.todo-list', [
			'title' => 'Spacha — Todo list'
		], ['header', 'footer', 'secret-toolbar']);
	}

	public function settings()
	{
		return new View('secret.settings', [
			'title' => 'Spacha — Settings'
		], ['header', 'footer', 'secret-toolbar']);
	}
}
