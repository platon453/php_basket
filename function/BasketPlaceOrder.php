<?php
function BasketPlaceOrder($orderDetails, $cart, $pdo) {
    if (empty($cart)) {
        return ['success' => false, 'error' => 'Cart is empty'];
    }

    $totalPrice = array_reduce($cart, function ($sum, $item) {
        return $sum + ($item['discountPrice'] * $item['quantity']);
    }, 0);

    try {
        $pdo->beginTransaction();

        // 1. Вставить в таблицу 'basket_orders'
        $stmt = $pdo->prepare(
            "INSERT INTO basket_orders (customer_type, delivery_type, customer_details, legal_details, total_price) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $orderDetails['personType'],
            $orderDetails['deliveryType'],
            json_encode($orderDetails['deliveryDetails']),
            json_encode($orderDetails['legalDetails']),
            $totalPrice
        ]);

        $orderId = $pdo->lastInsertId();

        // 2. Вставить товары в 'basket_order_items'
        $stmt = $pdo->prepare(
            "INSERT INTO basket_order_items (order_id, product_id, product_title, quantity, price_per_item) VALUES (?, ?, ?, ?, ?)"
        );
        foreach ($cart as $item) {
            $stmt->execute([
                $orderId,
                $item['id'],
                $item['title'],
                $item['quantity'],
                $item['discountPrice']
            ]);
        }

        $pdo->commit();

        return ['success' => true, 'orderId' => $orderId];

    } catch (Exception $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => 'Failed to save order: ' . $e->getMessage()];
    }
}
?>