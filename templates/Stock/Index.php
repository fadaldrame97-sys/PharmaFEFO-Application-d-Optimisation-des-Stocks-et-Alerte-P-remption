<?php include __DIR__ . '/../layout/base.php'; ?>

<h1 class="text-2xl font-bold mb-6">Gestion des Stocks</h1>

<?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-500 text-white p-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['success']); ?>
    </div>
<?php unset($_SESSION['success']); endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-500 text-white p-3 rounded mb-4">
          <?= htmlspecialchars($_SESSION['error']); ?>
    </div>
<?php unset($_SESSION['error']); endif; ?>

<div class="overflow-x-auto">
    <table class="min-w-full border border-gray-300 rounded-lg shadow-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2 text-left">ID Lot</th>
                <th class="border px-4 py-2 text-left">Produit</th>
                <th class="border px-4 py-2 text-left">Numéro de Lot</th>
                <th class="border px-4 py-2 text-left">Quantité</th>
                <th class="border px-4 py-2 text-left">Date de Péremption</th>
                <th class="border px-4 py-2 text-left">Statut</th>
                <th class="border px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>