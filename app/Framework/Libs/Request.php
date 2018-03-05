<?php

namespace App\Framework\Libs;

class Request
{
	public function __construct()
	{
		//
	}

	public function setParams(array $params)
	{
		foreach ($params as $key => $value) {
			$this->$key = $value;
		}
	}
}
