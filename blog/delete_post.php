<?php
require 'functions.php';

if (!isLoggedIn() || !isset($_GET['id'])) {
    die('Brak dostępu.');
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
    die('Brak uprawnień do usunięcia wpisu.');
}

// Usuń wpis
$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$postId]);

logAction("Usunięto wpis: {$post['title']}");

header("Location: index.php");
exit;
