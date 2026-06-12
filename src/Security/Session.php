<?php

declare(strict_types=1);

class Session
{
    public static function secureStart(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        ini_set('session.cookie_httponly', '1');
        ini_set('session.cookie_secure', '1');
        ini_set('session.use_strict_mode', '1');
        ini_set('session.cookie_samesite', 'Strict');

        session_start();
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }
}
