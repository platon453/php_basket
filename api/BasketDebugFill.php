<?php
session_start();
header('Content-Type: application/json');

// "База данных" товаров для примера
$productsDB = [
    1 => ['id' => 1, 'title' => 'Дом, милый дом', 'author' => 'Begemot', 'price' => 435, 'discountPrice' => 347, 'image' => '/assets/img/image32.png'],
    2 => ['id' => 2, 'title' => 'Шёпот шахт', 'author' => 'Андрей винтер', 'price' => 447, 'discountPrice' => 447, 'image' => '/assets/img/image34.png']
];

$_SESSION['cart'] = [
    1 => array_merge($productsDB[1], ['quantity' => 1]),
    2 => array_merge($productsDB[2], ['quantity' => 2])
];

echo json_encode(['success' => true, 'message' => 'Cart filled for debugging.', 'cart' => $_SESSION['cart']]);
?>