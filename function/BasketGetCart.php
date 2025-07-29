<?php
function BasketGetCart() {
    return ['cart' => array_values($_SESSION['cart'] ?? [])];
}
?>