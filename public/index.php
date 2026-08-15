<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Maintenance mode
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Load from sibling skynetug folder (cPanel deployment)
// public_html/index.php → ../skynetug/ = /home3/karibupackpacker/skynetug/
require __DIR__.'/../skynetug/vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../skynetug/bootstrap/app.php';

$app->handleRequest(Request::capture());
