<?php
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$app_id = $data['application_id'] ?? 0;
$status = $data['status'] ?? '';

if (!$app_id || !in_array($status, ['pending','approved','rejected'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
    exit();
}

$stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
$stmt->execute([$status, $app_id]);
echo json_encode(['success' => true]);