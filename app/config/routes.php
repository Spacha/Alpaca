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
	// this could be also defined just as: '$'
	'&: /'									=> 'TestController@home',
	'&: users/{?userId}'					=> 'TestController@users',
	'&: users/{userId}/posts/{postId}' 		=> 'TestController@posts',

	'$: {subject}/send'				 		=> 'TestController@papers',
];
