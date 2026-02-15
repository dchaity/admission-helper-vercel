<?php
require_once 'db.php';

$type = $_GET['type'] ?? 'all';

$sql = "SELECT * FROM universities";
if ($type !== 'all') {
    $sql .= " WHERE type = :type";
}
$stmt = $pdo->prepare($sql);
if ($type !== 'all') {
    $stmt->execute(['type' => $type]);
} else {
    $stmt->execute();
}
$universities = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($universities);