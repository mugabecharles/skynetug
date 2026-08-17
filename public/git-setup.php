<?php
define('SECRET', 'SkyNetug2026Deploy');
if (($_GET['token'] ?? '') !== SECRET) { http_response_code(403); die('403'); }

$root = file_exists(__DIR__ . '/artisan') ? __DIR__ : dirname(__DIR__);
$results = [];

function run_cmd($cmd, $label) {
    $output = []; $code = 0;
    exec($cmd . ' 2>&1', $output, $code);
    return ['label' => $label, 'output' => implode("\n", $output), 'ok' => $code === 0];
}

// Step 1: Init git if not already
if (!is_dir($root . '/.git')) {
    $results[] = run_cmd("cd {$root} && git init", 'git init');
    $results[] = run_cmd("cd {$root} && git remote add origin https://mugabecharles:".($_GET['pat'] ?? 'TOKEN')."@github.com/mugabecharles/skynetug.git", 'git remote add origin');
} else {
    $results[] = ['label' => 'git already initialised', 'output' => '', 'ok' => true];
    // Update remote URL with token if provided
    if (!empty($_GET['pat'])) {
        $results[] = run_cmd("cd {$root} && git remote set-url origin https://mugabecharles:{$_GET['pat']}@github.com/mugabecharles/skynetug.git", 'git remote set-url');
    }
}

// Step 2: Fetch and reset to remote main
$results[] = run_cmd("cd {$root} && git fetch origin main", 'git fetch origin main');
$results[] = run_cmd("cd {$root} && git reset --hard origin/main", 'git reset --hard origin/main');

// Step 3: Clear caches
$viewPath = $root . '/storage/framework/views';
$deleted = 0;
if (is_dir($viewPath)) {
    foreach (glob($viewPath . '/*.php') as $f) { unlink($f); $deleted++; }
}
$results[] = ['label' => "Clear view cache ({$deleted} files)", 'output' => '', 'ok' => true];

foreach (glob($root . '/bootstrap/cache/*.php') as $f) {
    if (basename($f) !== '.gitignore') unlink($f);
}
$results[] = ['label' => 'Clear bootstrap cache', 'output' => '', 'ok' => true];
?>
<!DOCTYPE html><html><head><title>Git Setup</title>
<style>
body{font-family:sans-serif;padding:30px;background:#f1f5f9;max-width:860px;}
.card{background:#fff;border-radius:8px;padding:14px 18px;margin-bottom:8px;box-shadow:0 1px 4px rgba(0,0,0,.07);}
.ok{color:#16a34a;font-weight:700;} .err{color:#dc2626;font-weight:700;}
pre{background:#0A0F1E;color:#00C896;padding:12px;border-radius:6px;white-space:pre-wrap;font-size:.82rem;margin:8px 0 0;}
.warn{background:#FEF3C7;border:1px solid #F59E0B;padding:12px;border-radius:8px;margin-top:20px;}
.info{background:#e0f2fe;border:1px solid #7dd3fc;padding:12px;border-radius:8px;margin-bottom:20px;}
</style></head><body>
<h2>⚙️ SkyNetug — Git Setup</h2>

<?php if (empty($_GET['pat'])): ?>
<div class="info">
    <strong>GitHub Personal Access Token required.</strong><br>
    Add <code>?token=SkyNetug2026Deploy&amp;pat=YOUR_GITHUB_TOKEN</code> to the URL.<br><br>
    Get a token at: <a href="https://github.com/settings/tokens" target="_blank">github.com/settings/tokens</a>
    → Generate new token (classic) → tick <strong>repo</strong> → Generate.
</div>
<?php else: ?>
<p style="color:#6b7280;font-size:.9rem;">Root: <code><?= $root ?></code></p>
<?php foreach ($results as $r): ?>
<div class="card">
    <span class="<?= $r['ok'] ? 'ok' : 'err' ?>"><?= $r['ok'] ? '✅' : '❌' ?> <?= htmlspecialchars($r['label']) ?></span>
    <?php if ($r['output']): ?>
    <pre><?= htmlspecialchars($r['output']) ?></pre>
    <?php endif; ?>
</div>
<?php endforeach; ?>
<?php endif; ?>

<div class="warn">⚠️ <strong>Delete this file immediately after use!</strong></div>
</body></html>
