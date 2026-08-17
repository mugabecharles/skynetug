<?php
define('SECRET', 'SkyNetug2026Deploy');
if (($_GET['token'] ?? '') !== SECRET) { http_response_code(403); die('403'); }

// Find Laravel log
$logPaths = [
    __DIR__ . '/storage/logs/laravel.log',
    dirname(__DIR__) . '/storage/logs/laravel.log',
    '/home/skynetug/public_html/storage/logs/laravel.log',
    '/home/fskynetug/public_html/storage/logs/laravel.log',
];

$logFile = null;
foreach ($logPaths as $p) {
    if (file_exists($p)) { $logFile = $p; break; }
}
?>
<!DOCTYPE html><html><head><title>Error Log</title>
<style>body{font-family:sans-serif;padding:20px;background:#f1f5f9;}
pre{background:#0A0F1E;color:#00C896;padding:16px;border-radius:8px;white-space:pre-wrap;font-size:.78rem;max-height:600px;overflow-y:auto;}
.warn{background:#FEF3C7;border:1px solid #F59E0B;padding:12px;border-radius:8px;margin-top:20px;}</style>
</head><body>
<h2>🔍 Laravel Error Log</h2>
<?php if (!$logFile): ?>
<p style="color:red">Log file not found. Searched: <?= implode(', ', $logPaths) ?></p>
<?php else: ?>
<p><strong>Log file:</strong> <code><?= $logFile ?></code></p>
<?php
// Show last 100 lines
$lines = file($logFile);
$last  = array_slice($lines, -100);
echo '<pre>' . htmlspecialchars(implode('', $last)) . '</pre>';
?>
<?php endif; ?>
<div class="warn">⚠️ Delete this file immediately after use!</div>
</body></html>
