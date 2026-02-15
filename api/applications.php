<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$stmt = $pdo->prepare("SELECT a.*, u.name AS university_name FROM applications a JOIN universities u ON a.university_id = u.id WHERE a.user_id = ? ORDER BY a.application_date DESC");
$stmt->execute([$_SESSION['user_id']]);
$apps = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($apps);