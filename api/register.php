<?php
require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$required = ['name', 'email', 'password', 'sscGPA', 'hscGPA', 'group'];
foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "$field is required"]);
        exit();
    }
}

// Check email exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$data['email']]);
if ($stmt->fetch()) {
    http_response_code(409);
    echo json_encode(['error' => 'Email already registered']);
    exit();
}

$hashed = password_hash($data['password'], PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password, ssc_gpa, hsc_gpa, academic_group) VALUES (?, ?, ?, ?, ?, ?, ?)");
$success = $stmt->execute([
    $data['name'],
    $data['email'],
    $data['phone'] ?? null,
    $hashed,
    $data['sscGPA'],
    $data['hscGPA'],
    $data['group']
]);

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Registration successful']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Registration failed']);
}