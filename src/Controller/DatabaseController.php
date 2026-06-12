<?php

declare(strict_types=1);

class DatabaseController
{
    public function index(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
            $_SESSION['error'] = "Acces reserve a l'administrateur.";
            header('Location: index.php?action=login');
            exit;
        }

        require __DIR__ . '/../../templates/dashboard/admin/db.php';
    }
}
