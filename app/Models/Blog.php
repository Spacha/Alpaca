<?php

namespace App\Models;

use App\Framework\Libs\Model;
use App\Framework\Libs\Database;

class Blog extends Model
{
	public function add($data)
	{
		$this->db->insert('posts', [
			'name' 			=> $data['title'],
			'age' 			=> $data['content'],
			'category_id' 	=> 1,
			'created_at' 	=> date(config('app')['date_format'])
		]);

		return $this->db->lastInsertId();
	}

	public function list() : array
	{
		return $this->db->select('posts', ['id', 'title', 'content', 'created_at'])->get();
	}

	public function view($postId)
	{
		return $this->db->select('posts', [], "id = {$postId}")->first();
	}
}
