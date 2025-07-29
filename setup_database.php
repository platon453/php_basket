<?php
// Этот скрипт нужно запустить только один раз для создания таблиц в базе данных.
// После успешного выполнения его следует удалить.

// Подключаем конфигурацию БД (предполагается, что $pdo будет доступен из этого файла)
require_once __DIR__ . '/config/db.php';

if (!isset($pdo)) {
    echo "Ошибка: Не удалось получить объект PDO из config/db.php. Проверьте этот файл.\n";
    exit(1);
}

try {
    echo "Начинаю создание таблиц...\n";

    // SQL для создания таблицы заказов
    $sql_orders = "
    CREATE TABLE IF NOT EXISTS basket_orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        customer_type VARCHAR(255),
        delivery_type VARCHAR(255),
        customer_details TEXT,
        legal_details TEXT,
        total_price DECIMAL(10, 2)
    );";

    // SQL для создания таблицы товаров в заказе
    $sql_order_items = "
    CREATE TABLE IF NOT EXISTS basket_order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT,
        product_id INT,
        product_title VARCHAR(255),
        quantity INT,
        price_per_item DECIMAL(10, 2),
        FOREIGN KEY (order_id) REFERENCES basket_orders(id) ON DELETE CASCADE
    );";

    // Выполняем запросы
    $pdo->exec($sql_orders);
    echo "Таблица 'basket_orders' успешно создана (или уже существует).\n";

    $pdo->exec($sql_order_items);
    echo "Таблица 'basket_order_items' успешно создана (или уже существует).\n";

    echo "\nУСПЕХ! База данных готова к работе.\n";

} catch (PDOException $e) {
    // В случае ошибки выводим сообщение
    die("ОШИБКА БАЗЫ ДАННЫХ: " . $e->getMessage() . "\n");
}
?>