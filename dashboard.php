<?php
include 'config.php';

if (!isLoggedIn()) {
    redirect('login.php', 'Silakan login terlebih dahulu', 'warning');
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Get user balance
$balance = getUserBalance($user_id);

// Get API Key
$api_key = getApiKeyByUserId($user_id);

// Get recent orders
$stmt = $conn->prepare("
    SELECT o.id, o.service_id, s.name, o.quantity, o.total, o.status, o.created_at
    FROM orders o
    JOIN services s ON o.service_id = s.id
    WHERE o.user_id = ?
    ORDER BY o.created_at DESC
    LIMIT 10
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get total orders count
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_count = $stmt->get_result()->fetch_assoc()['total'];

// Get completed orders count
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM orders WHERE user_id = ? AND status = 'completed'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$completed_count = $stmt->get_result()->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - DooStore-Digital</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="navbar-brand">
                <a href="index.php" style="text-decoration: none; color: inherit;">
                    DooStore<span>-Digital</span>
                </a>
            </div>
            <div style="display: flex; gap: 20px; align-items: center;">
                <span style="color: #d4af37; font-weight: bold;">👤 <?php echo $username; ?></span>
                <?php if ($role === 'admin'): ?>
                    <a href="admin.php" class="nav-link">Admin Panel</a>
                <?php endif; ?>
                <a href="logout.php" class="nav-link" style="color: #e74c3c;">Logout</a>
            </div>
        </div>
    </nav>

    <!-- DASHBOARD HEADER -->
    <div class="dashboard-header">
        <h1>📊 Dashboard <?php echo ucfirst($role); ?></h1>
        <p style="color: #b0b0b0; margin-top: 10px;">Selamat datang kembali, <strong><?php echo $username; ?></strong>!</p>
    </div>

    <!-- STATS -->
    <div class="container section">
        <div class="row col-3">
            <div class="stat-box">
                <h3>💰 Saldo Anda</h3>
                <div class="value"><?php echo formatCurrency($balance); ?></div>
                <a href="topup.php" class="btn btn-primary" style="margin-top: 15px; padding: 8px 15px; font-size: 13px;">
                    ➕ Top-up Saldo
                </a>
            </div>

            <div class="stat-box">
                <h3>📦 Total Order</h3>
                <div class="value"><?php echo $order_count; ?></div>
                <p style="color: #b0b0b0; margin-top: 10px; font-size: 13px;">Pesanan sepanjang waktu</p>
            </div>

            <div class="stat-box">
                <h3>✅ Order Selesai</h3>
                <div class="value"><?php echo $completed_count; ?></div>
                <p style="color: #b0b0b0; margin-top: 10px; font-size: 13px;">Berhasil diproses</p>
            </div>
        </div>
    </div>

    <!-- API KEY SECTION -->
    <div class="container section">
        <div class="card">
            <div class="card-header">🔑 API Key Anda</div>
            <div class="card-body">
                <p style="margin-bottom: 15px; color: #b0b0b0;">Gunakan API Key ini untuk mengintegrasikan layanan kami ke aplikasi Anda:</p>
                
                <div style="background-color: #1a1a1a; padding: 15px; border-radius: 5px; border-left: 4px solid #d4af37; margin-bottom: 15px;">
                    <code style="color: #d4af37; font-family: 'Courier New', monospace; font-size: 14px; word-break: break-all;"><?php echo $api_key; ?></code>
                </div>

                <button onclick="copyToClipboard('<?php echo $api_key; ?>')" class="btn btn-secondary" style="padding: 10px 20px;">
                    📋 Salin API Key
                </button>

                <div style="background-color: #2a2a2a; padding: 15px; border-radius: 5px; margin-top: 20px;">
                    <h4 style="color: #d4af37; margin-bottom: 10px;">📚 Contoh Penggunaan API:</h4>
                    
                    <div style="background-color: #1a1a1a; padding: 10px; border-radius: 3px; margin-bottom: 10px;">
                        <p style="color: #b0b0b0; margin: 0; font-size: 12px;"><strong>Cek Saldo:</strong></p>
                        <code style="color: #27ae60; font-family: monospace; font-size: 12px;"><?php echo SITE_URL; ?>/api.php?action=balance&api_key=<?php echo $api_key; ?></code>
                    </div>

                    <div style="background-color: #1a1a1a; padding: 10px; border-radius: 3px; margin-bottom: 10px;">
                        <p style="color: #b0b0b0; margin: 0; font-size: 12px;"><strong>List Layanan:</strong></p>
                        <code style="color: #27ae60; font-family: monospace; font-size: 12px;"><?php echo SITE_URL; ?>/api.php?action=services&api_key=<?php echo $api_key; ?></code>
                    </div>

                    <a href="docs.php" class="btn btn-info" style="margin-top: 10px; padding: 8px 15px; font-size: 13px;">
                        📖 Lihat Dokumentasi API Lengkap
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- RECENT ORDERS -->
    <div class="container section">
        <div class="card">
            <div class="card-header">📋 Pesanan Terbaru</div>
            <div class="card-body">
                <?php if (!empty($orders)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID Order</th>
                                <th>Layanan</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><strong>#<?php echo $order['id']; ?></strong></td>
                                    <td><?php echo $order['name']; ?></td>
                                    <td><?php echo $order['quantity']; ?></td>
                                    <td><?php echo formatCurrency($order['total']); ?></td>
                                    <td>
                                        <?php 
                                        $statusClass = 'status-' . $order['status'];
                                        $statusText = ucfirst($order['status']);
                                        echo "<span class=\"badge badge-" . ($order['status'] === 'completed' ? 'success' : ($order['status'] === 'failed' ? 'danger' : 'warning')) . "\">" . $statusText . "</span>";
                                        ?>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px;">
                        <p style="color: #b0b0b0; font-size: 16px;">📭 Belum ada pesanan</p>
                        <p style="color: #999; font-size: 13px;">Mulai buat pesanan melalui API untuk melihatnya di sini</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- QUICK ACTION -->
    <div class="container section">
        <div class="row col-2">
            <div class="card">
                <div class="card-header">🚀 Aksi Cepat</div>
                <div class="card-body">
                    <a href="topup.php" class="btn btn-primary btn-block" style="padding: 12px; margin-bottom: 10px;">
                        💳 Top-up Saldo
                    </a>
                    <a href="docs.php" class="btn btn-secondary btn-block" style="padding: 12px;">
                        📖 API Documentation
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">📞 Informasi</div>
                <div class="card-body" style="color: #b0b0b0; font-size: 13px;">
                    <p><strong>Email:</strong> <?php echo $_SESSION['email'] ?? 'N/A'; ?></p>
                    <p><strong>Role:</strong> <?php echo ucfirst($role); ?></p>
                    <p><strong>Member sejak:</strong> 2024</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <p style="margin-bottom: 10px;">&copy; 2024 <strong>DooStore-Digital</strong> - All Rights Reserved</p>
        </div>
    </footer>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('✅ API Key berhasil disalin!');
            }, function(err) {
                console.error('Gagal menyalin:', err);
            });
        }
    </script>
</body>
</html>
