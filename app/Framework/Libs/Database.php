<?php

namespace App\Framework\Libs;

use PDO;

Class Database extends PDO
{
	protected $db;
	
	public function __construct()
	{
		echo "<li> Database registered";
	}
}
