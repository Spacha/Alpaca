<?php

/**
	Application specific routes
*/

return [
	'/' 					=> 'TestController@home',
	'/test' 				=> 'TestController@list',
	'/test/{userId}' 		=> 'TestController@list',
];
