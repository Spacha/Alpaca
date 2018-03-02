<?php

/**
	Application specific routes
*/

return [
	'/' 					=> 'TestController@home',
	'/test' 				=> 'TestController@home',
	'/test/{userId}' 		=> 'TestController@list',
];
