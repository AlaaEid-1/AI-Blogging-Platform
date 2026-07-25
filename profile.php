<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/', 'GET');
\DB::enableQueryLog();

if (\App\Models\User::count() > 0) {
    \Auth::login(\App\Models\User::first());
}

$response = $kernel->handle($request);

$queries = \DB::getQueryLog();
echo "Total Queries Executed: " . count($queries) . "\n";
$unique = [];
foreach ($queries as $q) {
    $unique[$q['query']] = ($unique[$q['query']] ?? 0) + 1;
}
foreach ($unique as $q => $count) {
    echo $count . "x: " . $q . "\n";
}
