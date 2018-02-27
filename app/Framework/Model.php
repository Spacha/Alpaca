<?php

namespace Alpaca;

Class Model
{
	protected $db;
	
	public function __construct()
	{
		$this->db = new Database($config['database']);
	}
}
