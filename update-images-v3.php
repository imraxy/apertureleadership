<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SessionImage;
use App\Models\AlbumCategory;

echo "=== Updating LAST 54 images from photo-category-mapping.csv ===\n\n";

$csv = array_map('str_getcsv', file(__DIR__ . '/photo-category-mapping.csv'));
$header = array_shift($csv);

$cats = AlbumCategory::all();
$catMap = [];
foreach ($cats as $c) { $catMap[$c->name] = $c->id; }

echo "Categories in DB:\n";
foreach ($catMap as $name => $id) { echo "  - $name (ID: $id)\n"; }
echo "\n";

// Get last 54 images (assuming they match CSV order)
$images = SessionImage::orderBy('id', 'desc')->take(54)->get()->reverse();
echo "Got " . $images->count() . " images from DB (last 54, reversed to match CSV order)\n\n";

$updated = 0;
$csvIndex = 0;

foreach ($images as $img) {
    if ($csvIndex >= count($csv)) break;
    
    $row = $csv[$csvIndex];
    $filename = $row[1];
    $title = pathinfo($filename, PATHINFO_FILENAME);
    $catName = $row[3];
    $catId = $catMap[$catName] ?? $row[2];
    
    echo "Row " . ($csvIndex+1) . ": $filename\n";
    echo "  DB ID: {$img->id}, Current: '{$img->title}' (Cat: {$img->album_category_id})\n";
    echo "  New: '$title' (Cat: $catName - ID: $catId)\n";
    
    $img->title = $title;
    $img->album_category_id = $catId;
    $img->save();
    
    echo "  ✓ UPDATED\n\n";
    $updated++;
    $csvIndex++;
}

echo "=== SUMMARY ===\n";
echo "Updated: $updated images\n";
echo "Done!\n";
