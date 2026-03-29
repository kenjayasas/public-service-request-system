<?php

define('LARAVEL_START', microtime(true));

// Vercel's serverless filesystem is read-only except /tmp
// Set up writable directories for Laravel's runtime files
$tmpStorage = '/tmp/laravel-storage';
foreach (['framework/sessions', 'framework/cache/data', 'framework/views', 'logs', 'app'] as $dir) {
    $path = "$tmpStorage/$dir";
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->useStoragePath($tmpStorage);

$app->handleRequest(\Illuminate\Http\Request::capture());
