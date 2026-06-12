<?php

declare(strict_types=1);

class AuthMiddleware
{
    /**
     * Require the user to be logged in. Redirects to login if not.
     */
    public static function requireLogin(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    /**
     * Require the logged-in user to have one of the given roles.
     * Calls requireLogin() first, then checks the role.
     *
     * @param string ...$allowedRoles  e.g. 'ADMIN', 'PHARMACIEN'
     */
    public static function requireRole(string ...$allowedRoles): void
    {
        self::requireLogin();

        $role = $_SESSION['user']['role'] ?? '';

        if (!in_array($role, $allowedRoles, true)) {
            $_SESSION['error'] = "Acces non autorise.";
            header('Location: index.php?action=login');
            exit;
        }
    }

    public static function currentRole(): string
    {
        return $_SESSION['user']['role'] ?? '';
    }
}
