<?php

namespace App\Models;

use App\Framework\Libs\Model;
use App\Framework\Libs\Database;

class Blog extends Model
{
	public function add($data)
	{
		$this->db->into('posts')->insert([
			'title' 		=> $data['title'],
			'content'		=> $data['content'],
			'author_id' 	=> $data['author_id'],
			'category_id' 	=> $data['category_id'],
			'created_at' 	=> date(config('app')['date_format'])
		])->execute();

		return $this->db->lastInsertId();
	}

	public function list() : array
	{
		return $this->db->select(['id', 'title', 'content', 'created_at'])
			->from('posts')
			->orderBy('created_at', 'desc')
			->get();
	}

	public function view($postId)
	{
		// $this->db->select('posts', ['title', 'content', 'category_id', 'created_at'], "id = {$postId}")
		// 	->join('posts.user_id', 'users.id')
		// 	->use('users.name as author')
		return $this->db->select(['title', 'content', 'category_id', 'posts.created_at as created_at', 'users.name as author'])
			->from('posts')
			->leftJoin('users', 'author_id = users.id')
			->where('posts.id', $postId)
			->first();
	}

	public function delete($postId)
	{
		return $this->db->delete()->from('posts')->where('id', $postId)->execute();
	}
}
