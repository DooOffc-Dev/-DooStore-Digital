<?php
/*
 * DooStore-Digital - Setup Check
 * Panduan cepat setup sistem
 */

// Check PHP Version
if (version_compare(phpversion(), '7.4', '<')) {
    die('❌ PHP 7.4 atau lebih tinggi diperlukan');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Check - DooStore-Digital</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%);
            color: #e0e0e0;
            padding: 40px 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #d4af37;
        }
        .header h1 {
            font-size: 32px;
            color: #d4af37;
            margin-bottom: 10px;
        }
        .section {
            background-color: #2a2a2a;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            border-left: 4px solid #d4af37;
        }
        .section h2 {
            color: #d4af37;
            font-size: 18px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .check-item {
            padding: 10px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .check-item.success {
            color: #27ae60;
        }
        .check-item.error {
            color: #e74c3c;
        }
        .check-item.warning {
            color: #f39c12;
        }
        .icon {
            font-size: 20px;
            min-width: 25px;
        }
        code {
            background-color: #1a1a1a;
            padding: 3px 8px;
            border-radius: 4px;
            color: #d4af37;
            font-family: 'Courier New', monospace;
        }
        .summary {
            background: linear-gradient(135deg, #8b2626 0%, #5a1a1a 100%);
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: center;
        }
        .summary.ready {
            background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #d4af37 0%, #c19821 100%);
            color: #1a1a1a;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #8b2626 0%, #5a1a1a 100%);
            color: white;
        }
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(139, 38, 38, 0.4);
        }
        .credentials {
            background-color: #1a1a1a;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #d4af37;
        }
        .credentials p {
            margin: 8px 0;
            font-size: 14px;
        }
        .warning-box {
            background-color: rgba(243, 156, 18, 0.1);
            border-left: 4px solid #f39c12;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .warning-box strong {
            color: #f39c12;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 DooStore-Digital Setup Check</h1>
            <p>Verifikasi sistem sebelum digunakan</p>
        </div>

        <?php
        // Check Database Connection
        echo '<div class="section">';
        echo '<h2><span class="icon">🗄️</span> Database Connection</h2>';
        
        $db_error = false;
        try {
            $conn = new mysqli('localhost', 'root', '', 'doostore_digital');
            if ($conn->connect_error) {
                echo '<div class="check-item error">';
                echo '<span class="icon">❌</span>';
                echo '<span>Database Error: ' . htmlspecialchars($conn->connect_error) . '</span>';
                echo '</div>';
                $db_error = true;
            } else {
                echo '<div class="check-item success">';
                echo '<span class="icon">✅</span>';
                echo '<span>Database Connected</span>';
                echo '</div>';
                $conn->close();
            }
        } catch (Exception $e) {
            echo '<div class="check-item error">';
            echo '<span class="icon">❌</span>';
            echo '<span>Exception: ' . htmlspecialchars($e->getMessage()) . '</span>';
            echo '</div>';
            $db_error = true;
        }
        echo '</div>';

        // Check Required Files
        echo '<div class="section">';
        echo '<h2><span class="icon">📁</span> Required Files</h2>';
        
        $required_files = [
            'config.php',
            'index.php',
            'login.php',
            'register.php',
            'dashboard.php',
            'admin.php',
            'api.php',
            'topup.php',
            'payment.php',
            'docs.php',
            'logout.php',
            'style.css',
            'install.sql'
        ];

        $all_files_exist = true;
        foreach ($required_files as $file) {
            if (file_exists($file)) {
                echo '<div class="check-item success">';
                echo '<span class="icon">✅</span>';
                echo '<span>' . htmlspecialchars($file) . '</span>';
                echo '</div>';
            } else {
                echo '<div class="check-item error">';
                echo '<span class="icon">❌</span>';
                echo '<span>' . htmlspecialchars($file) . ' (MISSING)</span>';
                echo '</div>';
                $all_files_exist = false;
            }
        }
        echo '</div>';

        // Check PHP Extensions
        echo '<div class="section">';
        echo '<h2><span class="icon">⚙️</span> PHP Extensions</h2>';
        
        $extensions = ['mysqli', 'json', 'session', 'filter', 'hash'];
        $all_ext_loaded = true;
        
        foreach ($extensions as $ext) {
            if (extension_loaded($ext)) {
                echo '<div class="check-item success">';
                echo '<span class="icon">✅</span>';
                echo '<span>' . htmlspecialchars($ext) . '</span>';
                echo '</div>';
            } else {
                echo '<div class="check-item error">';
                echo '<span class="icon">❌</span>';
                echo '<span>' . htmlspecialchars($ext) . ' (NOT INSTALLED)</span>';
                echo '</div>';
                $all_ext_loaded = false;
            }
        }
        echo '</div>';

        // Check Permissions
        echo '<div class="section">';
        echo '<h2><span class="icon">🔐</span> File Permissions</h2>';
        
        $perms = fileperms('.');
        $perms_str = substr(sprintf('%o', $perms), -4);
        echo '<div class="check-item">';
        echo '<span>Current directory permissions: <code>' . htmlspecialchars($perms_str) . '</code></span>';
        echo '</div>';
        
        if (is_readable('.')) {
            echo '<div class="check-item success">';
            echo '<span class="icon">✅</span>';
            echo '<span>Directory is readable</span>';
            echo '</div>';
        } else {
            echo '<div class="check-item error">';
            echo '<span class="icon">❌</span>';
            echo '<span>Directory is not readable</span>';
            echo '</div>';
        }
        echo '</div>';

        // Setup Summary
        echo '<div class="section">';
        echo '<h2><span class="icon">📋</span> Setup Summary</h2>';
        
        $is_ready = !$db_error && $all_files_exist && $all_ext_loaded;
        
        if ($is_ready) {
            echo '<div class="summary ready">';
            echo '<h3 style="color: white; margin-bottom: 15px;">✅ Sistem Siap Digunakan!</h3>';
            echo '<p>Semua check telah lulus. Sistem DooStore-Digital siap untuk dijalankan.</p>';
            echo '</div>';
        } else {
            echo '<div class="summary">';
            echo '<h3 style="color: white; margin-bottom: 15px;">⚠️ Sistem Belum Siap</h3>';
            echo '<p>Ada beberapa masalah yang perlu diperbaiki sebelum sistem dapat digunakan.</p>';
            echo '</div>';
        }
        
        echo '<div class="credentials">';
        echo '<p><strong>📝 Credentials Default:</strong></p>';
        echo '<p>Username: <code>admin</code></p>';
        echo '<p>Password: <code>admin123</code></p>';
        echo '<p>API Key: <code>ds-admin2024secret</code></p>';
        echo '</div>';
        
        echo '<div class="warning-box">';
        echo '<p><strong>⚠️ PENTING:</strong> Ubah password admin segera setelah login pertama kali!</p>';
        echo '</div>';
        
        echo '<div class="action-buttons">';
        if ($is_ready) {
            echo '<a href="index.php" class="btn btn-primary">🚀 Buka DooStore-Digital</a>';
            echo '<a href="login.php" class="btn btn-secondary">🔐 Login Admin</a>';
        } else {
            echo '<button class="btn btn-secondary" onclick="location.reload()" style="cursor: pointer;">🔄 Refresh Check</button>';
            echo '<a href="https://github.com/DooOffc-Dev/-DooStore-Digital" class="btn btn-secondary">📚 Dokumentasi</a>';
        }
        echo '</div>';
        
        echo '</div>';
        ?>
    </div>
</body>
</html>
