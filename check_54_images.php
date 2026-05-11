<?php
// Script: Check DB state for the 54 images
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Checking DB State for 54 Images ===\n\n";

// 1. Total count
$total = DB::table('session_images')->count();
echo "1. Total images in DB: $total\n\n";

// 2. List first 20 images (id, title, session_image, album_category_id)
echo "2. First 20 images in DB:\n";
$images = DB::table('session_images')->select('id', 'title', 'session_image', 'album_category_id')->limit(20)->get();
foreach ($images as $img) {
    echo "   ID={$img->id}, Title='{$img->title}', File='{$img->session_image}', CatID={$img->album_category_id}\n";
}

// 3. Read CSV and try to match
echo "\n3. Matching CSV to DB:\n";
$csvFile = __DIR__ . '/photo-category-mapping.csv';
$rows = array_map('str_getcsv', file($csvFile));
$header = array_shift($rows);

$foundCount = 0;
$notFound = [];

foreach ($rows as $row) {
    $filename = $row[1];
    $titleFromFilename = pathinfo($filename, PATHINFO_FILENAME);
    
    // Search by title (exact match)
    $img = DB::table('session_images')->where('title', $titleFromFilename)->first();
    
    if ($img) {
        $foundCount++;
        echo "   ✓ Found: CSV '$filename' → DB ID={$img->id}, Title='{$img->title}', CatID={$img->album_category_id}\n";
    } else {
        $notFound[] = $filename;
        echo "   ✗ Not found: '$filename' (title: '$titleFromFilename')\n";
    }
}

echo "\nSummary: Found $foundCount/" . count($rows) . "\n";
if (count($notFound) > 0) {
    echo "\nNot found (" . count($notFound) . "):\n";
    foreach ($notFound as $f) {
        echo "   - $f\n";
    }
}

echo "\n=== Done ===\n";
