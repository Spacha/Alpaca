<?php

/*---------------------------------------------------------
 * Application main config file
 *---------------------------------------------------------
 *
 *
 */

return [
	// Application name
	'name' => 'Alpaca',

	// Application environment (development / production)
	'env' => 'production',

	// Default timezone
	'timezone' => 'Europe/Helsinki',

	// Default locale
	'locale' => 'en',

	// Date format in database
	// @todo move to another config file
	'date_format' => 'Y-m-d H:i:s',

	// Whether or not to log errors to a log file
	'log_errors' => true
];
