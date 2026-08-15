<?php
if (($_GET['pass'] ?? '') !== 'SkyNetug2026!') die('Access Denied');

$appPath = dirname(__DIR__) . '/skynetug';

// Load env
foreach (file($appPath . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if (str_starts_with($line, '#') || !str_contains($line, '=')) continue;
    [$k, $v] = explode('=', $line, 2);
    putenv(trim($k) . '=' . trim($v, '"\''));
    $_ENV[trim($k)] = trim($v, '"\'');
}

require_once $appPath . '/vendor/autoload.php';
$app    = require $appPath . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$out    = new \Symfony\Component\Console\Output\BufferedOutput();

echo "<pre>";

// Clear all caches
foreach (['config:clear', 'route:clear', 'view:clear', 'cache:clear'] as $cmd) {
    $kernel->call($cmd, [], $out);
    echo "✅ $cmd\n" . $out->fetch();
}

// Re-cache
foreach (['config:cache', 'route:cache'] as $cmd) {
    $kernel->call($cmd, [], $out);
    echo "✅ $cmd\n" . $out->fetch();
}

echo "</pre>";
echo "<p style='color:green;font-weight:bold'>Done! Delete clearfix.php now.</p>";
echo "<p><a href='/admin/hosting'>Test /admin/hosting</a></p>";
