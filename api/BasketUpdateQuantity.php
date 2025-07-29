<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../function/BasketUpdateQuantity.php';

$request = json_decode(file_get_contents('php://input'), true);
$itemId = $request['itemId'] ?? null;
$quantity = $request['quantity'] ?? null;

if ($itemId !== null && $quantity !== null) {
    BasketUpdateQuantity($itemId, $quantity);
}

// Возвращаем обновленное состояние корзины
echo json_encode(['cart' => array_values($_SESSION['cart'] ?? [])]);
?>