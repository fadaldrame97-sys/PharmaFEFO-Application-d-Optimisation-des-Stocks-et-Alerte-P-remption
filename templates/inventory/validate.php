<?php include __DIR__ . '/../layout/base.php'; ?>

<h1 class="text-2xl font-bold mb-6">Validation des Inventaires</h1>

<?php include __DIR__ . '/../partials/flash_messages.php'; ?>

<div class="overflow-x-auto">
<table class="min-w-full border border-gray-300 rounded-lg shadow-sm">
    <thead class="bg-gray-200">
        <tr>
            <th class="px-4 py-2 border">ID Lot</th>
            <th class="px-4 py-2 border">Produit ID</th>
            <th class="px-4 py-2 border">Numero de Lot</th>
            <th class="px-4 py-2 border">Quantite</th>
            <th class="px-4 py-2 border">Date de Peremption</th>
            <th class="px-4 py-2 border">Statut</th>
            <th class="px-4 py-2 border">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($batches as $batch): ?>
            <tr class="hover:bg-gray-100">
                <td class="px-4 py-2 border"><?= $batch->getId(); ?></td>
                <td class="px-4 py-2 border"><?= $batch->getProductId(); ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($batch->getLotNumber()); ?></td>
                <td class="px-4 py-2 border"><?= $batch->getQuantity(); ?></td>
                <td class="px-4 py-2 border"><?= $batch->getExpirationDate()->format('Y-m-d'); ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($batch->getStatus()); ?></td>
                <td class="px-4 py-2 border text-center">
                    <form method="post" action="index.php?action=inventory">
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
</div>

    </main>
</body>
</html>
