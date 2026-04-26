<?php
require_once __DIR__ . '/Database.php';

class InitDB {
    public static function setup() {
        $schemaVersion = 5; // Bump for full service + portfolio seed

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
            try { $db->exec("ALTER TABLE users ADD COLUMN spins_available INT DEFAULT 0"); } catch(Exception $e){}
            try { $db->exec("ALTER TABLE users ADD COLUMN wallet_credits DECIMAL(10,2) DEFAULT 0.00"); } catch(Exception $e){}

            // Services
            $db->exec("CREATE TABLE IF NOT EXISTS services (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(150) NOT NULL,
                description TEXT,
                category VARCHAR(50),
                icon_key VARCHAR(30) DEFAULT 'code',
                sort_order INT DEFAULT 0,
                price_estimate DECIMAL(10,2),
                is_active BOOLEAN DEFAULT TRUE
            )");
            try { $db->exec("ALTER TABLE services ADD COLUMN icon_key VARCHAR(30) DEFAULT 'code'"); } catch(Exception $e){}
            try { $db->exec("ALTER TABLE services ADD COLUMN sort_order INT DEFAULT 0"); } catch(Exception $e){}

            // Portfolio
            $db->exec("CREATE TABLE IF NOT EXISTS portfolio (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(150) NOT NULL,
                category VARCHAR(80),
                description TEXT,
                image_url VARCHAR(255),
                demo_url VARCHAR(255),
                tech_tags VARCHAR(255),
                sort_order INT DEFAULT 0,
                is_active BOOLEAN DEFAULT TRUE
            )");

            // Packages
            $db->exec("CREATE TABLE IF NOT EXISTS packages (
                id INT AUTO_INCREMENT PRIMARY KEY,
                service_id INT NOT NULL,
                name VARCHAR(150) NOT NULL,
                type ENUM('Monthly','Weekly','One-Time') DEFAULT 'One-Time',
                price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                features TEXT,
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
                status ENUM('Pending','Ongoing','Completed','Cancelled') DEFAULT 'Pending',
                total_amount DECIMAL(10,2) DEFAULT 0.00,
                whatsapp_thread_id VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");
            try { $db->exec("ALTER TABLE orders ADD COLUMN package_id INT NULL"); } catch(Exception $e){}
            try { $db->exec("ALTER TABLE orders ADD COLUMN package_name VARCHAR(150)"); } catch(Exception $e){}
            try { $db->exec("ALTER TABLE orders ADD COLUMN package_type VARCHAR(30)"); } catch(Exception $e){}

            // Billing
            $db->exec("CREATE TABLE IF NOT EXISTS billing (
                id INT AUTO_INCREMENT PRIMARY KEY,
                order_id INT NOT NULL,
                type ENUM('Invoice','Quotation') NOT NULL,
                file_path VARCHAR(255),
                status ENUM('Paid','Unpaid') DEFAULT 'Unpaid',
                generated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
            )");

            // ─── Seed Services ───────────────────────────────────────────
            $svcCount = $db->query("SELECT COUNT(*) FROM services")->fetchColumn();
            if ($svcCount == 0) {
                $svcData = [
                    // [title, description, category, icon_key, sort]
                    ['Custom Website Development',    'Professional websites from landing pages to multi-page corporate sites. Fully responsive, fast-loading, and SEO-optimized from day one.',         'Software', 'globe',   1],
                    ['ERP & Business Management',     'Automate your entire operation — inventory, HR, payroll, reporting. Custom-built ERP tailored to your exact business workflow.',                 'Software', 'server',  2],
                    ['CRM & Sales Tracking',          'Never lose a lead again. Our custom CRM tracks customers, follow-ups, deals, and sales pipelines with real-time dashboards.',                   'Software', 'users',   3],
                    ['School & Institute Software',   'Complete school ERP — student admissions, attendance, fees, exams, results, teacher portal and parent app all in one system.',                  'Software', 'school',  4],
                    ['POS & Inventory System',        'Lightning-fast Point of Sale with barcode scanning, stock alerts, supplier management, and daily profit/loss reports.',                         'Software', 'pos',     5],
                    ['E-commerce Store',              'Full-featured online stores with product catalogs, cart, payment gateways, order tracking, and admin dashboard.',                               'Software', 'cart',    6],
                    ['Logo & Brand Identity',         'Memorable logos, brand color palettes, typography kits, and complete brand guideline documents that make you stand out.',                       'Design',   'logo',    7],
                    ['Poster & Graphic Design',       'Eye-catching social media posts, event posters, banners, and promotional materials that stop the scroll and drive action.',                     'Design',   'poster',  8],
                    ['Video Ads & 2D Animation',      'High-impact video advertisements, explainer animations, product showcases, and motion graphics that convert viewers into buyers.',              'Design',   'video',   9],
                    ['Brochure & Print Design',       'Professional brochures, flyers, business cards, letterheads, and catalogs designed for maximum visual impact in print and digital.',            'Design',   'print',   10],
                    ['Facebook & Google Ads',         'Data-driven paid campaigns with Pixel tracking, A/B testing, audience segmentation, and conversion optimization for maximum ROAS.',            'Marketing','ads',     11],
                    ['SEO & Organic Growth',          'Full technical SEO audits, keyword strategy, on-page optimization, and high-authority link building to dominate search rankings.',              'Marketing','search',  12],
                    ['Social Media Management',       'Complete social media handling — daily posts, stories, engagement, community management across Facebook, Instagram, TikTok, and LinkedIn.',    'Marketing','social',  13],
                    ['Content Creation & Copywriting','Engaging blog posts, product descriptions, ad copy, email sequences, and landing page content that converts readers into customers.',           'Marketing','content', 14],
                ];
                $insS = $db->prepare("INSERT INTO services (title,description,category,icon_key,sort_order) VALUES(?,?,?,?,?)");
                foreach ($svcData as $s) $insS->execute($s);
            }

            // ─── Seed Packages ───────────────────────────────────────────
            $pkgCount = $db->query("SELECT COUNT(*) FROM packages")->fetchColumn();
            if ($pkgCount == 0) {
                // Fetch service IDs by title
                $svcIds = [];
                foreach ($db->query("SELECT id, title FROM services") as $r) {
                    $svcIds[$r['title']] = $r['id'];
                }

                $pkgData = [
                    // Website packages
                    [$svcIds['Custom Website Development'] ?? 1, 'Landing Page',     'One-Time', 149, '["Single Page","Mobile Responsive","Contact Form","WhatsApp Button","Basic SEO","1 Month Support"]'],
                    [$svcIds['Custom Website Development'] ?? 1, 'Business Site',    'One-Time', 349, '["Up to 8 Pages","Custom Design","Blog / News","Google Maps","Social Links","3 Months Support"]'],
                    [$svcIds['Custom Website Development'] ?? 1, 'Corporate Pro',    'One-Time', 699, '["Unlimited Pages","Admin Panel","Multi-language","Custom Domain Setup","Priority Support 6 Months"]'],
                    // ERP
                    [$svcIds['ERP & Business Management'] ?? 2, 'ERP Starter',      'One-Time', 999,  '["Up to 3 Modules","Inventory + HR + Reporting","Admin Dashboard","Staff Accounts","1 Year Support"]'],
                    [$svcIds['ERP & Business Management'] ?? 2, 'ERP Enterprise',   'One-Time', 2499, '["Unlimited Modules","Multi-Branch","Mobile App","API Integration","Lifetime Support"]'],
                    // School
                    [$svcIds['School & Institute Software'] ?? 4, 'Institute Basic',  'One-Time', 799,  '["Student + Teacher Portal","Fee Management","Exam + Results","Attendance System","SMS Alerts"]'],
                    [$svcIds['School & Institute Software'] ?? 4, 'Institute Pro',    'One-Time', 1499, '["All Basic Features","Parent App","Online Admissions","Library Module","Timetable Builder"]'],
                    // POS
                    [$svcIds['POS & Inventory System'] ?? 5, 'POS Basic',           'One-Time', 299,  '["Barcode Scanning","Daily Sales Report","Expense Tracking","Single Branch","1 Year Support"]'],
                    [$svcIds['POS & Inventory System'] ?? 5, 'POS Pro',             'One-Time', 599,  '["Multi-branch","Supplier Management","Low Stock Alerts","Profit/Loss Reports","Cloud Sync"]'],
                    // Logo
                    [$svcIds['Logo & Brand Identity'] ?? 7, 'Logo Basic',           'One-Time', 29,   '["3 Concepts","2 Revision Rounds","PNG + PDF Files","24h Delivery"]'],
                    [$svcIds['Logo & Brand Identity'] ?? 7, 'Brand Identity Kit',   'One-Time', 99,   '["5 Concepts","Unlimited Revisions","Logo + Business Card + Letterhead","Brand Guideline PDF","All Source Files"]'],
                    // Graphic Design (Monthly)
                    [$svcIds['Poster & Graphic Design'] ?? 8, '8 Posts/Month',       'Monthly',  79,   '["8 Social Media Posts","Professional Design","Brand Consistent","File Delivery in 24h"]'],
                    [$svcIds['Poster & Graphic Design'] ?? 8, '20 Posts/Month',      'Monthly',  149,  '["20 Social Media Posts","Story Designs Included","Captions Provided","Priority Delivery"]'],
                    // Video Ads
                    [$svcIds['Video Ads & 2D Animation'] ?? 9, '30s Video Ad',        'One-Time', 199,  '["30-Second Video","Script Writing","Voiceover","Motion Graphics","HD Export"]'],
                    [$svcIds['Video Ads & 2D Animation'] ?? 9, 'Explainer Video',     'One-Time', 399,  '["60-90 Second Animation","Full Script","Character Animation","Background Music","HD + Social Formats"]'],
                    // Ads
                    [$svcIds['Facebook & Google Ads'] ?? 11, 'Ads Starter',          'Monthly',  149,  '["1 Campaign","Pixel Setup","Weekly Optimization","Monthly Report","Budget: Up to $500"]'],
                    [$svcIds['Facebook & Google Ads'] ?? 11, 'Ads Growth',            'Monthly',  299,  '["3 Campaigns","A/B Testing","Retargeting","Lookalike Audiences","Budget: Up to $2000"]'],
                    [$svcIds['Facebook & Google Ads'] ?? 11, 'Ads Agency',            'Monthly',  599,  '["Unlimited Campaigns","Google + Meta Combined","Dedicated Manager","Daily Report","Budget: Unlimited"]'],
                    // SEO
                    [$svcIds['SEO & Organic Growth'] ?? 12, 'SEO Basic',             'Monthly',  99,   '["10 Keywords","On-Page SEO","Monthly Report","Google Console Setup"]'],
                    [$svcIds['SEO & Organic Growth'] ?? 12, 'SEO Growth',            'Monthly',  199,  '["30 Keywords","Technical Audit","5 Backlinks/Month","Competitor Analysis","Bi-weekly Report"]'],
                    // Social Media
                    [$svcIds['Social Media Management'] ?? 13, 'Social Starter',     'Monthly',  129,  '["15 Posts/Month","2 Platforms","Captions + Hashtags","Monthly Analytics"]'],
                    [$svcIds['Social Media Management'] ?? 13, 'Social Pro',          'Monthly',  249,  '["30 Posts/Month","4 Platforms","Story Content","Community Management","Weekly Report"]'],
                ];

                $insP = $db->prepare("INSERT INTO packages (service_id,name,type,price,features) VALUES(?,?,?,?,?)");
                foreach ($pkgData as $p) $insP->execute($p);
            }

            // ─── Seed Portfolio ───────────────────────────────────────────
            $portCount = $db->query("SELECT COUNT(*) FROM portfolio")->fetchColumn();
            if ($portCount == 0) {
                $portfolio = [
                    ['School Management System',   'Software',  'Full ERP for educational institutes — admissions, fees, exams, and parent portal.',              'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&q=80', '#', 'PHP,MySQL,Bootstrap',   1],
                    ['Restaurant POS System',       'Software',  'Touch-screen POS with KOT, table management, inventory, and daily sales reporting.',             'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&q=80', '#', 'PHP,MySQL,JavaScript',  2],
                    ['E-commerce Fashion Store',    'Software',  'Full-featured online store with product filter, cart, bKash/Nagad payment, and admin panel.',    'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&q=80', '#', 'PHP,MySQL,Tailwind',    3],
                    ['Corporate Brand Identity',   'Design',   'Complete brand package — logo, business card, letterhead, and brand guideline for a BD firm.',    'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=600&q=80', '#', 'Illustrator,Photoshop', 4],
                    ['Facebook Ads — 3x ROAS',      'Marketing', 'Performance marketing campaign that delivered 3x Return on Ad Spend for a clothing brand.',      'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&q=80', '#', 'Meta Ads,Pixel,Analytics',5],
                    ['Hospital Management ERP',     'Software',  'Patient records, doctor scheduling, billing, pharmacy, and lab management in one system.',       'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?w=600&q=80', '#', 'PHP,MySQL,Chart.js',    6],
                ];
                $insPort = $db->prepare("INSERT INTO portfolio (title,category,description,image_url,demo_url,tech_tags,sort_order) VALUES(?,?,?,?,?,?,?)");
                foreach ($portfolio as $p) $insPort->execute($p);
            }

            $_SESSION['db_schema_version'] = $schemaVersion;
        } catch (Exception $e) {
            // Silent fail — check error_log in production
        }
    }
}
