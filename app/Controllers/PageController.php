<?php

namespace App\Controllers;

use App\Framework\Exceptions\RoutingException as NotFound;
use App\Framework\Logs\ActivityLog as Log;
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
	// only 'viewLive' is public
	protected $requiresAuth = [
		'list', 'view', 'create', 'edit', 'add',
		'update', 'updatePublicity', 'delete'
	];

	const SNIPPETS = ['header', 'footer', 'secret-toolbar'];

	public function __construct()
	{
		parent::__construct(
			new Page(),
			new AuthMiddleware($this->requiresAuth)
		);
	}

	public function afterMiddleware() : void
	{
		$this->user = Authenticator::user();
	}

	public function list()
	{
		$pages = $this->model->list();

		return new View('pages.home', [
			'pages' => $pages,
			'active' => 'pages'
		], self::SNIPPETS);
	}

	public function view(Request $request, $pageId)
	{
		$page = $this->model->view($pageId);

		// page does not exist or is not public
		if (!$page || (!$page->is_public && !Authenticator::loggedIn()))
			throw new NotFound("Page id '{$pageId}' not found");

		return new View('pages.view', ['page' => $page, 'active' => 'pages'], self::SNIPPETS);
	}

	public function viewLive(Request $request, $url)
	{
		$page = $this->model->view($url, true);

		// page does not exist or is not public
		if (strlen($url) == 0 || !$page || (!$page->is_public && !Authenticator::loggedIn()))
			throw new NotFound("Page id not found");

		return new View('pages.view-live', [
			'title' => $page->title,
			'active' => $page->url,
			'page' => $page
		], ['header', 'footer']);
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
		$id = $this->model->add([
			'header' 		=> $request->data('header'),
			'title' 		=> $request->data('title'),
			'content' 		=> $request->data('content'),
			'is_public' 	=> ($request->data('is_public') == '1') ? '1' : '0',
			'url' 			=> $request->data('url')
		]);

		if ($id > 0) {
			Log::write("User [{$this->user['id']}] CREATED a page [{$id}].");
			redirect("/secret/pages/{$id}");
		}

		redirect("/secret/pages/create");
	}

	public function update(Request $request, $pageId)
	{
		$success = $this->model->update($pageId, [
			'header' 		=> $request->data('header'),
			'title' 		=> $request->data('title'),
			'content' 		=> $request->data('content'),
			'is_public' 	=> ($request->data('is_public') == '1') ? '1' : '0',
			'url' 			=> $request->data('url')
		]);

		if ($success) {
			Log::write("User [{$this->user['id']}] EDITED a page [{$pageId}].");
		}

		redirect("/secret/pages/{$pageId}");
	}

	public function updatePublicity(Request $request, $pageId)
	{
		$isPublic = $this->model->isPublic($pageId);

		$this->model->update($pageId, [
			'is_public' => ($isPublic == '1') ? '0' : '1'
		]);

		redirect("/secret/pages");
	}

	public function delete(Request $request, $pageId)
	{
		$success = $this->model->delete($pageId);

		if ($success)
			Log::write("User [{$this->user['id']}] DELETED a blog post [{$postId}].");

		redirect("/secret/pages");
	}
}
