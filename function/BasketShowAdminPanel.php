<?php
function BasketShowAdminPanel() {
    require_once __DIR__ . '/../admin_list.php';

    if (!in_array($_SERVER['REMOTE_ADDR'], $admin_ips)) {
        echo 'Доступ запрещен';
        return;
    }

    // Отображение шаблона админ-панели
    include_once __DIR__ . '/../templates/basket/admin.php';
}
?>