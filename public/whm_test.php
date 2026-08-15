<?php
/**
 * WHM Connection Test - DELETE AFTER USE
 * Visit: https://skynetug.com/whm_test.php?pass=SkyNetug2026!
 */
if (($_GET['pass'] ?? '') !== 'SkyNetug2026!') die('Access Denied');

$appPath = dirname(__DIR__) . '/skynetug';

// Parse .env
$env = [];
foreach (file($appPath . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if (str_starts_with($line, '#') || !str_contains($line, '=')) continue;
    [$k, $v] = explode('=', $line, 2);
    $env[trim($k)] = trim($v, '"\'');
}

$host     = $env['CPANEL_HOST']      ?? 'NOT SET';
$port     = $env['CPANEL_PORT']      ?? '2087';
$username = $env['CPANEL_USERNAME']  ?? 'NOT SET';
$token    = $env['CPANEL_API_TOKEN'] ?? 'NOT SET';

echo "<h2>WHM Connection Test</h2>";
echo "<p>Host: <strong>$host:$port</strong></p>";
echo "<p>Username: <strong>$username</strong></p>";
echo "<p>Token: <strong>" . substr($token, 0, 8) . "...</strong></p>";

if ($host === 'NOT SET' || $token === 'NOT SET') {
    die('<p style="color:red">❌ CPANEL settings not found in .env</p>');
}

// Test WHM API connection
$url = "https://{$host}:{$port}/json-api/version?api.version=1";
$ch  = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_HTTPHEADER     => [
        "Authorization: WHM {$username}:{$token}",
    ],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error    = curl_error($ch);
curl_close($ch);

echo "<p>HTTP Status: <strong>$httpCode</strong></p>";

if ($error) {
    echo "<p style='color:red'>❌ cURL Error: $error</p>";
} elseif ($httpCode === 200) {
    $data = json_decode($response, true);
    echo "<p style='color:green'>✅ WHM Connected Successfully!</p>";
    echo "<pre>" . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT)) . "</pre>";
} else {
    echo "<p style='color:red'>❌ Failed — HTTP $httpCode</p>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}

// Test listing accounts
echo "<h3>Listing cPanel Accounts:</h3>";
$url2 = "https://{$host}:{$port}/json-api/listaccts?api.version=1&searchtype=all";
$ch2  = curl_init($url2);
curl_setopt_array($ch2, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_HTTPHEADER     => ["Authorization: WHM {$username}:{$token}"],
]);
$resp2 = curl_exec($ch2);
$code2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);

if ($code2 === 200) {
    $data2 = json_decode($resp2, true);
    $accounts = $data2['accts'] ?? [];
    echo "<p style='color:green'>✅ Found " . count($accounts) . " cPanel accounts</p>";
    foreach (array_slice($accounts, 0, 5) as $acc) {
        echo "<p>• {$acc['user']} — {$acc['domain']}</p>";
    }
} else {
    echo "<p style='color:red'>❌ Could not list accounts — HTTP $code2</p>";
    echo "<pre>" . htmlspecialchars(substr($resp2, 0, 500)) . "</pre>";
}

echo "<br><p style='color:orange'><strong>⚠️ Delete whm_test.php after testing!</strong></p>";
