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

        try {
            $batches = $this->stockBatchRepository->findAll();
        } catch (RuntimeException $e) {
            error_log('StockController::index error: ' . $e->getMessage());
            $_SESSION['error'] = "Impossible de charger les lots.";
            $batches = [];
        }

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

        try {
            $batch = $this->stockBatchRepository->getNextExpiringBatch($productId);

            if (!$batch) {
                $_SESSION['error'] = "Aucun lot disponible pour ce produit.";
                header('Location: index.php?action=stock');
                exit;
            }

            $newQuantity = $batch->getQuantity() - $quantity;

            if ($newQuantity < 0) {
                $_SESSION['error'] = "Stock insuffisant pour ce lot.";
                header('Location: index.php?action=stock');
                exit;
            }

            $this->stockBatchRepository->updateQuantity($batch->getId(), $newQuantity);
            $_SESSION['success'] = "Dispensation reussie : lot " . $batch->getLotNumber() . " decremente de $quantity.";
        } catch (RuntimeException $e) {
            error_log('StockController::dispenseProduct error: ' . $e->getMessage());
            $_SESSION['error'] = "Erreur lors de la dispensation : " . $e->getMessage();
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

        try {
            $this->stockBatchRepository->markAsExpired($batchId);
            $_SESSION['success'] = "Lot $batchId marque comme expire.";
        } catch (RuntimeException $e) {
            error_log('StockController::markExpired error: ' . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du marquage du lot : " . $e->getMessage();
        }

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
            $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
            $lotNumber = trim($_POST['lot_number'] ?? '');
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
            $expirationDateStr = $_POST['expiration_date'] ?? '';

            if (!$productId || $productId <= 0 || $lotNumber === '' || !$quantity || $quantity <= 0 || $expirationDateStr === '') {
                $_SESSION['error'] = "Tous les champs sont obligatoires et doivent etre valides.";
                header('Location: index.php?action=scan');
                exit;
            }

            try {
                $expirationDate = new DateTime($expirationDateStr);
            } catch (\Exception $e) {
                $_SESSION['error'] = "Date d'expiration invalide.";
                header('Location: index.php?action=scan');
                exit;
            }

            try {
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
            } catch (RuntimeException $e) {
                error_log('StockController::scanEntry error: ' . $e->getMessage());
                $_SESSION['error'] = "Erreur lors de l'enregistrement du lot : " . $e->getMessage();
            }

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
