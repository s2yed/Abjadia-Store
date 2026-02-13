<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$product = Product::first();
if ($product) {
    echo "Product JSON:\n";
    echo json_encode($product, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    echo "No product found.\n";
}
