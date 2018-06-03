<?php

namespace App\Controllers;

use App\Framework\Libs\{
	Controller,
	Request,
	View
};

use App\Models\Blog;

class BlogController extends Controller
{
	public function __construct()
	{
		parent::__construct(new Blog());
	}

	public function list()
	{
		//$posts = $this->model->list('id', 'title', ['content', 'SELECT LEFT', 50]);
		$posts = $this->model->list();

		return new View('blog.home', [
			'active' 	=> 'blog',
			'posts' 	=> $posts
		], ['header', 'footer']);
	}

	public function view(Request $request, $postId)
	{
		$post = $this->model->list($postId);

		return new View('blog.view', ['post' => $post], ['header', 'footer']);
	}

	public function create()
	{
		return new View('blog.create', [], ['header', 'footer']);
	}

	public function add(Request $request)
	{
		$id = $this->model->add([
			'title' => $request->data('title'),
			'content' => $request->data('content'),
		]);

		header("Location: /blog/{$id}");
	}
}
