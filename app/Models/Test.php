<?php

namespace App\Models;

use App\Framework\Model;

class Test extends Model
{
	public function __construct()
	{
		parent::__construct();
		echo "Test Model";
	}
}
