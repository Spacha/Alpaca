<?php

namespace App\Models;

use App\Framework\Libs\Model;
use App\Framework\Libs\Database;

class Blog extends Model
{
	public function add($data)
	{
		// $this->db->into('posts')->insert([
		// 	'title' 		=> $data['title'],
		// 	'content'		=> $data['content'],
		// 	'category_id' 	=> 1,
		// 	'created_at' 	=> date(config('app')['date_format'])
		// ]);

		// return $this->db->lastInsertId();
	}

	public function list() : array
	{
		return $this->db->select(['id', 'title', 'content', 'created_at'])->from('posts')->get();
	}

	public function view($postId)
	{
		// $this->db->select('posts', ['title', 'content', 'category_id', 'created_at'], "id = {$postId}")
		// 	->join('posts.user_id', 'users.id')
		// 	->use('users.name as author')
	}
}
