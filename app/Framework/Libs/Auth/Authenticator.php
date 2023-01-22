<?php

namespace App\Framework\Libs\Auth;

use App\Framework\Libs\Database\QueryBuilder;

class Authenticator
{
    /**
     * ...
     */
    public static function authenticate($email, $password) : bool
    {
        $db = new QueryBuilder();

        $user = $db->select(['id', 'email', 'name', 'password'])
            ->from('users')
            ->where('email', $email)
            ->first();

        // check login
        // TODO: use login throttling
        // create with password_hash('my-password', PASSWORD_BCRYPT)
        if (!$user || (password_verify($password, $user->password) == false))
            return false;

        // authentication successful, log in
        self::createSession($user);

        return true;
    }

    public static function logout()
    {
        self::destroySession();
    }

    private static function createSession($user)
    {
        $_SESSION['authenticated_at'] = date(config('app')['date_format']);
        $_SESSION['user'] = [
            'id' => $user->id,
            'name' => $user->name
        ];
    }

    public static function validSession() : bool
    {
        return (
            isset($_SESSION['authenticated_at'])
            && ($_SESSION['authenticated_at'] > 0)
            && isset($_SESSION['user'])
        );
    }

    public static function loggedIn() : bool
    {
        return Authenticator::validSession();
    }

    public static function user()
    {
        return $_SESSION['user'] ?? [];
    }

    private static function destroySession()
    {
        unset($_SESSION['authenticated_at']);
        unset($_SESSION['user']);
    }

    public static function startSession(array $options = []) : bool
    {
        // don't set up multiple sessions
        if (session_status() !== PHP_SESSION_NONE) {
            return false;
        }
        session_set_cookie_params(0);
        return session_start($options);
    }
}
