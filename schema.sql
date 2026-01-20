-- Smart Chemical Inventory Management Portal
-- MySQL schema (ready for XAMPP / phpMyAdmin)

DROP DATABASE IF EXISTS smart_chemical_inventory;
CREATE DATABASE smart_chemical_inventory CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smart_chemical_inventory;

-- Users table for authentication & roles
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'guest') NOT NULL DEFAULT 'guest'
) ENGINE=InnoDB;

-- Chemicals table (Module 1: Chemical Stock Logging)
CREATE TABLE chemicals (
    chemical_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    quantity VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Expiry & Safety table (Module 2: Expiry & Safety Alerts)
CREATE TABLE chemical_safety (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chemical_id INT NOT NULL,
    expiry_date DATE NULL,
    safety_level ENUM('Low', 'Medium', 'High') NOT NULL DEFAULT 'Low',
    CONSTRAINT fk_safety_chemical
        FOREIGN KEY (chemical_id) REFERENCES chemicals(chemical_id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- Optional: Trigger example to enforce minimum safety level when expiry is in the past
DELIMITER $$
CREATE TRIGGER trg_expired_high_risk
BEFORE INSERT ON chemical_safety
FOR EACH ROW
BEGIN
    IF NEW.expiry_date IS NOT NULL AND NEW.expiry_date < CURDATE() THEN
        SET NEW.safety_level = 'High';
    END IF;
END$$
DELIMITER ;

-- Sample users (plain passwords for demo; can be changed to hashed + password_verify in PHP)
INSERT INTO users (username, password, role) VALUES
('admin', 'admin123', 'admin'),
('guest', 'guest123', 'guest');

-- Sample chemicals
INSERT INTO chemicals (name, quantity, location) VALUES
('Hydrochloric Acid 1M', '2 L', 'Shelf A1'),
('Sodium Hydroxide Pellets', '500 g', 'Cabinet B2'),
('Ethanol 99%', '5 L', 'Flammables Cabinet C1'),
('Acetone', '3 L', 'Flammables Cabinet C2');

-- Sample safety/expiry data
INSERT INTO chemical_safety (chemical_id, expiry_date, safety_level) VALUES
-- Expired high risk
(1, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'High'),
-- Expiring soon, medium risk
(2, DATE_ADD(CURDATE(), INTERVAL 10 DAY), 'Medium'),
-- Far expiry, low risk
(3, DATE_ADD(CURDATE(), INTERVAL 365 DAY), 'Low'),
-- High risk flammable
(4, DATE_ADD(CURDATE(), INTERVAL 200 DAY), 'High');