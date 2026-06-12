<?php

class LoginController {
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
        session_start();
    }

    // Affiche le formulaire de login
    public function showLoginForm(): void {
        require __DIR__ . '/../../templates/Autentification/login.php';
    }

    // Traitement du login
    public function login(): void {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Vérification du format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Format d'email invalide.";
            header('Location: index.php?action=login');
            exit;
        }

        // Vérification via UserRepository
        $user = $this->userRepository->findByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
            // Stocker l’utilisateur en session
            $_SESSION['user'] = [
                'id'    => $user->getId(),
                'email' => $user->getEmail(),
                'role'  => $user->getRole()
            ];

            // Redirection selon le rôle
            switch ($user->getRole()) {
                case 'ADMIN':
                    header('Location: index.php?action=dashboard');
                    break;
                case 'PHARMACIEN':
                    header('Location: index.php?action=inventory');
                    break;
                case 'PREPARATEUR':
                    header('Location: index.php?action=stock');
                    break;
                default:
                    header('Location: index.php?action=dashboard');
            }
            exit;

        } else {
            // Erreur → retour au formulaire avec message
            $_SESSION['error'] = "Email ou mot de passe incorrect.";
            header('Location: index.php?action=login');
            exit;
        }
    }

    // Déconnexion
    public function logout(): void {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
}
