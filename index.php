<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

$request_uri = strtok($_SERVER['REQUEST_URI'], '?');
$request_path = trim($request_uri, '/');

require __DIR__ . '/routes/list.php';

$route_id = array_search($request_path, $routes);

if ($route_id !== false) {
    require __DIR__ . '/routes/titles.php';
    require __DIR__ . '/routes/actions.php';
} else {
    if (empty($request_path)) {
        header('Location: /cart');
        exit;
    }
    http_response_code(404);
    echo "<h1>404 - Страница не найдена</h1>";
}
?>