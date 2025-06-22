<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        header('Location: index.php');
        exit;
    } else {
        echo "Nieprawidłowe dane logowania.";
    }
}
?>

<form method="post">
    <h2>Logowanie</h2>
    <input type="text" name="username" placeholder="Login" required><br>
    <input type="password" name="password" placeholder="Hasło" required><br>
    <button type="submit">Zaloguj się</button>
</form>
