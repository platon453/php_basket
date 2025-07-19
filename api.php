<?php
session_start();
header('Content-Type: application/json');

// "База данных" товаров для примера
$productsDB = [
    1 => ['id' => 1, 'title' => 'Дом, милый дом', 'author' => 'Begemot', 'price' => 435, 'discountPrice' => 347, 'image' => 'img/image32.png'],
    2 => ['id' => 2, 'title' => 'Шёпот шахт', 'author' => 'Андрей винтер', 'price' => 447, 'discountPrice' => 447, 'image' => 'img/image34.png']
];

if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        1 => array_merge($productsDB[1], ['quantity' => 1]),
        2 => array_merge($productsDB[2], ['quantity' => 1])
    ];
}

$request = json_decode(file_get_contents('php://input'), true);
$action = $request['action'] ?? 'get';

switch ($action) {
    case 'update_quantity':
        $itemId = $request['itemId'];
        $quantity = $request['quantity'];
        if (isset($_SESSION['cart'][$itemId])) {
            if ($quantity > 0) $_SESSION['cart'][$itemId]['quantity'] = $quantity;
            else unset($_SESSION['cart'][$itemId]);
        }
        break;
    case 'clear':
        $_SESSION['cart'] = [];
        break;
    case 'place_order':
        // Здесь должна быть реальная логика обработки заказа: 
        // сохранение в базу данных, интеграция с платежной системой и т.д.
        // Сейчас мы просто очищаем корзину и возвращаем успех.
        $orderDetails = $request['orderDetails']; // Можно использовать для логирования
        $_SESSION['cart'] = [];
        echo json_encode(['success' => true, 'orderId' => rand(10000, 99999)]);
        exit;

    case 'get':
    default:
        break;
}

echo json_encode(['cart' => array_values($_SESSION['cart'] ?? [])]);
?>