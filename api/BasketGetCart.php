<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../function/BasketGetCart.php';

echo json_encode(BasketGetCart());
?>