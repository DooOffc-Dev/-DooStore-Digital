<?php
include 'config.php';

header('Content-Type: application/json');

$action = sanitize($_GET['action'] ?? '');
$api_key = sanitize($_GET['api_key'] ?? $_POST['api_key'] ?? '');

// Validate API Key
if (empty($api_key)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'API Key diperlukan']);
    exit;
}

$user = getUserByApiKey($api_key);
if (!$user) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'API Key tidak valid']);
    logApiRequest(null, $action, 'invalid_key');
    exit;
}

$user_id = $user['id'];

// Handle different actions
switch ($action) {
    case 'balance':
        $balance = getUserBalance($user_id);
        echo json_encode([
            'status' => 'success',
            'balance' => $balance,
            'currency' => 'IDR'
        ]);
        logApiRequest($user_id, 'balance', 'success');
        break;

    case 'services':
        $category = sanitize($_GET['category'] ?? '');
        
        if (!empty($category)) {
            $stmt = $conn->prepare("SELECT id, name, category, price, min_qty, max_qty, description FROM services WHERE status = 'active' AND category = ? ORDER BY name");
            $stmt->bind_param("s", $category);
        } else {
            $stmt = $conn->prepare("SELECT id, name, category, price, min_qty, max_qty, description FROM services WHERE status = 'active' ORDER BY category, name");
        }
        
        $stmt->execute();
        $services = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        echo json_encode([
            'status' => 'success',
            'total' => count($services),
            'services' => $services
        ]);
        logApiRequest($user_id, 'services', 'success');
        break;

    case 'order':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method tidak diizinkan. Gunakan POST']);
            exit;
        }

        $service_id = (int)($_POST['service_id'] ?? 0);
        $target = sanitize($_POST['target'] ?? '');
        $quantity = (int)($_POST['quantity'] ?? 0);

        // Validate input
        if (empty($service_id) || empty($target) || $quantity <= 0) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'service_id, target, dan quantity harus diisi dengan benar']);
            exit;
        }

        // Get service details
        $stmt = $conn->prepare("SELECT id, price, min_qty, max_qty, status FROM services WHERE id = ?");
        $stmt->bind_param("i", $service_id);
        $stmt->execute();
        $service = $stmt->get_result()->fetch_assoc();

        if (!$service || $service['status'] !== 'active') {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Layanan tidak ditemukan atau tidak aktif']);
            exit;
        }

        // Validate quantity
        if ($quantity < $service['min_qty'] || $quantity > $service['max_qty']) {
            http_response_code(400);
            echo json_encode([
                'status' => 'error',
                'message' => 'Quantity harus antara ' . $service['min_qty'] . ' - ' . $service['max_qty']
            ]);
            exit;
        }

        // Calculate total price
        $total_price = $service['price'] * $quantity;
        $current_balance = getUserBalance($user_id);

        // Check balance
        if ($current_balance < $total_price) {
            http_response_code(402);
            echo json_encode([
                'status' => 'error',
                'message' => 'Saldo tidak cukup',
                'required' => $total_price,
                'balance' => $current_balance
            ]);
            exit;
        }

        // Create order
        $status = 'pending';
        $stmt = $conn->prepare("INSERT INTO orders (user_id, service_id, target, quantity, price, total, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisiiis", $user_id, $service_id, $target, $quantity, $service['price'], $total_price, $status);

        if ($stmt->execute()) {
            $order_id = $conn->insert_id;
            
            // Deduct balance
            updateUserBalance($user_id, $total_price, 'subtract');
            
            // Auto process order (simulate)
            $new_status = 'completed';
            $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $new_status, $order_id);
            $stmt->execute();
            
            $new_balance = getUserBalance($user_id);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Order berhasil dibuat',
                'order_id' => $order_id,
                'order_status' => 'completed',
                'remaining_balance' => $new_balance
            ]);
            logApiRequest($user_id, 'order', 'success');
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Gagal membuat order']);
            logApiRequest($user_id, 'order', 'failed');
        }
        break;

    case 'status':
        $order_id = (int)($_GET['order_id'] ?? 0);
        
        if (empty($order_id)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'order_id diperlukan']);
            exit;
        }

        $stmt = $conn->prepare("SELECT id, service_id, target, quantity, total, status, created_at, updated_at FROM orders WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $order_id, $user_id);
        $stmt->execute();
        $order = $stmt->get_result()->fetch_assoc();

        if (!$order) {
            http_response_code(404);
            echo json_encode(['status' => 'error', 'message' => 'Order tidak ditemukan']);
            exit;
        }

        echo json_encode([
            'status' => 'success',
            'order' => $order
        ]);
        logApiRequest($user_id, 'status', 'success');
        break;

    default:
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Action tidak dikenali. Gunakan: balance, services, order, status']);
        break;
}
?>
