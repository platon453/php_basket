<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../function/BasketGetAllOrders.php';
require_once __DIR__ . '/../admin_list.php';

if (!in_array($_SERVER['REMOTE_ADDR'], $admin_ips)) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$result = BasketGetAllOrders($pdo);

if (!$result['success']) {
    http_response_code(500);
}

echo json_encode($result);
?>