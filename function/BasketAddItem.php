<?php
function BasketAddItem($productData) {
    // 1. Валидация данных товара
    $productId = filter_var($productData['id'] ?? null, FILTER_VALIDATE_INT);
    $title = htmlspecialchars($productData['title'] ?? '', ENT_QUOTES, 'UTF-8');
    $price = filter_var($productData['price'] ?? null, FILTER_VALIDATE_FLOAT);
    $quantity = filter_var($productData['quantity'] ?? null, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1, 'max_range' => 99]
    ]);

    if (!$productId || empty($title) || $price === false || !$quantity) {
        return ['success' => false, 'error' => 'Invalid product data provided.'];
    }

    // 2. Логика добавления в корзину
    if (isset($_SESSION['cart'][$productId])) {
        // Если товар уже есть, увеличиваем количество
        $newQuantity = $_SESSION['cart'][$productId]['quantity'] + $quantity;
        if ($newQuantity > 99) {
            $newQuantity = 99; // Не превышаем максимум
        }
        $_SESSION['cart'][$productId]['quantity'] = $newQuantity;
    } else {
        // Если товара нет, добавляем его
        $_SESSION['cart'][$productId] = [
            'id' => $productId,
            'title' => $title,
            'price' => $price,
            'quantity' => $quantity
        ];
    }

    return ['success' => true];
}
?>