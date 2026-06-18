<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
echo 'admission_enquiries: ' . DB::table('admission_enquiries')->count() . PHP_EOL;
echo 'classes: ' . DB::table('classes')->count() . PHP_EOL;
echo 'teachers: ' . DB::table('teachers')->count() . PHP_EOL;
