<?php include __DIR__ . '/../../layout/base.php'; ?>

<h1 class="text-2xl font-bold mb-6">Administration - Base de donnees</h1>

<div class="bg-white shadow-md rounded-lg p-6">
    <h2 class="text-lg font-semibold mb-4">Informations de la base</h2>
    <ul class="list-disc pl-6 space-y-2 text-gray-700">
        <li>Serveur : localhost</li>
        <li>Base de donnees : PharmaFEFO</li>
        <li>Moteur : MySQL / MariaDB</li>
    </ul>

    <h2 class="text-lg font-semibold mt-6 mb-4">Tables principales</h2>
    <ul class="list-disc pl-6 space-y-1 text-gray-700">
        <li><strong>users</strong> - Utilisateurs du systeme</li>
        <li><strong>products</strong> - Catalogue des produits</li>
        <li><strong>stock_batches</strong> - Lots de stock avec dates de peremption</li>
        <li><strong>etat_stock</strong> - Historique des etats de stock</li>
    </ul>
</div>

    </main>
</body>
</html>
