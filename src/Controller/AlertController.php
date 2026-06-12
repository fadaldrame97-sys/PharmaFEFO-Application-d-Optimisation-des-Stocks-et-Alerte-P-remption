<?php
class AlertController
{
    public function configure(): void
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'PHARMACIEN') {
            header('Location: /unauthorized');
            exit;
        }

        require __DIR__ . '/../templates/alerts/config.php';
    }
}
