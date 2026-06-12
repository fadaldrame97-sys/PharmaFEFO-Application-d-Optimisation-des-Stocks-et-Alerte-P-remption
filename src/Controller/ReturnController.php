
<?php
class ReturnController
{
    public function manage(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'PHARMACIEN') {
            header('Location: /unauthorized');
            exit;
        }

        // Ici tu récupères les retours depuis ReturnRepository
        $returns = []; // à remplacer par $returnRepo->findAll()
        require __DIR__ . '/../templates/returns/index.php';
    }
}
