<?php
require 'functions.php';

if (!isLoggedIn() || currentUser()['role'] !== 'admin') {
    die('Dostęp zabroniony.');
}
?>

<h2>Panel administracyjny</h2>

<p><a href="index.php">⟵ Powrót do bloga</a></p>

<ul>
    <li><a href="users.php">👥 Lista użytkowników</a></li>
    <p><a href="logi.php">📜 Podgląd logów</a></p>
</ul>
