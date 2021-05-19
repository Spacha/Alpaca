<?php

namespace App\Models;

use App\Framework\Libs\Model;
use App\Framework\Libs\Database;

class User extends Model
{
	public function add($data)
	{
		$this->db->into('users')->insert([
			'name' 		=> $data['name'],
			'email' 	=> $data['email'],
			'password' 	=> $data['password'],
			'active' 	=> $data['active']
		])->execute();

		return $this->db->lastInsertId();
	}

	public function list() : array
	{
		return $this->db->select()->from('users')
			->orderBy('id', 'DESC')
			->get();
	}

	public function view(int $userId)
	{
		return $this->db->select()->from('users')->where('id', $userId)->first();
	}

	public function delete(int $userId)
	{
		return $this->db->delete()->from('users')->where('id', $userId)->execute();
	}
}
