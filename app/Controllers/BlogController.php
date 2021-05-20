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
	protected $requiresAuth = [
		'create', 'edit', 'add', 'update', 'updatePublicity', 'delete'
	];

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
		$posts = $this->model->list(Authenticator::loggedIn());

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

		return new View('blog.view', ['active' => 'blog', 'post' => $post, 'categories' => []], ['header', 'footer']);
	}

	public function create()
	{
		return new View('blog.create', ['active' => 'blog'], ['header', 'footer']);
	}

	public function edit(Request $request, $postId)
	{
		$post = $this->model->view($postId);

		if (!$post)
			throw new NotFound("Blog post id '{$postId}' not found");

		return new View('blog.edit', [
			'active' 		=> 'blog',
			'post' 			=> $post,
			'categories' 	=> []
		], ['header', 'footer']);
	}

	public function add(Request $request)
	{
		$authUser = Authenticator::user();

		$id = $this->model->add([
			'title' 		=> $request->data('title'),
			'content' 		=> $request->data('content'),
			'author_id' 	=> (int)$authUser['id'],
			'is_public' 	=> ($request->data('is_public') == '1') ? '1' : '0',
			'category_id' 	=> $request->data('category_id')
		]);

		if ($id > 0)
			redirect("/blog/{$id}");

		redirect("/blog/create");
	}

	public function update(Request $request, $postId)
	{
		$this->model->update($postId, [
			'title' 		=> $request->data('title'),
			'content' 		=> $request->data('content'),
			'is_public' 	=> ($request->data('is_public') == '1') ? '1' : '0',
			'category_id' 	=> $request->data('category_id')
		]);

		redirect("/blog/{$postId}");
	}

	public function updatePublicity(Request $request, $postId)
	{
		$isPublic = $this->model->isPublic($postId);

		$this->model->update($postId, [
			'is_public' => ($isPublic == '1') ? '0' : '1'
		]);

		redirect("/blog");
	}

	public function delete(Request $request, $postId)
	{
		$this->model->delete($postId);

		redirect("/blog");
	}
}
