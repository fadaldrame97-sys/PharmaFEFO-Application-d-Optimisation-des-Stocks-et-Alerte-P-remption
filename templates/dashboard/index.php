<?php
function getCriticityClass(string $criticity): string {
    switch ($criticity) {
        case 'CRITICAL': return 'bg-red-500 text-white';   // Rouge
        case 'WARNING':  return 'bg-yellow-400 text-black'; // Orange/Jaune
        default:         return 'bg-green-500 text-white';  // Vert
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link rel="stylesheet" href="css.css">
</head>
<body>
    


<h1>Tableau de bord PharmaFEFO</h1>

<h2>Lots qui expirent bientôt</h2>
<ul>
    <?php foreach ($expiringSoon as $batch): ?>
        <li><?= $batch->getLotNumber() ?> - expire le <?= $batch->getExpirationDate()->format('Y-m-d') ?></li>
    <?php endforeach; ?>
</ul>

<h2>Lots expirés</h2>
<ul>
    <?php foreach ($expired as $batch): ?>
        <li><?= $batch->getLotNumber() ?> (EXPIRÉ)</li>
    <?php endforeach; ?>
</ul>

<table class="table-auto border-collapse border border-gray-300 w-full">
    <thead>
        <tr>
            <th class="border px-4 py-2">Lot</th>
            <th class="border px-4 py-2">Produit</th>
            <th class="border px-4 py-2">Quantité</th>
            <th class="border px-4 py-2">Date péremption</th>
            <th class="border px-4 py-2">Criticité</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($batches as $batch): ?>
        <tr>
            <td class="border px-4 py-2"><?= $batch['lot_number']; ?></td>
            <td class="border px-4 py-2"><?= $batch['product_id']; ?></td>
            <td class="border px-4 py-2"><?= $batch['quantity']; ?></td>
            <td class="border px-4 py-2"><?= $batch['expiration_date']; ?></td>
            <td class="border px-4 py-2 <?= getCriticityClass($batch['criticity']); ?>">
                <?= $batch['criticity']; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
