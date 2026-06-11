<?php include __DIR__ . '/../layout/base.php'; ?>

<h1>Gestion des Stocks</h1>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['success']); ?>
    </div>
<?php unset($_SESSION['success']); endif; ?>