<?php
require_once __DIR__ . '/Database.php';

class InitDB {
    public static function setup() {
        if (isset($_SESSION['db_setup_done']) && $_SESSION['db_setup_done'] === true) {
            return; // Skip if already done in this session
        }

        try {
            $db = Database::getInstance()->getConnection();

            // Create Users Table
            $db->exec("CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                company_name VARCHAR(100) NOT NULL,
                phone VARCHAR(20) NOT NULL,
                password_hash VARCHAR(255) NULL,
                wallet_credits DECIMAL(10,2) DEFAULT 0.00,
                spins_available INT DEFAULT 0,
                role ENUM('admin', 'client') DEFAULT 'client',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY unique_phone (phone)
            )");

            try {
                $db->exec("ALTER TABLE users ADD COLUMN spins_available INT DEFAULT 0");
            } catch (Exception $e) {}

            // Create Services Table
            $db->exec("CREATE TABLE IF NOT EXISTS services (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(150) NOT NULL,
                description TEXT,
                category VARCHAR(50),
                icon_svg TEXT,
                price_estimate DECIMAL(10,2),
                is_active BOOLEAN DEFAULT TRUE
            )");

            // Create Orders Table
            $db->exec("CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_uid VARCHAR(20) NOT NULL UNIQUE,
                user_id INT NOT NULL,
                service_id INT NULL,
                service_name VARCHAR(150),
                status ENUM('Pending', 'Ongoing', 'Completed', 'Cancelled') DEFAULT 'Pending',
                total_amount DECIMAL(10,2) DEFAULT 0.00,
                whatsapp_thread_id VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");

            // Create Billing Table
            $db->exec("CREATE TABLE IF NOT EXISTS billing (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                type ENUM('Invoice', 'Quotation') NOT NULL,
                file_path VARCHAR(255),
                status ENUM('Paid', 'Unpaid') DEFAULT 'Unpaid',
                generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
            )");
            
            // Insert dummy services if empty
            $stmt = $db->query("SELECT COUNT(*) FROM services");
            if($stmt->fetchColumn() == 0) {
                $db->exec("INSERT INTO services (title, category) VALUES 
                    ('Web Development & ERP', 'Development'),
                    ('SEO & Traffic Growth', 'Marketing'),
                    ('Facebook Ads Campaign', 'Marketing'),
                    ('Branding & UI/UX', 'Design')
                ");
            }

            $_SESSION['db_setup_done'] = true;
        } catch (Exception $e) {
            // Ignore if tables can't be created here
        }
    }
}
