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
            $productId = (int) ($_POST['product_id'] ?? 0);
            $lotNumber = trim($_POST['lot_number'] ?? '');
            $quantity = (int) ($_POST['quantity'] ?? 0);
            $expirationDateStr = $_POST['expiration_date'] ?? '';

            if ($productId <= 0 || $lotNumber === '' || $quantity <= 0 || $expirationDateStr === '') {
                $_SESSION['error'] = "Tous les champs sont obligatoires.";
                header('Location: index.php?action=scan');
                exit;
            }

            $expirationDate = new DateTime($expirationDateStr);

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
