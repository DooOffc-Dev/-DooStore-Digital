<?php
include 'config.php';

if (isLoggedIn()) {
    redirect('dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            $success = 'Login berhasil! Mengalihkan ke dashboard...';
            redirect('dashboard.php', 'Login berhasil!', 'success');
        } else {
            $error = 'Username atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DooStore-Digital</title>
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

    <!-- LOGIN FORM -->
    <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);">
        <div class="container" style="max-width: 450px;">
            <div class="card">
                <div class="card-header" style="text-align: center; font-size: 24px;">
                    🔐 Login Akun
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
                            <input type="text" id="username" name="username" placeholder="Masukkan username" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block" style="padding: 12px;">
                            🔓 Login
                        </button>
                    </form>

                    <hr style="border: none; border-top: 1px solid #333; margin: 20px 0;">

                    <p style="text-align: center; color: #b0b0b0;">
                        Belum punya akun? <a href="register.php" style="color: #d4af37; text-decoration: none; font-weight: bold;">Daftar di sini</a>
                    </p>

                    <div style="background-color: #1a1a1a; padding: 15px; border-radius: 5px; margin-top: 20px;">
                        <p style="color: #999; font-size: 13px; margin: 0; text-align: center;">
                            <strong style="color: #d4af37;">Demo Admin:</strong><br>
                            Username: admin<br>
                            Password: admin123
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
