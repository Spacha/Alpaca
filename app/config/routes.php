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
	'&: about'									=> 'HomeController@about',
	
	// Users

	'&: users'									=> 'UserController@list',
	'&: users/{userId}'							=> 'UserController@view',
	'&: users/create'							=> 'UserController@create',
	'&: users/{userId}/delete'					=> 'UserController@delete',
	'$: users/add'								=> 'UserController@add',

	// Blog

	'&: blog'									=> 'BlogController@list',
	'&: blog/{postId}'							=> 'BlogController@view',
	'&: blog/create'							=> 'BlogController@create',
	'&: blog/{postId}/delete'					=> 'BlogController@delete',
	'$: blog/add'								=> 'BlogController@add',

	// Authentication

	'&: login'									=> 'UserController@login',
	'$: login'									=> 'UserController@tryLogin',

	'&: register'								=> 'UserController@register',
	'$: register'								=> 'UserController@tryRegister',

	'&: logout'									=> 'UserController@logout',
];
