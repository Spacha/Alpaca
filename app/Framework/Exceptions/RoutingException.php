<?php

namespace App\Framework\Exceptions;

use Exception;

class RoutingException extends Exception
{
	public $name;
	public $description;

    public function __construct(string $message, $code = 404, Exception $previous = null)
    {
		parent::__construct($message, $code, $previous);

		if ($code == 404) {
			$this->name = "Not Found";
			$this->description = "The page you are looking for doesn't exist.";
		} else {
			$this->name = "";
			$this->description = "";
		}
	}
}
