<?php
require 'functions.php';

if (!isLoggedIn() || currentUser()['role'] !== 'admin') {
    die('Dostęp zabroniony.');
}

$users = $pdo->query("SELECT id, username, role FROM users ORDER BY id")->fetchAll();
?>

<h2>Lista użytkowników</h2>
<a href="admin.php">⟵ Panel admina</a>

<table border="1" cellpadding="5">
    <tr>
        <th>ID</th><th>Login</th><th>Rola</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= $user['role'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>
