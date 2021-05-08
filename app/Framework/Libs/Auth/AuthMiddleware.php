<?php

namespace App\Framework\Libs\Auth;

use App\Framework\Interfaces\Middleware;
use App\Framework\Libs\Auth\Authenticator;

class AuthMiddleware implements Middleware
{
	protected $requiresAuth = [];
	public $loginPath = 'login';

	public function __construct($requiresAuth = ['*'])
	{
		$this->requiresAuth = $requiresAuth;
	}

	public function check(string $methodName = '') : bool
	{
		// check if an active session is required for given method
		// if yes, check session and throw auth exception
		$authNeeded = in_array('*', $this->requiresAuth) || in_array($methodName, $this->requiresAuth);

		if ($authNeeded)
			return $this->checkSession();

		return true;
	}

	public function loggedIn() : bool
	{
		return Authenticator::loggedIn();
	}

	protected function checkSession() : bool
	{
		if (!Authenticator::validSession())
			header("Location: /{$this->loginPath}");
			die();

		return true;
	}
}