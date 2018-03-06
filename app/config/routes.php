<?php

/*---------------------------------------------------------
 * Application specific routes
 *---------------------------------------------------------
 *
 * These routes are tose the end user is going to access if
 * one has needed priviledges.
 *
 * Usage:
 * Defining a route consists of few parts. First is defining
 * the url. The url is defined like following:
 *
 * ==> '&: blog/post/{parameter}/{?optionalParameter}'
 *
 * # Methods
 * The first sign in the beginning of the route definition
 * is method in use. The method key is followed by a semicolon
 * and space for clarity, though this is optional. Definitions without
 * method are considered as GET request.
 * & => GET
 * $ => POST
 *
 * # Passing parameters
 * You can wrap parameters in parentheses. These parameters
 * will be passed to the controller as arguments, in the
 * same order which they are in the url.
 * {parameter}
 * {?optionalParam}
 *
 * We now accept WHITESPACES in routes. Not recommended but possible.
 *
 * Idea: We could accept names without method tag and default it as GET
 */

return [
	// this could be also defined just as: '$'
	'&: /'									=> 'TestController@home',
	'&: users/{?userId}'					=> 'TestController@users',

	'$: {someId}/papers/{?another}' 							=> 'TestController@papers',



	'&: users/{userId}/posts/{postId}' => 'TestController@posts'
];
