<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation - DooStore-Digital</title>
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
                <a href="#" class="nav-link">API Docs</a>
                <?php if (isLoggedIn()): ?>
                    <a href="dashboard.php" class="nav-link">Dashboard</a>
                    <a href="logout.php" class="nav-link" style="color: #e74c3c;">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="nav-link">Login</a>
                    <a href="register.php" class="nav-link btn btn-primary" style="margin: 0; padding: 8px 15px;">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- DOCS HEADER -->
    <div style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); padding: 60px 20px; text-align: center; border-bottom: 3px solid #d4af37;">
        <div class="container">
            <h1 style="font-size: 42px; color: #d4af37; margin-bottom: 20px; letter-spacing: 2px;">
                📖 API Documentation
            </h1>
            <p style="font-size: 16px; color: #b0b0b0;">
                Panduan lengkap integrasi API DooStore-Digital untuk aplikasi Anda
            </p>
        </div>
    </div>

    <!-- DOCS CONTENT -->
    <div class="container section" style="max-width: 1000px;">
        <!-- INTRODUCTION -->
        <div class="card" style="margin-bottom: 30px;">
            <div class="card-header">🚀 Memulai</div>
            <div class="card-body">
                <p>DooStore-Digital menyediakan API untuk mengintegrasikan layanan digital ke aplikasi Anda. Setiap reseller mendapatkan API Key unik dalam format <code style="background: #1a1a1a; padding: 3px 8px; border-radius: 3px; color: #d4af37;">ds-xxxxxxxxxxx</code>.</p>
                
                <div style="background-color: #2a2a2a; padding: 15px; border-radius: 5px; margin-top: 15px; border-left: 4px solid #d4af37;">
                    <p style="color: #b0b0b0; margin: 0; font-size: 13px;">
                        <strong>Base URL:</strong><br>
                        <code style="background: #1a1a1a; color: #27ae60;"><?php echo SITE_URL; ?>/api.php</code>
                    </p>
                </div>
            </div>
        </div>

        <!-- ENDPOINT 1: CHECK BALANCE -->
        <div class="card" style="margin-bottom: 30px;">
            <div class="card-header">💰 1. Cek Saldo (GET /api.php)</div>
            <div class="card-body">
                <p><strong>Deskripsi:</strong> Mengecek saldo reseller Anda</p>
                
                <div style="background-color: #1a1a1a; padding: 15px; border-radius: 5px; margin: 15px 0;">
                    <p style="color: #b0b0b0; margin: 0 0 10px 0; font-size: 12px;"><strong>URL:</strong></p>
                    <code style="color: #27ae60; font-family: monospace; font-size: 13px; word-break: break-all;"><?php echo SITE_URL; ?>/api.php?action=balance&api_key=YOUR_API_KEY</code>
                </div>

                <p><strong>Contoh cURL:</strong></p>
                <div style="background-color: #1a1a1a; padding: 10px; border-radius: 5px; margin-bottom: 15px; overflow-x: auto;">
                    <code style="color: #e0e0e0; font-family: monospace; font-size: 12px;">curl -X GET "<?php echo SITE_URL; ?>/api.php?action=balance&api_key=ds-abc123xyz789"</code>
                </div>

                <p><strong>Contoh JavaScript:</strong></p>
                <div style="background-color: #1a1a1a; padding: 10px; border-radius: 5px; margin-bottom: 15px; overflow-x: auto;">
                    <pre style="color: #e0e0e0; font-family: monospace; font-size: 12px; margin: 0;">fetch('<?php echo SITE_URL; ?>/api.php?action=balance&api_key=YOUR_API_KEY')
  .then(res => res.json())
  .then(data => console.log(data))</pre>
                </div>

                <p><strong>Response:</strong></p>
                <div style="background-color: #1a1a1a; padding: 10px; border-radius: 5px; overflow-x: auto;">
                    <pre style="color: #27ae60; font-family: monospace; font-size: 12px; margin: 0;">{
  "status": "success",
  "balance": 500000,
  "currency": "IDR"
}</pre>
                </div>
            </div>
        </div>

        <!-- ENDPOINT 2: LIST SERVICES -->
        <div class="card" style="margin-bottom: 30px;">
            <div class="card-header">📦 2. List Layanan (GET /api.php)</div>
            <div class="card-body">
                <p><strong>Deskripsi:</strong> Mengambil daftar semua layanan yang tersedia</p>
                
                <div style="background-color: #1a1a1a; padding: 15px; border-radius: 5px; margin: 15px 0;">
                    <p style="color: #b0b0b0; margin: 0 0 10px 0; font-size: 12px;"><strong>URL (semua kategori):</strong></p>
                    <code style="color: #27ae60; font-family: monospace; font-size: 13px; word-break: break-all;"><?php echo SITE_URL; ?>/api.php?action=services&api_key=YOUR_API_KEY</code>
                    
                    <p style="color: #b0b0b0; margin: 15px 0 10px 0; font-size: 12px;"><strong>URL (kategori spesifik):</strong></p>
                    <code style="color: #27ae60; font-family: monospace; font-size: 13px; word-break: break-all;"><?php echo SITE_URL; ?>/api.php?action=services&api_key=YOUR_API_KEY&category=Pulsa</code>
                </div>

                <p><strong>Kategori yang tersedia:</strong></p>
                <div style="background-color: #2a2a2a; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <ul style="color: #b0b0b0; font-size: 13px; margin: 0; padding-left: 20px;">
                        <li>Nokos Virtual</li>
                        <li>APK Premium</li>
                        <li>Topup Game</li>
                        <li>Pulsa</li>
                        <li>Paket Data</li>
                        <li>E-Wallet</li>
                        <li>Voucher Digital</li>
                        <li>Jasa Sosmed</li>
                    </ul>
                </div>

                <p><strong>Response:</strong></p>
                <div style="background-color: #1a1a1a; padding: 10px; border-radius: 5px; overflow-x: auto;">
                    <pre style="color: #27ae60; font-family: monospace; font-size: 12px; margin: 0;">{
  "status": "success",
  "total": 5,
  "services": [
    {
      "id": 1,
      "name": "Pulsa Telkomsel 10rb",
      "category": "Pulsa",
      "price": 11000,
      "min_qty": 1,
      "max_qty": 100,
      "description": "Pulsa Telkomsel nominal 10rb"
    },
    ...
  ]
}</pre>
                </div>
            </div>
        </div>

        <!-- ENDPOINT 3: CREATE ORDER -->
        <div class="card" style="margin-bottom: 30px;">
            <div class="card-header">📝 3. Buat Order (POST /api.php)</div>
            <div class="card-body">
                <p><strong>Deskripsi:</strong> Membuat order baru (transaksi)</p>
                
                <p><strong>Parameter:</strong></p>
                <div style="background-color: #2a2a2a; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <ul style="color: #b0b0b0; font-size: 13px; margin: 0; padding-left: 20px;">
                        <li><code style="color: #d4af37;">api_key</code> (required) - API Key Anda</li>
                        <li><code style="color: #d4af37;">service_id</code> (required) - ID layanan dari list services</li>
                        <li><code style="color: #d4af37;">target</code> (required) - Target (nomor telepon, username, email, dll)</li>
                        <li><code style="color: #d4af37;">quantity</code> (required) - Jumlah (sesuai min-max)</li>
                    </ul>
                </div>

                <p><strong>Contoh cURL:</strong></p>
                <div style="background-color: #1a1a1a; padding: 10px; border-radius: 5px; margin-bottom: 15px; overflow-x: auto;">
                    <pre style="color: #e0e0e0; font-family: monospace; font-size: 12px; margin: 0;">curl -X POST "<?php echo SITE_URL; ?>/api.php" \
  -d "action=order" \
  -d "api_key=YOUR_API_KEY" \
  -d "service_id=1" \
  -d "target=081234567890" \
  -d "quantity=1"</pre>
                </div>

                <p><strong>Contoh JavaScript (Fetch):</strong></p>
                <div style="background-color: #1a1a1a; padding: 10px; border-radius: 5px; margin-bottom: 15px; overflow-x: auto;">
                    <pre style="color: #e0e0e0; font-family: monospace; font-size: 12px; margin: 0;">const data = new FormData();
data.append('action', 'order');
data.append('api_key', 'YOUR_API_KEY');
data.append('service_id', 1);
data.append('target', '081234567890');
data.append('quantity', 1);

fetch('<?php echo SITE_URL; ?>/api.php', {method: 'POST', body: data})
  .then(res => res.json())
  .then(data => console.log(data))</pre>
                </div>

                <p><strong>Response Success:</strong></p>
                <div style="background-color: #1a1a1a; padding: 10px; border-radius: 5px; overflow-x: auto;">
                    <pre style="color: #27ae60; font-family: monospace; font-size: 12px; margin: 0;">{
  "status": "success",
  "message": "Order berhasil dibuat",
  "order_id": 123,
  "order_status": "completed",
  "remaining_balance": 489000
}</pre>
                </div>
            </div>
        </div>

        <!-- ENDPOINT 4: CHECK STATUS -->
        <div class="card" style="margin-bottom: 30px;">
            <div class="card-header">✅ 4. Cek Status Order (GET /api.php)</div>
            <div class="card-body">
                <p><strong>Deskripsi:</strong> Mengecek status order yang telah dibuat</p>
                
                <div style="background-color: #1a1a1a; padding: 15px; border-radius: 5px; margin: 15px 0;">
                    <p style="color: #b0b0b0; margin: 0 0 10px 0; font-size: 12px;"><strong>URL:</strong></p>
                    <code style="color: #27ae60; font-family: monospace; font-size: 13px; word-break: break-all;"><?php echo SITE_URL; ?>/api.php?action=status&api_key=YOUR_API_KEY&order_id=123</code>
                </div>

                <p><strong>Response:</strong></p>
                <div style="background-color: #1a1a1a; padding: 10px; border-radius: 5px; overflow-x: auto;">
                    <pre style="color: #27ae60; font-family: monospace; font-size: 12px; margin: 0;">{
  "status": "success",
  "order": {
    "id": 123,
    "service_id": 1,
    "target": "081234567890",
    "quantity": 1,
    "total": 11000,
    "status": "completed",
    "created_at": "2024-07-21 12:30:45",
    "updated_at": "2024-07-21 12:31:00"
  }
}</pre>
                </div>
            </div>
        </div>

        <!-- ERROR CODES -->
        <div class="card" style="margin-bottom: 30px;">
            <div class="card-header">⚠️ HTTP Status Codes</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Meaning</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>200</strong></td>
                            <td>OK</td>
                            <td>Request berhasil</td>
                        </tr>
                        <tr>
                            <td><strong>400</strong></td>
                            <td>Bad Request</td>
                            <td>Data tidak lengkap atau invalid</td>
                        </tr>
                        <tr>
                            <td><strong>401</strong></td>
                            <td>Unauthorized</td>
                            <td>API Key tidak valid</td>
                        </tr>
                        <tr>
                            <td><strong>402</strong></td>
                            <td>Payment Required</td>
                            <td>Saldo tidak cukup</td>
                        </tr>
                        <tr>
                            <td><strong>404</strong></td>
                            <td>Not Found</td>
                            <td>Layanan atau order tidak ditemukan</td>
                        </tr>
                        <tr>
                            <td><strong>405</strong></td>
                            <td>Method Not Allowed</td>
                            <td>Method HTTP salah (gunakan GET atau POST)</td>
                        </tr>
                        <tr>
                            <td><strong>500</strong></td>
                            <td>Server Error</td>
                            <td>Kesalahan server</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TESTING -->
        <div class="card">
            <div class="card-header">🧪 Testing API</div>
            <div class="card-body">
                <p style="color: #b0b0b0; margin-bottom: 15px;">Gunakan tools berikut untuk testing API:</p>
                <ul style="color: #b0b0b0; font-size: 13px; margin: 0; padding-left: 20px;">
                    <li><a href="https://www.postman.com/" style="color: #d4af37; text-decoration: none;">Postman</a> - API Testing Tool</li>
                    <li><a href="https://insomnia.rest/" style="color: #d4af37; text-decoration: none;">Insomnia</a> - REST Client</li>
                    <li><a href="https://curl.se/" style="color: #d4af37; text-decoration: none;">cURL</a> - Command Line Tool</li>
                </ul>
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
