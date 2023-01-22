<?php

namespace App\Framework\Libs;

use App\Framework\Exceptions\RoutingException as NotFound;

Class Validator
{
    /**
     * Validates an integer.
     *
     * @param mixed $input  Input to validate-
     * @throws NotFound     If input is invalid.
     * @return bool
     */
    public static function integer($input) : bool
    {
        $number = filter_var($input, FILTER_VALIDATE_INT);

        if ($number == false)
        {
            self::handleInvalid();
            return false;
        }

        return true;
    }

    /**
     * Handles an invalid entry.
     * 
     * @todo Ability to change behaviour per use case!
     * @throws NotFound
     */
    public static function handleInvalid() : void
    {
        throw new NotFound("Page is not found.");
    }
}
