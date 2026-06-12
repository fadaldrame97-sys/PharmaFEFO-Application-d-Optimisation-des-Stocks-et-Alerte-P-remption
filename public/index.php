<?php

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

require_once __DIR__ . '/../src/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/../src/Repository/AbstractRepository.php';
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

$userRepo   = new UserRepository();
$stockRepo  = new StockBatchRepository();
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
        if (isset($_GET['batch'])) {
            $stockController->markExpired((int) $_GET['batch']);
        } else {
            header('Location: index.php?action=stock');
        }
        break;

    case 'dispense':
        if (isset($_GET['product'])) {
            $stockController->dispenseProduct((int) $_GET['product']);
        } else {
            header('Location: index.php?action=stock');
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
        echo "404 - Page non trouvee";
        break;
}
