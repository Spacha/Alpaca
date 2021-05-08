<?php

namespace App\Framework\Libs;

use App\Framework\Libs\Database\QueryBuilder;

abstract class Model
{
	protected $db;
	
	public function __construct()
	{
		$this->db = $this->connect();
	}

	public function connect()
	{
		return new QueryBuilder();
	}
}
