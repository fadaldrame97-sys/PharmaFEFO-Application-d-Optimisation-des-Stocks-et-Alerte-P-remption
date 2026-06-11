<?php
function getCriticityClass(DateTime $expiry): string {
    $now = new DateTime();
    $interval = $now->diff($expiry)->days;
    if ($interval <= 30) return 'bg-red-500 text-white';   // Rouge
    if ($interval <= 90) return 'bg-yellow-400 text-black'; // Jaune/Orange
    return 'bg-green-500 text-white';                      // Vert
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard PharmaFEFO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-6">

<h1 class="text-2xl font-bold mb-4">Tableau de bord PharmaFEFO</h1>

<h2 class="text-xl mb-2">Lots qui expirent bientôt</h2>
<ul class="list-disc pl-6 mb-4">
    <?php foreach ($expiringSoon as $batch): ?>
        <li class="<?= getCriticityClass($batch->getExpirationDate()); ?>">
            <?= $batch->getLotNumber() ?> - expire le <?= $batch->getExpirationDate()->format('Y-m-d') ?>
        </li>
    <?php endforeach; ?>
</ul>

<h2 class="text-xl mb-2">Lots expirés</h2>
<ul class="list-disc pl-6 mb-4">
    <?php foreach ($expired as $batch): ?>
        <li class="bg-red-700 text-white">
            <?= $batch->getLotNumber() ?> (EXPIRÉ)
        </li>
    <?php endforeach; ?>
</ul>

<h2 class="text-xl mb-2">Vue globale des lots</h2>
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
    <?php foreach ($allBatches as $batch): ?>
        <tr>
            <td class="border px-4 py-2"><?= $batch->getLotNumber(); ?></td>
            <td class="border px-4 py-2"><?= $batch->getProductId(); ?></td>
            <td class="border px-4 py-2"><?= $batch->getQuantity(); ?></td>
            <td class="border px-4 py-2"><?= $batch->getExpirationDate()->format('Y-m-d'); ?></td>
            <td class="border px-4 py-2 <?= getCriticityClass($batch->getExpirationDate()); ?>">
                <?= (new DateTime())->diff($batch->getExpirationDate())->days <= 30 ? 'CRITICAL' :
                    ((new DateTime())->diff($batch->getExpirationDate())->days <= 90 ? 'WARNING' : 'OK'); ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
