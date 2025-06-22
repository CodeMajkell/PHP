<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $password]);

    header('Location: login.php');
    exit;
}
?>

<form method="post">
    <h2>Rejestracja</h2>
    <input type="text" name="username" placeholder="Login" required><br>
    <input type="email" name="email" placeholder="Email"><br>
    <input type="password" name="password" placeholder="Hasło" required><br>
    <button type="submit">Zarejestruj się</button>
</form>
