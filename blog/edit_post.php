<?php
require 'functions.php';

if (!isLoggedIn()) {
    die('Brak dostępu.');
}

if (!isset($_GET['id'])) {
    die('Brak ID wpisu.');
}

$postId = (int)$_GET['id'];

// Pobierz wpis
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch();

if (!$post) {
    die('Wpis nie istnieje.');
}

// Sprawdź uprawnienia
if (currentUser()['role'] !== 'admin' && currentUser()['id'] !== $post['author_id']) {
    die('Brak uprawnień do edycji tego wpisu.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = $_POST['content'];

    // Aktualizacja obrazka
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'uploads/';
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $post['image_path'] = $targetPath;
        }
    }

    $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, image_path = ? WHERE id = ?");
    $stmt->execute([$title, $content, $post['image_path'], $postId]);

    logAction("Edytowano wpis: $title");

    header("Location: post.php?id=$postId");
    exit;
}
?>

<h2>Edytuj wpis</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required><br>
    <textarea name="content" rows="6" required><?= htmlspecialchars($post['content']) ?></textarea><br>
    <input type="file" name="image"><br>
    <?php if ($post['image_path']): ?>
        <p>Obecny obrazek:</p>
        <img src="<?= $post['image_path'] ?>" style="max-width:300px;"><br>
    <?php endif; ?>
    <button type="submit">Zapisz zmiany</button>
</form>
<a href="post.php?id=<?= $postId ?>">⟵ Anuluj</a>
