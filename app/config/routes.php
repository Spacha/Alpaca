<?php

/**
	Application specific routes
*/

return [
	'/' 					=> 'TestController@home',
	'/test' 				=> 'TestController@test',
	'/test/{userId}' 		=> 'TestController@list',
	'/test/{userId}/kakka' 	=> 'TestController@kakka',
];
