<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$userId = $_SESSION['user_id'];

// Get user's GPA
$stmt = $pdo->prepare("SELECT ssc_gpa, hsc_gpa FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Eligible programs count (basic)
$eligible = 0;
if ($user) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM universities WHERE min_ssc_gpa <= ? AND min_hsc_gpa <= ?");
    $stmt->execute([$user['ssc_gpa'], $user['hsc_gpa']]);
    $eligible = $stmt->fetchColumn();
}

// Bookmarks count
$stmt = $pdo->prepare("SELECT COUNT(*) FROM bookmarks WHERE user_id = ?");
$stmt->execute([$userId]);
$bookmarks = $stmt->fetchColumn();

// Applications count
$stmt = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE user_id = ?");
$stmt->execute([$userId]);
$applications = $stmt->fetchColumn();

echo json_encode([
    'eligible' => $eligible,
    'bookmarks' => $bookmarks,
    'applications' => $applications
]);