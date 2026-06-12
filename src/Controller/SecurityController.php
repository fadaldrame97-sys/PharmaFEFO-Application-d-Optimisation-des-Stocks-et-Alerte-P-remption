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
        AuthMiddleware::requireRole('ADMIN');

        $users = $this->userRepository->findAll();
        require __DIR__ . '/../../templates/dashboard/admin/security.php';
    }
}
