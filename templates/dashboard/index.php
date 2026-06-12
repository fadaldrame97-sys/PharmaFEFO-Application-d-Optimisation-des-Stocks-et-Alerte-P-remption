<?php
require_once __DIR__ . '/../../src/Services/Service.php';

function getCriticityClass(DateTime $expiry): string {
    $level = Service::getCriticityLevel($expiry);
    return Service::getCriticityClass($level);
}
?>
<?php include __DIR__ . '/../layout/base.php'; ?>

<h1 class="text-2xl font-bold mb-4">Tableau de bord PharmaFEFO</h1>

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

<h2 class="text-xl mb-2">Lots qui expirent bientot</h2>
<?php if (!empty($expiringSoon)): ?>
<ul class="list-disc pl-6 mb-4">
    <?php foreach ($expiringSoon as $batch): ?>
        <li class="<?= getCriticityClass($batch->getExpirationDate()); ?> px-2 py-1 rounded mb-1">
            <?= htmlspecialchars($batch->getLotNumber()); ?> - expire le <?= $batch->getExpirationDate()->format('Y-m-d'); ?>
            (Quantite: <?= $batch->getQuantity(); ?>)
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p class="text-gray-500 mb-4">Aucun lot expirant dans les 30 prochains jours.</p>
<?php endif; ?>

<h2 class="text-xl mb-2">Lots expires</h2>
<?php if (!empty($expired)): ?>
<ul class="list-disc pl-6 mb-4">
    <?php foreach ($expired as $batch): ?>
        <li class="bg-red-700 text-white px-2 py-1 rounded mb-1">
            <?= htmlspecialchars($batch->getLotNumber()); ?> (EXPIRE - <?= $batch->getExpirationDate()->format('Y-m-d'); ?>)
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<p class="text-gray-500 mb-4">Aucun lot expire.</p>
<?php endif; ?>

<h2 class="text-xl mb-2">Vue globale des lots</h2>
<div class="overflow-x-auto">
<table class="table-auto border-collapse border border-gray-300 w-full">
    <thead class="bg-gray-200">
        <tr>
            <th class="border px-4 py-2">Lot</th>
            <th class="border px-4 py-2">Produit ID</th>
            <th class="border px-4 py-2">Quantite</th>
            <th class="border px-4 py-2">Date peremption</th>
            <th class="border px-4 py-2">Criticite</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($allBatches as $batch): ?>
        <?php $level = Service::getCriticityLevel($batch->getExpirationDate()); ?>
        <tr>
            <td class="border px-4 py-2"><?= htmlspecialchars($batch->getLotNumber()); ?></td>
            <td class="border px-4 py-2"><?= $batch->getProductId(); ?></td>
            <td class="border px-4 py-2"><?= $batch->getQuantity(); ?></td>
            <td class="border px-4 py-2"><?= $batch->getExpirationDate()->format('Y-m-d'); ?></td>
            <td class="border px-4 py-2 <?= Service::getCriticityClass($level); ?>">
                <?= $level; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

    </main>
</body>
</html>
