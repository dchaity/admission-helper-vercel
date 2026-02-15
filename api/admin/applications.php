<?php
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit();
}

$status = $_GET['status'] ?? 'all';
$sql = "SELECT a.*, u.name AS user_name, u.email, un.name AS university_name FROM applications a JOIN users u ON a.user_id = u.id JOIN universities un ON a.university_id = un.id";
if ($status !== 'all') {
    $sql .= " WHERE a.status = :status";
}
$sql .= " ORDER BY a.application_date DESC";
$stmt = $pdo->prepare($sql);
if ($status !== 'all') {
    $stmt->execute(['status' => $status]);
} else {
    $stmt->execute();
}
$apps = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($apps);