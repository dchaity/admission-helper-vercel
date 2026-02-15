<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$stmt = $pdo->prepare("SELECT b.university_id, u.name AS university_name FROM bookmarks b JOIN universities u ON b.university_id = u.id WHERE b.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($bookmarks);