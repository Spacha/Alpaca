<?php

namespace App\Libs;

use App\Framework\Libs\Email;

class EmailNotifier
{
    protected static function getReceiver()
    {
        $env = envConfig();
        return !empty($env) ? $env['webmaster_mail'] : false;
    }

    protected static function notify($subject, $message, $headers = [])
    {
        $receiver = self::getReceiver();

        if ($receiver == false)
            return false;

        $headers["From"] = "Alpaca";
        return Email::send($receiver, $subject, $message, $headers);
    }

    public static function notifyTest($var = 0) : bool
    {
        return self::notify(
            "This is a test notification - please ignore",
            "Message number ({$var}), Ignore this message, bye.");
    }

    public static function notifySuccessfulLogin($email) : bool
    {
        return self::notify(
            "Successful login to spacha.dev",
            "A user succesfully logged in to spacha.dev using email: {$email}.");
    }
}
