<?php
switch ($route_id) {
    case 148:
        require_once(__DIR__ . '/../function/BasketShowCart.php');
        BasketShowCart();
        break;
    case 149:
        require_once(__DIR__ . '/../function/BasketShowCheckout.php');
        BasketShowCheckout();
        break;
    case 150:
        require_once(__DIR__ . '/../function/BasketShowAdminPanel.php');
        BasketShowAdminPanel();
        break;
}
?>