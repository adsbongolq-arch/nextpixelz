<?php
require_once __DIR__ . '/Database.php';

class InitDB {
    public static function setup() {
        // Version bump this when you add new tables/columns
        $schemaVersion = 4;

        if (isset($_SESSION['db_schema_version']) && $_SESSION['db_schema_version'] >= $schemaVersion) {
            return;
        }

        try {
            $db = Database::getInstance()->getConnection();

            // Users
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

            try { $db->exec("ALTER TABLE users ADD COLUMN spins_available INT DEFAULT 0"); } catch (Exception $e) {}

            // Services
            $db->exec("CREATE TABLE IF NOT EXISTS services (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(150) NOT NULL,
                description TEXT,
                category VARCHAR(50),
                icon_key VARCHAR(30) DEFAULT 'code',
                price_estimate DECIMAL(10,2),
                is_active BOOLEAN DEFAULT TRUE
            )");
            try { $db->exec("ALTER TABLE services ADD COLUMN icon_key VARCHAR(30) DEFAULT 'code'"); } catch(Exception $e) {}

            // Packages (Admin-driven dynamic pricing)
            $db->exec("CREATE TABLE IF NOT EXISTS packages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                service_id INT NOT NULL,
                name VARCHAR(150) NOT NULL,
                type ENUM('Monthly','Weekly','One-Time') DEFAULT 'One-Time',
                price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                features TEXT COMMENT 'JSON array of feature strings',
                is_active BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
            )");

            // Orders
            $db->exec("CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_uid VARCHAR(20) NOT NULL UNIQUE,
                user_id INT NOT NULL,
                service_id INT NULL,
                package_id INT NULL,
                service_name VARCHAR(150),
                package_name VARCHAR(150),
                package_type VARCHAR(30),
                status ENUM('Pending', 'Ongoing', 'Completed', 'Cancelled') DEFAULT 'Pending',
                total_amount DECIMAL(10,2) DEFAULT 0.00,
                whatsapp_thread_id VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");
            try { $db->exec("ALTER TABLE orders ADD COLUMN package_id INT NULL"); } catch(Exception $e) {}
            try { $db->exec("ALTER TABLE orders ADD COLUMN package_name VARCHAR(150)"); } catch(Exception $e) {}
            try { $db->exec("ALTER TABLE orders ADD COLUMN package_type VARCHAR(30)"); } catch(Exception $e) {}

            // Billing
            $db->exec("CREATE TABLE IF NOT EXISTS billing (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                type ENUM('Invoice', 'Quotation') NOT NULL,
                file_path VARCHAR(255),
                status ENUM('Paid', 'Unpaid') DEFAULT 'Unpaid',
                generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
            )");

            // Seed Services
            $stmt = $db->query("SELECT COUNT(*) FROM services");
            if ($stmt->fetchColumn() == 0) {
                $services = [
                    ['Custom Web & Software Development', 'From business websites to full ERP, CRM, POS systems — we build anything custom.', 'Development', 'code'],
                    ['SEO & Organic Traffic Growth', 'Technical SEO, link building, local maps ranking. Get to Page 1 and stay there.', 'Marketing', 'search'],
                    ['Facebook & Google Ads', 'Data-driven paid campaigns with Pixel tracking, retargeting, and guaranteed leads.', 'Marketing', 'ads'],
                    ['UI/UX & Branding Design', 'Premium logos, brand identity, and user interfaces that convert visitors into clients.', 'Design', 'brush'],
                    ['E-commerce Solutions', 'Full-stack online stores with payment gateways, inventory, and order management.', 'Development', 'cart'],
                    ['Social Media Management', 'Content creation, scheduling, and community management across all platforms.', 'Marketing', 'social'],
                ];
                $ins = $db->prepare("INSERT INTO services (title, description, category, icon_key) VALUES (?,?,?,?)");
                foreach ($services as $s) $ins->execute($s);
            }

            // Seed Packages if empty
            $pStmt = $db->query("SELECT COUNT(*) FROM packages");
            if ($pStmt->fetchColumn() == 0) {
                // Get service IDs
                $svcMap = [];
                foreach ($db->query("SELECT id, category FROM services") as $r) {
                    $svcMap[$r['category']][] = $r['id'];
                }

                $packages = [
                    // Development packages (service id 1)
                    [1, 'Starter Website', 'One-Time', 299.00, '["5-Page Custom Website","Mobile Responsive","Contact Form","1 Month Free Support","Basic SEO Setup"]'],
                    [1, 'Business Pro', 'One-Time', 799.00, '["10-Page Custom Website","Admin Dashboard","Database Integration","3 Months Free Support","Advanced SEO"]'],
                    [1, 'ERP / CRM System', 'One-Time', 1999.00, '["Full Custom ERP/CRM","Unlimited Modules","API Integration","6 Months Support","Staff Training"]'],
                    // SEO (service id 2)
                    [2, 'SEO Basic', 'Monthly', 99.00, '["10 Target Keywords","On-Page Optimization","Monthly Report","Google Search Console Setup"]'],
                    [2, 'SEO Growth', 'Monthly', 199.00, '["30 Target Keywords","Full Technical SEO Audit","Link Building (5/month)","Competitor Analysis","Bi-weekly Report"]'],
                    // Ads (service id 3)
                    [3, 'Ads Starter', 'Monthly', 149.00, '["1 Campaign Setup","Pixel Installation","Weekly Optimization","Monthly Report","Ad Budget: Up to $500"]'],
                    [3, 'Ads Pro', 'Monthly', 299.00, '["3 Campaigns","A/B Testing","Retargeting Setup","Weekly Report","Ad Budget: Up to $2000"]'],
                ];

                $pins = $db->prepare("INSERT INTO packages (service_id, name, type, price, features) VALUES (?,?,?,?,?)");
                foreach ($packages as $p) $pins->execute($p);
            }

            $_SESSION['db_schema_version'] = $schemaVersion;
        } catch (Exception $e) {
            // Silent fail — log in production
        }
    }
}
