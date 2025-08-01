<?php
function BasketPlaceOrder($orderDetails, $cart, $pdo) {
    // 1. Проверка корзины
    if (empty($cart)) {
        return ['success' => false, 'error' => 'Cart is empty'];
    }

    // 2. Валидация основных данных заказа
    if (empty($orderDetails)) {
        return ['success' => false, 'error' => 'Order details are missing'];
    }

    $customerType = $orderDetails['personType'] ?? '';
    if (!in_array($customerType, ['natural', 'legal'])) {
        return ['success' => false, 'error' => 'Invalid customer type'];
    }

    $deliveryType = $orderDetails['deliveryType'] ?? '';
    if (!in_array($deliveryType, ['pickup', 'delivery'])) {
        return ['success' => false, 'error' => 'Invalid delivery type'];
    }

    // 3. Валидация деталей в зависимости от типа
    $customerDetails = $orderDetails['deliveryDetails'] ?? [];
    $legalDetails = $orderDetails['legalDetails'] ?? [];

    // Проверяем обязательные поля для физ. лица
    if (empty($customerDetails['name']) || empty($customerDetails['phone'])) {
        return ['success' => false, 'error' => 'Name and phone are required'];
    }
    // Если доставка, а не самовывоз, адрес обязателен
    if ($deliveryType === 'delivery' && empty($customerDetails['address'])) {
        return ['success' => false, 'error' => 'Address is required for delivery'];
    }

    // Проверяем обязательные поля для юр. лица
    if ($customerType === 'legal' && (empty($legalDetails['companyName']) || empty($legalDetails['inn']))) {
        return ['success' => false, 'error' => 'Company name and INN are required for legal entities'];
    }
    
    // 4. Очистка всех строковых данных для безопасности
    array_walk_recursive($customerDetails, function (&$value) {
        if (is_string($value)) {
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
    });
    array_walk_recursive($legalDetails, function (&$value) {
        if (is_string($value)) {
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
    });

    // 5. Расчет итоговой стоимости (как и было)
    $totalPrice = array_reduce($cart, function ($sum, $item) {
        return $sum + ($item['price'] * $item['quantity']);
    }, 0);

    // 6. Сохранение в БД (транзакция)
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            "INSERT INTO basket_orders (customer_type, delivery_type, customer_details, legal_details, total_price) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $customerType,
            $deliveryType,
            json_encode($customerDetails),
            json_encode($legalDetails),
            $totalPrice
        ]);

        $orderId = $pdo->lastInsertId();

        $stmt = $pdo->prepare(
            "INSERT INTO basket_order_items (order_id, product_id, product_title, quantity, price_per_item) VALUES (?, ?, ?, ?, ?)"
        );
        foreach ($cart as $item) {
            $stmt->execute([
                $orderId,
                $item['id'],
                $item['title'],
                $item['quantity'],
                $item['price']
            ]);
        }

        $pdo->commit();

        return ['success' => true, 'orderId' => $orderId];

    } catch (Exception $e) {
        $pdo->rollBack();
        // Для отладки можно логировать $e->getMessage(), но пользователю вернем общее сообщение
        return ['success' => false, 'error' => 'Failed to save order due to a server error.'];
    }
}
?>