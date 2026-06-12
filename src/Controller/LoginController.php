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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=login');
            exit;
        }

        if (!Csrf::validateToken($_POST['csrf_token'] ?? null)) {
            $_SESSION['error'] = "Jeton de securite invalide. Veuillez reessayer.";
            header('Location: index.php?action=login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Format d'email invalide.";
            header('Location: index.php?action=login');
            exit;
        }

        $user = $this->userRepository->findByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
            Session::regenerate();

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
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
