<?php include __DIR__ . '/../../layout/base.php'; ?>

<h1 class="text-2xl font-bold mb-6">Configuration des Alertes</h1>

<?php include __DIR__ . '/../../partials/flash_messages.php'; ?>

<?php
$config = $_SESSION['alert_config'] ?? [
    'critical_days' => 30,
    'warning_days' => 90,
    'ok_days' => 180,
    'notification_type' => 'popup',
];
?>

<form method="post" action="index.php?action=alerts" class="space-y-6 max-w-lg">

    <div>
        <label class="block font-semibold mb-2">Seuil CRITICAL (Rouge)</label>
        <input type="number" name="critical_days" value="<?= $config['critical_days']; ?>" min="1"
               class="border rounded px-3 py-2 w-32">
        <span class="text-gray-600">jours avant peremption</span>
    </div>

    <div>
        <label class="block font-semibold mb-2">Seuil WARNING (Orange)</label>
        <input type="number" name="warning_days" value="<?= $config['warning_days']; ?>" min="1"
               class="border rounded px-3 py-2 w-32">
        <span class="text-gray-600">jours avant peremption</span>
    </div>

    <div>
        <label class="block font-semibold mb-2">Seuil OK (Vert)</label>
        <input type="number" name="ok_days" value="<?= $config['ok_days']; ?>" min="1"
               class="border rounded px-3 py-2 w-32">
        <span class="text-gray-600">jours avant peremption</span>
    </div>

    <div>
        <label class="block font-semibold mb-2">Notifications</label>
        <select name="notification_type" class="border rounded px-3 py-2">
            <option value="email" <?= $config['notification_type'] === 'email' ? 'selected' : ''; ?>>Email</option>
            <option value="popup" <?= $config['notification_type'] === 'popup' ? 'selected' : ''; ?>>Popup sur le dashboard</option>
            <option value="both" <?= $config['notification_type'] === 'both' ? 'selected' : ''; ?>>Les deux</option>
        </select>
    </div>

    <div>
        <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Sauvegarder la configuration
        </button>
    </div>
</form>

    </main>
</body>
</html>
