<?php
// public/index.php
session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Repository/StockBatchRepository.php';
require_once __DIR__ . '/../src/Controller/DashboardController.php';

use PharmaFEFO\Controller\DashboardController;
use PharmaFEFO\Repository\StockBatchRepository;

// Recupéri l-action, default hiya login
$action = $_GET['action'] ?? 'login';

// --- 1. ACTION : LOGOUT ---
if ($action === 'logout') {
    session_destroy();
    header("Location: index.php?action=login");
    exit();
}

// --- 2. ACTION : LOGIN (Affichage et Traitement) ---
if ($action === 'login') {
    // Ila dejà m-connecti, ddie direkt l-dashboard
    if (isset($_SESSION['user_role'])) {
        header("Location: index.php?action=dashboard");
        exit();
    }

    $error = null;

    // Traitement dyal Formulaire POST melli t-cliquye user 3la Se connecter
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $repo = new StockBatchRepository();
        $user = $repo->checkLogin($email, $password);

        if ($user) {
            $_SESSION['user_role'] = $user['role']; // ADMIN, PHARMACIEN, PREPARATEUR
            $_SESSION['user_email'] = $user['email'];
            header("Location: index.php?action=dashboard");
            exit();
        } else {
            $error = "Identifiants incorrects. Veuillez réessayer.";
        }
    }

    // Affichage de la vue login
    require_once __DIR__ . '/../templates/auth/login.php';
    exit();
}

// --- 3. ACTION : DASHBOARD (Protégé) ---
if ($action === 'dashboard') {
    if (!isset($_SESSION['user_role'])) {
        header("Location: index.php?action=login");
        exit();
    }

    $controller = new DashboardController();
    $controller->index();
    exit();
}

// --- 4. CAS PAR DÉFAUT : 404 ---
header("HTTP/1.0 404 Not Found");
echo "<h1>Page non trouvée</h1>";