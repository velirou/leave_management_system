<?php
require_once __DIR__ . '/src/leave.php';
require_once __DIR__ . '/src/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    request_leave($_SESSION['user_id'], $start_date, $end_date);
    echo "Leave requested.";
}
?>
<form method="POST">
    Start Date: <input type="date" name="start_date">
    End Date: <input type="date" name="end_date">
    <button type="submit">Request Leave</button>
</form>
