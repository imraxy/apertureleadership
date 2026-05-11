<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SessionImage;
use App\Models\AlbumCategory;

echo "=== Updating images from photo-category-mapping.csv ===\n\n";

$csv = array_map('str_getcsv', file(__DIR__ . '/photo-category-mapping.csv'));
$header = array_shift($csv);

$cats = AlbumCategory::all();
$catMap = [];
foreach ($cats as $c) { $catMap[$c->name] = $c->id; }

echo "Categories in DB:\n";
foreach ($catMap as $name => $id) { echo "  - $name (ID: $id)\n"; }
echo "\n";

$updated = 0;
$notFound = 0;

foreach ($csv as $row) {
    $filename = $row[1];
    $title = pathinfo($filename, PATHINFO_FILENAME);
    $catName = $row[3];
    $catId = $catMap[$catName] ?? $row[2];
    
    // Search by session_image column
    $img = SessionImage::where('session_image', 'LIKE', "%$filename")->first();
    
    if (!$img) {
        echo "NOT FOUND: $filename\n";
        $notFound++;
        continue;
    }
    
    $oldTitle = $img->title;
    $oldCatId = $img->album_category_id;
    
    $img->title = $title;
    $img->album_category_id = $catId;
    $img->save();
    
    echo "UPDATED: $filename\n";
    echo "  Title: '$oldTitle' → '$title'\n";
    echo "  Category: $oldCatId → $catId ($catName)\n\n";
    $updated++;
}

echo "=== SUMMARY ===\n";
echo "Updated: $updated images\n";
echo "Not found: $notFound images\n";
echo "Done!\n";
