<?php
// Connect to database directly
$pdo = new PDO('mysql:host=localhost;dbname=gintec_solutions', 'root', '');

// Get all pages
$stmt = $pdo->query('SELECT id, title, slug, status FROM gintec_pages ORDER BY created_at DESC LIMIT 30');
$pages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total Pages: " . count($pages) . "\n\n";
foreach ($pages as $page) {
    echo "ID: {$page['id']} | {$page['title']} (/{$page['slug']}) | Status: {$page['status']}\n";
}

// Check existing menu items
$stmt2 = $pdo->query('SELECT id, label, url FROM gintec_menus ORDER BY menu_order ASC');
$menus = $stmt2->fetchAll(PDO::FETCH_ASSOC);

echo "\n\nExisting Menu Items: " . count($menus) . "\n\n";
foreach ($menus as $menu) {
    echo "ID: {$menu['id']} | {$menu['label']} | URL: {$menu['url']}\n";
}
