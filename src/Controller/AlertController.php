<?php

declare(strict_types=1);

class AlertController
{
    public function configure(): void
    {
        AuthMiddleware::requireRole('PHARMACIEN');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $criticalDays = (int) ($_POST['critical_days'] ?? 30);
            $warningDays  = (int) ($_POST['warning_days'] ?? 90);
            $okDays       = (int) ($_POST['ok_days'] ?? 180);
            $notifType    = $_POST['notification_type'] ?? 'popup';

            $_SESSION['alert_config'] = [
                'critical_days'     => $criticalDays,
                'warning_days'      => $warningDays,
                'ok_days'           => $okDays,
                'notification_type' => $notifType,
            ];

            $_SESSION['success'] = "Configuration des alertes sauvegardee.";
            header('Location: index.php?action=alerts');
            exit;
        }

        require __DIR__ . '/../../templates/dashboard/alerts/config.php';
    }
}
