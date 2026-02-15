<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $stmt = $pdo->prepare("SELECT id, name, email, phone, ssc_gpa, hsc_gpa, academic_group FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($user);
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $allowed = ['name', 'phone', 'ssc_gpa', 'hsc_gpa', 'academic_group'];
    $updates = [];
    $params = [];
    foreach ($allowed as $field) {
        if (isset($data[$field])) {
            $updates[] = "$field = ?";
            $params[] = $data[$field];
        }
    }
    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['error' => 'No fields to update']);
        exit();
    }
    $params[] = $_SESSION['user_id'];
    $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo json_encode(['success' => true]);
}