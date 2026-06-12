<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    
</body>
</html>

<h1 class="text-2xl font-bold mb-6">Gestion des Stocks</h1>

<table class="min-w-full border border-gray-300 rounded-lg shadow-sm">
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
                <tr>
                    <td class="px-4 py-2 border"><?= $batch->getId(); ?></td>
                    <td class="px-4 py-2 border"><?= $batch->getProductId(); ?></td>
                    <td class="px-4 py-2 border"><?= $batch->getLotNumber(); ?></td>
                    <td class="px-4 py-2 border"><?= $batch->getQuantity(); ?></td>
                    <td class="px-4 py-2 border"><?= $batch->getExpirationDate()->format('Y-m-d'); ?></td>
                    <td class="px-4 py-2 border"><?= $batch->getStatus(); ?></td>
                    <td class="px-4 py-2 border">
                        <a href="/stock/dispense?product=<?= $batch->getProductId(); ?>" 
                           class="text-blue-600 hover:underline">Dispense</a> |
                        <a href="/stock/expire?batch=<?= $batch->getId(); ?>" 
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