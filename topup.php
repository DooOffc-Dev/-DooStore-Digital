<?php
include 'config.php';

if (!isLoggedIn()) {
    redirect('login.php', 'Silakan login terlebih dahulu', 'warning');
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Handle top-up request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = (float)($_POST['amount'] ?? 0);
    $payment_method = sanitize($_POST['payment_method'] ?? '');

    if ($amount <= 0) {
        $_SESSION['message'] = '❌ Jumlah top-up tidak valid!';
        $_SESSION['message_type'] = 'danger';
    } elseif (empty($payment_method)) {
        $_SESSION['message'] = '❌ Pilih metode pembayaran!';
        $_SESSION['message_type'] = 'danger';
    } else {
        // Generate invoice number
        $invoice_number = 'INV-' . date('YmdHis') . '-' . rand(1000, 9999);
        $invoice_id = 'TOPUP-' . date('YmdHis') . '-' . rand(1000, 9999);
        
        // Create invoice
        $expired_at = date('Y-m-d H:i:s', strtotime('+24 hours'));
        $status = 'pending';
        
        $stmt = $conn->prepare("INSERT INTO invoices (user_id, invoice_number, amount, payment_method, status, expired_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isdsss", $user_id, $invoice_number, $amount, $payment_method, $status, $expired_at);
        
        if ($stmt->execute()) {
            // Create topup record
            $stmt = $conn->prepare("INSERT INTO topups (user_id, amount, payment_method, invoice_id, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("idss", $user_id, $amount, $payment_method, $invoice_id, $status);
            $stmt->execute();
            
            redirect('payment.php?invoice=' . $invoice_number, '✅ Invoice berhasil dibuat! Silakan lanjutkan pembayaran.', 'success');
        } else {
            $_SESSION['message'] = '❌ Gagal membuat invoice!';
            $_SESSION['message_type'] = 'danger';
        }
    }
}

// Get user balance
$balance = getUserBalance($user_id);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top-up Saldo - DooStore-Digital</title>
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
                <span style="color: #d4af37; font-weight: bold;">💰 <?php echo formatCurrency($balance); ?></span>
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <a href="logout.php" class="nav-link" style="color: #e74c3c;">Logout</a>
            </div>
        </div>
    </nav>

    <!-- TOP-UP SECTION -->
    <div class="container section" style="max-width: 600px; margin: 40px auto;">
        <div class="card">
            <div class="card-header" style="text-align: center; font-size: 24px;">
                💳 Top-up Saldo
            </div>
            <div class="card-body">
                <?php displayMessage(); ?>

                <div style="background-color: #2a2a2a; padding: 20px; border-radius: 5px; margin-bottom: 30px; border-left: 4px solid #d4af37;">
                    <p style="color: #b0b0b0; margin: 0; font-size: 13px;"><strong>Saldo Saat Ini:</strong></p>
                    <p style="color: #d4af37; font-size: 24px; font-weight: bold; margin: 10px 0 0 0;"><?php echo formatCurrency($balance); ?></p>
                </div>

                <form method="POST">
                    <div class="form-group">
                        <label for="amount">Jumlah Top-up (Rp)</label>
                        <input type="number" id="amount" name="amount" placeholder="Contoh: 100000" min="1000" step="1000" required>
                        <small style="color: #999; display: block; margin-top: 5px;">Minimum top-up: Rp 1.000</small>
                    </div>

                    <div class="form-group">
                        <label for="payment_method">Metode Pembayaran</label>
                        <select id="payment_method" name="payment_method" required>
                            <option value="">- Pilih Metode -</option>
                            <option value="bank_transfer">💳 Bank Transfer</option>
                            <option value="gopay">🔵 GoPay</option>
                            <option value="ovo">🔴 OVO</option>
                            <option value="dana">🟠 DANA</option>
                            <option value="linkaja">🟢 LinkAja</option>
                        </select>
                    </div>

                    <div style="background-color: #1a1a1a; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <p style="color: #d4af37; font-weight: bold; margin: 0 0 10px 0;">📋 Paket Top-up Populer:</p>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <button type="button" onclick="setAmount(50000)" class="btn btn-secondary" style="padding: 8px 12px; font-size: 12px;">Rp 50.000</button>
                            <button type="button" onclick="setAmount(100000)" class="btn btn-secondary" style="padding: 8px 12px; font-size: 12px;">Rp 100.000</button>
                            <button type="button" onclick="setAmount(200000)" class="btn btn-secondary" style="padding: 8px 12px; font-size: 12px;">Rp 200.000</button>
                            <button type="button" onclick="setAmount(500000)" class="btn btn-secondary" style="padding: 8px 12px; font-size: 12px;">Rp 500.000</button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" style="padding: 12px;">
                        ✅ Lanjutkan Pembayaran
                    </button>
                </form>

                <div style="background-color: #2a2a2a; padding: 15px; border-radius: 5px; margin-top: 20px;">
                    <p style="color: #d4af37; font-weight: bold; margin: 0 0 10px 0;">ℹ️ Informasi:</p>
                    <ul style="color: #b0b0b0; font-size: 13px; margin: 0; padding-left: 20px;">
                        <li>Pembayaran akan diproses otomatis</li>
                        <li>Invoice berlaku 24 jam</li>
                        <li>Saldo akan langsung bertambah setelah pembayaran dikonfirmasi</li>
                        <li>Tidak ada biaya tambahan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- HISTORY -->
    <div class="container section" style="max-width: 600px; margin: 40px auto;">
        <?php
        $stmt = $conn->prepare("
            SELECT id, amount, payment_method, status, created_at, paid_at
            FROM topups
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT 5
        ");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $topups = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        ?>

        <?php if (!empty($topups)): ?>
            <div class="card">
                <div class="card-header">📜 History Top-up</div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Jumlah</th>
                                <th>Metode</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topups as $topup): ?>
                                <tr>
                                    <td><?php echo formatCurrency($topup['amount']); ?></td>
                                    <td><?php echo ucfirst(str_replace('_', ' ', $topup['payment_method'])); ?></td>
                                    <td>
                                        <?php 
                                        $badge_class = $topup['status'] === 'paid' ? 'badge-success' : ($topup['status'] === 'expired' ? 'badge-danger' : 'badge-warning');
                                        echo "<span class='badge $badge_class">" . ucfirst($topup['status']) . "</span>";
                                        ?>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($topup['created_at'])); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <p style="margin-bottom: 10px;">&copy; 2024 <strong>DooStore-Digital</strong> - All Rights Reserved</p>
        </div>
    </footer>

    <script>
        function setAmount(amount) {
            document.getElementById('amount').value = amount;
            document.getElementById('amount').focus();
        }
    </script>
</body>
</html>
