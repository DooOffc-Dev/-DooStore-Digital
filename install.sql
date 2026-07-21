-- DooStore-Digital Database Schema
-- Database untuk sistem API Provider layanan digital

CREATE DATABASE IF NOT EXISTS doostore_digital;
USE doostore_digital;

-- ===== USERS TABLE =====
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_api_key (api_key),
    INDEX idx_role (role)
);

-- ===== SERVICES TABLE =====
CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    min_qty INT DEFAULT 1,
    max_qty INT DEFAULT 1000,
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_status (status)
);

-- ===== ORDERS TABLE =====
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    target VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    total DECIMAL(15, 2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
);

-- ===== TOPUPS TABLE =====
CREATE TABLE topups (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    payment_method VARCHAR(50),
    status ENUM('pending', 'paid', 'expired') DEFAULT 'pending',
    invoice_id VARCHAR(100) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    paid_at TIMESTAMP NULL,
    expired_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_invoice_id (invoice_id)
);

-- ===== INVOICES TABLE =====
CREATE TABLE invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    invoice_number VARCHAR(100) UNIQUE NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    payment_method VARCHAR(50),
    status ENUM('pending', 'paid', 'expired') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    paid_at TIMESTAMP NULL,
    expired_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_status (status),
    INDEX idx_invoice_number (invoice_number)
);

-- ===== API LOGS TABLE =====
CREATE TABLE api_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100),
    status VARCHAR(50),
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at)
);

-- ===== INSERT DEFAULT ADMIN USER =====
-- Username: admin | Password: admin123
INSERT INTO users (username, password, email, saldo, api_key, role) 
VALUES ('admin', '$2y$10$YIjlrBJWezQbNXe.3P8vdOPmFxRMYV6v9LdDmI0xHQ7RvzxnC2Svy', 'admin@doostore.local', 9999999.99, 'ds-admin2024secret', 'admin');

-- ===== INSERT DEFAULT SERVICES =====
-- Nokos Virtual
INSERT INTO services (name, category, price, min_qty, max_qty, description, status) VALUES
('Nomor Virtual Singapura', 'Nokos Virtual', 5000, 1, 100, 'Nomor telepon virtual Singapura untuk verifikasi', 'active'),
('Nomor Virtual Indonesia', 'Nokos Virtual', 3000, 1, 100, 'Nomor telepon virtual Indonesia untuk verifikasi', 'active'),
('Nomor Virtual Malaysia', 'Nokos Virtual', 4000, 1, 100, 'Nomor telepon virtual Malaysia untuk verifikasi', 'active');

-- APK Premium
INSERT INTO services (name, category, price, min_qty, max_qty, description, status) VALUES
('Spotify Premium 1 Bulan', 'APK Premium', 15000, 1, 50, 'Akses Spotify Premium selama 1 bulan', 'active'),
('Netflix Premium 1 Bulan', 'APK Premium', 80000, 1, 50, 'Akses Netflix Premium selama 1 bulan', 'active'),
('Canva Pro 1 Bulan', 'APK Premium', 12000, 1, 50, 'Akses Canva Pro selama 1 bulan', 'active');

-- Topup Game
INSERT INTO services (name, category, price, min_qty, max_qty, description, status) VALUES
('Mobile Legends 100 Diamond', 'Topup Game', 15000, 1, 100, 'Top-up 100 Diamond Mobile Legends', 'active'),
('PUBG Mobile 300 UC', 'Topup Game', 30000, 1, 100, 'Top-up 300 UC PUBG Mobile', 'active'),
('Free Fire 100 Diamond', 'Topup Game', 10000, 1, 100, 'Top-up 100 Diamond Free Fire', 'active');

-- Pulsa
INSERT INTO services (name, category, price, min_qty, max_qty, description, status) VALUES
('Pulsa Telkomsel 10rb', 'Pulsa', 11000, 1, 100, 'Pulsa Telkomsel nominal 10rb', 'active'),
('Pulsa XL 20rb', 'Pulsa', 21000, 1, 100, 'Pulsa XL nominal 20rb', 'active'),
('Pulsa Indosat 10rb', 'Pulsa', 11000, 1, 100, 'Pulsa Indosat nominal 10rb', 'active'),
('Pulsa Tri 5rb', 'Pulsa', 5000, 1, 100, 'Pulsa Tri nominal 5rb', 'active');

-- Paket Data
INSERT INTO services (name, category, price, min_qty, max_qty, description, status) VALUES
('Paket Data Telkomsel 1GB', 'Paket Data', 15000, 1, 100, 'Paket internet Telkomsel 1GB', 'active'),
('Paket Data XL 5GB', 'Paket Data', 50000, 1, 100, 'Paket internet XL 5GB', 'active'),
('Paket Data Indosat 2GB', 'Paket Data', 25000, 1, 100, 'Paket internet Indosat 2GB', 'active');

-- E-Wallet
INSERT INTO services (name, category, price, min_qty, max_qty, description, status) VALUES
('Voucher GoPay 50rb', 'E-Wallet', 52000, 1, 100, 'Voucher GoPay nominal 50rb', 'active'),
('Voucher OVO 100rb', 'E-Wallet', 105000, 1, 100, 'Voucher OVO nominal 100rb', 'active'),
('Voucher Dana 50rb', 'E-Wallet', 52500, 1, 100, 'Voucher Dana nominal 50rb', 'active');

-- Voucher Digital
INSERT INTO services (name, category, price, min_qty, max_qty, description, status) VALUES
('Voucher Spotify 30 hari', 'Voucher Digital', 15000, 1, 50, 'Voucher Spotify 30 hari premium', 'active'),
('Voucher Netflix 1 Bulan', 'Voucher Digital', 80000, 1, 50, 'Voucher Netflix 1 bulan premium', 'active'),
('Voucher Canva Pro 1 Bulan', 'Voucher Digital', 12000, 1, 50, 'Voucher Canva Pro 1 bulan', 'active');

-- Jasa Sosmed
INSERT INTO services (name, category, price, min_qty, max_qty, description, status) VALUES
('TikTok Followers 100', 'Jasa Sosmed', 10000, 100, 10000, 'Tambah follower TikTok 100-10000', 'active'),
('Instagram Likes 1000', 'Jasa Sosmed', 15000, 1000, 100000, 'Tambah likes Instagram 1000-100000', 'active'),
('YouTube Views 1000', 'Jasa Sosmed', 20000, 1000, 100000, 'Tambah views YouTube 1000-100000', 'active'),
('Instagram Followers 100', 'Jasa Sosmed', 12000, 100, 10000, 'Tambah follower Instagram 100-10000', 'active');