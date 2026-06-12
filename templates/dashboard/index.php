<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaFEFO - Tableaux de Bord & Pertes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans text-gray-900">

    <div class="container mx-auto p-6 max-w-7xl">
        
      <header class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center bg-white p-6 rounded-xl shadow-sm border border-gray-100 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-blue-600">PharmaFEFO 💊</h1>
                <p class="text-sm text-gray-500 mt-1">Système de gestion et d'optimisation anti-gaspillage (Règle FEFO)</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-4 w-full md:w-auto justify-end">
                <div class="bg-red-50 border border-red-200 px-4 py-2 rounded-xl text-left">
                    <span class="text-xs text-red-700 font-bold uppercase tracking-wider block">Valeur du stock perdu</span>
                    <span class="text-base font-black text-red-600"><?= isset($total_pertes) ? $total_pertes : 0 ?> boîtes</span>
                </div>

                <div class="bg-gray-50 border border-gray-200 px-4 py-2 rounded-xl flex items-center gap-3">
                    <div class="text-right">
                        <span class="text-[10px] text-gray-400 font-bold uppercase block">Compte</span>
                        <span class="text-xs font-semibold text-gray-600"><?= htmlspecialchars($_SESSION['user_email']) ?></span>
                    </div>
                    <div class="border-l pl-3">
                        <span class="text-[10px] text-blue-500 font-bold uppercase block">Rôle</span>
                        <span class="text-xs font-black text-blue-700"><?= htmlspecialchars($_SESSION['user_role']) ?></span>
                    </div>
                </div>

                <a href="index.php?action=logout" class="bg-red-600 hover:bg-red-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition shadow-sm">
                    🚪 Déconnexion
                </a>
            </div>
        </header>

        <?php if (!empty($error_msg)): ?>
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 font-medium rounded-lg shadow-sm flex items-center gap-2">
                <span>⚠️</span> <?= $error_msg ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($success_msg)): ?>
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 font-medium rounded-lg shadow-sm flex items-center gap-2">
                <span>✅</span> <?= $success_msg ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">📥 [Préparateur] Réception de Commande</h2>
                    
                    <?php if (($_SESSION['user_role'] ?? '') === 'PREPARATEUR'): ?>
                        <form action="index.php?action=dashboard" method="POST" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Choisir le Médicament</label>
                                <select name="product_id" class="w-full p-2.5 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500" required>
                                    <?php foreach ($products as $prod): ?>
                                        <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['name']) ?> (<?= htmlspecialchars($prod['code']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">N° de Lot</label>
                                    <input type="text" name="lot_number" placeholder="Ex: LOT-XYZ" class="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Quantité</label>
                                    <input type="number" name="quantity" min="1" placeholder="100" class="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Date de Péremption (DLU)</label>
                                <input type="date" name="expiration_date" class="w-full p-2.5 border rounded-lg text-gray-700 focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <button type="submit" name="add_batch" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold p-3 rounded-lg transition shadow-sm">Enregistrer dans la file FEFO</button>
                        </form>
                    <?php else: ?>
                        <div class="p-6 text-center border-2 border-dashed border-gray-200 rounded-xl bg-gray-50 text-gray-400 text-sm">
                            🔒 Formulaire bloqué. Seul un utilisateur avec le rôle <span class="font-bold">PREPARATEUR</span> peut enregistrer des entrées.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                <div>
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">⚡ Dispensation / Vente Automatique</h2>
                    <form action="index.php" method="GET" class="space-y-4">
                        <input type="hidden" name="action" value="dashboard">
                        <div>
                            <label class="block text-xs font-bold uppercase text-gray-500 mb-1">Sélectionner le médicament à sortir</label>
                            <select name="search_product_id" class="w-full p-2.5 border rounded-lg bg-gray-50 focus:ring-2 focus:ring-emerald-500" required>
                                <?php foreach ($products as $prod): ?>
                                    <option value="<?= $prod['id'] ?>" <?= isset($_GET['search_product_id']) && $_GET['search_product_id'] == $prod['id'] ? 'selected' : '' ?>><?= htmlspecialchars($prod['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" name="check_priority" value="1" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold p-3 rounded-lg transition shadow-sm">Désigner le lot du tiroir (Moteur FEFO)</button>
                    </form>
                </div>

                <?php if (isset($_GET['check_priority'])): ?>
                    <div class="mt-6 p-4 rounded-xl border <?php echo $priority_batch ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-200'; ?>">
                        <?php if ($priority_batch): ?>
                            <p class="text-xs font-bold uppercase text-emerald-800 mb-1">🎯 Lot Prioritaire désigné :</p>
                            <p class="text-sm text-gray-700">Prendre impérativement le lot <span class="font-bold text-emerald-700 text-base"><?= htmlspecialchars($priority_batch['lot_number']) ?></span> (Expire le <?= date('d/m/Y', strtotime($priority_batch['expiration_date'])) ?>).</p>
                            <p class="text-xs text-gray-500 mt-1">Quantité disponible restante : <?= $priority_batch['quantity'] ?> boîtes.</p>
                        <?php else: ?>
                            <p class="text-sm text-amber-800 font-medium">⚠️ Aucun lot valide ou non expiré disponible pour ce produit.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-4 bg-slate-800 text-white font-semibold tracking-wide text-xs uppercase">
                File d'attente globale des lots (Ordre FEFO strict)
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 font-bold text-xs uppercase border-b border-gray-100">
                            <th class="p-4">Code CIP</th>
                            <th class="p-4">Médicament</th>
                            <th class="p-4">N° Lot</th>
                            <th class="p-4">Quantité en Stock</th>
                            <th class="p-4">Date Expiration</th>
                            <th class="p-4 text-center">Niveau de Criticité</th>
                            <th class="p-4 text-center">Actions [Pharmacien/Admin]</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($batches as $batch): 
                            // --- LOGIQUE D'AFFICHAGE (MVC Compliant - Aucun SQL ici) ---
                            $daysLeft = (strtotime($batch['expiration_date']) - time()) / (60 * 60 * 24);
                            
                            if ($daysLeft <= 0 || $batch['status'] === 'EXPIRED') {
                                $color = 'bg-gray-500 text-white';
                                $criticite = 'Expiré';
                                $is_expired = true;
                            } elseif ($daysLeft < 30) {
                                $color = 'bg-red-500 text-white';
                                $criticite = 'Alerte Rouge (< 30j)';
                                $is_expired = false;
                            } elseif ($daysLeft < 90) {
                                $color = 'bg-orange-500 text-white';
                                $criticite = 'Alerte Orange (< 90j)';
                                $is_expired = false;
                            } else {
                                $color = 'bg-green-500 text-white';
                                $criticite = 'Sécurisé (> 6 mois)';
                                $is_expired = false;
                            }
                        ?>
                            <tr class="border-b border-gray-100 hover:bg-gray-50/70 text-sm transition">
                                <td class="p-4 font-mono text-gray-400 text-xs"><?= htmlspecialchars($batch['product_code']) ?></td>
                                <td class="p-4 font-semibold text-gray-800"><?= htmlspecialchars($batch['product_name']) ?></td>
                                <td class="p-4 font-mono text-gray-600 font-medium"><?= htmlspecialchars($batch['lot_number']) ?></td>
                                <td class="p-4 text-gray-700"><?= $batch['quantity'] ?> boîtes</td>
                                <td class="p-4 text-gray-600 font-medium"><?= date('d/m/Y', strtotime($batch['expiration_date'])) ?></td>
                                <td class="p-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold tracking-wide shadow-sm <?= $color ?>">
                                        <?= $criticite ?>
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <?php if (!$is_expired): ?>
                                        <?php if (($_SESSION['user_role'] ?? '') === 'PHARMACIEN' || ($_SESSION['user_role'] ?? '') === 'ADMIN'): ?>
                                            <a href="index.php?action=dashboard&action_batch=expire&id=<?= $batch['id'] ?>" 
                                               class="text-xs bg-red-100 text-red-700 font-extrabold px-3 py-1.5 rounded-lg hover:bg-red-200 transition"
                                               onclick="return confirm('Voulez-vous vraiment déclarer ce lot comme Périmé/À détruire ?')">
                                                ❌ Retirer (Périmé)
                                            </a>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400 font-medium italic">Lecture seule</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400 italic font-medium bg-gray-100 px-2 py-1 rounded">Détruit (Cyclamed)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>