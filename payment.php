<?php
include 'config.php';

if (!isLoggedIn()) {
    redirect('login.php', 'Silakan login terlebih dahulu', 'warning');
}

$user_id = $_SESSION['user_id'];
$invoice_number = sanitize($_GET['invoice'] ?? '');

if (empty($invoice_number)) {
    redirect('topup.php', '❌ Invoice tidak ditemukan!', 'danger');
}

// Get invoice details
$stmt = $conn->prepare("SELECT * FROM invoices WHERE invoice_number = ? AND user_id = ?");
$stmt->bind_param("si", $invoice_number, $user_id);
$stmt->execute();
$invoice = $stmt->get_result()->fetch_assoc();

if (!$invoice) {
    redirect('topup.php', '❌ Invoice tidak ditemukan!', 'danger');
}

// Handle payment confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'confirm_payment') {
    // Update invoice status
    $status = 'paid';
    $paid_at = date('Y-m-d H:i:s');
    
    $stmt = $conn->prepare("UPDATE invoices SET status = ?, paid_at = ? WHERE invoice_number = ?");
    $stmt->bind_param("sss", $status, $paid_at, $invoice_number);
    
    if ($stmt->execute()) {
        // Update topup status
        $stmt = $conn->prepare("UPDATE topups SET status = ?, paid_at = ? WHERE invoice_id = ?");
        $stmt->bind_param("sss", $status, $paid_at, $invoice_number);
        $stmt->execute();
        
        // Add balance to user
        updateUserBalance($user_id, $invoice['amount'], 'add');
        
        redirect('dashboard.php', '✅ Pembayaran berhasil! Saldo Anda telah diperbarui.', 'success');
    }
}

// Check if invoice is expired
$expired_at = strtotime($invoice['expired_at']);
$now = strtotime(date('Y-m-d H:i:s'));
$is_expired = $now > $expired_at && $invoice['status'] === 'pending';

if ($is_expired) {
    // Mark as expired
    $status = 'expired';
    $stmt = $conn->prepare("UPDATE invoices SET status = ? WHERE invoice_number = ?");
    $stmt->bind_param("ss", $status, $invoice_number);
    $stmt->execute();
    
    redirect('topup.php', '❌ Invoice telah kadaluarsa!', 'danger');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - DooStore-Digital</title>
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
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <a href="logout.php" class="nav-link" style="color: #e74c3c;">Logout</a>
            </div>
        </div>
    </nav>

    <!-- PAYMENT PAGE -->
    <div class="container section" style="max-width: 600px; margin: 40px auto;">
        <div class="card">
            <div class="card-header" style="text-align: center; font-size: 24px;">
                🔐 Konfirmasi Pembayaran
            </div>
            <div class="card-body">
                <?php displayMessage(); ?>

                <!-- INVOICE DETAILS -->
                <div style="background-color: #2a2a2a; padding: 20px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #d4af37;">
                    <div style="margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 15px;">
                        <p style="color: #b0b0b0; margin: 0 0 5px 0; font-size: 12px;">NOMOR INVOICE</p>
                        <p style="color: #d4af37; font-size: 16px; font-weight: bold; margin: 0;"><?php echo $invoice['invoice_number']; ?></p>
                    </div>

                    <div style="margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 15px;">
                        <p style="color: #b0b0b0; margin: 0 0 5px 0; font-size: 12px;">JUMLAH PEMBAYARAN</p>
                        <p style="color: #27ae60; font-size: 28px; font-weight: bold; margin: 0;"><?php echo formatCurrency($invoice['amount']); ?></p>
                    </div>

                    <div style="margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 15px;">
                        <p style="color: #b0b0b0; margin: 0 0 5px 0; font-size: 12px;">METODE PEMBAYARAN</p>
                        <p style="color: #e0e0e0; font-size: 14px; margin: 0;"><?php echo ucfirst(str_replace('_', ' ', $invoice['payment_method'])); ?></p>
                    </div>

                    <div style="margin-bottom: 0;">
                        <p style="color: #b0b0b0; margin: 0 0 5px 0; font-size: 12px;">STATUS</p>
                        <p style="margin: 0;">
                            <?php 
                            $badge_class = $invoice['status'] === 'paid' ? 'badge-success' : 'badge-warning';
                            echo "<span class='badge $badge_class">" . ucfirst($invoice['status']) . "</span>";
                            ?>
                        </p>
                    </div>
                </div>

                <!-- PAYMENT INSTRUCTIONS -->
                <?php if ($invoice['status'] === 'pending'): ?>
                    <div style="background-color: #1a1a1a; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #f39c12;">
                        <p style="color: #f39c12; font-weight: bold; margin: 0 0 10px 0;">📋 Instruksi Pembayaran:</p>
                        
                        <?php if ($invoice['payment_method'] === 'bank_transfer'): ?>
                            <p style="color: #b0b0b0; margin: 0; font-size: 13px;">
                                <strong>1. Transfer ke rekening berikut:</strong><br>
                                Bank: BCA<br>
                                No. Rekening: 1234567890 (a.n. DooStore Digital)<br>
                                Jumlah: <span style="color: #27ae60; font-weight: bold;"><?php echo formatCurrency($invoice['amount']); ?></span>
                            </p>
                        <?php elseif ($invoice['payment_method'] === 'gopay'): ?>
                            <p style="color: #b0b0b0; margin: 0; font-size: 13px;">
                                <strong>1. Scan QR Code GoPay:</strong><br>
                                Scan kode di aplikasi GoPay Anda<br>
                                Nominal: <span style="color: #27ae60; font-weight: bold;"><?php echo formatCurrency($invoice['amount']); ?></span>
                            </p>
                        <?php else: ?>
                            <p style="color: #b0b0b0; margin: 0; font-size: 13px;">
                                <strong>1. Buka aplikasi <?php echo ucfirst(str_replace('_', ' ', $invoice['payment_method'])); ?>:</strong><br>
                                Kirim jumlah: <span style="color: #27ae60; font-weight: bold;"><?php echo formatCurrency($invoice['amount']); ?></span><br>
                                Ke: @doostore.official
                            </p>
                        <?php endif; ?>

                        <p style="color: #b0b0b0; margin: 10px 0 0 0; font-size: 13px;">
                            <strong>2. Klik tombol "Konfirmasi Pembayaran" setelah transfer</strong>
                        </p>
                    </div>

                    <!-- PAYMENT FORM -->
                    <form method="POST">
                        <input type="hidden" name="action" value="confirm_payment">
                        <button type="submit" class="btn btn-success btn-block" style="padding: 12px; margin-bottom: 10px;">
                            ✅ Konfirmasi Pembayaran
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-success" style="margin-bottom: 20px;">
                        ✅ Pembayaran telah dikonfirmasi! Saldo Anda sudah diperbarui.
                    </div>
                <?php endif; ?>

                <a href="topup.php" class="btn btn-secondary btn-block" style="padding: 12px;">
                    ← Kembali ke Top-up
                </a>

                <!-- IMPORTANT INFO -->
                <div style="background-color: #2a2a2a; padding: 15px; border-radius: 5px; margin-top: 20px;">
                    <p style="color: #d4af37; font-weight: bold; margin: 0 0 10px 0;">⏰ Informasi Penting:</p>
                    <ul style="color: #b0b0b0; font-size: 12px; margin: 0; padding-left: 20px;">
                        <li>Invoice berlaku hingga: <strong><?php echo date('d/m/Y H:i', strtotime($invoice['expired_at'])); ?></strong></li>
                        <li>Setelah pembayaran dikonfirmasi, saldo langsung bertambah</li>
                        <li>Pastikan jumlah transfer sesuai dengan nominal di atas</li>
                        <li>Jangan lupa klik "Konfirmasi Pembayaran" setelah transfer</li>
                    </ul>
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
</body>
</html>
