<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../function/BasketClear.php';

BasketClear();

// Возвращаем обновленное состояние корзины
echo json_encode(['cart' => array_values($_SESSION['cart'] ?? [])]);
?>