<?php
// Fix NULL width/height in session_images table

// Load Laravel's environment
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Update NULL or zero width/height
    $affected = DB::update("UPDATE session_images SET width=800, height=600 WHERE width IS NULL OR width=0 OR height IS NULL OR height=0");
    
    echo "Updated {$affected} rows with default dimensions (800x600).\n";
    
    // Verify
    $remaining = DB::select("SELECT COUNT(*) as cnt FROM session_images WHERE width IS NULL OR width=0 OR height IS NULL OR height=0");
    echo "Remaining rows with NULL/zero dimensions: " . $remaining[0]->cnt . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
