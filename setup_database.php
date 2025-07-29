<?php
require_once __DIR__ . '/config/db.php';

if (!isset($pdo)) {
    die("Ошибка: Не удалось получить объект PDO из config/db.php.\n");
}

try {
    echo "Начинаю создание таблиц с правильной схемой для SQLite...\n";

    $sql_orders = "
    CREATE TABLE IF NOT EXISTS basket_orders (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        customer_type VARCHAR(255),
        delivery_type VARCHAR(255),
        customer_details TEXT,
        legal_details TEXT,
        total_price DECIMAL(10, 2)
    );";

    $sql_order_items = "
    CREATE TABLE IF NOT EXISTS basket_order_items (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        order_id INT,
        product_id INT,
        product_title VARCHAR(255),
        quantity INT,
        price_per_item DECIMAL(10, 2),
        FOREIGN KEY (order_id) REFERENCES basket_orders(id) ON DELETE CASCADE
    );";

    $pdo->exec($sql_orders);
    echo "Таблица 'basket_orders' успешно создана.\n";

    $pdo->exec($sql_order_items);
    echo "Таблица 'basket_order_items' успешно создана.\n";

    echo "\nУСПЕХ! База данных готова к работе.\n";

} catch (PDOException $e) {
    die("ОШИБКА БАЗЫ ДАННЫХ: " . $e->getMessage() . "\n");
}
?>