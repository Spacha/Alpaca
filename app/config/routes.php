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
	'&:'										=> 'HomeController@home',
	'&: about'									=> 'HomeController@about',
	
	'&: users'									=> 'UserController@list',
	'&: users/{userId}'							=> 'UserController@view',
	'&: users/create'							=> 'UserController@create',
	'$: users/add'								=> 'UserController@add',
];
