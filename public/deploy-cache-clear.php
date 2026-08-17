<?php
/**
 * SkyNetug — Post-Deploy Cache Clear
 * -------------------------------------------------------
 * 1. Upload this file to your public_html/public/ folder
 * 2. Visit https://skynetug.com/deploy-cache-clear.php
 * 3. DELETE this file immediately after running it
 * -------------------------------------------------------
 */

// ── Simple password protection — change this before uploading ──
define('SECRET', 'SkyNetug2026Deploy');

$token = $_GET['token'] ?? '';
if ($token !== SECRET) {
    http_response_code(403);
    die('<h2 style="font-family:sans-serif;color:red;">403 — Forbidden. Add ?token=SkyNetug2026Deploy to the URL.</h2>');
}

// ── Point to the Laravel root ──────────────────────────────────────────
// On cPanel hosting the Laravel root is public_html/ and the web root
// is public_html/public/ — so artisan is one level above this file
// when the file sits in public_html/ directly.
// If this file is in public_html/, artisan is also in public_html/
$laravelRoot = __DIR__;  // same folder as this file (public_html/)
$artisan     = $laravelRoot . '/artisan';

// Fallback: try one level up (public_html/public/ scenario)
if (!file_exists($artisan)) {
    $laravelRoot = dirname(__DIR__);
    $artisan     = $laravelRoot . '/artisan';
}

if (!file_exists($artisan)) {
    die('<h2 style="font-family:sans-serif;color:red;">artisan not found at: ' . $artisan . '</h2>');
}

$phpBin   = PHP_BINARY ?: 'php';
$commands = [
    'config:clear',
    'route:clear',
    'view:clear',
    'cache:clear',
    'config:cache',
    'route:cache',
    'view:cache',
];

$results = [];
foreach ($commands as $cmd) {
    $output  = [];
    $code    = 0;
    exec("{$phpBin} {$artisan} {$cmd} 2>&1", $output, $code);
    $results[] = [
        'cmd'    => $cmd,
        'output' => implode("\n", $output),
        'ok'     => $code === 0,
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SkyNetug Deploy</title>
<style>
  body { font-family: sans-serif; background: #f1f5f9; padding: 40px; }
  h2   { color: #0A0F1E; }
  .card { background:#fff; border-radius:10px; padding:20px; margin-bottom:14px;
          box-shadow:0 2px 8px rgba(0,0,0,.07); }
  .ok   { color: #16a34a; font-weight:700; }
  .fail { color: #dc2626; font-weight:700; }
  pre  { background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px;
          padding:10px; font-size:.85rem; white-space:pre-wrap; margin:8px 0 0; }
  .warn { background:#FEF3C7; border:1px solid #F59E0B; border-radius:8px;
           padding:14px; margin-top:24px; font-size:.9rem; color:#92400e; }
</style>
</head>
<body>
<h2>🚀 SkyNetug — Cache Clear Results</h2>
<?php foreach ($results as $r): ?>
<div class="card">
    <span class="<?= $r['ok'] ? 'ok' : 'fail' ?>">
        <?= $r['ok'] ? '✅' : '❌' ?> php artisan <?= htmlspecialchars($r['cmd']) ?>
    </span>
    <?php if ($r['output']): ?>
    <pre><?= htmlspecialchars($r['output']) ?></pre>
    <?php endif; ?>
</div>
<?php endforeach; ?>

<div class="warn">
    ⚠️ <strong>Important:</strong> Delete <code>deploy-cache-clear.php</code>
    from your server immediately after running this — anyone with the URL can run it.
</div>
</body>
</html>
