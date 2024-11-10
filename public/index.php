<?php
require_once __DIR__ . '/src/auth.php';
if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

// Fetch the user's name from the database if desired
// Display as "Welcome, [Name]"
?>

<h2>Welcome to the Leave Management System</h2>
<a href="leave_request.php">Request Leave</a>
<a href="employee_management.php">Manage Employees</a>
<a href="logout.php">Logout</a>
