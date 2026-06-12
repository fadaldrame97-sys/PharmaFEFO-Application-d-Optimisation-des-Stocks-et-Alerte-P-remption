-- PharmaFEFO - Database Setup
-- Execute this file to create the database and tables.
--
-- Usage (terminal):    mysql -u root < sql/setup.sql
-- Usage (phpMyAdmin):  Import this file via the Import tab

CREATE DATABASE IF NOT EXISTS PharmaFEFO
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE PharmaFEFO;

-- -------------------------------------------------------
-- Users
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Products
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Stock Batches (lots)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS stock_batches (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    lot_number VARCHAR(100) NOT NULL,
    quantity INT NOT NULL DEFAULT 0,
    expiration_date DATE NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'AVAILABLE',
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Stock State History (etat_stock)
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS etat_stock (
    id INT AUTO_INCREMENT PRIMARY KEY,
    batch_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    checked_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_id) REFERENCES stock_batches(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Test Data
-- -------------------------------------------------------

-- Users (passwords: admin123, pharma123, prep123)
INSERT INTO users (email, password, role) VALUES
    ('admin@pharma.com', '$2y$10$FBs4BMhNxVzrt972UA.Y0uqVz.cA5bE1IajNninQG4mFNEU2jqQEe', 'ADMIN'),
    ('pharmacien@pharma.com', '$2y$10$Fis3XOqJoMqQbInl.GiShOQf3eNtn6QeTg3uGYtY2OMnGkC6nX3va', 'PHARMACIEN'),
    ('preparateur@pharma.com', '$2y$10$1r1xXh8CI9SDzHih3VrdeuvHK0gwdRgZoA6DFm1BwlHIlcuvXv/Hi', 'PREPARATEUR')
ON DUPLICATE KEY UPDATE email=email;

-- Products
INSERT INTO products (name, code, description) VALUES
    ('Paracetamol 500mg', 'PARA500', 'Analgesique et antipyretique'),
    ('Amoxicilline 1g', 'AMOX1G', 'Antibiotique penicilline'),
    ('Ibuprofene 400mg', 'IBU400', 'Anti-inflammatoire non steroidien')
ON DUPLICATE KEY UPDATE code=code;

-- Stock batches (mix of expiring, expired, and OK)
INSERT INTO stock_batches (product_id, lot_number, quantity, expiration_date, status) VALUES
    (1, 'LOT-PARA-001', 100, DATE_ADD(CURDATE(), INTERVAL 15 DAY), 'AVAILABLE'),
    (1, 'LOT-PARA-002', 50, DATE_ADD(CURDATE(), INTERVAL 60 DAY), 'AVAILABLE'),
    (2, 'LOT-AMOX-001', 30, DATE_ADD(CURDATE(), INTERVAL 5 DAY), 'AVAILABLE'),
    (2, 'LOT-AMOX-002', 200, DATE_ADD(CURDATE(), INTERVAL 120 DAY), 'AVAILABLE'),
    (3, 'LOT-IBU-001', 0, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 'EXPIRED'),
    (3, 'LOT-IBU-002', 75, DATE_ADD(CURDATE(), INTERVAL 45 DAY), 'AVAILABLE');
