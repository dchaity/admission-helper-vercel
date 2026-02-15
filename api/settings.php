<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$current = $data['current_password'] ?? '';
$new = $data['new_password'] ?? '';

if (!$current || !$new) {
    http_response_code(400);
    echo json_encode(['error' => 'Current and new password required']);
    exit();
}

$stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!password_verify($current, $user['password'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Current password is incorrect']);
    exit();
}

$hashed = password_hash($new, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
$stmt->execute([$hashed, $_SESSION['user_id']]);
echo json_encode(['success' => true]);