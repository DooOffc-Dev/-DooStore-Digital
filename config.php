<?php
/**
 * DooStore-Digital Configuration
 * Database Connection & System Settings
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'doostore_digital');

// Connect to Database
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

// System Configuration
define('SITE_URL', 'http://localhost/doostore-digital');
define('SITE_NAME', 'DooStore-Digital');
define('ADMIN_EMAIL', 'admin@doostore.local');

// API Configuration
define('API_KEY_PREFIX', 'ds-');
define('API_KEY_LENGTH', 20); // Total length including prefix

// Session Configuration
session_start();

// Function: Generate Unique API Key
function generateApiKey() {
    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $randomString = '';
    for ($i = 0; $i < (API_KEY_LENGTH - strlen(API_KEY_PREFIX)); $i++) {
        $randomString .= $chars[rand(0, strlen($chars) - 1)];
    }
    return API_KEY_PREFIX . $randomString;
}

// Function: Hash Password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Function: Verify Password
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Function: Sanitize Input
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Function: Validate Email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function: Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Function: Get current user role
function getUserRole() {
    return isset($_SESSION['role']) ? $_SESSION['role'] : null;
}

// Function: Redirect with message
function redirect($url, $message = '', $type = 'info') {
    if ($message) {
        $_SESSION['message'] = $message;
        $_SESSION['message_type'] = $type;
    }
    header("Location: $url");
    exit;
}

// Function: Display message
function displayMessage() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        $type = isset($_SESSION['message_type']) ? $_SESSION['message_type'] : 'info';
        echo '<div class="alert alert-' . $type . ' alert-dismissible" role="alert">';
        echo $message;
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        echo '</div>';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
}

// Function: Format Currency
function formatCurrency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// Function: Get User Balance
function getUserBalance($userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT saldo FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['saldo'] ?? 0;
}

// Function: Update User Balance
function updateUserBalance($userId, $amount, $operation = 'add') {
    global $conn;
    if ($operation === 'add') {
        $stmt = $conn->prepare("UPDATE users SET saldo = saldo + ? WHERE id = ?");
    } else {
        $stmt = $conn->prepare("UPDATE users SET saldo = saldo - ? WHERE id = ?");
    }
    $stmt->bind_param("di", $amount, $userId);
    return $stmt->execute();
}

// Function: Get API Key by User ID
function getApiKeyByUserId($userId) {
    global $conn;
    $stmt = $conn->prepare("SELECT api_key FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['api_key'] ?? null;
}

// Function: Get User by API Key
function getUserByApiKey($apiKey) {
    global $conn;
    $stmt = $conn->prepare("SELECT id, username, saldo, role FROM users WHERE api_key = ?");
    $stmt->bind_param("s", $apiKey);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Function: Log API Request
function logApiRequest($userId, $action, $status) {
    global $conn;
    $ipAddress = $_SERVER['REMOTE_ADDR'];
    $stmt = $conn->prepare("INSERT INTO api_logs (user_id, action, status, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isss", $userId, $action, $status, $ipAddress);
    $stmt->execute();
}

?>