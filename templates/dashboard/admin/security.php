<?php include __DIR__ . '/../../layout/base.php'; ?>

<h1 class="text-2xl font-bold mb-6">Gestion de la Securite</h1>

<h2 class="text-lg font-semibold mb-3">Utilisateurs du systeme</h2>

<?php if (!empty($users)): ?>
<div class="overflow-x-auto">
<table class="min-w-full border border-gray-300 rounded-lg shadow-sm">
    <thead class="bg-gray-200">
        <tr>
            <th class="px-4 py-2 border">ID</th>
            <th class="px-4 py-2 border">Email</th>
            <th class="px-4 py-2 border">Role</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr class="hover:bg-gray-100">
                <td class="px-4 py-2 border"><?= $u->getId(); ?></td>
                <td class="px-4 py-2 border"><?= htmlspecialchars($u->getEmail()); ?></td>
                <td class="px-4 py-2 border">
                    <span class="bg-blue-500 text-white px-2 py-1 rounded text-sm"><?= htmlspecialchars($u->getRole()); ?></span>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>
<?php else: ?>
<p class="text-gray-500">Aucun utilisateur trouve.</p>
<?php endif; ?>

    </main>
</body>
</html>
