<?php
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    $date = date('Y-m-d H:i:s');

    if ($name && $email && $message) {
        $entry = "[$date] Od: $name <$email>\n$message\n\n";
        file_put_contents('messages.txt', $entry, FILE_APPEND);
        $success = true;
    } else {
        $error = "Wszystkie pola są wymagane.";
    }
}
?>

<h2>Kontakt z autorem bloga</h2>

<?php if (!empty($success)): ?>
    <p style="color:green;">Dziękujemy za wiadomość!</p>
<?php elseif (!empty($error)): ?>
    <p style="color:red;"><?= $error ?></p>
<?php endif; ?>

<form method="post">
    <input type="text" name="name" placeholder="Imię" required><br>
    <input type="email" name="email" placeholder="E-mail" required><br>
    <textarea name="message" rows="5" placeholder="Wiadomość" required></textarea><br>
    <button type="submit">Wyślij</button>
</form>

<a href="index.php">⟵ Wróć do bloga</a>
