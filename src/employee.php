<?php
require_once 'config.php';
require_once 'auth.php';

function add_employee($name, $email, $employee_code, $password) {
    global $pdo;
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (name, email, employee_code, password, role) VALUES (?, ?, ?, ?, 'employee')");
    return $stmt->execute([$name, $email, $employee_code, $hashed_password]);
}

function get_employees() {
    global $pdo;
    $stmt = $pdo->query("SELECT id, name, email, employee_code FROM users WHERE role = 'employee'");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function delete_employee($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$user_id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validate_csrf($_POST['csrf_token'])) {
    // Handle form actions...
}
?>
