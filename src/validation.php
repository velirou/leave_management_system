<?php
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function validate_date($date) {
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && strtotime($date);
}

function validate_username($username) {
    return preg_match('/^[a-zA-Z0-9_]{5,20}$/', $username);
}

function validate_name($name) {
    return preg_match('/^[a-zA-Z\s]{3,100}$/', $name);
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validate_employee_code($employee_code) {
    return preg_match('/^\d{7}$/', $employee_code);
}

function validate_password($password) {
    return strlen($password) >= 8;
}
?>
