<?php include __DIR__ . '/../layout/base.php'; ?>

<h1 class="text-2xl font-bold mb-6">Gestion des Retours</h1>

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
            <th class="px-4 py-2 border">ID Retour</th>
            <th class="px-4 py-2 border">Produit</th>
            <th class="px-4 py-2 border">Lot</th>
            <th class="px-4 py-2 border">Quantité</th>
            <th class="px-4 py-2 border">Motif</th>
            <th class="px-4 py-2 border">Date</th>
            <th class="px-4 py-2 border">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($returns as $return): ?>
            <tr class="hover:bg-gray-100">
                <td class="px-4 py-2 border"><?= $return->getId(); ?></td>
                <td class="px-4 py-2 border"><?= $return->getProductId(); ?></td>
                <td class="px-4 py-2 border"><?= $return->getLotNumber(); ?></td>
                <td class="px-4 py-2 border"><?= $return->getQuantity(); ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($return->getReason()); ?></td>
                <td class="px-4 py-2 border"><?= $return->getDate()->format('Y-m-d'); ?></td>
                <td class="px-4 py-2 border text-center">
                    <form method="post" action="/returns/accept" style="display:inline;">
                        <input type="hidden" name="returnId" value="<?= $return->getId(); ?>">
                        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                            Accepter
                        </button>
                    </form>
                    <form method="post" action="/returns/refuse" style="display:inline;">
                        <input type="hidden" name="returnId" value="<?= $return->getId(); ?>">
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                            Refuser
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
