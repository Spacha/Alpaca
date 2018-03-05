<?php

namespace App\Framework\Exceptions;

use Exception;
use App\Framework\Libs\HttpResponse;

class RoutingException extends Exception
{
    public function __construct(string $message, $code = 404, Exception $previous = null)
    {
    	HttpResponse::setStatus($code);

		parent::__construct($message, $code, $previous);

		echo "Error ".$code;
		// return view('errors.404', ['message' => $message]);
	}
}
