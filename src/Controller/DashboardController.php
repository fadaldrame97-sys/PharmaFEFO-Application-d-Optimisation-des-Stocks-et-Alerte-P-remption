<?php
namespace PharmaFEFO\Controller;

use PharmaFEFO\Repository\StockBatchRepository;

class DashboardController {
    private $batchRepository;

    public function __construct() {
        $this->batchRepository = new StockBatchRepository();
    }

    public function index() {
        $error_msg = null;
        $success_msg = null;
        $priority_batch = null;
        
        $user_role = $_SESSION['user_role'] ?? 'PREPARATEUR'; 

        // 1. ACTION : Déclarer Périmé (US 4.1)
        if (isset($_GET['action_batch']) && $_GET['action_batch'] === 'expire' && isset($_GET['id'])) {
            if ($user_role === 'PHARMACIEN' || $user_role === 'ADMIN') {
                $this->batchRepository->markAsExpired((int)$_GET['id']);
                $success_msg = "Le lot a été retiré du stock virtuel par le Pharmacien.";
            } else {
                $error_msg = "Sécurité : Seul un Pharmacien ou Admin peut déclarer un lot périmé !";
            }
        }

        // 2. ACTION : Réception (US 1.1)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_batch'])) {
            if ($user_role === 'PREPARATEUR') {
                $product_id = (int)$_POST['product_id'];
                $lot_number = trim($_POST['lot_number']);
                $quantity = (int)$_POST['quantity'];
                $expiration_date = $_POST['expiration_date'];

                if (empty($expiration_date) || strtotime($expiration_date) < strtotime(date('Y-m-d'))) {
                    $error_msg = "Erreur : La date de péremption ne peut pas être antérieure à aujourd'hui !";
                } else {
                    $this->batchRepository->save([
                        'product_id' => $product_id,
                        'lot_number' => $lot_number,
                        'quantity' => $quantity,
                        'expiration_date' => $expiration_date,
                        'status' => 'OK'
                    ]);
                    $success_msg = "Le lot a été enregistré avec succès.";
                }
            } else {
                $error_msg = "Sécurité : Seul un Préparateur peut enregistrer une commande !";
            }
        }

        // 3. ACTION : Sortie intelligente FEFO (US 3.1)
        if (isset($_GET['check_priority']) && !empty($_GET['search_product_id'])) {
            $priority_batch = $this->batchRepository->getPriorityBatch((int)$_GET['search_product_id']);
        }

        // 4. RÉCUPÉRATION STRICTE (Bla hssab dyal l-alwân hna)
        $batches = $this->batchRepository->findAllFEFO();
        $products = $this->batchRepository->getAllProducts();
        $total_pertes = $this->batchRepository->getTotalPertesBoites();

        // Require de la vue directement
        require_once __DIR__ . '/../../templates/dashboard/index.php';
    }
}