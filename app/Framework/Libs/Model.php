<?php

namespace App\Framework\Libs;

abstract Class Model
{
	protected $db;
	
	public function __construct()
	{
		echo "<li> Main Model";
	}

	public function connect()
	{
		$this->db = new Database();
	}
}
