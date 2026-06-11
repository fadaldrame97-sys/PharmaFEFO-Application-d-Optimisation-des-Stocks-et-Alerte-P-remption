<?php

class LoginController{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository){

        $this->userRepository=$userRepository;
        session_start();
    }

  
    public function showLoginForm(): void{
        require __DIR__ . '/.../templates/login.php';
    }


        public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Vérification via UserRepository
        $user = $this->userRepository->findByEmail($email);

        if ($user && password_verify($password, $user->getPassword())) {
            // Stocker l’utilisateur en session
            $_SESSION['user'] = [
                'id'    => $user->getId(),
                'email' => $user->getEmail(),
                'role'  => $user->getRole()->value
            ];

            // Redirection vers le dashboard
            header('Location: /dashboard');
            exit;
        } else {
            // Erreur → retour au formulaire avec message
            $_SESSION['error'] = "Email ou mot de passe incorrect.";
            header('Location: /login');
            exit;
        }
    }
}

