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
        session_start();
    }

     public function dispenseProduct(int $productId, int $quantity = 1): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
               $batch = $this->stockBatchRepository->getNextExpiringBatch($productId);

        if ($batch) {
            $newQuantity = $batch->getQuantity() - 1;

            if ($newQuantity >= 0) {
                $this->stockBatchRepository->updateQuantity($batch->getId(), $newQuantity);
                $_SESSION['success'] = "Dispensation réussie : lot {$batch->getLotNumber()} décrémenté.";
            } else {
                $_SESSION['error'] = "Stock insuffisant pour ce lot.";
            }
        } else {
            $_SESSION['error'] = "Aucun lot disponible pour ce produit.";
        }

        header('Location: /dashboard');
        exit;
    }


    public function markExpired(int $batchId): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $this->stockBatchRepository->markAsExpired($batchId);
        $_SESSION['success'] = "Lot $batchId marqué comme expiré.";
        header('Location: /dashboard');
        exit;
    }

     public function index(): void
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $batches = $this->stockBatchRepository->findAll();
        require __DIR__ . '/../templates/stock/index.php';

    

    
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }

    $role = $_SESSION['user']['role'];
    $batches = $this->stockBatchRepository->findAll();

    if ($role === 'ADMIN' || $role === 'GESTIONNAIRE') {
        require __DIR__ . '/../templates/stock/index.php'; // Vue avec actions
    } elseif ($role === 'PHARMACIEN') {
        require __DIR__ . '/../templates/stock/read_only.php'; // Vue lecture seule
    } else {
        die("Accès interdit.");
    }
    }


   public function scanEntry(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: /login');
        exit;
    }

    $role = $_SESSION['user']['role'];

    if ($role !== 'ADMIN' && $role !== 'GESTIONNAIRE' && $role !== 'PREPARATEUR') {
        die("Accès interdit.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productId = (int) $_POST['product_id'];
        $lotNumber = $_POST['lot_number'];
        $quantity = (int) $_POST['quantity'];
        $expirationDate = new DateTime($_POST['expiration_date']);

        // Création du lot avec statut par défaut
     $batch = new StockBatch(
       $productId,
       $lotNumber,
       $quantity,
       $expirationDate
      );

      $this->stockBatchRepository->create($batch);


        $_SESSION['success'] = "Lot enregistré avec succès.";
        header('Location: /stock');
        exit;
    }

    require __DIR__ . '/../templates/stock/scan.php';
}



    }


    
    

    