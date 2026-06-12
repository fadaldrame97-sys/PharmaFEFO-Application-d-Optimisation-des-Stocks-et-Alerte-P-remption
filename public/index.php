<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Controller/StockController.php';
require_once __DIR__ . '/../src/Repository/StockBatchRepository.php';
require_once __DIR__ . '/../src/Repository/ProductRepository.php';

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$stockRepo = new StockBatchRepository();
$productRepo = new ProductRepository();
$controller = new StockController($stockRepo, $productRepo);

switch ($uri) {
    case 'stock':
        $controller->index();
        break;
    case 'stock/scan':
        $controller->scanEntry();
        break;
    case 'stock/reception':
        $controller->receptionForm();
        break;
    case 'stock/expire':
        if (isset($_GET['batch'])) {
            $controller->markExpired((int)$_GET['batch']);
        }
        break;
    case 'stock/dispense':
        if (isset($_GET['product'])) {
            $controller->dispenseProduct((int)$_GET['product']);
        }
        break;
    default:
        echo "404 - Page non trouvée";
}

