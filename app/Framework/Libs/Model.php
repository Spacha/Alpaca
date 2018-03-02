<?php

namespace App\Framework;

abstract Class Model
{
	protected $db;
	
	public function __construct()
	{
		$this->db = new Database();

		echo "<li> Main Model";
	}
}
