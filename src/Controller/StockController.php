<?php

declare(strict_types=1);

class StockController
{
    private StockBatchRepository $stockBatchRepository;
    private ProductRepository $productRepository;

    public function __construct(
        StockBatchRepository $stockBatchRepository,
        ProductRepository $productRepository
    ) {
        $this->stockBatchRepository = $stockBatchRepository;
        $this->productRepository = $productRepository;
    }

    public function index(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $role = $_SESSION['user']['role'];
        $batches = $this->stockBatchRepository->findAll();

        if ($role === 'ADMIN' || $role === 'GESTIONNAIRE') {
            require __DIR__ . '/../../templates/Stock/Index.php';
        } elseif ($role === 'PHARMACIEN') {
            require __DIR__ . '/../../templates/Stock/read_only.php';
        } else {
            $_SESSION['error'] = "Acces interdit.";
            header('Location: index.php?action=login');
            exit;
        }
    }

    public function dispenseProduct(int $productId): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;
        $batch = $this->stockBatchRepository->getNextExpiringBatch($productId);

        if ($batch) {
            $newQuantity = $batch->getQuantity() - $quantity;

            if ($newQuantity >= 0) {
                $this->stockBatchRepository->updateQuantity($batch->getId(), $newQuantity);
                $_SESSION['success'] = "Dispensation reussie : lot " . $batch->getLotNumber() . " decremente de $quantity.";
            } else {
                $_SESSION['error'] = "Stock insuffisant pour ce lot.";
            }
        } else {
            $_SESSION['error'] = "Aucun lot disponible pour ce produit.";
        }

        header('Location: index.php?action=stock');
        exit;
    }

    public function markExpired(int $batchId): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $this->stockBatchRepository->markAsExpired($batchId);
        $_SESSION['success'] = "Lot $batchId marque comme expire.";
        header('Location: index.php?action=stock');
        exit;
    }

    public function scanEntry(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $role = $_SESSION['user']['role'];
        if ($role !== 'ADMIN' && $role !== 'GESTIONNAIRE' && $role !== 'PREPARATEUR') {
            $_SESSION['error'] = "Acces interdit.";
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Csrf::validateToken($_POST['csrf_token'] ?? null)) {
                $_SESSION['error'] = "Jeton de securite invalide.";
                header('Location: index.php?action=scan');
                exit;
            }

            $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
            $lotNumber = trim($_POST['lot_number'] ?? '');
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
            $expirationDateStr = $_POST['expiration_date'] ?? '';

            if (!$productId || $productId <= 0) {
                $_SESSION['error'] = "ID produit invalide.";
                header('Location: index.php?action=scan');
                exit;
            }

            if ($lotNumber === '' || strlen($lotNumber) > 100 || !preg_match('/^[a-zA-Z0-9\-_]+$/', $lotNumber)) {
                $_SESSION['error'] = "Numero de lot invalide (alphanumerique, tirets et underscores uniquement).";
                header('Location: index.php?action=scan');
                exit;
            }

            if (!$quantity || $quantity <= 0) {
                $_SESSION['error'] = "Quantite invalide.";
                header('Location: index.php?action=scan');
                exit;
            }

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $expirationDateStr)) {
                $_SESSION['error'] = "Format de date invalide.";
                header('Location: index.php?action=scan');
                exit;
            }

            $expirationDate = DateTime::createFromFormat('Y-m-d', $expirationDateStr);
            if (!$expirationDate) {
                $_SESSION['error'] = "Date de peremption invalide.";
                header('Location: index.php?action=scan');
                exit;
            }

            $batch = new StockBatch(
                0,
                $productId,
                $lotNumber,
                $quantity,
                $expirationDate,
                'AVAILABLE'
            );

            $this->stockBatchRepository->create($batch);

            $_SESSION['success'] = "Lot enregistre avec succes.";
            header('Location: index.php?action=stock');
            exit;
        }

        require __DIR__ . '/../../templates/Stock/scan.php';
    }

    public function receptionForm(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $role = $_SESSION['user']['role'];
        if ($role !== 'GESTIONNAIRE' && $role !== 'PREPARATEUR') {
            $_SESSION['error'] = "Acces interdit a la reception.";
            header('Location: index.php?action=login');
            exit;
        }

        require __DIR__ . '/../../templates/reception_de_commandes/index.php';
    }
}
