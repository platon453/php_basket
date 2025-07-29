<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../function/BasketGetAllOrders.php';
require_once __DIR__ . '/../admin_list.php'; // Предполагаемый путь к файлу с IP админов

// Проверка на админа
if (!in_array($_SERVER['REMOTE_ADDR'], $admin_ips)) {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// $pdo берется из db.php
$result = BasketGetAllOrders($pdo);

if (!$result['success']) {
    http_response_code(500);
}

echo json_encode($result);
?>