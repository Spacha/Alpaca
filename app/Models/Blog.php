<?php

namespace App\Models;

use App\Framework\Libs\Model;
use App\Framework\Libs\Database;
use App\Framework\Traits\CachesMarkdown;

class Blog extends Model
{
	use CachesMarkdown;

	private $cacheName = "posts";

	/**
	 * List the recent (public) blog post titles to be shown on the front page.
	 */
	public function titles(int $max = 4, string $order = 'desc') : array
	{
		$query = $this->db->select(['id', 'title', 'created_at'])->from('posts')
			->where('is_public', '1')
			->limit($max);

		return $query->orderBy('created_at', $order)->get();
	}

	/**
	 * Store a new post.
	 */
	public function add($data) : int
	{
		$this->updateMarkdownCache(69, $data['content']);
		$this->db->into('posts')->insert([
			'title' 		=> $data['title'],
			'content'		=> $data['content'],
			'author_id' 	=> $data['author_id'],
			'is_public'		=> $data['is_public'],
			'category_id' 	=> $data['category_id'],
			'created_at' 	=> date(config('app')['date_format'])
		])->execute();
		$postId = $this->db->lastInsertId();

		$this->updateMarkdownCache($postId, $data['content']);
		return $postId;
	}

	/**
	 * Save updates to a post.
	 */
	public function update(int $postId, $data) : bool
	{
		$success = $this->db->table('posts')
			->update($data)
			->where('id', $postId)
			->execute();

		$this->updateMarkdownCache($postId, $data['content']);
		return $success;
	}

	/**
	 * Show a listing of the posts.
	 * 
	 * @param bool $includeHidden If true, also non-public posts are included.
	 */
	public function list(bool $includeHidden = false) : array
	{
		$query = $this->db->select(['id', 'title', 'content', 'is_public', 'created_at'])->from('posts');

		if (!$includeHidden) {
			$query->where('is_public', '1');
		}

		return $query->orderBy('created_at', 'desc')->get();
	}

	/**
	 * Show details of a post.
	 */
	public function view(int $postId)
	{
		$post = $this->db->select(['posts.id', 'title', 'content', 'category_id', 'is_public', 'posts.created_at as created_at', 'users.name as author'])
			->from('posts')
			->leftJoin('users', 'author_id = users.id')
			->where('posts.id', $postId)
			->first();

		$post->contentHtml = $this->getMarkdownCache($postId);
		return $post;
	}

	/**
	 * Return a boolean telling if a post is public or not.
	 */
	public function isPublic(int $postId)
	{
		$post = $this->db->select(['is_public'])
			->from('posts')
			->where('posts.id', $postId)
			->first();

		if ($post) {
			return $post->is_public;
		} else {
			return false;
		}
	}

	/**
	 * Delete a post.
	 */
	public function delete(int $postId)
	{
		return $this->db->delete()->from('posts')->where('id', $postId)->execute();
	}
}
