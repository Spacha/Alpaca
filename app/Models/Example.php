<?php

namespace App\Models;

use App\Framework\Libs\Model;

class Example extends Model
{
	/**
	 * Return bar as provided.
	 *
	 * @param string $bar
	 * @return string
	 */
	public static function foo($bar = '') : string
	{
		return "{$bar} from Model";
	}
}
