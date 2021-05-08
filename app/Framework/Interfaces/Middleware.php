<?php

namespace App\Framework\Interfaces;

interface Middleware
{
	public function check(string $methodName = '') : bool;
}
