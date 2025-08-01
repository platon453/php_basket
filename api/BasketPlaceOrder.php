<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
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
    // Если ошибка связана с неверными данными от клиента - это 400 Bad Request
    // Если это ошибка сервера (не удалось записать в БД) - это 500 Internal Server Error
    $errorCode = strpos($result['error'], 'server error') !== false ? 500 : 400;
    http_response_code($errorCode);
    echo json_encode($result);
}
?>