<?php include __DIR__ . '/../../layout/base.php'; ?>

<h1 class="text-2xl font-bold mb-6">Rapports</h1>

<h2 class="text-lg font-semibold mb-3">Statistiques par statut</h2>
<?php if (!empty($countByStatus)): ?>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <?php foreach ($countByStatus as $stat): ?>
        <div class="bg-white shadow rounded-lg p-4 text-center">
            <div class="text-2xl font-bold"><?= $stat['total']; ?></div>
            <div class="text-gray-600"><?= htmlspecialchars($stat['status']); ?></div>
        </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<p class="text-gray-500 mb-6">Aucune donnee disponible.</p>
<?php endif; ?>

<h2 class="text-lg font-semibold mb-3">Lots par criticite</h2>
<?php if (!empty($byCriticality)): ?>
<div class="overflow-x-auto">
<table class="min-w-full border border-gray-300 rounded-lg shadow-sm">
    <thead class="bg-gray-200">
        <tr>
            <th class="px-4 py-2 border">Lot</th>
            <th class="px-4 py-2 border">Produit ID</th>
            <th class="px-4 py-2 border">Quantite</th>
            <th class="px-4 py-2 border">Date peremption</th>
            <th class="px-4 py-2 border">Criticite</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($byCriticality as $row): ?>
            <tr class="hover:bg-gray-100">
                <td class="px-4 py-2 border"><?= htmlspecialchars($row['lot_number']); ?></td>
                <td class="px-4 py-2 border"><?= $row['product_id']; ?></td>
                <td class="px-4 py-2 border"><?= $row['quantity']; ?></td>
                <td class="px-4 py-2 border"><?= $row['expiration_date']; ?></td>
                <td class="px-4 py-2 border">
                    <?php
                    $cls = 'bg-green-500 text-white';
                    if ($row['criticity'] === 'CRITICAL') $cls = 'bg-red-500 text-white';
                    elseif ($row['criticity'] === 'WARNING') $cls = 'bg-yellow-400 text-black';
                    ?>
                    <span class="<?= $cls; ?> px-2 py-1 rounded"><?= $row['criticity']; ?></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php else: ?>
<p class="text-gray-500">Aucun lot actif.</p>
<?php endif; ?>

    </main>
</body>
</html>
