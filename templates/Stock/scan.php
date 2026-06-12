<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Scanner une entrée</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-lg">
        <h1 class="text-2xl font-bold text-gray-700 mb-6">Scanner une entrée de stock</h1>

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/stock/scanEntry" class="space-y-4">
            <div>
                <label class="block text-gray-600 mb-1">Produit ID</label>
                <input type="number" name="product_id" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
            </div>

            <div>
                <label class="block text-gray-600 mb-1">Numéro de lot</label>
                <input type="text" name="lot_number" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
            </div>

            <div>
                <label class="block text-gray-600 mb-1">Quantité</label>
                <input type="number" name="quantity" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
            </div>

            <div>
                <label class="block text-gray-600 mb-1">Date de péremption</label>
                <input type="date" name="expiration_date" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Enregistrer le lot
            </button>
        </form>
    </div>

</body>
</html>
