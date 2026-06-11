<?php include __DIR__ . '/../layout/base.php'; ?>

<h1 class="text-2xl font-bold mb-6">Gestion des Stocks</h1>

<?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-500 text-white p-3 rounded mb-4">
        <?= htmlspecialchars($_SESSION['success']); ?>
    </div>
<?php unset($_SESSION['success']); endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-500 text-white p-3 rounded mb-4">
          <?= htmlspecialchars($_SESSION['error']); ?>
    </div>
<?php unset($_SESSION['error']); endif; ?>