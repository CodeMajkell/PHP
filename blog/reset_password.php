<?php
require 'functions.php';

if (!isLoggedIn()) {
    die('Dostęp tylko dla zalogowanych użytkowników.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([currentUser()['id']]);
    $user = $stmt->fetch();

    if (!password_verify($old, $user['password'])) {
        $error = "Stare hasło jest nieprawidłowe.";
    } elseif ($new !== $confirm) {
        $error = "Nowe hasła się nie zgadzają.";
    } elseif (strlen($new) < 4) {
        $error = "Nowe hasło musi mieć co najmniej 4 znaki.";
    } else {
        $newHash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$newHash, currentUser()['id']]);
        logAction("Użytkownik zmienił hasło.");
        $success = "Hasło zostało zmienione.";
    }
}
?>

<h2>Reset hasła</h2>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= $error ?></p>
<?php elseif (!empty($success)): ?>
    <p style="color:green;"><?= $success ?></p>
<?php endif; ?>

<form method="post">
    <input type="password" name="old_password" placeholder="Stare hasło" required><br>
    <input type="password" name="new_password" placeholder="Nowe hasło" required><br>
    <input type="password" name="confirm_password" placeholder="Powtórz nowe hasło" required><br>
    <button type="submit">Zmień hasło</button>
</form>

<p><a href="index.php">⟵ Wróć</a></p>
