<?php
require_once __DIR__ . '/src/auth.php';
require_once __DIR__ . '/src/employee.php';
require_once __DIR__ . '/src/validation.php';

if (!is_logged_in() || !is_manager()) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $csrf_token = $_POST['csrf_token'];

    if (!validate_csrf($csrf_token)) {
        die("Invalid CSRF token");
    }

    if ($action === 'add') {
        $name = sanitize_input($_POST['name']);
        $email = sanitize_input($_POST['email']);
        $employee_code = sanitize_input($_POST['employee_code']);
        $password = sanitize_input($_POST['password']);

        if (validate_name($name) && validate_email($email) && validate_employee_code($employee_code) && validate_password($password)) {
            add_employee($name, $email, $employee_code, $password);
        } else {
            echo "Invalid data provided.";
        }
    } elseif ($action === 'delete') {
        $user_id = intval($_POST['user_id']);
        delete_employee($user_id);
    }
}

$employees = get_employees();
?>

<h2>Employee Management</h2>

<!-- Link to Manage Leave Requests -->
<a href="manager_leave_requests.php">Manage Leave Requests</a>

<!-- Form to Add a New Employee -->
<form method="POST">
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
    Name: <input type="text" name="name" required>
    Email: <input type="email" name="email" required>
    Employee Code: <input type="text" name="employee_code" required pattern="\d{7}" title="7-digit code">
    Password: <input type="password" name="password" required>
    <button type="submit">Add Employee</button>
</form>

<!-- List of Employees with Delete Option -->
<h3>Employees</h3>
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Employee Code</th>
        <th>Action</th>
    </tr>
    <?php foreach ($employees as $employee): ?>
    <tr>
        <td><?= htmlspecialchars($employee['id']) ?></td>
        <td><?= htmlspecialchars($employee['name']) ?></td>
        <td><?= htmlspecialchars($employee['email']) ?></td>
        <td><?= htmlspecialchars($employee['employee_code']) ?></td>
        <td>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="user_id" value="<?= $employee['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
