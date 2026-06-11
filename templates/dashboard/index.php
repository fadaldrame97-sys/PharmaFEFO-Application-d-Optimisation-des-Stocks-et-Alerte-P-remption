<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    
</body>
</html>

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
