<?php include __DIR__ . '/../layout/base.php'; ?>

<h1 class="text-2xl font-bold mb-6">Gestion des Stocks</h1>

<?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['success']); ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['error']); ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="mb-4">
    <a href="index.php?action=scan" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Ajouter un lot
    </a>
</div>

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
            <th class="px-4 py-2 border">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($batches)): ?>
            <?php foreach ($batches as $batch): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border"><?= $batch->getId(); ?></td>
                    <td class="px-4 py-2 border"><?= $batch->getProductId(); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($batch->getLotNumber()); ?></td>
                    <td class="px-4 py-2 border"><?= $batch->getQuantity(); ?></td>
                    <td class="px-4 py-2 border"><?= $batch->getExpirationDate()->format('Y-m-d'); ?></td>
                    <td class="px-4 py-2 border">
                        <?php if ($batch->getStatus() === 'EXPIRED'): ?>
                            <span class="bg-red-500 text-white px-2 py-1 rounded">EXPIRE</span>
                        <?php else: ?>
                            <span class="bg-green-500 text-white px-2 py-1 rounded"><?= htmlspecialchars($batch->getStatus()); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-2 border">
                        <?php if ($batch->getStatus() !== 'EXPIRED' && $batch->getQuantity() > 0): ?>
                            <a href="index.php?action=dispense&product=<?= $batch->getProductId(); ?>"
                               class="text-blue-600 hover:underline">Dispenser</a> |
                        <?php endif; ?>
                        <?php if ($batch->getStatus() !== 'EXPIRED'): ?>
                            <a href="index.php?action=expire&batch=<?= $batch->getId(); ?>"
                               class="text-red-600 hover:underline">Marquer expire</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center py-4 text-gray-600">
                    Aucun lot disponible
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

    </main>
</body>
</html>
