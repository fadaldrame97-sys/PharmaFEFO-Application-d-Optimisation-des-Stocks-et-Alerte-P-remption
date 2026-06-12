<div class="bg-white shadow-md rounded-lg p-8 max-w-lg">
    <form method="POST" action="index.php?action=scan" class="space-y-4">
        <div>
            <label class="block text-gray-600 mb-1">Produit ID</label>
            <input type="number" name="product_id" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block text-gray-600 mb-1">Numero de lot</label>
            <input type="text" name="lot_number" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block text-gray-600 mb-1">Quantite</label>
            <input type="number" name="quantity" min="1" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>

        <div>
            <label class="block text-gray-600 mb-1">Date de peremption</label>
            <input type="date" name="expiration_date" class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
            Enregistrer le lot
        </button>
    </form>
</div>
