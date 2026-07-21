<?php
include 'config.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi
    if (empty($username) || empty($email) || empty($password)) {
        $error = 'Semua field harus diisi!';
    } elseif (strlen($username) < 3) {
        $error = 'Username minimal 3 karakter!';
    } elseif (!isValidEmail($email)) {
        $error = 'Email tidak valid!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif ($password !== $confirm_password) {
        $error = 'Password tidak sama!';
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'Username atau email sudah terdaftar!';
        } else {
            $api_key = generateApiKey();
            $hashed_password = hashPassword($password);
            $saldo = 0;
            $role = 'reseller';

            $stmt = $conn->prepare("INSERT INTO users (username, password, email, saldo, api_key, role) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssdss", $username, $hashed_password, $email, $saldo, $api_key, $role);

            if ($stmt->execute()) {
                redirect('login.php', 'Registrasi berhasil! Silakan login dengan akun Anda.', 'success');
            } else {
                $error = 'Terjadi kesalahan saat registrasi!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DooStore-Digital</title>
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
        </div>
    </nav>

    <!-- REGISTER FORM -->
    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);">
        <div class="container" style="max-width: 450px;">
            <div class="card">
                <div class="card-header" style="text-align: center; font-size: 24px;">
                    ✍️ Daftar Akun Baru
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" placeholder="Minimal 3 karakter" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" placeholder="nama@email.com" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi password" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="padding: 12px;">
                            📝 Daftar Sekarang
                        </button>
                    </form>

                    <hr style="border: none; border-top: 1px solid #333; margin: 20px 0;">

                    <p style="text-align: center; color: #b0b0b0;">
                        Sudah punya akun? <a href="login.php" style="color: #d4af37; text-decoration: none; font-weight: bold;">Login di sini</a>
                    </p>

                    <div style="background-color: #1a1a1a; padding: 15px; border-radius: 5px; margin-top: 20px;">
                        <p style="color: #999; font-size: 13px; margin: 0;">
                            <strong style="color: #d4af37;">Keuntungan Mendaftar:</strong>
                            <br>✓ Dapatkan API Key unik
                            <br>✓ Akses dashboard lengkap
                            <br>✓ Kelola saldo & order
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer style="position: fixed; bottom: 0; width: 100%; margin-top: 30px;">
        <div class="container">
            <p style="margin: 0;">&copy; 2024 <strong>DooStore-Digital</strong> - All Rights Reserved</p>
        </div>
    </footer>
</body>
</html>
