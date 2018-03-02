<?php

namespace App\Models;

use App\Framework\Libs\Model;

class Test extends Model
{
	public function __construct()
	{
		connect();
		echo "Test Model";
	}

	public static function items()
	{
		return [
			[
				'name' => 'Moikka',
				'phone' => '0123456789',
				'age' => 21,
			],
			[
				'name' => 'Vaippa',
				'phone' => '044043202',
				'age' => 15,
			],
			[
				'name' => 'Kassinkoiskari',
				'phone' => '',
				'age' => 33,
			]
		];
	}
}
