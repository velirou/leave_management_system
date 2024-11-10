<?php
require_once __DIR__ . '/src/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = sanitize_input($_POST['password']);
    if (login($email, $password)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Invalid login";
    }
}
?>
<form method="POST">
    Email: <input type="email" name="email" required>
    Password: <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>
