<?php
require_once 'config.php';
require_once 'auth.php';

function request_leave($user_id, $start_date, $end_date) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO leaves (user_id, start_date, end_date, status) VALUES (?, ?, ?, 'pending')");
    return $stmt->execute([$user_id, $start_date, $end_date]);
}

// Fetch all leave requests for pending, approved, or rejected
function get_leave_requests() {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT l.id, l.user_id, l.start_date, l.end_date, l.status, u.name
        FROM leaves l
        JOIN users u ON l.user_id = u.id
        WHERE l.status = 'pending'
        ORDER BY l.start_date DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function approve_leave($leave_id) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE leaves SET status = 'approved' WHERE id = ?");
    return $stmt->execute([$leave_id]);
}

function reject_leave($leave_id) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE leaves SET status = 'rejected' WHERE id = ?");
    return $stmt->execute([$leave_id]);
}
?>
