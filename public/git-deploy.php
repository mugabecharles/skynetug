<?php
if (($_GET['token'] ?? '') !== 'SkyNetug2026Deploy') { die('403'); }

$root = dirname(__DIR__);

// Clear view cache
$deleted = 0;
$viewPath = $root . '/storage/framework/views';
if (is_dir($viewPath)) {
    foreach (glob($viewPath . '/*.php') as $f) {
        unlink($f);
        $deleted++;
    }
}

// Clear bootstrap cache
$boot = 0;
foreach (glob($root . '/bootstrap/cache/*.php') as $f) {
    if (basename($f) !== '.gitignore') { unlink($f); $boot++; }
}

echo "<pre style='font-family:sans-serif;padding:20px;background:#f1f5f9;'>";
echo "✅ View cache cleared: {$deleted} files\n";
echo "✅ Bootstrap cache cleared: {$boot} files\n";
echo "✅ Done — site updated!\n";
echo "\nRoot: {$root}";
echo "\nTo pull latest code, use cPanel Git Version Control → Update from Remote.";
echo "</pre>";
