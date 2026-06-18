<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/test-error', 'GET');
try {
    $response = $kernel->handle($request);
    echo "Status: " . $response->getStatusCode();
} catch (\Throwable $e) {
    echo get_class($e) . ": " . $e->getMessage();
}
