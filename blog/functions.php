<?php
session_start();
require_once 'db.php';

// Sprawdza, czy użytkownik jest zalogowany
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Zwraca aktualnie zalogowanego użytkownika
function currentUser() {
    return $_SESSION['user'] ?? null;
}

// Sprawdza, czy użytkownik ma daną rolę
function hasRole($role) {
    return isLoggedIn() && $_SESSION['user']['role'] === $role;
}

// Zapisuje log
function logAction($action) {
    global $pdo;
    $userId = $_SESSION['user']['id'] ?? null;
    $stmt = $pdo->prepare("INSERT INTO logs (action, user_id) VALUES (?, ?)");
    $stmt->execute([$action, $userId]);
}
?>
