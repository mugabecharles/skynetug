<?php
if (($_GET['pass'] ?? '') !== 'SkyNetug2026!') die('Access Denied');

$appPath = dirname(__DIR__) . '/skynetug';

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
$kernel->bootstrap();

use App\Models\HostingPackage;

$packages = [
    ['name'=>'Starter Hosting',     'slug'=>'starter',      'type'=>'shared',    'price_monthly'=>5,    'price_yearly'=>50,    'disk_space_mb'=>5120,  'email_accounts'=>5,   'databases'=>1,  'softaculous_included'=>true, 'ssl_included'=>true, 'is_featured'=>false,'sort_order'=>1,'description'=>'Perfect for personal websites and blogs'],
    ['name'=>'Business Hosting',    'slug'=>'business',     'type'=>'shared',    'price_monthly'=>10,   'price_yearly'=>100,   'disk_space_mb'=>20480, 'email_accounts'=>20,  'databases'=>5,  'softaculous_included'=>true, 'ssl_included'=>true, 'is_featured'=>true, 'sort_order'=>2,'description'=>'Ideal for growing businesses'],
    ['name'=>'Professional Hosting','slug'=>'professional', 'type'=>'shared',    'price_monthly'=>18,   'price_yearly'=>180,   'disk_space_mb'=>51200, 'email_accounts'=>50,  'databases'=>20, 'softaculous_included'=>true, 'ssl_included'=>true, 'is_featured'=>false,'sort_order'=>3,'description'=>'For high-traffic professional sites'],
    ['name'=>'Unlimited Hosting',   'slug'=>'unlimited',    'type'=>'shared',    'price_monthly'=>25,   'price_yearly'=>250,   'disk_space_mb'=>0,     'email_accounts'=>0,   'databases'=>0,  'softaculous_included'=>true, 'ssl_included'=>true, 'backup_included'=>true,'is_featured'=>false,'sort_order'=>4,'description'=>'Unlimited resources'],
    ['name'=>'WordPress Starter',   'slug'=>'wp-starter',   'type'=>'wordpress', 'price_monthly'=>6,    'price_yearly'=>60,    'disk_space_mb'=>10240, 'email_accounts'=>10,  'databases'=>2,  'softaculous_included'=>true, 'ssl_included'=>true, 'is_featured'=>false,'sort_order'=>5,'description'=>'Fast WordPress hosting'],
    ['name'=>'WordPress Business',  'slug'=>'wp-business',  'type'=>'wordpress', 'price_monthly'=>12,   'price_yearly'=>120,   'disk_space_mb'=>30720, 'email_accounts'=>30,  'databases'=>10, 'softaculous_included'=>true, 'ssl_included'=>true, 'backup_included'=>true,'is_featured'=>true,'sort_order'=>6,'description'=>'Optimised for business WordPress'],
    ['name'=>'Managed WordPress',   'slug'=>'wp-managed',   'type'=>'wordpress', 'price_monthly'=>22,   'price_yearly'=>220,   'disk_space_mb'=>0,     'email_accounts'=>0,   'databases'=>0,  'softaculous_included'=>true, 'ssl_included'=>true, 'backup_included'=>true,'is_featured'=>false,'sort_order'=>7,'description'=>'Fully managed WordPress'],
    ['name'=>'VPS Starter',         'slug'=>'vps-starter',  'type'=>'vps',       'price_monthly'=>32,   'price_yearly'=>320,   'disk_space_mb'=>25600, 'email_accounts'=>0,   'databases'=>0,  'features'=>['1 vCPU','1 GB RAM','Full Root Access'],'is_featured'=>false,'sort_order'=>8,'description'=>'Entry VPS'],
    ['name'=>'VPS Business',        'slug'=>'vps-business', 'type'=>'vps',       'price_monthly'=>59,   'price_yearly'=>590,   'disk_space_mb'=>51200, 'email_accounts'=>0,   'databases'=>0,  'features'=>['2 vCPU','2 GB RAM','Full Root Access'],'is_featured'=>true, 'sort_order'=>9,'description'=>'Powerful VPS'],
    ['name'=>'Email Starter',       'slug'=>'email-starter','type'=>'email',     'price_monthly'=>3,    'price_yearly'=>30,    'disk_space_mb'=>5120,  'email_accounts'=>5,   'databases'=>0,  'is_featured'=>false,'sort_order'=>10,'description'=>'5 professional email accounts'],
    ['name'=>'Email Business',      'slug'=>'email-business','type'=>'email',    'price_monthly'=>6,    'price_yearly'=>60,    'disk_space_mb'=>20480, 'email_accounts'=>20,  'databases'=>0,  'is_featured'=>false,'sort_order'=>11,'description'=>'20 email accounts'],
    ['name'=>'DV SSL Certificate',  'slug'=>'ssl-dv',       'type'=>'ssl',       'price_monthly'=>0,    'price_yearly'=>40,    'disk_space_mb'=>0,     'email_accounts'=>0,   'databases'=>0,  'is_featured'=>false,'sort_order'=>12,'description'=>'Domain Validation SSL'],
    ['name'=>'Wildcard SSL',        'slug'=>'ssl-wildcard', 'type'=>'ssl',       'price_monthly'=>0,    'price_yearly'=>135,   'disk_space_mb'=>0,     'email_accounts'=>0,   'databases'=>0,  'is_featured'=>false,'sort_order'=>13,'description'=>'Wildcard SSL certificate'],
];

$created = 0;
$skipped = 0;

foreach ($packages as $pkg) {
    $existing = HostingPackage::where('slug', $pkg['slug'])->first();
    if ($existing) { $skipped++; continue; }

    HostingPackage::create(array_merge([
        'price_biennially'     => ($pkg['price_yearly'] ?? 0) * 1.8,
        'bandwidth_mb'         => 0,
        'subdomains'           => 0,
        'addon_domains'        => 0,
        'parked_domains'       => 0,
        'ssl_included'         => $pkg['ssl_included'] ?? false,
        'softaculous_included' => $pkg['softaculous_included'] ?? false,
        'backup_included'      => $pkg['backup_included'] ?? false,
        'features'             => $pkg['features'] ?? [],
        'is_featured'          => $pkg['is_featured'] ?? false,
        'is_active'            => true,
        'sort_order'           => $pkg['sort_order'] ?? 0,
    ], $pkg));
    $created++;
}

echo "<h2 style='font-family:sans-serif;color:green'>✅ Done!</h2>";
echo "<p>Created: <strong>$created</strong> packages | Skipped (already exist): <strong>$skipped</strong></p>";
echo "<p><a href='/hosting/shared'>View Hosting Plans</a> | <a href='/admin/packages'>Admin Packages</a></p>";
echo "<p style='color:orange'><strong>Delete seed_packages.php after use!</strong></p>";
