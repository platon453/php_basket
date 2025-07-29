<?php
// Временная конфигурация для использования SQLite

try {
    // Путь к файлу базы данных в корне проекта
    $db_path = __DIR__ . '/../database.sqlite';
    
    // Создаем новый объект PDO для подключения к SQLite
    $pdo = new PDO('sqlite:' . $db_path);
    
    // Устанавливаем режим ошибок, чтобы видеть любые проблемы
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Если подключение не удалось, выводим ошибку
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
?>