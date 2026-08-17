<?php
if (($_GET['t'] ?? '') !== 'sky2026') { die('no'); }
$f = dirname(__DIR__) . '/storage/logs/laravel.log';
if (!file_exists($f)) { die('log not found at: ' . $f); }
$lines = file($f);
$last = array_slice($lines, -80);
header('Content-Type: text/plain');
echo implode('', $last);
