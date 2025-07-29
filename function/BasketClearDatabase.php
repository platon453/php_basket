<?php
function BasketClearDatabase($pdo) {
    try {
        $pdo->exec("DELETE FROM basket_order_items");
        $pdo->exec("DELETE FROM basket_orders");

        // Проверяем, существует ли таблица sqlite_sequence, и только потом очищаем ее
        $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='sqlite_sequence'");
        if ($stmt->fetch()) {
            $pdo->exec("DELETE FROM sqlite_sequence WHERE name='basket_orders'");
            $pdo->exec("DELETE FROM sqlite_sequence WHERE name='basket_order_items'");
        }

        return ['success' => true, 'message' => 'Database cleared and reset.'];
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Failed to clear database: ' . $e->getMessage()];
    }
}
?>