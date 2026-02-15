<?php
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit();
}

$students = $pdo->query("SELECT COUNT(*) FROM users WHERE user_type='student'")->fetchColumn();
$applications = $pdo->query("SELECT COUNT(*) FROM applications")->fetchColumn();
$pending = $pdo->query("SELECT COUNT(*) FROM applications WHERE status='pending'")->fetchColumn();

echo json_encode([
    'students' => $students,
    'applications' => $applications,
    'pending' => $pending
]);