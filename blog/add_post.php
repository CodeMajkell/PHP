<?php
require 'functions.php';

if (!isLoggedIn() || !in_array(currentUser()['role'], ['admin', 'author'])) {
    die('Brak dostępu.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = $_POST['content'];
    $imagePath = null;

    // Obsługa uploadu obrazka
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = 'uploads/';
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $imagePath = $targetPath;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO posts (title, content, image_path, author_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $content, $imagePath, currentUser()['id']]);

    logAction("Dodano wpis: $title");
    header('Location: index.php');
    exit;
}
?>

<h2>Dodaj nowy wpis</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Tytuł" required><br>
    <textarea name="content" placeholder="Treść" rows="6" required></textarea><br>
    <input type="file" name="image"><br>
    <button type="submit">Dodaj wpis</button>
</form>
<a href="index.php">⟵ Wróć</a>
