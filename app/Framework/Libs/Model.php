<?php

namespace App\Framework\Libs;

use App\Framework\Libs\Database;

abstract class Model
{
	protected $db;
	
	public function __construct()
	{
		$this->db = $this->connect();
	}

	public function connect()
	{
		return new Database(dbConfig('test'));
	}
}
