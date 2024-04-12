<?php

namespace App\Framework\Libs\Database;

use Exception;
use App\Framework\Libs\HttpResponse;

class QueryBuilderException extends Exception
{
    public function __construct(string $message, $code = 500, Exception $previous = null)
    {
        HttpResponse::setStatus($code);

        parent::__construct($message, $code, $previous);

        echo "Error ".$code;
        // return view('errors.500', ['message' => $message]);
    }
}
