<?php

namespace App\Controllers;

use App\Framework\Libs\Auth\RequiresAuth;
use App\Framework\Libs\Auth\AuthMiddleware;
use App\Framework\Libs\{
	Validator as Validate,
	Controller,
	Request,
	View
};

use App\Models\Secret;

class SecretController extends Controller
{
	protected $requiresAuth = ['*'];

	public function __construct()
	{
		parent::__construct(
			new Secret(),
			new AuthMiddleware($this->requiresAuth)
		);
	}

	public function home()
	{
		return new View('secret.home', [
			'title' => 'Spacha — Secret area'
		], ['header', 'footer', 'secret-toolbar']);
	}


	//
	// Sub-pages
	//

	public function logs()
	{
		return new View('secret.logs.logs', [
			'title' => 'Spacha — Logs',
			'active' => 'logs'
		], ['header', 'footer', 'secret-toolbar']);
	}

	public function activityLog()
	{
		return new View('secret.logs.activity-log', [
			'title' 		=> 'Spacha — Activity Log',
			'activityLog' 	=> $this->model->getLog('activity'),
			'active' 		=> 'logs'
		], ['header', 'footer', 'secret-toolbar']);
	}

	public function errorLog()
	{
		return new View('secret.logs.error-log', [
			'title' 	=> 'Spacha — Error Log',
			'errorLog' 	=> $this->model->getLog('error'),
			'active' 	=> 'logs'
		], ['header', 'footer', 'secret-toolbar']);
	}

	public function todoList()
	{
		//dd($this->model->listTodos());
		return new View('secret.todo-list', [
			'title' => 'Spacha — Todo list',
			'todos' => $this->model->listTodos(),
			'active' => 'todo-list'
		], ['header', 'footer', 'secret-toolbar']);
	}

	public function settings()
	{
		return new View('secret.settings', [
			'title' => 'Spacha — Settings',
			'active' => 'settings'
		], ['header', 'footer', 'secret-toolbar']);
	}


	// 
	// Todo List
	//
	
	public function createTodo()
	{
		return new View('secret.create-todo', [
			'title' => 'Spacha — New todo',
			'active' => 'todo-list'
		], ['header', 'footer', 'secret-toolbar']);
	}

	public function addTodo(Request $request)
	{
		$id = $this->model->addTodo([
			'name' 		=> $request->data('name'),
			'details' 	=> $request->data('details'),
		]);

		redirect("/secret/todo-list");
	}

	public function updateTodoStatus(Request $request, $todoId)
	{
		Validate::integer($todoId);
		$isDone = $this->model->isDone($todoId);

		$this->model->update($todoId, [
			'done_at' => $isDone ? NULL : date(config('app')['date_format'])
		]);

		redirect("/secret/todo-list");
	}

	public function deleteTodo(Request $request, $todoId)
	{
		Validate::integer($todoId);
		$this->model->delete($todoId);

		redirect("/secret/todo-list");
	}
}
