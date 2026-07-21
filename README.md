# 🚀 DooStore-Digital - API Provider Layanan Digital

**Platform API Provider untuk Menjual Layanan Digital dengan Mudah**

![Status](https://img.shields.io/badge/status-active-success)
![License](https://img.shields.io/badge/license-MIT-blue)
![PHP Version](https://img.shields.io/badge/php-%3E%3D7.4-blue)
![MySQL](https://img.shields.io/badge/mysql-%3E%3D5.7-blue)

## 📋 Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Kategori Layanan](#kategori-layanan)
- [Requirements](#requirements)
- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Penggunaan API](#penggunaan-api)
- [Struktur Folder](#struktur-folder)
- [Credentials Default](#credentials-default)
- [Troubleshooting](#troubleshooting)
- [Support](#support)

## ✨ Fitur Utama

✅ **Sistem Reseller** - Kelola multiple reseller dengan saldo terpisah
✅ **API Key Unik** - Setiap reseller mendapat API Key dalam format `ds-xxxxx`
✅ **Dashboard Lengkap** - Monitor order, saldo, dan history transaksi
✅ **Admin Panel** - Manajemen layanan, order, dan reseller
✅ **Top-up Otomatis** - Sistem pembayaran terintegrasi
✅ **8 Kategori Layanan** - Pulsa, Data, Topup Game, E-Wallet, dan lainnya
✅ **REST API** - Mudah diintegrasikan ke aplikasi apapun
✅ **Responsive Design** - Bekerja sempurna di desktop & mobile
✅ **Security** - Password hashing, SQL injection prevention, input validation
✅ **Logging** - Track semua API requests dan transaksi

## 📦 Kategori Layanan

1. **Nokos Virtual** - Nomor telepon virtual untuk verifikasi
2. **APK Premium** - Spotify, Netflix, Canva Pro, dan aplikasi premium lainnya
3. **Topup Game** - Mobile Legends, PUBG, Free Fire, Genshin Impact, dll
4. **Pulsa** - Semua operator (Telkomsel, XL, Indosat, Tri, Smart, Axis)
5. **Paket Data** - Internet 4G/5G berbagai provider (unlimited, regular)
6. **E-Wallet** - GoPay, OVO, Dana, LinkAja, Jenius
7. **Voucher Digital** - Spotify, Netflix, Canva, Adobe, Disney+
8. **Jasa Sosmed** - Followers, Likes, Views (TikTok, Instagram, YouTube)

## 🔧 Requirements

- **PHP** >= 7.4
- **MySQL** >= 5.7 atau MariaDB 10.3+
- **Apache/Nginx** dengan support mod_rewrite
- **Composer** (opsional)
- **Git** untuk version control

## 📥 Instalasi

### Step 1: Clone Repository

```bash
git clone https://github.com/DooOffc-Dev/-DooStore-Digital.git
cd -DooStore-Digital
```

### Step 2: Setup Database

**Opsi A: Menggunakan MySQL CLI**

```bash
mysql -u root -p < install.sql
```

**Opsi B: Menggunakan phpMyAdmin**

1. Buka phpMyAdmin (`http://localhost/phpmyadmin`)
2. Klik "New" untuk membuat database baru
3. Beri nama: `doostore_digital`
4. Klik "Import" tab
5. Upload file `install.sql`
6. Klik "Go" atau "Execute"

**Opsi C: Menggunakan MySQL Workbench**

1. Buka MySQL Workbench
2. Koneksi ke server MySQL Anda
3. File → Open SQL Script → pilih `install.sql`
4. Execute (Ctrl + Shift + Enter)

### Step 3: Konfigurasi File

Edit file `config.php` dan sesuaikan dengan server Anda:

```php
<?php
define('DB_HOST', 'localhost');      // Host database (default: localhost)
define('DB_USER', 'root');           // Username database (default: root)
define('DB_PASS', '');               // Password database (kosongkan jika tidak ada)
define('DB_NAME', 'doostore_digital'); // Nama database (sesuaikan dengan yang dibuat)
define('SITE_URL', 'http://localhost/doostore-digital'); // URL website Anda
define('TIMEZONE', 'Asia/Jakarta');  // Timezone
?>
```

**Contoh untuk production:**

```php
define('DB_HOST', '192.168.1.100');      // Server MySQL terpisah
define('DB_USER', 'doostore_user');      // User khusus
define('DB_PASS', 'securePassword123');  // Password yang kuat
define('DB_NAME', 'doostore_production'); // Database production
define('SITE_URL', 'https://doostore-digital.com'); // Domain production
```

### Step 4: Setup Folder Permissions (Linux/Mac)

```bash
# Set folder permissions
chmod 755 .
chmod 755 uploads/  # jika ada

# Set file permissions
chmod 644 *.php
chmod 644 style.css
chmod 644 *.sql

# If running with Apache
sudo chown -R www-data:www-data .
```

### Step 5: Verifikasi Setup

Buka browser dan akses:

```
http://localhost/doostore-digital/setup.php
```

Green checkmarks ✅ berarti semua siap!

### Step 6: Akses Website

```
http://localhost/doostore-digital
```

## ⚙️ Konfigurasi Lanjutan

### Apache (.htaccess)

Buat file `.htaccess` di root directory:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /doostore-digital/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
</IfModule>

# Disable directory listing
<IfModule mod_autoindex.c>
    Options -Indexes
</IfModule>
```

### Nginx

Tambahkan ke nginx.conf:

```nginx
server {
    listen 80;
    server_name doostore-digital.local;
    root /var/www/html/-DooStore-Digital;
    index index.php;

    location / {
        try_files $uri $uri/ =404;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Database Backup

```bash
# Backup full database
mysqldump -u root -p doostore_digital > backup_$(date +%Y%m%d_%H%M%S).sql

# Backup specific table
mysqldump -u root -p doostore_digital users > users_backup.sql
```

### Database Restore

```bash
mysql -u root -p doostore_digital < backup.sql
```

## 💡 Penggunaan API

### Authentication

Semua request API memerlukan `api_key` yang didapat dari Dashboard Reseller.

### Base URL

```
https://doostore-digital.com/api.php
```

### 1️⃣ Cek Saldo

**Endpoint:** `GET /api.php`

**Parameters:**
- `action=balance` (required)
- `api_key=YOUR_API_KEY` (required)

**URL Example:**
```
https://doostore-digital.com/api.php?action=balance&api_key=ds-abc123xyz789
```

**cURL Example:**
```bash
curl -X GET "https://doostore-digital.com/api.php?action=balance&api_key=ds-abc123xyz789"
```

**Response:**
```json
{
  "status": "success",
  "balance": 500000,
  "currency": "IDR"
}
```

### 2️⃣ List Layanan

**Endpoint:** `GET /api.php`

**Parameters:**
- `action=services` (required)
- `api_key=YOUR_API_KEY` (required)
- `category=Pulsa` (optional - untuk filter kategori)

**URL Example:**
```
https://doostore-digital.com/api.php?action=services&api_key=ds-abc123xyz789&category=Pulsa
```

**Kategori Filter:**
- Nokos Virtual
- APK Premium
- Topup Game
- Pulsa
- Paket Data
- E-Wallet
- Voucher Digital
- Jasa Sosmed

**Response:**
```json
{
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
    {
      "id": 2,
      "name": "Pulsa XL Axiata 10rb",
      "category": "Pulsa",
      "price": 10500,
      "min_qty": 1,
      "max_qty": 100,
      "description": "Pulsa XL Axiata nominal 10rb"
    }
  ]
}
```

### 3️⃣ Buat Order

**Endpoint:** `POST /api.php`

**Parameters:**
- `action=order` (required)
- `api_key=YOUR_API_KEY` (required)
- `service_id=1` (required)
- `target=081234567890` (required)
- `quantity=1` (required)

**cURL Example:**
```bash
curl -X POST "https://doostore-digital.com/api.php" \
  -d "action=order" \
  -d "api_key=ds-abc123xyz789" \
  -d "service_id=1" \
  -d "target=081234567890" \
  -d "quantity=1"
```

**JavaScript Example:**
```javascript
const formData = new FormData();
formData.append('action', 'order');
formData.append('api_key', 'ds-abc123xyz789');
formData.append('service_id', 1);
formData.append('target', '081234567890');
formData.append('quantity', 1);

fetch('https://doostore-digital.com/api.php', {
  method: 'POST',
  body: formData
})
.then(res => res.json())
.then(data => console.log(data));
```

**Python Example:**
```python
import requests

url = 'https://doostore-digital.com/api.php'
data = {
    'action': 'order',
    'api_key': 'ds-abc123xyz789',
    'service_id': 1,
    'target': '081234567890',
    'quantity': 1
}

response = requests.post(url, data=data)
print(response.json())
```

**Response Success:**
```json
{
  "status": "success",
  "message": "Order berhasil dibuat",
  "order_id": 123,
  "order_status": "completed",
  "remaining_balance": 489000
}
```

**Response Error (Balance Insufficient):**
```json
{
  "status": "error",
  "message": "Saldo tidak cukup",
  "required": 11000,
  "balance": 5000
}
```

### 4️⃣ Cek Status Order

**Endpoint:** `GET /api.php`

**Parameters:**
- `action=status` (required)
- `api_key=YOUR_API_KEY` (required)
- `order_id=123` (required)

**URL Example:**
```
https://doostore-digital.com/api.php?action=status&api_key=ds-abc123xyz789&order_id=123
```

**Response:**
```json
{
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
}
```

## 📁 Struktur Folder

```
-DooStore-Digital/
├── index.php              # Homepage
├── login.php              # Halaman login
├── register.php           # Halaman registrasi
├── dashboard.php          # Dashboard reseller
├── admin.php              # Admin panel
├── api.php                # REST API
├── topup.php              # Halaman top-up
├── payment.php            # Halaman pembayaran
├── docs.php               # Dokumentasi API
├── logout.php             # Logout
├── setup.php              # Setup checker
├── config.php             # Konfigurasi database
├── style.css              # CSS styling
├── install.sql            # Database schema
├── README.md              # Dokumentasi (file ini)
├── .gitignore             # Git ignore
└── .htaccess              # Apache rewrite rules
```

## 🔐 Credentials Default

Setelah instalasi berhasil, gunakan akun default berikut:

**Admin Account:**
```
Username: admin
Password: admin123
API Key: ds-admin2024secret
Role: Admin
```

⚠️ **PENTING:** Ubah password admin segera setelah login pertama kali!

**Test Reseller Account:**
```
Username: testuser
Password: test123456
API Key: ds-test2024demo
Role: Reseller
Initial Balance: Rp 100.000
```

## 💾 Database Schema

### Table: users
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    saldo DECIMAL(15, 2) DEFAULT 0.00,
    api_key VARCHAR(50) UNIQUE NOT NULL,
    role ENUM('admin', 'reseller') DEFAULT 'reseller',
    status ENUM('active', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Table: services
Menyimpan data layanan digital (Pulsa, Data, Topup Game, dll)

### Table: orders
Menyimpan semua order/transaksi yang dibuat melalui API

### Table: topups
Menyimpan history top-up saldo reseller

### Table: invoices
Menyimpan detail invoice pembayaran

### Table: api_logs
Menyimpan log semua API requests untuk audit trail

## 🛡️ Security Tips

1. **Ubah Password Admin**
   ```
   Segera setelah instalasi, login dan ubah password admin
   ```

2. **Gunakan HTTPS**
   ```
   Di production, selalu gunakan SSL/TLS certificate
   ```

3. **API Rate Limiting**
   ```php
   // Batasi request API per IP
   // Implementasi di config.php atau api.php
   ```

4. **Input Validation**
   ```
   Semua input sudah di-sanitize dengan sanitize() function
   ```

5. **SQL Injection Prevention**
   ```
   Menggunakan Prepared Statements di semua query
   ```

6. **XSS Prevention**
   ```
   Menggunakan htmlspecialchars() dan htmlentities()
   ```

7. **File Upload Security**
   ```
   Validasi file type, size, dan permissions
   ```

8. **Environment Variables**
   ```bash
   Gunakan .env file untuk sensitive data (opsional)
   ```

## 🚀 Deployment ke Production

### Pre-deployment Checklist

- [ ] Update semua credentials (database, admin password)
- [ ] Ganti SITE_URL dengan domain production
- [ ] Setup HTTPS/SSL certificate
- [ ] Set `display_errors = Off` di php.ini
- [ ] Enable firewall dan security headers
- [ ] Setup automated database backups
- [ ] Configure email notifications
- [ ] Setup monitoring dan alerting
- [ ] Enable gzip compression
- [ ] Optimize database indexes

### Recommended Hosting

**Minimum Requirements:**
- PHP 7.4+
- MySQL 5.7+
- 256MB RAM
- 1GB Storage

**Recommended:**
- PHP 8.0+
- MySQL 8.0 atau MariaDB 10.5+
- 512MB RAM
- 5GB Storage
- SSD storage
- Daily automated backups

### Upload ke Server

```bash
# FTP/SFTP
sftp user@host.com
put -r -DooStore-Digital/ /home/user/public_html/

# atau menggunakan Git
cd /home/user/public_html/
git clone https://github.com/DooOffc-Dev/-DooStore-Digital.git
cd -DooStore-Digital
```

## 🐛 Troubleshooting

### Database Connection Error

**Error Message:**
```
Connection failed: Unknown database 'doostore_digital'
```

**Solutions:**
1. Pastikan MySQL server running: `sudo service mysql status`
2. Jalankan install.sql: `mysql -u root -p < install.sql`
3. Verifikasi credentials di config.php
4. Check MySQL user permissions

### 404 Not Found

**Error Message:**
```
The requested URL /api.php was not found on this server
```

**Solutions:**
1. Pastikan semua file PHP ada di folder
2. Check SITE_URL di config.php
3. Enable mod_rewrite: `a2enmod rewrite`
4. Restart Apache: `sudo service apache2 restart`

### API Key Invalid

**Error Message:**
```json
{"status": "error", "message": "API Key tidak valid"}
```

**Solutions:**
1. Copy API Key dari dashboard (jangan edit manual)
2. Pastikan user account aktif
3. Check database users table
4. Verify API key format (harus mulai dengan `ds-`)

### Session Not Working

**Error Message:**
```
Login page redirect loop
```

**Solutions:**
1. Check folder permissions: `chmod 755 .`
2. Verify PHP session path: `php -i | grep session.save_path`
3. Clear browser cookies
4. Check PHP error log: `tail -f /var/log/php-errors.log`

### Slow API Response

**Solutions:**
1. Optimize database queries dengan indexes
2. Enable caching (Redis/Memcached)
3. Implement API rate limiting
4. Check server resources
5. Optimize PHP configuration

## 📞 Support & Help

- 📧 **Email:** support@doostore.local
- 💬 **Chat:** Available di website
- 🐛 **Bug Report:** GitHub Issues
- 💡 **Feature Request:** GitHub Discussions
- 📚 **Documentation:** `/docs.php`

## 📄 License

MIT License - Bebas digunakan untuk keperluan komersial maupun personal

Untuk detail lihat file LICENSE

## 👨‍💻 Author

**DooOfficial**
- GitHub: [@DooOffc-Dev](https://github.com/DooOffc-Dev)
- Website: [DooStore-Digital](https://github.com/DooOffc-Dev/-DooStore-Digital)
- Email: saparipari144@gmail.com

## 🙏 Kontribusi

Saya sangat menghargai kontribusi dari komunitas! Silakan:

1. Fork repository ini
2. Buat branch baru untuk fitur Anda (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan Anda (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## ⭐ Jika Proyek Ini Bermanfaat

Jika Anda merasa project ini membantu, silakan berikan bintang ⭐ di GitHub!

Setiap bintang memotivasi kami untuk terus mengembangkan fitur baru.

---

**Made with ❤️ by DooOfficial**

**Version:** 1.0.0  
**Last Updated:** 21 Juli 2024  
**Status:** Active & Maintained ✅
