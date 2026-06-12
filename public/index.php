<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/StockBatchRepository.php';
require_once __DIR__ . '/src/ProductRepository.php';
require_once __DIR__ . '/src/StockController.php';

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$stockRepo = new StockBatchRepository();
$productRepo = new ProductRepository();
$controller = new StockController($stockRepo, $productRepo);

switch ($uri) {
    case 'PharmaFEFO-Application-d-Optimisation-des-Stocks-et-Alerte-P-remption/stock':
        $controller->index();
        break;
    case 'PharmaFEFO-Application-d-Optimisation-des-Stocks-et-Alerte-P-remption/stock/scan':
        $controller->scanEntry();
        break;
    case 'PharmaFEFO-Application-d-Optimisation-des-Stocks-et-Alerte-P-remption/stock/reception':
        $controller->receptionForm();
        break;
    case 'PharmaFEFO-Application-d-Optimisation-des-Stocks-et-Alerte-P-remption/stock/expire':
        if (isset($_GET['batch'])) {
            $controller->markExpired((int)$_GET['batch']);
        }
        break;
    case 'PharmaFEFO-Application-d-Optimisation-des-Stocks-et-Alerte-P-remption/stock/dispense':
        if (isset($_GET['product'])) {
            $controller->dispenseProduct((int)$_GET['product']);
        }
        break;
    default:
        echo "404 - Page non trouvée";
}
