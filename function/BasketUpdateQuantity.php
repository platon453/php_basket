<?php
function BasketUpdateQuantity($itemId, $quantity) {
    if (isset($_SESSION['cart'][$itemId])) {
        if ($quantity > 0) {
            $_SESSION['cart'][$itemId]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$itemId]);
        }
    }
}
?>