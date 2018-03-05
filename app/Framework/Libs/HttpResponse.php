<?php

namespace App\Framework\Libs;

/**
* Take care of things concerning Http headers and responses.
*/
class HttpResponse
{
	/**
	 * Set status to the response
	 *
	 * @param int $code Status code
	 * @return void
	 */
	public static function setStatus($code = 200) : void
	{
		http_response_code($code);
	}

	public static function getStatus() : string
	{
		return http_response_code();
	}
}