<?php

/*---------------------------------------------------------
 * Application main config file
 *---------------------------------------------------------
 *
 *
 */

return [
	// Application name
	'name' => 'Spacha',

	// Application environment (development / production)
	'env' => 'prod',

	// Default timezone
	'timezone' => 'Europe/Helsinki',

	// Default locale
	'locale' => 'en',

	// Date format in database
	// @todo move to another config file
	'date_format' => 'Y-m-d H:i:s',

	// Whether or not to log errors to a log file
	'log_errors' => true,

	// Maximum size of any single log file in bytes
	// If a log is full, it's not written to
	'log_max_size' => 50*1024,

	// If true, composer vendor files are autoloaded
	'use_vendors' => true,
];
