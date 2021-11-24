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

use App\Models\Page;

class PageController extends Controller
{
	protected $requiresAuth = [
		'create', 'edit', 'add', 'update', 'updatePublicity', 'delete', 'list'
	];

	const SNIPPETS = ['header', 'footer', 'secret-toolbar'];

	public function __construct()
	{
		parent::__construct(
			new Page(),
			new AuthMiddleware($this->requiresAuth)
		);
	}

	public function list()
	{
		$pages = $this->model->list();

		return new View('pages.home', [
			'pages' => $pages
		], self::SNIPPETS);
	}

	public function view(Request $request, $pageId)
	{
		$page = $this->model->view($pageId);

		// page does not exist or is not public
		if (!$page || (!$page->is_public && !Authenticator::loggedIn()))
			throw new NotFound("Page id '{$pageId}' not found");

		return new View('pages.view', ['page' => $page], self::SNIPPETS);
	}

	public function create()
	{
		return new View('pages.create', [], self::SNIPPETS);
	}

	public function edit(Request $request, $pageId)
	{
		$page = $this->model->view($pageId);

		if (!$page)
			throw new NotFound("Page id '{$pageId}' not found");

		return new View('pages.edit', [
			'page' 			=> $page,
			'categories' 	=> []
		], self::SNIPPETS);
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
			redirect("/pages/{$id}");

		redirect("/pages/create");
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
