<?php
define('SECRET', 'SkyNetug2026Deploy');
if (($_GET['token'] ?? '') !== SECRET) { http_response_code(403); die('403'); }

$pat  = $_GET['pat'] ?? '';
$root = file_exists(__DIR__ . '/artisan') ? __DIR__ : dirname(__DIR__);

$results = [];

function run($cmd, $label, $cwd = null) {
    $descriptors = [0=>['pipe','r'], 1=>['pipe','w'], 2=>['pipe','w']];
    $process = proc_open($cmd, $descriptors, $pipes, $cwd);
    if (!is_resource($process)) {
        return ['label'=>$label,'output'=>'proc_open failed','ok'=>false];
    }
    fclose($pipes[0]);
    $stdout = stream_get_contents($pipes[1]); fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]); fclose($pipes[2]);
    $code   = proc_close($process);
    $output = trim($stdout . "\n" . $stderr);
    return ['label'=>$label,'output'=>$output,'ok'=>$code===0];
}

if (empty($pat)) {
    $showForm = true;
} else {
    $showForm = false;
    $remoteUrl = "https://mugabecharles:{$pat}@github.com/mugabecharles/skynetug.git";

    // Init git if needed
    if (!is_dir($root . '/.git')) {
        $results[] = run('git init', 'git init', $root);
        $results[] = run("git remote add origin {$remoteUrl}", 'git remote add origin', $root);
    } else {
        $results[] = ['label'=>'git already initialised ✓','output'=>'','ok'=>true];
        $results[] = run("git remote set-url origin {$remoteUrl}", 'git remote set-url', $root);
    }

    $results[] = run('git fetch origin main', 'git fetch', $root);
    $results[] = run('git reset --hard origin/main', 'git reset --hard', $root);

    // Clear view cache
    $viewPath = $root . '/storage/framework/views';
    $deleted  = 0;
    if (is_dir($viewPath)) {
        foreach (glob($viewPath . '/*.php') as $f) { unlink($f); $deleted++; }
    }
    $results[] = ['label'=>"Clear view cache ({$deleted} files)",'output'=>'','ok'=>true];

    // Clear bootstrap cache
    $n = 0;
    foreach (glob($root . '/bootstrap/cache/*.php') as $f) {
        if (basename($f) !== '.gitignore') { unlink($f); $n++; }
    }
    $results[] = ['label'=>"Clear bootstrap cache ({$n} files)",'output'=>'','ok'=>true];
}
?>
<!DOCTYPE html><html><head><title>Git Setup</title>
<style>
body{font-family:sans-serif;padding:30px;background:#f1f5f9;max-width:860px;}
.card{background:#fff;border-radius:8px;padding:14px 18px;margin-bottom:8px;box-shadow:0 1px 4px rgba(0,0,0,.07);}
.ok{color:#16a34a;font-weight:700;}.err{color:#dc2626;font-weight:700;}
pre{background:#0A0F1E;color:#00C896;padding:12px;border-radius:6px;white-space:pre-wrap;font-size:.82rem;margin:8px 0 0;}
.warn{background:#FEF3C7;border:1px solid #F59E0B;padding:12px;border-radius:8px;margin-top:20px;}
.info{background:#e0f2fe;border:1px solid #7dd3fc;padding:14px;border-radius:8px;margin-bottom:20px;}
</style></head><body>
<h2>⚙️ SkyNetug — Git Setup</h2>
<p style="color:#6b7280;font-size:.9rem;">Root: <code><?= htmlspecialchars($root) ?></code></p>

<?php if ($showForm): ?>
<div class="info">
    <strong>Enter your GitHub Personal Access Token:</strong><br><br>
    <form method="GET">
        <input type="hidden" name="token" value="SkyNetug2026Deploy">
        <input type="text" name="pat" class="form-control" placeholder="ghp_..." style="width:100%;padding:8px;border:1px solid #ccc;border-radius:6px;font-family:monospace;margin-bottom:10px;">
        <button type="submit" style="background:#0066FF;color:#fff;border:none;padding:10px 24px;border-radius:6px;cursor:pointer;font-weight:700;">Run Setup</button>
    </form>
</div>
<?php else: ?>
<?php foreach ($results as $r): ?>
<div class="card">
    <span class="<?= $r['ok'] ? 'ok' : 'err' ?>"><?= $r['ok'] ? '✅' : '❌' ?> <?= htmlspecialchars($r['label']) ?></span>
    <?php if (trim($r['output'])): ?>
    <pre><?= htmlspecialchars($r['output']) ?></pre>
    <?php endif; ?>
</div>
<?php endforeach; ?>
<?php endif; ?>

<div class="warn">⚠️ <strong>Delete this file immediately after use!</strong></div>
</body></html>
