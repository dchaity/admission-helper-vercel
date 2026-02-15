<?php
require_once 'config.php';

$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$ssl_cert_path = getenv('DB_SSL_CERT') ?: __DIR__ . '/certs/ca.pem';

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
$dsn .= ";sslmode=verify-ca;sslrootcert=" . $ssl_cert_path;

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}
?>