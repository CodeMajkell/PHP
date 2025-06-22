<?php
require 'functions.php';

// Pobierz wszystkie wpisy z bazy danych, posortowane malejąco po dacie
$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: auto;
        }
        .post {
            margin-bottom: 40px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 20px;
        }
        img {
            max-width: 100%;
            margin-top: 10px;
        }
        .top-nav {
            margin-bottom: 20px;
            padding: 10px;
            background: #f4f4f4;
        }
        .top-nav a {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <h1>📝 Mój Blog</h1>

    <div class="top-nav">
        <?php if (isLoggedIn()): ?>
            <span>Witaj, <strong><?= htmlspecialchars(currentUser()['username']) ?></strong>!</span> |
            <a href="logout.php">🚪 Wyloguj</a>
            <a href="reset_password.php">🔐 Zmień hasło</a>
            
            <?php if (currentUser()['role'] === 'admin'): ?>
                <a href="admin.php">⚙️ Panel administracyjny</a>
            <?php endif; ?>

            <?php if (currentUser()['role'] !== 'user'): ?>
                <a href="add_post.php">➕ Dodaj wpis</a>
            <?php endif; ?>
        <?php else: ?>
            <a href="login.php">🔑 Zaloguj</a>
            <a href="register.php">🧾 Zarejestruj się</a>
        <?php endif; ?>

        <a href="contact.php">📬 Kontakt</a>
    </div>

    <?php foreach ($posts as $post): ?>
        <div class="post">
            <h2><a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a></h2>
            <small>Dodano: <?= $post['created_at'] ?></small>
            <p><?= nl2br(htmlspecialchars(mb_substr($post['content'], 0, 300))) ?>...</p>

            <?php if (!empty($post['image']) && file_exists("uploads/" . $post['image'])): ?>
                <img src="uploads/<?= $post['image'] ?>" alt="Obrazek do wpisu">
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>
