<?php

declare(strict_types=1);

class ReturnController
{
    public function manage(): void
    {
        AuthMiddleware::requireRole('PHARMACIEN');

        $returns = [];
        require __DIR__ . '/../../templates/returns/index.php';
    }

    public function accept(): void
    {
        AuthMiddleware::requireRole('PHARMACIEN');

        $returnId = (int) ($_POST['returnId'] ?? 0);
        if ($returnId > 0) {
            $_SESSION['success'] = "Retour #$returnId accepte.";
        }

        header('Location: index.php?action=returns');
        exit;
    }

    public function refuse(): void
    {
        AuthMiddleware::requireRole('PHARMACIEN');

        $returnId = (int) ($_POST['returnId'] ?? 0);
        if ($returnId > 0) {
            $_SESSION['success'] = "Retour #$returnId refuse.";
        }

        header('Location: index.php?action=returns');
        exit;
    }
}
