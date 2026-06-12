<?php

declare(strict_types=1);

class LoginController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function showLoginForm(): void
    {
        require __DIR__ . '/../../templates/Autentification/login.php';
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Format d'email invalide.";
            header('Location: index.php?action=login');
            exit;
        }

        try {
            $user = $this->userRepository->findByEmail($email);
        } catch (RuntimeException $e) {
            error_log('LoginController::login error: ' . $e->getMessage());
            $_SESSION['error'] = "Erreur interne lors de l'authentification. Veuillez reessayer.";
            header('Location: index.php?action=login');
            exit;
        }

        if ($user && password_verify($password, $user->getPassword())) {
            $_SESSION['user'] = [
                'id'    => $user->getId(),
                'email' => $user->getEmail(),
                'role'  => $user->getRole(),
            ];

            switch ($user->getRole()) {
                case 'ADMIN':
                    header('Location: index.php?action=dashboard');
                    break;
                case 'PHARMACIEN':
                    header('Location: index.php?action=dashboard');
                    break;
                case 'PREPARATEUR':
                case 'GESTIONNAIRE':
                    header('Location: index.php?action=stock');
                    break;
                default:
                    header('Location: index.php?action=dashboard');
            }
            exit;
        } else {
            $_SESSION['error'] = "Email ou mot de passe incorrect.";
            header('Location: index.php?action=login');
            exit;
        }
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
