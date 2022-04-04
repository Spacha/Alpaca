<?php

namespace App\Framework\Libs;

Class Email
{
    /**
     * Describe me.
     *
     * @return bool
     */
    public static function send($to, $subject, $message, $headers = []) : bool
    {
        return mail($to, $subject, $message, $headers) != false;
    }
}
