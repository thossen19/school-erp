<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cols = DB::select('DESCRIBE notifications');
echo "Columns:\n";
foreach ($cols as $c) {
    echo "  {$c->Field} ({$c->Type})\n";
}

$count = DB::table('notifications')->count();
echo "\nTotal notifications: $count\n";

$rows = DB::table('notifications')->limit(3)->get();
foreach ($rows as $r) {
    echo "\n---\n";
    foreach ($r as $k => $v) {
        echo "  $k: " . substr((string)$v, 0, 200) . "\n";
    }
}
