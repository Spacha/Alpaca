<?php

namespace App\Controllers;

use App\Framework\Exceptions\RoutingException as NotFound;
use App\Framework\Libs\{
	Auth\AuthMiddleware,
	Auth\Authenticator,
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

		if (!$post)
			throw new NotFound("Blog post id '{$postId}' not found");

		return new View('blog.view', ['active' => 'blog', 'post' => $post], ['header', 'footer']);
	}

	public function create()
	{
		return new View('blog.create', ['active' => 'blog'], ['header', 'footer']);
	}

	public function add(Request $request)
	{
		$authUser = Authenticator::user();

		$id = $this->model->add([
			'title' 		=> $request->data('title'),
			'content' 		=> $request->data('content'),
			'author_id' 	=> (int)$authUser['id'],
			'category_id' 	=> 1
		]);

		header("Location: /blog/{$id}");
	}

	public function delete(Request $request, $postId)
	{
		$this->model->delete($postId);

		header("Location: /blog");
	}
}
