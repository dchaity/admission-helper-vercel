<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$university_id = $data['university_id'] ?? 0;

if (!$university_id) {
    http_response_code(400);
    echo json_encode(['error' => 'University ID required']);
    exit();
}

// Check if already bookmarked
$stmt = $pdo->prepare("SELECT id FROM bookmarks WHERE user_id = ? AND university_id = ?");
$stmt->execute([$_SESSION['user_id'], $university_id]);
$exists = $stmt->fetch();

if ($exists) {
    // Remove
    $stmt = $pdo->prepare("DELETE FROM bookmarks WHERE user_id = ? AND university_id = ?");
    $stmt->execute([$_SESSION['user_id'], $university_id]);
    echo json_encode(['bookmarked' => false]);
} else {
    // Add
    $stmt = $pdo->prepare("INSERT INTO bookmarks (user_id, university_id) VALUES (?, ?)");
    $stmt->execute([$_SESSION['user_id'], $university_id]);
    echo json_encode(['bookmarked' => true]);
}