<?php
require_once __DIR__ . '/src/auth.php';
require_once __DIR__ . '/src/leave.php';

// Ensure the user is logged in and has manager role
if (!is_logged_in() || !is_manager()) {
    header("Location: index.php");
    exit();
}

// Fetch all pending leave requests for managers
$leaves = get_leave_requests(); // Fetch pending leave requests for approval

// Handle approval or rejection of leave requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $leave_id = intval($_POST['leave_id']);
    $action = $_POST['action'];

    if ($action === 'approve') {
        approve_leave($leave_id);
    } elseif ($action === 'reject') {
        reject_leave($leave_id);
    }
    // Refresh the page to see updated status
    header("Location: manager_leave_requests.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Leave Requests</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Leave Requests</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Employee</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php foreach ($leaves as $leave): ?>
    <tr>
        <td><?= htmlspecialchars($leave['id']) ?></td>
        <td><?= htmlspecialchars($leave['name']) ?></td>
        <td><?= htmlspecialchars($leave['start_date']) ?></td>
        <td><?= htmlspecialchars($leave['end_date']) ?></td>
        <td><?= htmlspecialchars($leave['status']) ?></td>
        <td>
            <?php if ($leave['status'] === 'pending'): ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="leave_id" value="<?= $leave['id'] ?>">
                    <input type="hidden" name="action" value="approve">
                    <button type="submit">Approve</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="leave_id" value="<?= $leave['id'] ?>">
                    <input type="hidden" name="action" value="reject">
                    <button type="submit">Reject</button>
                </form>
            <?php else: ?>
                <!-- If leave is already approved or rejected, show only status -->
                <span>Action completed</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<a href="index.php">Back to Dashboard</a>

</body>
</html>
