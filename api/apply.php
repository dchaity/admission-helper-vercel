<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$university_id = $data['university_id'] ?? 0;
$program = $data['program'] ?? '';

if (!$university_id) {
    http_response_code(400);
    echo json_encode(['error' => 'University ID required']);
    exit();
}

// Check if already applied
$stmt = $pdo->prepare("SELECT id FROM applications WHERE user_id = ? AND university_id = ?");
$stmt->execute([$_SESSION['user_id'], $university_id]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['error' => 'Already applied to this university']);
    exit();
}

$stmt = $pdo->prepare("INSERT INTO applications (user_id, university_id, program_name) VALUES (?, ?, ?)");
$success = $stmt->execute([$_SESSION['user_id'], $university_id, $program]);

if ($success) {
    echo json_encode(['success' => true, 'application_id' => $pdo->lastInsertId()]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Application failed']);
}