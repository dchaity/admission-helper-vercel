<?php
require_once 'db.php';

$stmt = $pdo->query("SELECT s.*, u.name AS university_name FROM scholarships s JOIN universities u ON s.university_id = u.id");
$scholarships = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($scholarships);