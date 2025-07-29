<?php
function BasketGetAllOrders($pdo) {
    try {
        $stmt = $pdo->query("SELECT id, created_at, customer_type, delivery_type, total_price, customer_details, legal_details FROM basket_orders ORDER BY created_at DESC");
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Конвертируем время в московское
        $moscow_tz = new DateTimeZone('Europe/Moscow');
        foreach ($orders as &$order) {
            $utc_time = new DateTime($order['created_at'], new DateTimeZone('UTC'));
            $utc_time->setTimezone($moscow_tz);
            $order['created_at'] = $utc_time->format('Y-m-d H:i:s');
        }

        return ['success' => true, 'orders' => $orders];
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Failed to fetch orders: ' . $e->getMessage()];
    }
}
?>