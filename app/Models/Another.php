<?php

namespace App\Models;

use App\Framework\Libs\Model;

class Another extends Model
{
	public function __construct()
	{
	}

	public static function items($id)
	{
		$items = [];

		for ($i=0; $i < $id; $i++) { 
			$items[] = $i.'. ITEEM';
		}

		return $items;
	}
}
