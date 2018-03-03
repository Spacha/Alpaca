<?php

namespace App\Models;

use App\Framework\Libs\Model;

class Test extends Model
{
	public function __construct()
	{
		// connect();
		echo "Test Model";
	}

	public static function user($id)
	{
		$users = self::users();

		foreach ($users as $user) {
			if ($user['id'] == $id) return $user;
		}

		return [];
	}

	public static function users()
	{
		return [
			[
				'id' => 1,
				'name' => 'Moikka',
				'phone' => '0123456789',
				'age' => 21,
			],
			[
				'id' => 2,
				'name' => 'Vaippa',
				'phone' => '044043202',
				'age' => 15,
			],
			[
				'id' => 3,
				'name' => 'Kassinkoiskari',
				'phone' => '',
				'age' => 33,
			]
		];
	}
}
