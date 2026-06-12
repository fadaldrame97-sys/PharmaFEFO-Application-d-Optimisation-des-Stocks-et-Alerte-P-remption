<?php

declare(strict_types=1);

class ReturnController
{
    public function manage(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'PHARMACIEN') {
            $_SESSION['error'] = "Acces non autorise.";
            header('Location: index.php?action=login');
            exit;
        }

        $returns = [];
        require __DIR__ . '/../../templates/returns/index.php';
    }

    public function accept(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'PHARMACIEN') {
            $_SESSION['error'] = "Acces non autorise.";
            header('Location: index.php?action=login');
            exit;
        }

        $returnId = (int) ($_POST['returnId'] ?? 0);
        if ($returnId > 0) {
            $_SESSION['success'] = "Retour #$returnId accepte.";
        }

        header('Location: index.php?action=returns');
        exit;
    }

    public function refuse(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'PHARMACIEN') {
            $_SESSION['error'] = "Acces non autorise.";
            header('Location: index.php?action=login');
            exit;
        }

        $returnId = (int) ($_POST['returnId'] ?? 0);
        if ($returnId > 0) {
            $_SESSION['success'] = "Retour #$returnId refuse.";
        }

        header('Location: index.php?action=returns');
        exit;
    }
}
