<?php

/*---------------------------------------------------------
 * Application specific routes
 *---------------------------------------------------------
 *
 * These routes are tose the end user is going to access if
 * one has needed priviledges.
 *
 * We now accept WHITESPACES in routes. Not recommended but possible.
 * Idea: We could accept names without method tag and default it as GET
 */

return [

	// Static

	'&:'										=> 'HomeController@home',
	//'&: about'									=> 'HomeController@about',
	
	// Users

	'&: users'									=> 'UserController@list',
	'&: users/{userId}'							=> 'UserController@view',
	'&: users/create'							=> 'UserController@create',
	'&: users/{userId}/delete'					=> 'UserController@delete',
	'$: users/add'								=> 'UserController@add',

	// Blog

	'&: blog'									=> 'BlogController@list',
	'&: blog/create'							=> 'BlogController@create',
	'$: blog/add'								=> 'BlogController@add',
	'&: blog/{postId}'							=> 'BlogController@view',
	'&: blog/{postId}/edit'						=> 'BlogController@edit',
	'$: blog/{postId}/update'					=> 'BlogController@update',
	'&: blog/{postId}/delete'					=> 'BlogController@delete',
	'&: blog/{postId}/update-publicity'			=> 'BlogController@updatePublicity',

	// Authentication

	'&: login'									=> 'UserController@login',
	'$: login'									=> 'UserController@tryLogin',

	//'&: register'								=> 'UserController@register',
	//'$: register'								=> 'UserController@tryRegister',

	'&: logout'									=> 'UserController@logout',

	// Pages

	'&: secret/pages'							=> 'PageController@list',
	'&: secret/pages/create'					=> 'PageController@create',
	'$: secret/pages/add'						=> 'PageController@add',
	'&: secret/pages/{pageId}'					=> 'PageController@view',
	'&: secret/pages/{pageId}/edit'				=> 'PageController@edit',
	'$: secret/pages/{pageId}/update'			=> 'PageController@update',
	'&: secret/pages/{pageId}/delete'			=> 'PageController@delete',
	'&: secret/pages/{pageId}/update-publicity'	=> 'PageController@updatePublicity',

	// Secret
	
	'&: secret'									=> 'SecretController@home',
	// Logs
	'&: secret/logs'							=> 'SecretController@logs',
	'&: secret/logs/error-log'					=> 'SecretController@errorLog',
	'&: secret/logs/activity-log'				=> 'SecretController@activityLog',
	// Todo list
	'&: secret/todo-list'						=> 'SecretController@todoList',
	'&: secret/todo-list/create'				=> 'SecretController@createTodo',
	'$: secret/todo-list/add'					=> 'SecretController@addTodo',
	'&: secret/todo-list/{todoId}/update-status'=> 'SecretController@updateTodoStatus',
	'&: secret/todo-list/{todoId}/delete'		=> 'SecretController@deleteTodo',
	'&: secret/settings'						=> 'SecretController@settings',

	// Wildcard for pages
	'&: {url}' 									=> 'PageController@viewLive',
];
