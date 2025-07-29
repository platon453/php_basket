<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php'; // Предполагаемый путь к файлу конфигурации БД
require_once __DIR__ . '/../function/BasketPlaceOrder.php';

$request = json_decode(file_get_contents('php://input'), true);
$orderDetails = $request['orderDetails'] ?? [];
$cart = $_SESSION['cart'] ?? [];

// $pdo берется из db.php
$result = BasketPlaceOrder($orderDetails, $cart, $pdo);

if ($result['success']) {
    $_SESSION['cart'] = []; // Очищаем корзину только в случае успеха
    echo json_encode($result);
} else {
    http_response_code(500);
    echo json_encode($result);
}
?>