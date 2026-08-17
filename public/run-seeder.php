<?php
define('SECRET', 'SkyNetug2026Deploy');
$token = $_GET['token'] ?? '';
if ($token !== SECRET) { http_response_code(403); die('403'); }

// ── Load Laravel .env to get DB credentials ──────────────────────────────────
$envPaths = [
    __DIR__ . '/.env',
    dirname(__DIR__) . '/.env',
    '/home/skynetug/public_html/.env',
    '/home/fskynetug/public_html/.env',
];

$env = [];
foreach ($envPaths as $envFile) {
    if (file_exists($envFile)) {
        foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with(trim($line), '#')) continue;
            if (str_contains($line, '=')) {
                [$k, $v] = explode('=', $line, 2);
                $env[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
            }
        }
        break;
    }
}

$host   = $env['DB_HOST']     ?? 'localhost';
$port   = $env['DB_PORT']     ?? '3306';
$dbname = $env['DB_DATABASE'] ?? '';
$user   = $env['DB_USERNAME'] ?? '';
$pass   = $env['DB_PASSWORD'] ?? '';

if (!$dbname) { die('<h2 style="color:red">.env not found or DB not configured</h2>'); }

try {
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
} catch (Exception $e) {
    die('<h2 style="color:red">DB Connection failed: ' . htmlspecialchars($e->getMessage()) . '</h2>');
}

$results = [];

// ── Helper ────────────────────────────────────────────────────────────────────
function run(PDO $pdo, string $label, string $sql, array $params = []): void {
    global $results;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $results[] = ['ok' => true,  'label' => $label, 'msg' => 'Done (' . $stmt->rowCount() . ' rows affected)'];
    } catch (Exception $e) {
        $results[] = ['ok' => false, 'label' => $label, 'msg' => $e->getMessage()];
    }
}

// ── 1. Admin user ─────────────────────────────────────────────────────────────
// bcrypt hash of "Admin@1234"
$adminHash = '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
// Check if already exists
$exists = $pdo->query("SELECT COUNT(*) FROM users WHERE email='admin@skynetug.com'")->fetchColumn();
if (!$exists) {
    run($pdo, 'Create super_admin', "INSERT INTO users (name,email,password,role,phone,country,city,is_active,referral_code,email_verified_at,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,NOW(),NOW(),NOW())",
        ['Super Admin','admin@skynetug.com',$adminHash,'super_admin','+256700000001','UG','Kampala',1,'ADMIN001']);
} else {
    $results[] = ['ok' => true, 'label' => 'super_admin', 'msg' => 'Already exists — skipped'];
}

// ── 2. Billing manager ────────────────────────────────────────────────────────
$billingHash = '$2y$12$TKnKNmxnxlJsFGWnmRUOr.QH1Hv5BEm6n7Jv8U1tKoRsR3p5Vqe6'; // Billing@1234
$exists2 = $pdo->query("SELECT COUNT(*) FROM users WHERE email='billing@skynetug.com'")->fetchColumn();
if (!$exists2) {
    run($pdo, 'Create billing_manager', "INSERT INTO users (name,email,password,role,phone,country,is_active,referral_code,email_verified_at,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,NOW(),NOW(),NOW())",
        ['Billing Manager','billing@skynetug.com',$billingHash,'billing_manager','+256700000002','UG',1,'BILL0001']);
} else {
    $results[] = ['ok' => true, 'label' => 'billing_manager', 'msg' => 'Already exists — skipped'];
}

// ── 3. Hosting packages ───────────────────────────────────────────────────────
$pkgCount = $pdo->query("SELECT COUNT(*) FROM hosting_packages")->fetchColumn();
if ($pkgCount == 0) {
    $packages = [
        ['Starter Hosting',     'starter',      'shared',    18000,  180000,  324000,  5120,   5,  1,  'Perfect for personal websites'],
        ['Business Hosting',    'business',     'shared',    35000,  360000,  648000,  20480,  20, 5,  'Ideal for growing businesses'],
        ['Professional Hosting','professional', 'shared',    65000,  660000,  1188000, 51200,  50, 20, 'For high-traffic sites'],
        ['Unlimited Hosting',   'unlimited',    'shared',    90000,  960000,  1728000, 0,      0,  0,  'Unlimited resources'],
        ['WordPress Starter',   'wp-starter',   'wordpress', 20000,  200000,  360000,  10240,  10, 2,  'Fast WordPress hosting'],
        ['WordPress Business',  'wp-business',  'wordpress', 40000,  400000,  720000,  30720,  30, 10, 'Business WordPress'],
        ['Email Starter',       'email-starter','email',     10000,  100000,  180000,  5120,   5,  0,  '5 email accounts'],
        ['Email Business',      'email-business','email',    20000,  200000,  360000,  20480,  20, 0,  '20 email accounts'],
        ['VPS Starter',         'vps-starter',  'vps',       120000, 1200000, 2160000, 25600,  0,  0,  'Entry VPS'],
        ['VPS Business',        'vps-business', 'vps',       220000, 2200000, 3960000, 51200,  0,  0,  'Business VPS'],
    ];

    $i = 0;
    foreach ($packages as $p) {
        run($pdo, "Package: {$p[0]}", "INSERT INTO hosting_packages (name,slug,type,price_monthly,price_yearly,price_biennially,disk_space_mb,email_accounts,`databases`,description,ssl_included,softaculous_included,is_active,is_featured,sort_order,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,?,1,1,1,?,?,NOW(),NOW())",
            [$p[0],$p[1],$p[2],$p[3],$p[4],$p[5],$p[6],$p[7],$p[8],$p[9], ($i===1||$i===4) ? 1 : 0, $i]);
        $i++;
    }
} else {
    $results[] = ['ok' => true, 'label' => 'Hosting packages', 'msg' => "Already have {$pkgCount} packages — skipped"];
}

// ── 4. Welcome announcement ───────────────────────────────────────────────────
$annCount = $pdo->query("SELECT COUNT(*) FROM announcements")->fetchColumn();
if ($annCount == 0) {
    run($pdo, 'Announcement', "INSERT INTO announcements (title,content,status,created_by,published_at,created_at,updated_at) VALUES (?,?,?,?,NOW(),NOW(),NOW())",
        ['Welcome to SkyNetug!', "We're excited to welcome you to SkyNetug, Uganda's fastest growing web hosting provider. Enjoy fast, reliable hosting with 24/7 local support.", 'published', 1]);
}

// ── 5. Default server ─────────────────────────────────────────────────────────
$srvCount = $pdo->query("SELECT COUNT(*) FROM servers")->fetchColumn();
if ($srvCount == 0) {
    run($pdo, 'Default server', "INSERT INTO servers (name,hostname,ip_address,type,username,max_accounts,ns1,ns2,is_active,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,?,NOW(),NOW())",
        ['SkyNetug Main Server','server1.skynetug.com','197.157.0.1','shared','root',500,'ns1.skynetug.com','ns2.skynetug.com',1]);
}

// ── 6. Coupon ─────────────────────────────────────────────────────────────────
$couponCount = $pdo->query("SELECT COUNT(*) FROM coupons")->fetchColumn();
if ($couponCount == 0) {
    run($pdo, 'Welcome coupon', "INSERT INTO coupons (code,name,type,value,usage_limit,usage_count,expires_at,is_active,created_at,updated_at) VALUES (?,?,?,?,?,?,?,?,NOW(),NOW())",
        ['WELCOME20','20% off for new customers','percentage',20,100,0,date('Y-m-d', strtotime('+3 months')),1]);
}
?>
<!DOCTYPE html><html><head><title>SkyNetug Seeder</title>
<style>
body{font-family:sans-serif;padding:30px;background:#f1f5f9;max-width:800px;}
.ok  {color:#16a34a;font-weight:700;}
.err {color:#dc2626;font-weight:700;}
.card{background:#fff;border-radius:8px;padding:14px 18px;margin-bottom:8px;box-shadow:0 1px 4px rgba(0,0,0,.07);}
.warn{background:#FEF3C7;border:1px solid #F59E0B;padding:12px;border-radius:8px;margin-top:24px;}
</style></head><body>
<h2>🌱 SkyNetug — Database Seed Results</h2>
<p style="color:#6b7280;font-size:.9rem;">Connected to: <strong><?= htmlspecialchars($dbname) ?></strong></p>
<?php foreach ($results as $r): ?>
<div class="card">
    <span class="<?= $r['ok'] ? 'ok' : 'err' ?>"><?= $r['ok'] ? '✅' : '❌' ?> <?= htmlspecialchars($r['label']) ?></span>
    <span style="color:#6b7280;font-size:.85rem;margin-left:12px;"><?= htmlspecialchars($r['msg']) ?></span>
</div>
<?php endforeach; ?>
<div class="warn">
    ⚠️ <strong>Delete this file immediately!</strong><br>
    Then log in at <a href="/login">/login</a> with:<br>
    Email: <code>admin@skynetug.com</code> &nbsp; Password: <code>Admin@1234</code>
</div>
</body></html>
