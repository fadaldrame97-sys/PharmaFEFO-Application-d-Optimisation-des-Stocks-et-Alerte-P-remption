<?php include __DIR__ . '/../layout/base.php'; ?>

<h1 class="text-2xl font-bold mb-6">Consultation des Stocks</h1>

<div class="overflow-x-auto">
    <table class="min-w-full border border-gray-300 rounded-lg shadow-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2 text-left">ID Lot</th>
                <th class="border px-4 py-2 text-left">Produit ID</th>
                <th class="border px-4 py-2 text-left">Numero de Lot</th>
                <th class="border px-4 py-2 text-left">Quantite</th>
                <th class="border px-4 py-2 text-left">Date de Peremption</th>
                <th class="border px-4 py-2 text-left">Statut</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($batches as $batch): ?>
            <tr class="hover:bg-gray-50">
                <td class="border px-4 py-2"><?= $batch->getId(); ?></td>
                <td class="border px-4 py-2"><?= $batch->getProductId(); ?></td>
                <td class="border px-4 py-2"><?= htmlspecialchars($batch->getLotNumber()); ?></td>
                <td class="border px-4 py-2"><?= $batch->getQuantity(); ?></td>
                <td class="border px-4 py-2"><?= $batch->getExpirationDate()->format('Y-m-d'); ?></td>
                <td class="border px-4 py-2">
                    <?php if ($batch->getStatus() === 'EXPIRED'): ?>
                        <span class="bg-red-500 text-white px-2 py-1 rounded">EXPIRE</span>
                    <?php else: ?>
                        <span class="bg-green-500 text-white px-2 py-1 rounded"><?= htmlspecialchars($batch->getStatus()); ?></span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

    </main>
</body>
</html>
