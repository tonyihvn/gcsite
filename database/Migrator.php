<?php
/**
 * Migration Runner
 * GINTEC Solutions
 */

namespace Database;

use PDO;

class Migrator
{
    private $db;
    private $prefix = 'gintec_';

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function run()
    {
        $migrations = require __DIR__ . '/migrations/001_create_tables.php';

        foreach ($migrations as $sql) {
            try {
                $this->db->exec($sql);
                echo "✓ Migration executed successfully\n";
            } catch (\Exception $e) {
                echo "✗ Migration error: " . $e->getMessage() . "\n";
            }
        }

        // Run SQL migration files
        $this->runSqlMigrations();

        // Seed initial data
        $this->seed();
    }

    private function runSqlMigrations()
    {
        echo "\n--- Running SQL Migrations ---\n";
        
        try {
            // Add missing page fields
            $this->addColumnIfNotExists('pages', 'description', "TEXT AFTER `slug`");
            echo "✓ Page description field added\n";
            
            // Add missing product and service fields (from add_product_service_fields.sql)
            $this->addColumnIfNotExists('products', 'icon', "VARCHAR(255) AFTER `image_url`");
            $this->addColumnIfNotExists('products', 'website', "VARCHAR(255) AFTER `demo_url`");
            $this->addColumnIfNotExists('services', 'website', "VARCHAR(255) AFTER `image_url`");
            echo "✓ Product and service fields added\n";
            
            // Add password reset fields (from add_password_reset_fields.sql)
            $this->addColumnIfNotExists('users', 'reset_token', "VARCHAR(255)");
            $this->addColumnIfNotExists('users', 'reset_token_expires_at', "TIMESTAMP NULL");
            echo "✓ Password reset fields added\n";
            
        } catch (\Exception $e) {
            echo "✗ Error in SQL migrations: " . $e->getMessage() . "\n";
        }
    }

    private function addColumnIfNotExists($table, $column, $definition)
    {
        try {
            // Check if column exists
            $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_NAME = '{$this->prefix}{$table}' 
                   AND COLUMN_NAME = '{$column}'
                   AND TABLE_SCHEMA = DATABASE()";
            
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch();
            
            if (!$result) {
                // Column doesn't exist, add it
                $alterSql = "ALTER TABLE `{$this->prefix}{$table}` ADD COLUMN `{$column}` {$definition}";
                $this->db->exec($alterSql);
            }
        } catch (\Exception $e) {
            // Silently ignore errors for columns that might already exist
            if (strpos($e->getMessage(), 'Duplicate column') === false) {
                throw $e;
            }
        }
    }

    private function seed()
    {
        echo "\n--- Seeding initial data ---\n";

        try {
            // Insert default settings
            $settings = [
                ['key' => 'site_title', 'value' => 'GINTEC Solutions Consults Ltd', 'type' => 'string', 'group' => 'general'],
                ['key' => 'site_description', 'value' => 'Leading IT Solutions and Consultancy Provider', 'type' => 'string', 'group' => 'general'],
                ['key' => 'site_logo', 'value' => '/assets/images/logo.png', 'type' => 'string', 'group' => 'branding'],
                ['key' => 'site_favicon', 'value' => '/assets/images/favicon.ico', 'type' => 'string', 'group' => 'branding'],
                ['key' => 'company_address', 'value' => '2nd Floor, Peace Plaza B, Utako, Abuja', 'type' => 'string', 'group' => 'contact'],
                ['key' => 'company_phone', 'value' => '07067973091', 'type' => 'string', 'group' => 'contact'],
                ['key' => 'company_email', 'value' => 'info@gintec.com.ng', 'type' => 'string', 'group' => 'contact'],
                ['key' => 'company_website', 'value' => 'www.gintec.com.ng', 'type' => 'string', 'group' => 'contact'],
                ['key' => 'ceo_name', 'value' => 'Anthony Nwokoma', 'type' => 'string', 'group' => 'company'],
            ];

            foreach ($settings as $setting) {
                $sql = "INSERT INTO {$this->prefix}settings (`key`, `value`, `type`, `group`) 
                        VALUES (?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE `value` = ?";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $setting['key'],
                    $setting['value'],
                    $setting['type'],
                    $setting['group'],
                    $setting['value']
                ]);
            }

            echo "✓ Settings seeded successfully\n";

            // Insert sample products
            $products = [
                ['name' => 'AutoServe', 'slug' => 'autoserve', 'category' => 'ERP', 'pricing_model' => 'subscription'],
                ['name' => 'RealtyPlus', 'slug' => 'realtyplus', 'category' => 'Management', 'pricing_model' => 'subscription'],
                ['name' => 'MinistryManager', 'slug' => 'ministrymanager', 'category' => 'Church Management', 'pricing_model' => 'subscription'],
                ['name' => 'ProSales ERP', 'slug' => 'prosales-erp', 'category' => 'ERP', 'pricing_model' => 'subscription'],
                ['name' => 'Jobiz', 'slug' => 'jobiz', 'category' => 'Business Tools', 'pricing_model' => 'subscription'],
                ['name' => 'OneApp', 'slug' => 'oneapp', 'category' => 'NGO Tools', 'pricing_model' => 'subscription'],
                ['name' => 'AiWorkr', 'slug' => 'aiworkr', 'category' => 'AI Assistant', 'pricing_model' => 'custom'],
            ];

            foreach ($products as $product) {
                $sql = "INSERT INTO {$this->prefix}products (name, slug, category, pricing_model, status) 
                        VALUES (?, ?, ?, ?, 'published')
                        ON DUPLICATE KEY UPDATE status = 'published'";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $product['name'],
                    $product['slug'],
                    $product['category'],
                    $product['pricing_model']
                ]);
            }

            echo "✓ Products seeded successfully\n";

            // Insert sample services with enhanced descriptions
            $services = [
                [
                    'name' => 'Robotics & Automation',
                    'slug' => 'robotics',
                    'description' => 'Transform your business with cutting-edge robotics solutions',
                    'detailed_content' => 'Our robotics and automation services help businesses streamline operations, reduce manual labor, and increase productivity. From manufacturing automation to intelligent process automation, we deliver custom solutions tailored to your industry needs.',
                    'delivery_days' => 30
                ],
                [
                    'name' => 'Machine Learning Solutions',
                    'slug' => 'machine-learning',
                    'description' => 'Harness the power of AI and machine learning for your business',
                    'detailed_content' => 'Leverage advanced machine learning algorithms to gain insights from your data. Our ML services include predictive analytics, recommendation systems, computer vision, and natural language processing tailored to your specific use cases.',
                    'delivery_days' => 45
                ],
                [
                    'name' => 'AI Consultancy & Strategy',
                    'slug' => 'ai-consultancy',
                    'description' => 'Strategic guidance for AI implementation and digital transformation',
                    'detailed_content' => 'Our AI consultancy team helps you identify opportunities for artificial intelligence in your business, develop comprehensive AI strategies, and execute implementation plans. We guide you through every step of your AI journey.',
                    'delivery_days' => 14
                ],
                [
                    'name' => 'IT Training & Certification',
                    'slug' => 'it-training',
                    'description' => 'Comprehensive IT training programs for your team',
                    'detailed_content' => 'Upskill your workforce with our professional IT training programs. From cloud computing to cybersecurity, we offer hands-on training delivered by industry experts with real-world experience.',
                    'delivery_days' => 21
                ],
                [
                    'name' => 'Project Co-Management',
                    'slug' => 'project-co-management',
                    'description' => 'Expert project management for complex technical initiatives',
                    'detailed_content' => 'Our experienced project managers work alongside your team to ensure successful delivery of complex IT projects. We provide oversight, resource management, and risk mitigation throughout the project lifecycle.',
                    'delivery_days' => 60
                ],
                [
                    'name' => 'Custom Software Development',
                    'slug' => 'custom-development',
                    'description' => 'Bespoke software solutions tailored to your business needs',
                    'detailed_content' => 'We develop custom software applications designed specifically for your business requirements. From web applications to mobile apps and enterprise systems, our team delivers scalable, maintainable solutions.',
                    'delivery_days' => 90
                ],
            ];

            foreach ($services as $service) {
                $sql = "INSERT INTO {$this->prefix}services (name, slug, description, detailed_content, delivery_days, status) 
                        VALUES (?, ?, ?, ?, ?, 'active')
                        ON DUPLICATE KEY UPDATE description = VALUES(description), detailed_content = VALUES(detailed_content)";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $service['name'],
                    $service['slug'],
                    $service['description'],
                    $service['detailed_content'],
                    $service['delivery_days']
                ]);
            }

            echo "✓ Services seeded successfully\n";

            // Insert team members
            $teamMembers = [
                [
                    'name' => 'Anthony Nwokoma',
                    'title' => 'Chief Executive Officer',
                    'department' => 'Executive',
                    'bio' => 'Visionary leader with 15+ years of experience in IT solutions and digital transformation. Anthony founded GINTEC Solutions to bridge the gap between business challenges and technological innovation.',
                    'email' => 'anthony@gintec.com.ng',
                    'sort_order' => 1
                ],
                [
                    'name' => 'Dr. Onyeka Eze',
                    'title' => 'Chief Technology Officer',
                    'department' => 'Technology',
                    'bio' => 'PhD in Computer Science with expertise in AI and machine learning. Onyeka leads our technical architecture and innovation initiatives, ensuring we stay at the forefront of technology.',
                    'email' => 'onyeka@gintec.com.ng',
                    'sort_order' => 2
                ],
                [
                    'name' => 'Chioma Okonkwo',
                    'title' => 'Head of Product Development',
                    'department' => 'Product',
                    'bio' => 'Product strategist with 10+ years in SaaS development. Chioma ensures all GINTEC products deliver exceptional value and user experience to our customers.',
                    'email' => 'chioma@gintec.com.ng',
                    'sort_order' => 3
                ],
                [
                    'name' => 'Ibrahim Hassan',
                    'title' => 'Lead Solutions Architect',
                    'department' => 'Solutions',
                    'bio' => 'Enterprise solutions expert specializing in complex system integration and digital transformation. Ibrahim leads our consulting and architecture practice.',
                    'email' => 'ibrahim@gintec.com.ng',
                    'sort_order' => 4
                ],
                [
                    'name' => 'Tunde Adebayo',
                    'title' => 'Head of Operations',
                    'department' => 'Operations',
                    'bio' => 'Operations specialist ensuring seamless service delivery and client satisfaction. Tunde oversees all operational aspects of GINTEC Solutions.',
                    'email' => 'tunde@gintec.com.ng',
                    'sort_order' => 5
                ],
            ];

            foreach ($teamMembers as $member) {
                $sql = "INSERT INTO {$this->prefix}team_members (name, title, department, bio, email, sort_order, status) 
                        VALUES (?, ?, ?, ?, ?, ?, 'active')
                        ON DUPLICATE KEY UPDATE bio = VALUES(bio)";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $member['name'],
                    $member['title'],
                    $member['department'],
                    $member['bio'],
                    $member['email'],
                    $member['sort_order']
                ]);
            }

            echo "✓ Team members seeded successfully\n";

            // Insert partners
            $partners = [
                [
                    'name' => 'Microsoft',
                    'category' => 'Technology Partner',
                    'description' => 'Strategic partnership with Microsoft for cloud services and enterprise solutions',
                    'featured' => 1,
                    'sort_order' => 1
                ],
                [
                    'name' => 'AWS',
                    'category' => 'Cloud Services',
                    'description' => 'Amazon Web Services partnership for scalable cloud infrastructure',
                    'featured' => 1,
                    'sort_order' => 2
                ],
                [
                    'name' => 'Google Cloud',
                    'category' => 'Cloud Services',
                    'description' => 'Google Cloud partnership for AI and data analytics solutions',
                    'featured' => 1,
                    'sort_order' => 3
                ],
                [
                    'name' => 'Cisco Systems',
                    'category' => 'Networking',
                    'description' => 'Partnership with Cisco for enterprise networking and security solutions',
                    'featured' => 0,
                    'sort_order' => 4
                ],
                [
                    'name' => 'Dell Technologies',
                    'category' => 'Infrastructure',
                    'description' => 'Dell partnership for enterprise hardware and infrastructure solutions',
                    'featured' => 0,
                    'sort_order' => 5
                ],
            ];

            foreach ($partners as $partner) {
                $sql = "INSERT INTO {$this->prefix}partners (name, category, description, featured, sort_order, status) 
                        VALUES (?, ?, ?, ?, ?, 'active')
                        ON DUPLICATE KEY UPDATE description = VALUES(description)";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $partner['name'],
                    $partner['category'],
                    $partner['description'],
                    $partner['featured'],
                    $partner['sort_order']
                ]);
            }

            echo "✓ Partners seeded successfully\n";

            // Insert about sections
            $aboutSections = [
                [
                    'section_name' => 'company_story',
                    'title' => 'Our Story',
                    'content' => 'Founded in 2015, GINTEC Solutions emerged from a vision to revolutionize how businesses leverage technology. Starting with a small team of passionate technologists, we have grown into a trusted partner for hundreds of organizations across Nigeria and beyond. Our journey has been marked by innovation, customer success, and a commitment to excellence.',
                    'sort_order' => 1
                ],
                [
                    'section_name' => 'mission',
                    'title' => 'Our Mission',
                    'content' => 'To empower businesses with cutting-edge IT solutions that drive growth, efficiency, and innovation. We believe technology should be accessible, affordable, and transformative.',
                    'sort_order' => 2
                ],
                [
                    'section_name' => 'vision',
                    'title' => 'Our Vision',
                    'content' => 'To be the leading provider of innovative IT solutions and digital transformation services in Africa, recognized for our technical excellence and customer-centric approach.',
                    'sort_order' => 3
                ],
                [
                    'section_name' => 'values',
                    'title' => 'Our Core Values',
                    'content' => '• Innovation: We continually explore new technologies and methodologies
• Excellence: We maintain the highest standards in everything we do
• Integrity: We conduct business with honesty and transparency
• Customer Focus: Our clients\' success is our success
• Collaboration: We work as partners with our clients and team members',
                    'sort_order' => 4
                ],
            ];

            foreach ($aboutSections as $section) {
                $sql = "INSERT INTO {$this->prefix}about (section_name, title, content, sort_order) 
                        VALUES (?, ?, ?, ?)
                        ON DUPLICATE KEY UPDATE title = VALUES(title), content = VALUES(content)";
                
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $section['section_name'],
                    $section['title'],
                    $section['content'],
                    $section['sort_order']
                ]);
            }

            echo "✓ About sections seeded successfully\n";

        } catch (\Exception $e) {
            echo "✗ Seeding error: " . $e->getMessage() . "\n";
        }
    }
}
