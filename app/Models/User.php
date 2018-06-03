<?php

namespace App\Models;

use App\Framework\Libs\Model;
use App\Framework\Libs\Database;

class User extends Model
{
	public function add($data)
	{
		// $this->db->into('users')->insert([
		// 	'name' => $data['name'],
		// 	'age' => $data['age'],
		// 	'phone' => $data['phone']
		// ]);

		// return $this->db->lastInsertId();
	}

	public function list() : array
	{
		return $this->db->select()->from('users')->get();
	}

	public function view($userId)
	{
		return $this->db->select()->from('users')
			->where('id', $userId)
			->limit(1)
			->get()[0];
	}
}
