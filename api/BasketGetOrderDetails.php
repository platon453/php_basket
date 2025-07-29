<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../function/BasketGetOrderDetails.php';
require_once __DIR__ . '/../admin_list.php';

if (!in_array($_SERVER['REMOTE_ADDR'], $admin_ips)) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

$request = json_decode(file_get_contents('php://input'), true);
$orderId = $request['orderId'] ?? null;

if (!$orderId) {
    http_response_code(400);
    echo json_encode(['error' => 'Order ID is required']);
    exit;
}

$result = BasketGetOrderDetails($orderId, $pdo);

if (!$result['success']) {
    http_response_code(500);
}

echo json_encode($result);
?>