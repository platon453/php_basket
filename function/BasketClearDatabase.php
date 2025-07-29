<?php
function BasketClearDatabase($pdo) {
    try {
        $pdo->exec("DELETE FROM basket_order_items");
        $pdo->exec("DELETE FROM basket_orders");
        // Очистка автоинкремента для SQLite, для MySQL/PostgreSQL синтаксис будет другой
        // $pdo->exec("DELETE FROM sqlite_sequence WHERE name='basket_orders' OR name='basket_order_items'");
        return ['success' => true, 'message' => 'Database cleared.'];
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Failed to clear database: ' . $e->getMessage()];
    }
}
?>