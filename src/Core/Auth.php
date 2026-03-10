<?php

namespace Zahirzohair\Neuracore\Core;

class Auth
{
    public static function check(): bool
    {
        return isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']);
    }

    public static function id(): ?int
    {
        if (!self::check()) {
            return null;
        }

        return (int) $_SESSION['user_id'];
    }

    public static function requireLogin(): void
    {
        if (self::check()) {
            return;
        }

        Response::redirect('/login');
    }
}

