<?php

declare(strict_types=1);

class DatabaseController
{
    public function index(): void
    {
        AuthMiddleware::requireRole('ADMIN');

        require __DIR__ . '/../../templates/dashboard/admin/db.php';
    }
}
