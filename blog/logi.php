<?php
require 'db.php';
require 'functions.php';

if (!isLoggedIn() || currentUser()['role'] !== 'admin') {
    die('Brak dostępu.');
}

// Pobierz logi razem z nazwami użytkowników (jeśli istnieją)
$stmt = $pdo->query("
    SELECT logs.id, logs.action, logs.timestamp, users.username 
    FROM logs 
    LEFT JOIN users ON logs.user_id = users.id 
    ORDER BY logs.timestamp DESC
");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Logi systemowe</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Logi systemowe</h2>
    <a href="admin.php">⟵ Panel administratora</a><br><br>

    <?php if (count($logs) === 0): ?>
        <p>Brak logów w systemie.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Użytkownik</th>
                <th>Akcja</th>
                <th>Data i czas</th>
            </tr>
            <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?= $log['id'] ?></td>
                    <td><?= $log['username'] ?? 'Gość/system' ?></td>
                    <td><?= htmlspecialchars($log['action']) ?></td>
                    <td><?= $log['timestamp'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>