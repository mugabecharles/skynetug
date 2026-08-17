<?php
define('SECRET', 'SkyNetug2026Deploy');
$token = $_GET['token'] ?? '';
if ($token !== SECRET) { http_response_code(403); die('403'); }

// Load .env
$envPaths = [
    __DIR__ . '/.env',
    dirname(__DIR__) . '/.env',
    '/home/skynetug/public_html/.env',
    '/home/fskynetug/public_html/.env',
];
$env = [];
foreach ($envPaths as $f) {
    if (file_exists($f)) {
        foreach (file($f, FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with(trim($line),'#') || !str_contains($line,'=')) continue;
            [$k,$v] = explode('=', $line, 2);
            $env[trim($k)] = trim($v," \t\n\r\0\x0B\"'");
        }
        break;
    }
}

$pdo = new PDO(
    "mysql:host={$env['DB_HOST']};port={$env['DB_PORT']};dbname={$env['DB_DATABASE']};charset=utf8mb4",
    $env['DB_USERNAME'], $env['DB_PASSWORD'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

// New password: Admin@1234
$hash = password_hash('Admin@1234', PASSWORD_BCRYPT, ['cost' => 12]);

$stmt = $pdo->prepare("UPDATE users SET password = ?, role = 'super_admin', is_active = 1, email_verified_at = NOW() WHERE email = 'admin@skynetug.com'");
$stmt->execute([$hash]);
$rows = $stmt->rowCount();

// Also show what users exist
$users = $pdo->query("SELECT id, name, email, role, is_active FROM users ORDER BY id LIMIT 10")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html><html><head><title>Admin Reset</title>
<style>body{font-family:sans-serif;padding:30px;background:#f1f5f9;}
table{width:100%;border-collapse:collapse;background:#fff;border-radius:8px;overflow:hidden;}
th,td{padding:10px 14px;text-align:left;border-bottom:1px solid #f3f4f6;font-size:.88rem;}
th{background:#f8fafc;font-weight:600;}
.ok{color:#16a34a;font-weight:700;} .warn{background:#FEF3C7;border:1px solid #F59E0B;padding:12px;border-radius:8px;margin-top:20px;}
</style></head><body>
<h2>🔑 Admin Password Reset</h2>
<?php if ($rows > 0): ?>
<p class="ok">✅ Password updated for admin@skynetug.com → <strong>Admin@1234</strong></p>
<?php else: ?>
<p style="color:#dc2626;font-weight:700;">❌ No user found with email admin@skynetug.com</p>
<?php endif; ?>

<h4>All users in database:</h4>
<table>
<tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Active</th></tr>
<?php foreach ($users as $u): ?>
<tr>
    <td><?= $u['id'] ?></td>
    <td><?= htmlspecialchars($u['name']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td><?= htmlspecialchars($u['role']) ?></td>
    <td><?= $u['is_active'] ? '✅' : '❌' ?></td>
</tr>
<?php endforeach; ?>
</table>

<div class="warn">⚠️ <strong>Delete this file immediately after use!</strong></div>
</body></html>
