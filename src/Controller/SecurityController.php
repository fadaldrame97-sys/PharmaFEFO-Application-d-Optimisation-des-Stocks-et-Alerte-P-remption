<?php

declare(strict_types=1);

class SecurityController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'ADMIN') {
            $_SESSION['error'] = "Acces reserve a l'administrateur.";
            header('Location: index.php?action=login');
            exit;
        }

        $users = $this->userRepository->findAll();
        require __DIR__ . '/../../templates/dashboard/admin/security.php';
    }
}
