<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Offer;

echo "Offer Types:\n";
$types = Offer::pluck('type')->unique();
foreach ($types as $type) {
    echo "- '$type'\n";
}

echo "\nFirst Offer Actions:\n";
$offer = Offer::first();
if ($offer) {
    print_r($offer->actions);
} else {
    echo "No offers found.\n";
}
