<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaFEFO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-blue-700 text-white px-6 py-3 flex items-center justify-between">
        <a href="index.php?action=dashboard" class="text-xl font-bold">PharmaFEFO</a>
        <?php if (isset($_SESSION['user'])): ?>
            <div class="flex items-center gap-4">
                <span class="text-sm"><?= htmlspecialchars($_SESSION['user']['email']); ?> (<?= htmlspecialchars($_SESSION['user']['role']); ?>)</span>
                <a href="index.php?action=stock" class="hover:underline text-sm">Stock</a>
                <a href="index.php?action=dashboard" class="hover:underline text-sm">Dashboard</a>
                <?php if ($_SESSION['user']['role'] === 'PHARMACIEN'): ?>
                    <a href="index.php?action=inventory" class="hover:underline text-sm">Inventaire</a>
                    <a href="index.php?action=alerts" class="hover:underline text-sm">Alertes</a>
                    <a href="index.php?action=returns" class="hover:underline text-sm">Retours</a>
                <?php endif; ?>
                <?php if ($_SESSION['user']['role'] === 'ADMIN'): ?>
                    <a href="index.php?action=reports" class="hover:underline text-sm">Rapports</a>
                    <a href="index.php?action=security" class="hover:underline text-sm">Securite</a>
                <?php endif; ?>
                <a href="index.php?action=logout" class="bg-red-500 px-3 py-1 rounded text-sm hover:bg-red-600">Deconnexion</a>
            </div>
        <?php endif; ?>
    </nav>

    <main class="p-6">
