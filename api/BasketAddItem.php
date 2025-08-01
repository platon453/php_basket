<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../function/BasketAddItem.php';

// Получаем данные из запроса
$request = json_decode(file_get_contents('php://input'), true);

if (empty($request)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'No product data provided.']);
    exit;
}

// Вызываем функцию бизнес-логики
$result = BasketAddItem($request);

// Обрабатываем результат
if (!$result['success']) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => $result['error']]);
    exit;
}

// В случае успеха возвращаем обновленное состояние корзины
$updatedCart = array_values($_SESSION['cart'] ?? []);
$totalItems = 0;
$totalPrice = 0;

foreach ($updatedCart as $item) {
    $totalItems += $item['quantity'];
    $totalPrice += $item['price'] * $item['quantity'];
}

echo json_encode([
    'cart' => $updatedCart,
    'totalItems' => $totalItems,
    'totalPrice' => $totalPrice
]);
?>