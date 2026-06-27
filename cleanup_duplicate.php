<?php
$pdo = new PDO('mysql:host=localhost;dbname=gintec_solutions', 'root', '');

// Delete the duplicate Events and AI For Productivity menus (IDs 14, 15)
$stmt = $pdo->prepare('DELETE FROM gintec_menus WHERE id IN (14, 15)');
$stmt->execute();
echo "Deleted duplicate menu items (IDs 14 and 15)\n\n";

// Get the menu items again
$stmt = $pdo->query('SELECT id, label, url, menu_order FROM gintec_menus ORDER BY menu_order ASC');
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Final Menu Items: " . count($menus) . "\n\n";
foreach ($menus as $menu) {
    echo "ID: {$menu['id']} | {$menu['label']} | URL: {$menu['url']} | Order: {$menu['menu_order']}\n";
}
