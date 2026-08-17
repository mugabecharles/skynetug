<?php
define('SECRET', 'SkyNetug2026Deploy');
if (($_GET['token'] ?? '') !== SECRET) { http_response_code(403); die('403'); }

// Find Laravel root
$root = file_exists(__DIR__ . '/artisan') ? __DIR__ : dirname(__DIR__);

// Ensure remote URL has token for auth
exec("cd {$root} && git remote set-url origin https://mugabecharles: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@github.com/mugabecharles/skynetug.git 2>&1");

$results = [];

// Step 1: git pull
$output = []; $code = 0;
exec("cd {$root} && git pull origin main 2>&1", $output, $code);
$results[] = ['label' => 'git pull', 'output' => implode("\n", $output), 'ok' => $code === 0];
// Step 2: Clear view cache (delete compiled blades)
$viewPath = $root . '/storage/framework/views';
$deleted  = 0;
if (is_dir($viewPath)) {
    foreach (glob($viewPath . '/*.php') as $file) {
        unlink($file);
        $deleted++;
    }
}
$results[] = ['label' => "Clear view cache ({$deleted} files)", 'output' => '', 'ok' => true];

// Step 3: Clear bootstrap cache
$bootstrapCache = $root . '/bootstrap/cache';
$cacheDeleted   = 0;
if (is_dir($bootstrapCache)) {
    foreach (glob($bootstrapCache . '/*.php') as $file) {
        if (basename($file) !== '.gitignore') {
            unlink($file);
            $cacheDeleted++;
        }
    }
}
$results[] = ['label' => "Clear bootstrap cache ({$cacheDeleted} files)", 'output' => '', 'ok' => true];
?>
<!DOCTYPE html><html><head><title>Git Deploy</title>
<style>
body{font-family:sans-serif;padding:30px;background:#f1f5f9;max-width:800px;}
.card{background:#fff;border-radius:8px;padding:14px 18px;margin-bottom:8px;box-shadow:0 1px 4px rgba(0,0,0,.07);}
.ok {color:#16a34a;font-weight:700;} .err{color:#dc2626;font-weight:700;}
pre{background:#0A0F1E;color:#00C896;padding:12px;border-radius:6px;white-space:pre-wrap;font-size:.82rem;margin:8px 0 0;}
.warn{background:#FEF3C7;border:1px solid #F59E0B;padding:12px;border-radius:8px;margin-top:20px;}
</style></head><body>
<h2>🚀 SkyNetug — Git Deploy</h2>
<p style="color:#6b7280;font-size:.9rem;">Laravel root: <code><?= $root ?></code></p>
<?php foreach ($results as $r): ?>
<div class="card">
    <span class="<?= $r['ok'] ? 'ok' : 'err' ?>"><?= $r['ok'] ? '✅' : '❌' ?> <?= htmlspecialchars($r['label']) ?></span>
    <?php if ($r['output']): ?>
    <pre><?= htmlspecialchars($r['output']) ?></pre>
    <?php endif; ?>
</div>
<?php endforeach; ?>
<div class="warn">
    ⚠️ <strong>Delete this file after use.</strong> It allows anyone with the URL to deploy your site.
</div>
</body></html>
