<?php

namespace App\Controllers;

use App\Framework\Libs\{
	Auth\AuthMiddleware,
	Controller,
	Request,
	View
};

use App\Models\Blog;

class BlogController extends Controller
{
	protected $requiresAuth = ['create', 'add', 'delete'];

	public function __construct()
	{
		parent::__construct(
			new Blog(),
			new AuthMiddleware($this->requiresAuth)
		);
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
		$post = $this->model->view($postId);

		return new View('blog.view', ['active' => 'blog', 'post' => $post], ['header', 'footer']);
	}

	public function create()
	{
		return new View('blog.create', ['active' => 'blog'], ['header', 'footer']);
	}

	public function add(Request $request)
	{
		$id = $this->model->add([
			'title' 		=> $request->data('title'),
			'content' 		=> $request->data('content'),
			'category_id' 	=> 1
		]);

		// header("Location: /blog/{$id}");
		header("Location: /blog");
	}

	public function delete(Request $request, $postId)
	{
		$this->model->delete($postId);

		header("Location: /blog");
	}
}
