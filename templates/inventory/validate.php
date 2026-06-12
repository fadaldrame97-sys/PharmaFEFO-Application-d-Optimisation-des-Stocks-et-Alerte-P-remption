<?php include __DIR__ . '/../layout/base.php'; ?>

<h1 class="text-2xl font-bold mb-6">Validation des Inventaires</h1>

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

<table class="min-w-full border border-gray-300 rounded-lg shadow-sm">
    <thead class="bg-gray-200">
        <tr>
            <th class="px-4 py-2 border">ID Lot</th>
            <th class="px-4 py-2 border">Produit</th>
            <th class="px-4 py-2 border">Numéro de Lot</th>
            <th class="px-4 py-2 border">Quantité</th>
            <th class="px-4 py-2 border">Date de Péremption</th>
            <th class="px-4 py-2 border">Statut</th>
            <th class="px-4 py-2 border">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($batches as $batch): ?>
            <tr class="hover:bg-gray-100">
                <td class="px-4 py-2 border"><?= $batch->getId(); ?></td>
                <td class="px-4 py-2 border"><?= $batch->getProductId(); ?></td>
                <td class="px-4 py-2 border"><?= $batch->getLotNumber(); ?></td>
                <td class="px-4 py-2 border"><?= $batch->getQuantity(); ?></td>
                <td class="px-4 py-2 border"><?= $batch->getExpirationDate()->format('Y-m-d'); ?></td>
                <td class="px-4 py-2 border"><?= $batch->getStatus(); ?></td>
                <td class="px-4 py-2 border text-center">
                    <form method="post" action="/inventory/validate">
                        <input type="hidden" name="batchId" value="<?= $batch->getId(); ?>">
                        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                            Valider
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
