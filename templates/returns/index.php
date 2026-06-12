<?php include __DIR__ . '/../layout/base.php'; ?>

<h1 class="text-2xl font-bold mb-6">Gestion des Retours</h1>

<?php include __DIR__ . '/../partials/flash_messages.php'; ?>

<?php if (!empty($returns)): ?>
<div class="overflow-x-auto">
<table class="min-w-full border border-gray-300 rounded-lg shadow-sm">
    <thead class="bg-gray-200">
        <tr>
            <th class="px-4 py-2 border">ID Retour</th>
            <th class="px-4 py-2 border">Produit</th>
            <th class="px-4 py-2 border">Lot</th>
            <th class="px-4 py-2 border">Quantite</th>
            <th class="px-4 py-2 border">Motif</th>
            <th class="px-4 py-2 border">Date</th>
            <th class="px-4 py-2 border">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($returns as $return): ?>
            <tr class="hover:bg-gray-100">
                <td class="px-4 py-2 border"><?= $return['id'] ?? ''; ?></td>
                <td class="px-4 py-2 border"><?= $return['product_id'] ?? ''; ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($return['lot_number'] ?? ''); ?></td>
                <td class="px-4 py-2 border"><?= $return['quantity'] ?? ''; ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($return['reason'] ?? ''); ?></td>
                <td class="px-4 py-2 border"><?= $return['date'] ?? ''; ?></td>
                <td class="px-4 py-2 border text-center">
                    <form method="post" action="index.php?action=returnAccept" style="display:inline;">
                        <input type="hidden" name="returnId" value="<?= $return['id'] ?? 0; ?>">
                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                            Accepter
                        </button>
                    </form>
                    <form method="post" action="index.php?action=returnRefuse" style="display:inline;">
                        <input type="hidden" name="returnId" value="<?= $return['id'] ?? 0; ?>">
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                            Refuser
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php else: ?>
<p class="text-gray-500">Aucun retour en attente.</p>
<?php endif; ?>

    </main>
</body>
</html>
