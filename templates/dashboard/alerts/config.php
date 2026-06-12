<?php include __DIR__ . '/../layout/base.php'; ?>

<h1 class="text-2xl font-bold mb-6">Configuration des Alertes</h1>

<?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['success']); ?>
    </div>
<?php unset($_SESSION['success']); endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['error']); ?>
    </div>
<?php unset($_SESSION['error']); endif; ?>

<form method="post" action="/alerts/configure" class="space-y-6">

    <!-- Seuil CRITICAL -->
    <div>
        <label class="block font-semibold mb-2">Seuil CRITICAL (Rouge)</label>
        <input type="number" name="critical_days" value="30" min="1"
               class="border rounded px-3 py-2 w-32">
        <span class="text-gray-600">jours avant péremption</span>
    </div>

    <!-- Seuil WARNING -->
    <div>
        <label class="block font-semibold mb-2">Seuil WARNING (Orange)</label>
        <input type="number" name="warning_days" value="90" min="1"
               class="border rounded px-3 py-2 w-32">
        <span class="text-gray-600">jours avant péremption</span>
    </div>

    <!-- Seuil OK -->
    <div>
        <label class="block font-semibold mb-2">Seuil OK (Vert)</label>
        <input type="number" name="ok_days" value="180" min="1"
               class="border rounded px-3 py-2 w-32">
        <span class="text-gray-600">jours avant péremption</span>
    </div>

    <!-- Notifications -->
    <div>
        <label class="block font-semibold mb-2">Notifications</label>
        <select name="notification_type" class="border rounded px-3 py-2">
            <option value="email">Email</option>
            <option value="popup">Popup sur le dashboard</option>
            <option value="both">Les deux</option>
        </select>
    </div>

    <!-- Bouton de soumission -->
    <div>
        <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Sauvegarder la configuration
        </button>
    </div>
</form>
