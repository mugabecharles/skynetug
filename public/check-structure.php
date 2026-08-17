<?php
if (($_GET['t'] ?? '') !== 'sky2026') { die('no'); }

echo "<pre style='font-family:monospace;padding:20px;'>";
echo "__DIR__ = " . __DIR__ . "\n";
echo "dirname(__DIR__) = " . dirname(__DIR__) . "\n\n";

$root = dirname(__DIR__);
$dirs = ['app','bootstrap','config','database','resources','routes','storage','vendor'];
echo "Contents of {$root}:\n";
foreach (scandir($root) as $item) {
    if ($item === '.' || $item === '..') continue;
    $type = is_dir($root.'/'.$item) ? '[DIR]' : '[FILE]';
    echo "  {$type} {$item}\n";
}

echo "\nContents of {$root}/storage/logs/ (if exists):\n";
$logDir = $root . '/storage/logs';
if (is_dir($logDir)) {
    foreach (scandir($logDir) as $item) {
        if ($item === '.' || $item === '..') continue;
        $size = filesize($logDir.'/'.$item);
        echo "  {$item} (" . round($size/1024,1) . " KB)\n";
    }
} else {
    echo "  Not found\n";
}
echo "</pre>";
