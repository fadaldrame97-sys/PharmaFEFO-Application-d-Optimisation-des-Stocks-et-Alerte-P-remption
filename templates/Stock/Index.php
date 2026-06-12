<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Stocks</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">

<h1 class="text-2xl font-bold mb-6">Gestion des Stocks</h1>

<table class="min-w-full border border-gray-300 rounded-lg shadow-sm bg-white">
    <thead class="bg-gray-200">
        <tr>
            <th class="px-4 py-2 border">ID Lot</th>
            <th class="px-4 py-2 border">Produit</th>
            <th class="px-4 py-2 border">Numéro de Lot</th>
            <th class="px-4 py-2 border">Quantité</th>
            <th class="px-4 py-2 border">Date de Péremption</th>
            <th class="px-4 py-2 border">Statut</th>
            <th class="px-4 py-2 border">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($batches)): ?>
            <?php foreach ($batches as $batch): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 border"><?= htmlspecialchars($batch->getId()); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($batch->getProductId()); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($batch->getLotNumber()); ?></td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($batch->getQuantity()); ?></td>
                    <td class="px-4 py-2 border">
                        <?= htmlspecialchars($batch->getExpirationDate()->format('Y-m-d')); ?>
                    </td>
                    <td class="px-4 py-2 border"><?= htmlspecialchars($batch->getStatus()); ?></td>
                    <td class="px-4 py-2 border">
                        <a href="index.php?action=dispense&product=<?= $batch->getProductId(); ?>" 
                           class="text-blue-600 hover:underline">Dispense</a> |
                        <a href="index.php?action=expire&batch=<?= $batch->getId(); ?>" 
                           class="text-red-600 hover:underline">Marquer expiré</a>
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

</body>
</html>
