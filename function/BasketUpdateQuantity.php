<?php
function BasketUpdateQuantity($itemId, $quantity) {
    // 1. Валидация itemId
    $itemId = filter_var($itemId, FILTER_VALIDATE_INT);
    if ($itemId === false || $itemId <= 0) {
        return ['success' => false, 'error' => 'Invalid product ID'];
    }

    // 2. Валидация quantity
    $quantity = filter_var($quantity, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0, 'max_range' => 99] // 0 для удаления
    ]);
    if ($quantity === false) {
        return ['success' => false, 'error' => 'Quantity must be between 0 and 99'];
    }

    // 3. Проверка, существует ли товар в корзине
    if (!isset($_SESSION['cart'][$itemId])) {
        return ['success' => false, 'error' => 'Item not found in cart'];
    }

    // 4. Основная логика
    if ($quantity > 0) {
        $_SESSION['cart'][$itemId]['quantity'] = $quantity;
    } else { // quantity === 0
        unset($_SESSION['cart'][$itemId]);
    }

    return ['success' => true];
}
?>