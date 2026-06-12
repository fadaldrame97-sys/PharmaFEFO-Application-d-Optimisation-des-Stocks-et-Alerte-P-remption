<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Controller/StockController.php';
require_once __DIR__ . '/../src/Controller/LoginController.php'; // <-- ajout pour login
require_once __DIR__ . '/../src/Repository/StockBatchRepository.php';
require_once __DIR__ . '/../src/Repository/ProductRepository.php';

$stockRepo = new StockBatchRepository();
$productRepo = new ProductRepository();
$stockController = new StockController($stockRepo, $productRepo);

// Exemple : si tu as un UserRepository pour gérer les comptes
// $userRepo = new UserRepository();
// $loginController = new LoginController($userRepo);

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'stock':
        $stockController->index();
        break;

    case 'scan':
        $stockController->scanEntry();
        break;

    case 'reception':
        $stockController->receptionForm();
        break;

    case 'expire':
        if (isset($_GET['batch'])) {
            $stockController->markExpired((int) $_GET['batch']);
        }
        break;

    case 'dispense':
        if (isset($_GET['product'])) {
            $stockController->dispenseProduct((int) $_GET['product']);
        }
        break;

    case 'login': // <-- nouvelle route
        // $loginController->showLoginForm();
        require __DIR__ . '/../templates/Autentification/login.php';
        break;

    case 'doLogin': // <-- soumission du formulaire
        // $loginController->login();
        break;

    default:
        echo "404 - Page non trouvée";
}
