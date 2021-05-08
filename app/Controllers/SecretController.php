<?php

namespace App\Controllers;

use App\Framework\Libs\Auth\RequiresAuth;
use App\Framework\Libs\Auth\AuthMiddleware;
use App\Framework\Libs\{
	Controller,
	Request,
	View
};

use App\Models\User;

class SecretController extends Controller
{
	protected $requiresAuth = ['*'];

	public function __construct()
	{
		parent::__construct(
			new User(),
			new AuthMiddleware($this->requiresAuth)
		);
	}

	public function home()
	{
		return new View('secret.home', [], ['header', 'footer']);
	}
}
