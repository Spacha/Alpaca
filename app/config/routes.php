<?php

/*---------------------------------------------------------
 * Application specific routes
 *---------------------------------------------------------
 *
 *
 */

return [
	'/' 										=> 'TestController@home',
	'users/{?userId}' 							=> 'TestController@user',
	'users/{userId}/posts/{?postId}/{?pageId}'	=> 'TestController@posts',

	'secret/{?pageId}/{?moi}'					=> 'AnotherController@test',

	'pages/{?pageId}/{?postId}/{?commentId}'	=> 'AnotherController@pages',
];
