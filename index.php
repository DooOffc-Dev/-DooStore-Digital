<?php
include 'config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DooStore-Digital - API Provider Layanan Digital</title>
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
                <a href="#features" class="nav-link">Fitur</a>
                <a href="docs.php" class="nav-link">API Docs</a>
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

    <!-- HERO SECTION -->
    <div style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); padding: 80px 20px; text-align: center; border-bottom: 3px solid #d4af37;">
        <div class="container">
            <h1 style="font-size: 48px; color: #d4af37; margin-bottom: 20px; letter-spacing: 2px;">
                DooStore-Digital
            </h1>
            <p style="font-size: 20px; color: #e0e0e0; margin-bottom: 30px;">
                Platform API Provider untuk Layanan Digital Terlengkap
            </p>
            <p style="font-size: 16px; color: #b0b0b0; margin-bottom: 30px;">
                Menjual berbagai layanan digital melalui API yang mudah diintegrasikan
            </p>
            <?php if (!isLoggedIn()): ?>
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="register.php" class="btn btn-primary" style="padding: 15px 40px; font-size: 16px;">
                        Mulai Sekarang
                    </a>
                    <a href="docs.php" class="btn btn-secondary" style="padding: 15px 40px; font-size: 16px;">
                        Lihat Dokumentasi API
                    </a>
                </div>
            <?php else: ?>
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="dashboard.php" class="btn btn-primary" style="padding: 15px 40px; font-size: 16px;">
                        Dashboard
                    </a>
                    <a href="docs.php" class="btn btn-secondary" style="padding: 15px 40px; font-size: 16px;">
                        API Documentation
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- FEATURES SECTION -->
    <div class="container section" id="features">
        <h2 style="color: #d4af37; text-align: center; font-size: 36px; margin-bottom: 40px; letter-spacing: 1px;">
            ✨ Fitur Utama
        </h2>
        
        <div class="row col-3">
            <div class="card">
                <div class="card-header">💰 Saldo Reseller</div>
                <div class="card-body">
                    <p class="card-text">Kelola saldo Anda dengan mudah. Setiap transaksi otomatis dipotong dari saldo Anda.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">🔑 API Key Unik</div>
                <div class="card-body">
                    <p class="card-text">Dapatkan API Key unik dalam format ds-xxxxxx untuk mengintegrasikan layanan kami.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">📊 Dashboard Lengkap</div>
                <div class="card-body">
                    <p class="card-text">Monitor semua order, history transaksi, dan saldo dalam satu dashboard yang user-friendly.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">🛍️ Layanan Lengkap</div>
                <div class="card-body">
                    <p class="card-text">Pulsa, Data, Nomor Virtual, Game Topup, E-Wallet, dan masih banyak lagi.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">⚡ API Cepat & Mudah</div>
                <div class="card-body">
                    <p class="card-text">Integrasi mudah dengan dokumentasi lengkap. Cukup panggil endpoint kami dari aplikasi Anda.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">💳 Top-up Otomatis</div>
                <div class="card-body">
                    <p class="card-text">Top-up saldo secara otomatis. Pembayaran terdeteksi otomatis, saldo langsung bertambah.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CATEGORIES SECTION -->
    <div style="background-color: #2a2a2a; padding: 50px 20px; border-top: 2px solid #d4af37; border-bottom: 2px solid #d4af37;">
        <div class="container">
            <h2 style="color: #d4af37; text-align: center; font-size: 36px; margin-bottom: 40px; letter-spacing: 1px;">
                📦 Kategori Layanan
            </h2>
            
            <div class="row col-4">
                <div style="background-color: #1a1a1a; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #d4af37;">
                    <h3 style="color: #d4af37; margin-bottom: 10px;">📱 Nokos Virtual</h3>
                    <p style="color: #b0b0b0; font-size: 14px;">Nomor telepon virtual untuk verifikasi</p>
                </div>

                <div style="background-color: #1a1a1a; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #d4af37;">
                    <h3 style="color: #d4af37; margin-bottom: 10px;">📲 APK Premium</h3>
                    <p style="color: #b0b0b0; font-size: 14px;">Aplikasi dan layanan premium</p>
                </div>

                <div style="background-color: #1a1a1a; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #d4af37;">
                    <h3 style="color: #d4af37; margin-bottom: 10px;">🎮 Topup Game</h3>
                    <p style="color: #b0b0b0; font-size: 14px;">Diamond, UC, Chip untuk berbagai game</p>
                </div>

                <div style="background-color: #1a1a1a; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #d4af37;">
                    <h3 style="color: #d4af37; margin-bottom: 10px;">📞 Pulsa</h3>
                    <p style="color: #b0b0b0; font-size: 14px;">Pulsa semua operator Indonesia</p>
                </div>

                <div style="background-color: #1a1a1a; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #d4af37;">
                    <h3 style="color: #d4af37; margin-bottom: 10px;">🌐 Paket Data</h3>
                    <p style="color: #b0b0b0; font-size: 14px;">Internet 4G/5G berbagai provider</p>
                </div>

                <div style="background-color: #1a1a1a; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #d4af37;">
                    <h3 style="color: #d4af37; margin-bottom: 10px;">💳 E-Wallet</h3>
                    <p style="color: #b0b0b0; font-size: 14px;">GoPay, OVO, Dana, LinkAja, dll</p>
                </div>

                <div style="background-color: #1a1a1a; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #d4af37;">
                    <h3 style="color: #d4af37; margin-bottom: 10px;">🎵 Voucher Digital</h3>
                    <p style="color: #b0b0b0; font-size: 14px;">Spotify, Netflix, Canva, dll</p>
                </div>

                <div style="background-color: #1a1a1a; padding: 20px; border-radius: 8px; text-align: center; border-left: 4px solid #d4af37;">
                    <h3 style="color: #d4af37; margin-bottom: 10px;">👥 Jasa Sosmed</h3>
                    <p style="color: #b0b0b0; font-size: 14px;">Followers, Likes, Views, TikTok, IG</p>
                </div>
            </div>
        </div>
    </div>

    <!-- HOW IT WORKS -->
    <div class="container section">
        <h2 style="color: #d4af37; text-align: center; font-size: 36px; margin-bottom: 40px; letter-spacing: 1px;">
            ⚙️ Cara Kerja
        </h2>
        
        <div class="row col-3">
            <div style="text-align: center;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #d4af37 0%, #c19821 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 20px;">1️⃣</div>
                <h3 style="color: #d4af37; margin-bottom: 10px;">Register & Login</h3>
                <p style="color: #b0b0b0;">Daftar akun reseller dan dapatkan API Key unik</p>
            </div>

            <div style="text-align: center;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #d4af37 0%, #c19821 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 20px;">2️⃣</div>
                <h3 style="color: #d4af37; margin-bottom: 10px;">Top-up Saldo</h3>
                <p style="color: #b0b0b0;">Isi saldo Anda melalui payment gateway</p>
            </div>

            <div style="text-align: center;">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #d4af37 0%, #c19821 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 28px; margin: 0 auto 20px;">3️⃣</div>
                <h3 style="color: #d4af37; margin-bottom: 10px;">Integrasi API</h3>
                <p style="color: #b0b0b0;">Gunakan API Key untuk membuat order</p>
            </div>
        </div>
    </div>

    <!-- CTA SECTION -->
    <div style="background: linear-gradient(135deg, #8b2626 0%, #5a1a1a 100%); padding: 60px 20px; text-align: center;">
        <div class="container">
            <h2 style="color: #d4af37; font-size: 32px; margin-bottom: 20px;">Siap Memulai?</h2>
            <p style="color: #e0e0e0; font-size: 18px; margin-bottom: 30px;">
                Bergabunglah dengan ribuan reseller yang sudah menggunakan DooStore-Digital
            </p>
            <?php if (!isLoggedIn()): ?>
                <a href="register.php" class="btn btn-primary" style="padding: 15px 40px; font-size: 16px;">
                    Daftar Sekarang - Gratis!
                </a>
            <?php else: ?>
                <a href="dashboard.php" class="btn btn-primary" style="padding: 15px 40px; font-size: 16px;">
                    Ke Dashboard
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <p style="margin-bottom: 10px;">&copy; 2024 <strong>DooStore-Digital</strong> - All Rights Reserved</p>
            <p style="color: #999; font-size: 14px;">
                Platform API Provider Layanan Digital Terpercaya
            </p>
        </div>
    </footer>
</body>
</html>
