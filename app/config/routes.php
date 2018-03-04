<?php

/*---------------------------------------------------------
 * Application specific routes
 *---------------------------------------------------------
 *
 *
 */

return [
	'/' 										=> 'TestController@home',
	'users' 									=> 'TestController@user',
	'users/{userId}' 							=> 'TestController@user',
	'users/{userId}/posts'	 					=> 'TestController@posts',
	'users/{userId}/posts/{postId}' 			=> 'TestController@posts',
	'users/{userId}/posts/{postId}/{pageId}'	=> 'TestController@posts',

	'secret/{?pageId}'							=> 'AnotherController@home',
];
