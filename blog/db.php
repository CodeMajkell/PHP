<?php
$host = 'localhost';
$dbname = 'blog';
$user = 'root'; // Zmień jeśli masz inne dane
$pass = '';     // np. '1234'

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}
?>