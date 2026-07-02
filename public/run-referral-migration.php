<?php
/**
 * Referral System Migration Runner
 * Access in browser: https://gintec.com.ng/run-referral-migration.php
 *
 * Idempotent: safe to run multiple times.
 * DELETE this file after running on production.
 */

session_start();

// ---- Locate application root (same detection as index.php) ----
$currentDir = __DIR__;
$parentDir = dirname($currentDir);
$possibleRoots = [
    $parentDir . '/gcsite',
    dirname($parentDir) . '/gcsite',
    $parentDir,
];
$appRoot = null;
foreach ($possibleRoots as $testPath) {
    if (is_dir($testPath) && file_exists($testPath . '/core') && file_exists($testPath . '/config')) {
        $appRoot = $testPath;
        break;
    }
}
if ($appRoot === null) {
    die('Migration Error: could not locate application root. Checked: ' . implode(', ', $possibleRoots));
}

require_once $appRoot . '/core/DotEnv.php';
(new \Core\DotEnv($appRoot . '/.env'))->load();
require_once $appRoot . '/core/Database.php';

$dbConfig = require $appRoot . '/config/database.php';
$pdo = \Core\Database::getInstance($dbConfig)->getConnection();

$prefix = $dbConfig['connections'][$dbConfig['default']]['prefix'] ?? 'gintec_';
$results = [];

function runStep(&$results, $label, callable $fn) {
    try {
        $fn();
        $results[] = ['ok', $label];
    } catch (\Throwable $e) {
        $msg = $e->getMessage();
        if (stripos($msg, 'Duplicate') !== false
            || stripos($msg, 'already exists') !== false
            || stripos($msg, 'exists') !== false) {
            $results[] = ['skip', $label . ' (already applied)'];
        } else {
            $results[] = ['err', $label . ' — ' . $msg];
        }
    }
}

// 1. Add referral_code column to users
runStep($results, "Add referral_code column to {$prefix}users", function () use ($pdo, $prefix) {
    $pdo->exec("ALTER TABLE `{$prefix}users` ADD COLUMN `referral_code` VARCHAR(20) DEFAULT NULL");
});

// 2. Unique index on referral_code
runStep($results, "Add unique index on referral_code", function () use ($pdo, $prefix) {
    $pdo->exec("ALTER TABLE `{$prefix}users` ADD UNIQUE INDEX `referral_code_idx` (`referral_code`)");
});

// 3. referral_clicks table
runStep($results, "Create {$prefix}referral_clicks table", function () use ($pdo, $prefix) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `{$prefix}referral_clicks` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `referral_code` VARCHAR(20) NOT NULL,
        `user_id` INT NOT NULL,
        `link_type` VARCHAR(20) NOT NULL,
        `item_slug` VARCHAR(255) DEFAULT NULL,
        `item_id` INT DEFAULT NULL,
        `ip_address` VARCHAR(45) DEFAULT NULL,
        `user_agent` VARCHAR(500) DEFAULT NULL,
        `referrer_url` VARCHAR(500) DEFAULT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        KEY `rc_user_id_idx` (`user_id`),
        KEY `rc_code_idx` (`referral_code`),
        KEY `rc_created_idx` (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
});

// 4. referrals table
runStep($results, "Create {$prefix}referrals table", function () use ($pdo, $prefix) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `{$prefix}referrals` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `user_id` INT NOT NULL,
        `name` VARCHAR(255) NOT NULL,
        `phone` VARCHAR(50) NOT NULL,
        `email` VARCHAR(255) DEFAULT NULL,
        `interested_in` VARCHAR(255) DEFAULT NULL,
        `notes` TEXT DEFAULT NULL,
        `status` VARCHAR(20) DEFAULT 'pending',
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        KEY `ref_user_id_idx` (`user_id`),
        KEY `ref_status_idx` (`status`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
});

// 5. Backfill referral codes for existing users
runStep($results, "Backfill referral codes for existing users", function () use ($pdo, $prefix) {
    $users = $pdo->query("SELECT id FROM `{$prefix}users` WHERE referral_code IS NULL OR referral_code = ''")->fetchAll(PDO::FETCH_ASSOC);
    $upd = $pdo->prepare("UPDATE `{$prefix}users` SET referral_code = ? WHERE id = ?");
    foreach ($users as $u) {
        do {
            $code = strtoupper(bin2hex(random_bytes(4))); // 8 hex chars
            $exists = $pdo->prepare("SELECT 1 FROM `{$prefix}users` WHERE referral_code = ? LIMIT 1");
            $exists->execute([$code]);
        } while ($exists->fetch());
        $upd->execute([$code, $u['id']]);
    }
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Referral System Migration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{padding:2rem;background:#f5f5f5}</style>
</head>
<body>
<div class="container">
    <h2 class="mb-4">🎯 Referral System Migration</h2>
    <ul class="list-group mb-4">
        <?php foreach ($results as [$status, $label]): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($label) ?>
                <?php if ($status === 'ok'): ?>
                    <span class="badge bg-success">Applied</span>
                <?php elseif ($status === 'skip'): ?>
                    <span class="badge bg-secondary">Skipped</span>
                <?php else: ?>
                    <span class="badge bg-danger">Error</span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="alert alert-warning">
        ⚠️ For security, <strong>delete this file</strong> (<code>run-referral-migration.php</code>) after the migration succeeds.
    </div>
    <a href="/dashboard/referrals" class="btn btn-primary">Go to Referral Dashboard →</a>
</div>
</body>
</html>
