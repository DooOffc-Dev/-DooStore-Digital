<?php
include 'config.php';

if (!isLoggedIn()) {
    redirect('login.php', 'Silakan login terlebih dahulu', 'warning');
}

$role = $_SESSION['role'];

// Only admin can access
if ($role !== 'admin') {
    redirect('dashboard.php', 'Akses ditolak! Halaman ini hanya untuk admin.', 'danger');
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Handle CRUD Services
$action = $_GET['action'] ?? '';

// Add Service
if ($_POST['form_type'] ?? '' === 'add_service') {
    $name = sanitize($_POST['name'] ?? '');
    $category = sanitize($_POST['category'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $min_qty = (int)($_POST['min_qty'] ?? 1);
    $max_qty = (int)($_POST['max_qty'] ?? 1000);
    $description = sanitize($_POST['description'] ?? '');
    $status = 'active';

    if (empty($name) || empty($category) || $price <= 0) {
        $_SESSION['message'] = '❌ Silakan isi semua field dengan benar!';
        $_SESSION['message_type'] = 'danger';
    } else {
        $stmt = $conn->prepare("INSERT INTO services (name, category, price, min_qty, max_qty, description, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssddiss", $name, $category, $price, $min_qty, $max_qty, $description, $status);
        if ($stmt->execute()) {
            $_SESSION['message'] = '✅ Layanan berhasil ditambahkan!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '❌ Gagal menambahkan layanan!';
            $_SESSION['message_type'] = 'danger';
        }
    }
    header('Location: admin.php');
    exit;
}

// Update Service
if ($_POST['form_type'] ?? '' === 'edit_service') {
    $id = (int)($_POST['id'] ?? 0);
    $name = sanitize($_POST['name'] ?? '');
    $category = sanitize($_POST['category'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $min_qty = (int)($_POST['min_qty'] ?? 1);
    $max_qty = (int)($_POST['max_qty'] ?? 1000);
    $description = sanitize($_POST['description'] ?? '');
    $status = sanitize($_POST['status'] ?? 'active');

    if (empty($id) || empty($name)) {
        $_SESSION['message'] = '❌ Data tidak lengkap!';
        $_SESSION['message_type'] = 'danger';
    } else {
        $stmt = $conn->prepare("UPDATE services SET name = ?, category = ?, price = ?, min_qty = ?, max_qty = ?, description = ?, status = ? WHERE id = ?");
        $stmt->bind_param("ssddiisi", $name, $category, $price, $min_qty, $max_qty, $description, $status, $id);
        if ($stmt->execute()) {
            $_SESSION['message'] = '✅ Layanan berhasil diperbarui!';
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = '❌ Gagal memperbarui layanan!';
            $_SESSION['message_type'] = 'danger';
        }
    }
    header('Location: admin.php');
    exit;
}

// Delete Service
if ($action === 'delete' && $_GET['id'] ?? '') {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = '✅ Layanan berhasil dihapus!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = '❌ Gagal menghapus layanan!';
        $_SESSION['message_type'] = 'danger';
    }
    header('Location: admin.php');
    exit;
}

// Get all services
$stmt = $conn->prepare("SELECT * FROM services ORDER BY category, name");
$stmt->execute();
$services = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get all orders
$stmt = $conn->prepare("
    SELECT o.id, o.user_id, u.username, s.name, o.quantity, o.total, o.status, o.created_at
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN services s ON o.service_id = s.id
    ORDER BY o.created_at DESC
    LIMIT 20
");
$stmt->execute();
$orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get all users
$stmt = $conn->prepare("SELECT id, username, email, saldo, role, created_at FROM users ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Get statistics
$stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE role = 'reseller'");
$stmt->execute();
$total_resellers = $stmt->get_result()->fetch_assoc()['total'];

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM orders");
$stmt->execute();
$total_orders = $stmt->get_result()->fetch_assoc()['total'];

$stmt = $conn->prepare("SELECT SUM(total) as total FROM orders WHERE status = 'completed'");
$stmt->execute();
$revenue = $stmt->get_result()->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - DooStore-Digital</title>
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
                <span style="color: #d4af37; font-weight: bold;">👤 Admin - <?php echo $username; ?></span>
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <a href="logout.php" class="nav-link" style="color: #e74c3c;">Logout</a>
            </div>
        </div>
    </nav>

    <!-- ADMIN HEADER -->
    <div class="dashboard-header">
        <h1>⚙️ Admin Panel</h1>
        <p style="color: #b0b0b0; margin-top: 10px;">Kelola sistem DooStore-Digital</p>
    </div>

    <!-- STATISTICS -->
    <div class="container section">
        <h2 style="color: #d4af37; margin-bottom: 20px;">📊 Statistik</h2>
        <div class="row col-3">
            <div class="stat-box">
                <h3>👥 Total Reseller</h3>
                <div class="value"><?php echo $total_resellers; ?></div>
            </div>
            <div class="stat-box">
                <h3>📦 Total Order</h3>
                <div class="value"><?php echo $total_orders; ?></div>
            </div>
            <div class="stat-box">
                <h3>💹 Total Revenue</h3>
                <div class="value"><?php echo formatCurrency($revenue); ?></div>
            </div>
        </div>
    </div>

    <!-- MANAGE SERVICES -->
    <div class="container section">
        <div class="card">
            <div class="card-header">🛍️ Manajemen Layanan</div>
            <div class="card-body">
                <button onclick="toggleModal('addServiceModal')" class="btn btn-primary" style="margin-bottom: 20px;">
                    ➕ Tambah Layanan Baru
                </button>

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Min-Max Qty</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?php echo $service['id']; ?></td>
                                <td><?php echo $service['name']; ?></td>
                                <td><?php echo $service['category']; ?></td>
                                <td><?php echo formatCurrency($service['price']); ?></td>
                                <td><?php echo $service['min_qty'] . ' - ' . $service['max_qty']; ?></td>
                                <td>
                                    <span class="badge <?php echo $service['status'] === 'active' ? 'badge-success' : 'badge-danger'; ?>">
                                        <?php echo ucfirst($service['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button onclick="editService(<?php echo htmlspecialchars(json_encode($service)); ?>)" class="btn btn-info btn-sm" style="padding: 5px 10px; font-size: 11px;">
                                        ✏️ Edit
                                    </button>
                                    <a href="admin.php?action=delete&id=<?php echo $service['id']; ?>" class="btn btn-danger btn-sm" style="padding: 5px 10px; font-size: 11px; margin-left: 5px;" onclick="return confirm('Yakin ingin menghapus?')">
                                        🗑️ Hapus
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RECENT ORDERS -->
    <div class="container section">
        <div class="card">
            <div class="card-header">📋 Order Terbaru</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Reseller</th>
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
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo $order['username']; ?></td>
                                <td><?php echo $order['name']; ?></td>
                                <td><?php echo $order['quantity']; ?></td>
                                <td><?php echo formatCurrency($order['total']); ?></td>
                                <td>
                                    <span class="badge <?php echo $order['status'] === 'completed' ? 'badge-success' : ($order['status'] === 'failed' ? 'badge-danger' : 'badge-warning'); ?>">
                                        <?php echo ucfirst($order['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- USERS LIST -->
    <div class="container section">
        <div class="card">
            <div class="card-header">👥 Daftar Reseller</div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Saldo</th>
                            <th>Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <?php if ($user['role'] === 'reseller'): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo $user['username']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo formatCurrency($user['saldo']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ADD SERVICE MODAL -->
    <div id="addServiceModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>➕ Tambah Layanan Baru</h2>
                <button type="button" class="modal-close" onclick="toggleModal('addServiceModal')">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="form_type" value="add_service">
                
                <div class="form-group">
                    <label>Nama Layanan</label>
                    <input type="text" name="name" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category" required>
                        <option value="">- Pilih Kategori -</option>
                        <option value="Nokos Virtual">Nokos Virtual</option>
                        <option value="APK Premium">APK Premium</option>
                        <option value="Topup Game">Topup Game</option>
                        <option value="Pulsa">Pulsa</option>
                        <option value="Paket Data">Paket Data</option>
                        <option value="E-Wallet">E-Wallet</option>
                        <option value="Voucher Digital">Voucher Digital</option>
                        <option value="Jasa Sosmed">Jasa Sosmed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" step="0.01" required>
                </div>

                <div class="form-group">
                    <label>Min Quantity</label>
                    <input type="number" name="min_qty" value="1" required>
                </div>

                <div class="form-group">
                    <label>Max Quantity</label>
                    <input type="number" name="max_qty" value="1000" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding: 12px;">
                    ✅ Tambah Layanan
                </button>
            </form>
        </div>
    </div>

    <!-- EDIT SERVICE MODAL -->
    <div id="editServiceModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>✏️ Edit Layanan</h2>
                <button type="button" class="modal-close" onclick="toggleModal('editServiceModal')">&times;</button>
            </div>
            <form method="POST">
                <input type="hidden" name="form_type" value="edit_service">
                <input type="hidden" name="id" id="editServiceId">
                
                <div class="form-group">
                    <label>Nama Layanan</label>
                    <input type="text" name="name" id="editServiceName" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category" id="editServiceCategory" required>
                        <option value="Nokos Virtual">Nokos Virtual</option>
                        <option value="APK Premium">APK Premium</option>
                        <option value="Topup Game">Topup Game</option>
                        <option value="Pulsa">Pulsa</option>
                        <option value="Paket Data">Paket Data</option>
                        <option value="E-Wallet">E-Wallet</option>
                        <option value="Voucher Digital">Voucher Digital</option>
                        <option value="Jasa Sosmed">Jasa Sosmed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="price" id="editServicePrice" step="0.01" required>
                </div>

                <div class="form-group">
                    <label>Min Quantity</label>
                    <input type="number" name="min_qty" id="editServiceMinQty" required>
                </div>

                <div class="form-group">
                    <label>Max Quantity</label>
                    <input type="number" name="max_qty" id="editServiceMaxQty" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="description" id="editServiceDescription" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="editServiceStatus" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-block" style="padding: 12px;">
                    💾 Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <p style="margin-bottom: 10px;">&copy; 2024 <strong>DooStore-Digital</strong> - All Rights Reserved</p>
        </div>
    </footer>

    <script>
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('show');
        }

        function editService(service) {
            document.getElementById('editServiceId').value = service.id;
            document.getElementById('editServiceName').value = service.name;
            document.getElementById('editServiceCategory').value = service.category;
            document.getElementById('editServicePrice').value = service.price;
            document.getElementById('editServiceMinQty').value = service.min_qty;
            document.getElementById('editServiceMaxQty').value = service.max_qty;
            document.getElementById('editServiceDescription').value = service.description;
            document.getElementById('editServiceStatus').value = service.status;
            toggleModal('editServiceModal');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const addModal = document.getElementById('addServiceModal');
            const editModal = document.getElementById('editServiceModal');
            if (event.target === addModal) toggleModal('addServiceModal');
            if (event.target === editModal) toggleModal('editServiceModal');
        }
    </script>
</body>
</html>
