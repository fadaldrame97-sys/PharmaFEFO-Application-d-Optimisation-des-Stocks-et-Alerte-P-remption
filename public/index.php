<?php

declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

set_exception_handler(function (Throwable $e): void {
    error_log('Uncaught exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['error'] = "Une erreur interne est survenue. Veuillez reessayer plus tard.";
    header('Location: index.php?action=login');
    exit;
});

session_start();

require_once __DIR__ . '/../config/database.php';

require_once __DIR__ . '/../src/Entity/User.php';
require_once __DIR__ . '/../src/Entity/Admin.php';
require_once __DIR__ . '/../src/Entity/Pharmacien.php';
require_once __DIR__ . '/../src/Entity/Preparateur.php';
require_once __DIR__ . '/../src/Entity/product.php';
require_once __DIR__ . '/../src/Entity/StockBatch.php';
require_once __DIR__ . '/../src/Entity/EtatStock.php';

require_once __DIR__ . '/../src/Enum/BatchStatus.php';
require_once __DIR__ . '/../src/Enum/Role.php';

require_once __DIR__ . '/../src/Repository/UserRepository.php';
require_once __DIR__ . '/../src/Repository/StockBatchRepository.php';
require_once __DIR__ . '/../src/Repository/ProductRepository.php';
require_once __DIR__ . '/../src/Repository/EtatStockRepository.php';

require_once __DIR__ . '/../src/Services/Service.php';

require_once __DIR__ . '/../src/Controller/LoginController.php';
require_once __DIR__ . '/../src/Controller/StockController.php';
require_once __DIR__ . '/../src/Controller/DashboardController.php';
require_once __DIR__ . '/../src/Controller/AlertController.php';
require_once __DIR__ . '/../src/Controller/InventoryController.php';
require_once __DIR__ . '/../src/Controller/ReturnController.php';
require_once __DIR__ . '/../src/Controller/DatabaseController.php';
require_once __DIR__ . '/../src/Controller/ReportController.php';
require_once __DIR__ . '/../src/Controller/SecurityController.php';

try {
    $userRepo    = new UserRepository();
    $stockRepo   = new StockBatchRepository();
    $productRepo = new ProductRepository();

    $loginController     = new LoginController($userRepo);
    $stockController     = new StockController($stockRepo, $productRepo);
    $dashboardController = new DashboardController($stockRepo);
    $alertController     = new AlertController();
    $inventoryController = new InventoryController($stockRepo);
    $returnController    = new ReturnController();
    $databaseController  = new DatabaseController();
    $reportController    = new ReportController($stockRepo);
    $securityController  = new SecurityController($userRepo);
} catch (RuntimeException $e) {
    error_log('Application bootstrap failed: ' . $e->getMessage());
    http_response_code(503);
    echo "Service temporairement indisponible. Veuillez reessayer plus tard.";
    exit;
}

$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'login':
        $loginController->showLoginForm();
        break;

    case 'doLogin':
        $loginController->login();
        break;

    case 'logout':
        $loginController->logout();
        break;

    case 'dashboard':
        $dashboardController->index();
        break;

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
        $batchId = filter_input(INPUT_GET, 'batch', FILTER_VALIDATE_INT);
        if (!$batchId) {
            $_SESSION['error'] = "ID de lot invalide.";
            header('Location: index.php?action=stock');
        } else {
            $stockController->markExpired($batchId);
        }
        break;

    case 'dispense':
        $productId = filter_input(INPUT_GET, 'product', FILTER_VALIDATE_INT);
        if (!$productId) {
            $_SESSION['error'] = "ID de produit invalide.";
            header('Location: index.php?action=stock');
        } else {
            $stockController->dispenseProduct($productId);
        }
        break;

    case 'alerts':
        $alertController->configure();
        break;

    case 'inventory':
        $inventoryController->validate();
        break;

    case 'returns':
        $returnController->manage();
        break;

    case 'returnAccept':
        $returnController->accept();
        break;

    case 'returnRefuse':
        $returnController->refuse();
        break;

    case 'db':
        $databaseController->index();
        break;

    case 'reports':
        $reportController->index();
        break;

    case 'security':
        $securityController->index();
        break;

    default:
        http_response_code(404);
        echo "404 - Page non trouvee.";
        break;
}
