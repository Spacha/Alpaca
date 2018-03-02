<?php

namespace App\Framework;

use PDO;

Class Database extends PDO
{
	protected $db;
	
	public function __construct()
	{
		echo "<li> Database registered";
	}
}
