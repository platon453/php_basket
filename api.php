<?php
session_start();
header('Content-Type: application/json');

// --- PRE-FLIGHT CHECKS ---
if (!in_array('sqlite', PDO::getAvailableDrivers())) {
    http_response_code(500);
    echo json_encode(['error' => 'PDO SQLite driver is not installed or enabled in this PHP environment.']);
    exit;
}

// --- DATABASE CONNECTION ---
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/php_basket.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// "База данных" товаров для примера
$productsDB = [
    1 => ['id' => 1, 'title' => 'Дом, милый дом', 'author' => 'Begemot', 'price' => 435, 'discountPrice' => 347, 'image' => 'img/image32.png'],
    2 => ['id' => 2, 'title' => 'Шёпот шахт', 'author' => 'Андрей винтер', 'price' => 447, 'discountPrice' => 447, 'image' => 'img/image34.png']
];

$request = json_decode(file_get_contents('php://input'), true);
$action = $request['action'] ?? 'get';

switch ($action) {
    case 'debug_fill_cart': // Новое действие для наполнения корзины
        $_SESSION['cart'] = [
            1 => array_merge($productsDB[1], ['quantity' => 1]),
            2 => array_merge($productsDB[2], ['quantity' => 2])
        ];
        break;

    case 'update_quantity':
        $itemId = $request['itemId'];
        $quantity = $request['quantity'];
        if (isset($_SESSION['cart'][$itemId])) {
            if ($quantity > 0) {
                $_SESSION['cart'][$itemId]['quantity'] = $quantity;
            } else {
                unset($_SESSION['cart'][$itemId]);
            }
        }
        break;

    case 'clear':
        $_SESSION['cart'] = [];
        break;

    case 'place_order':
        $orderDetails = $request['orderDetails'];
        $cart = $_SESSION['cart'] ?? [];

        if (empty($cart)) {
            echo json_encode(['success' => false, 'error' => 'Cart is empty']);
            exit;
        }

        $totalPrice = array_reduce($cart, function ($sum, $item) {
            return $sum + ($item['discountPrice'] * $item['quantity']);
        }, 0);

        try {
            $pdo->beginTransaction();

            // 1. Вставить в таблицу 'orders'
            $stmt = $pdo->prepare(
                "INSERT INTO orders (customer_type, delivery_type, customer_details, legal_details, total_price) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $orderDetails['personType'],
                $orderDetails['deliveryType'],
                json_encode($orderDetails['deliveryDetails']),
                json_encode($orderDetails['legalDetails']),
                $totalPrice
            ]);

            $orderId = $pdo->lastInsertId();

            // 2. Вставить товары в 'order_items'
            $stmt = $pdo->prepare(
                "INSERT INTO order_items (order_id, product_id, product_title, quantity, price_per_item) VALUES (?, ?, ?, ?, ?)"
            );
            foreach ($cart as $item) {
                $stmt->execute([
                    $orderId,
                    $item['id'],
                    $item['title'],
                    $item['quantity'],
                    $item['discountPrice']
                ]);
            }

            $pdo->commit();

            // 3. Очистить корзину и вернуть результат
            $_SESSION['cart'] = [];
            echo json_encode(['success' => true, 'orderId' => $orderId]);

        } catch (Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to save order: ' . $e->getMessage()]);
        }
        exit;

    case 'get_all_orders':
        // В реальном приложении пароль должен храниться в виде хеша и в конфигурационном файле
        $adminPassword = '1234'; 
        if (!isset($request['password']) || $request['password'] !== $adminPassword) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        try {
            $stmt = $pdo->query("SELECT id, created_at, customer_type, delivery_type, total_price, customer_details, legal_details FROM orders ORDER BY created_at DESC");
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Конвертируем время в московское
            $moscow_tz = new DateTimeZone('Europe/Moscow');
            foreach ($orders as &$order) {
                $utc_time = new DateTime($order['created_at'], new DateTimeZone('UTC'));
                $utc_time->setTimezone($moscow_tz);
                $order['created_at'] = $utc_time->format('Y-m-d H:i:s');
            }

            echo json_encode(['success' => true, 'orders' => $orders]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to fetch orders: ' . $e->getMessage()]);
        }
        exit;

    case 'get_order_details':
        $adminPassword = '1234';
        if (!isset($request['password']) || $request['password'] !== $adminPassword) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        $orderId = $request['orderId'] ?? null;
        if (!$orderId) {
            http_response_code(400);
            echo json_encode(['error' => 'Order ID is required']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
            $stmt->execute([$orderId]);
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'items' => $items]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => 'Failed to fetch order items: ' . $e->getMessage()]);
        }
        exit;

    case 'get':
    default:
        break;
}

// По умолчанию возвращаем содержимое корзины
echo json_encode(['cart' => array_values($_SESSION['cart'] ?? [])]);
?>