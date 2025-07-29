<?php
function BasketGetOrderDetails($orderId, $pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM basket_order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return ['success' => true, 'items' => $items];
    } catch (Exception $e) {
        return ['success' => false, 'error' => 'Failed to fetch order items: ' . $e->getMessage()];
    }
}
?>