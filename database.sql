-- ============================================================
--  Sistem Booking Lapangan Olahraga
--  Database: booking_lapangan_db
-- ============================================================

CREATE DATABASE IF NOT EXISTS booking_lapangan_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE booking_lapangan_db;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS lapangan;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- -------------------------------------------------------
-- Tabel users
-- -------------------------------------------------------
CREATE TABLE users (
    id         INT PRIMARY KEY AUTO_INCREMENT,
    name       VARCHAR(100)  NOT NULL,
    email      VARCHAR(150)  UNIQUE NOT NULL,
    password   VARCHAR(255)  NOT NULL,
    phone      VARCHAR(20)   DEFAULT NULL,
    role       ENUM('customer','admin') DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel categories
-- -------------------------------------------------------
CREATE TABLE categories (
    id   INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(50)  DEFAULT NULL
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel lapangan
-- -------------------------------------------------------
CREATE TABLE lapangan (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    category_id    INT,
    name           VARCHAR(150)   NOT NULL,
    description    TEXT,
    price_per_hour DECIMAL(10,2)  NOT NULL,
    location       VARCHAR(200)   DEFAULT NULL,
    image          VARCHAR(255)   DEFAULT NULL,
    is_available   TINYINT(1)     DEFAULT 1,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel bookings
-- -------------------------------------------------------
CREATE TABLE bookings (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    user_id        INT NOT NULL,
    lapangan_id    INT NOT NULL,
    booking_date   DATE         NOT NULL,
    start_time     TIME         NOT NULL,
    end_time       TIME         NOT NULL,
    duration_hours DECIMAL(4,1) NOT NULL,
    total_price    DECIMAL(10,2) NOT NULL,
    status         ENUM('pending','confirmed','in_progress','completed','cancelled') DEFAULT 'pending',
    notes          TEXT         DEFAULT NULL,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id)     REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (lapangan_id) REFERENCES lapangan(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel payments
-- -------------------------------------------------------
CREATE TABLE payments (
    id             INT PRIMARY KEY AUTO_INCREMENT,
    booking_id     INT         NOT NULL UNIQUE,
    proof_image    VARCHAR(255) DEFAULT NULL,
    payment_method VARCHAR(50)  DEFAULT NULL,
    status         ENUM('pending','verified','rejected') DEFAULT 'pending',
    verified_at    TIMESTAMP   NULL DEFAULT NULL,
    verified_by    INT         NULL DEFAULT NULL,
    notes          TEXT        DEFAULT NULL,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id)  REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- -------------------------------------------------------
-- Tabel reviews
-- -------------------------------------------------------
CREATE TABLE reviews (
    id          INT PRIMARY KEY AUTO_INCREMENT,
    booking_id  INT NOT NULL UNIQUE,
    user_id     INT NOT NULL,
    lapangan_id INT NOT NULL,
    rating      TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment     TEXT    DEFAULT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id)  REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id)     REFERENCES users(id)    ON DELETE CASCADE,
    FOREIGN KEY (lapangan_id) REFERENCES lapangan(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ============================================================
-- SEED DATA
-- ============================================================

-- Admin: password = admin123
INSERT INTO users (name, email, password, phone, role) VALUES
('Administrator', 'admin@sportfield.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081234567890', 'admin'),
('Budi Santoso',  'budi@email.com',       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '081298765432', 'customer'),
('Siti Rahayu',   'siti@email.com',       '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '087812345678', 'customer');
-- password semua user: password

-- Categories
INSERT INTO categories (name, icon) VALUES
('Futsal',    'futbol'),
('Badminton', 'badminton'),
('Basket',    'basket'),
('Tennis',    'tennis');

-- Lapangan
INSERT INTO lapangan (category_id, name, description, price_per_hour, location, is_available) VALUES
(1, 'Lapangan Futsal A', 'Lapangan futsal premium dengan rumput sintetis berkualitas tinggi, cocok untuk pertandingan resmi maupun latihan rutin.', 100000, 'Gedung Sport Hall Lt.1', 1),
(1, 'Lapangan Futsal B', 'Lapangan futsal standar dengan pencahayaan LED, tersedia untuk sewa malam hari hingga pukul 22.00.', 85000, 'Gedung Sport Hall Lt.1', 1),
(2, 'Lapangan Badminton 1', 'Lapangan badminton indoor ber-AC dengan lantai kayu parket, menggunakan net standar BWF.', 60000, 'Gedung Badminton Hall', 1),
(2, 'Lapangan Badminton 2', 'Lapangan badminton indoor, cocok untuk latihan maupun kompetisi antar klub.', 60000, 'Gedung Badminton Hall', 1),
(3, 'Lapangan Basket Full', 'Lapangan basket ukuran penuh dengan papan ring standar NBL, lantai kayu maple.', 150000, 'Lapangan Terbuka Area A', 1),
(4, 'Lapangan Tennis', 'Lapangan tennis outdoor dengan permukaan hard court, dilengkapi lampu sorot untuk malam hari.', 120000, 'Area Outdoor Blok B', 1);

-- Sample bookings (status completed untuk testing review)
INSERT INTO bookings (user_id, lapangan_id, booking_date, start_time, end_time, duration_hours, total_price, status) VALUES
(2, 1, DATE_SUB(CURDATE(), INTERVAL 3 DAY), '08:00:00', '10:00:00', 2.0, 200000, 'completed'),
(2, 3, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '14:00:00', '16:00:00', 2.0, 120000, 'confirmed'),
(3, 2, CURDATE(), '19:00:00', '21:00:00', 2.0, 170000, 'pending');

-- Sample payments
INSERT INTO payments (booking_id, payment_method, status, verified_at, verified_by) VALUES
(1, 'Transfer Bank', 'verified', NOW(), 1),
(2, 'QRIS', 'verified', NOW(), 1),
(3, 'Transfer Bank', 'pending', NULL, NULL);

-- Sample review
INSERT INTO reviews (booking_id, user_id, lapangan_id, rating, comment) VALUES
(1, 2, 1, 5, 'Lapangan sangat bersih dan nyaman, petugas ramah. Akan booking lagi!');
