<?php
require 'functions.php';

if (!isset($_GET['id'])) {
    die('Brak ID wpisu.');
}

$postId = (int)$_GET['id'];

// Pobierz wpis
$stmt = $pdo->prepare("
    SELECT posts.*, users.username 
    FROM posts 
    LEFT JOIN users ON posts.author_id = users.id 
    WHERE posts.id = ?
");
$stmt->execute([$postId]);
$post = $stmt->fetch();

if (!$post) {
    die('Wpis nie istnieje.');
}

// Obsługa dodania komentarza
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $content = trim($_POST['comment']);

    if ($content !== '') {
        $authorId = isLoggedIn() ? currentUser()['id'] : null;
        $guestName = isLoggedIn() ? null : 'Gość';

        $stmt = $pdo->prepare("INSERT INTO comments (post_id, author_id, guest_name, content) VALUES (?, ?, ?, ?)");
        $stmt->execute([$postId, $authorId, $guestName, $content]);
        header("Location: post.php?id=$postId");
        exit;
    }
}

// Pobierz komentarze
$stmt = $pdo->prepare("
    SELECT comments.*, users.username 
    FROM comments 
    LEFT JOIN users ON comments.author_id = users.id 
    WHERE post_id = ? 
    ORDER BY created_at ASC
");
$stmt->execute([$postId]);
$comments = $stmt->fetchAll();
?>

<h2><?= htmlspecialchars($post['title']) ?></h2>
<p><small>Autor: <?= htmlspecialchars($post['username'] ?? 'Nieznany') ?> | <?= $post['created_at'] ?></small></p>
<?php if (isLoggedIn() && (currentUser()['id'] === $post['author_id'] || currentUser()['role'] === 'admin')): ?>
    <p>
        <a href="edit_post.php?id=<?= $post['id'] ?>">✏️ Edytuj</a> |
        <a href="delete_post.php?id=<?= $post['id'] ?>" onclick="return confirm('Na pewno usunąć?')">🗑️ Usuń</a>
    </p>
<?php endif; ?>

<?php if ($post['image_path']): ?>
    <img src="<?= $post['image_path'] ?>" alt="Obrazek" style="max-width:400px;"><br>
<?php endif; ?>
<p><?= nl2br(htmlspecialchars($post['content'])) ?></p>

<hr>
<h3>Komentarze</h3>

<?php foreach ($comments as $c): ?>
    <div style="margin-bottom:10px; border-bottom:1px solid #ccc;">
        <strong><?= htmlspecialchars($c['username'] ?? $c['guest_name'] ?? 'Gość') ?></strong>
        <small><?= $c['created_at'] ?></small>
        <p><?= nl2br(htmlspecialchars($c['content'])) ?></p>
    </div>
<?php endforeach; ?>

<h4>Dodaj komentarz</h4>
<form method="post">
    <textarea name="comment" rows="4" required></textarea><br>
    <button type="submit">Dodaj komentarz</button>
</form>
<?php
// Poprzedni wpis
$stmt = $pdo->prepare("SELECT id, title FROM posts WHERE id < ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$postId]);
$prev = $stmt->fetch();

// Następny wpis
$stmt = $pdo->prepare("SELECT id, title FROM posts WHERE id > ? ORDER BY id ASC LIMIT 1");
$stmt->execute([$postId]);
$next = $stmt->fetch();
?>

<p>
    <?php if ($prev): ?>
        ⬅️ <a href="post.php?id=<?= $prev['id'] ?>">Poprzedni: <?= htmlspecialchars($prev['title']) ?></a>
    <?php endif; ?>
    |
    <?php if ($next): ?>
        <a href="post.php?id=<?= $next['id'] ?>">Następny: <?= htmlspecialchars($next['title']) ?></a> ➡️
    <?php endif; ?>
</p>
<a href="index.php">⟵ Wróć do bloga</a>