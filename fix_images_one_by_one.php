<?php
// Script: T06-T07 Fix image-title mismatches and re-categorize (one-at-a-time with verification)
// Run from staging application root: php fix_images_one_by_one.php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\SessionImage;
use App\Models\AlbumCategory;

echo "=== T06-T07: Fix 54 Image Titles & Categories (One-at-a-time) ===\n\n";

// 1. Read CSV
$csvFile = __DIR__ . '/photo-category-mapping.csv';
if (!file_exists($csvFile)) {
    die("ERROR: CSV file not found at $csvFile\n");
}

$rows = array_map('str_getcsv', file($csvFile));
$header = array_shift($rows); // Remove header

echo "1. CSV loaded: " . count($rows) . " images to process\n\n";

// 2. Get category mapping (name → id)
$categories = AlbumCategory::all()->pluck('id', 'name')->toArray();
echo "2. Categories: " . json_encode($categories) . "\n\n";

// 3. Process each image ONE at a time
$successCount = 0;
$failCount = 0;
$results = [];

foreach ($rows as $index => $row) {
    $photoNum = $row[0];
    $filename = $row[1];
    $csvCategoryId = $row[2];
    $csvCategoryName = $row[3];
    
    echo "--- Processing #$photoNum: $filename ---\n";
    
    // Find the image in DB by filename (session_image field)
    // The filename in DB might be stored differently, let's search
    $image = SessionImage::where('session_image', $filename)
        ->orWhere('session_image', basename($filename))
        ->first();
    
    if (!$image) {
        // Try searching by title (maybe title contains the filename)
        $image = SessionImage::where('title', 'like', '%' . pathinfo($filename, PATHINFO_FILENAME) . '%')->first();
    }
    
    if (!$image) {
        echo "   ✗ NOT FOUND in DB (filename: $filename)\n\n";
        $failCount++;
        $results[] = ['#', $photoNum, $filename, 'NOT FOUND', '', 'FAIL'];
        continue;
    }
    
    echo "   Found: ID={$image->id}, Current Title='{$image->title}', Current CatID={$image->album_category_id}\n";
    
    // Verify/update title (use filename without extension as title?)
    $expectedTitle = pathinfo($filename, PATHINFO_FILENAME);
    $titleUpdated = false;
    if ($image->title !== $expectedTitle) {
        $image->title = $expectedTitle;
        $titleUpdated = true;
        echo "   → Title updated: '$expectedTitle'\n";
    } else {
        echo "   ✓ Title correct: '$expectedTitle'\n";
    }
    
    // Verify/update category
    $categoryUpdated = false;
    if ($image->album_category_id != $csvCategoryId) {
        $image->album_category_id = $csvCategoryId;
        $categoryUpdated = true;
        echo "   → Category updated: ID $csvCategoryId ($csvCategoryName)\n";
    } else {
        echo "   ✓ Category correct: ID $csvCategoryId ($csvCategoryName)\n";
    }
    
    // Save if changes made
    if ($titleUpdated || $categoryUpdated) {
        try {
            $image->save();
            echo "   ✅ SAVED successfully\n\n";
            $successCount++;
            $results[] = ['#', $photoNum, $filename, $expectedTitle, $csvCategoryName, 'SUCCESS'];
        } catch (Exception $e) {
            echo "   ✗ SAVE FAILED: " . $e->getMessage() . "\n\n";
            $failCount++;
            $results[] = ['#', $photoNum, $filename, $expectedTitle, $csvCategoryName, 'FAIL'];
        }
    } else {
        echo "   ✓ No changes needed\n\n";
        $successCount++;
        $results[] = ['#', $photoNum, $filename, $image->title, $csvCategoryName, 'OK'];
    }
    
    // VERIFICATION: Re-read from DB to confirm
    $verify = SessionImage::find($image->id);
    if ($verify) {
        $correct = ($verify->title === $expectedTitle) && ($verify->album_category_id == $csvCategoryId);
        echo "   Verification: " . ($correct ? "✅ PASS" : "✗ FAIL") . "\n";
        echo "      DB Title='{$verify->title}', DB CatID={$verify->album_category_id}\n\n";
    }
}

// Summary
echo "=== SUMMARY ===\n";
echo "Total: " . count($rows) . "\n";
echo "Success: $successCount\n";
echo "Failed: $failCount\n\n";

echo "Detailed Results:\n";
echo str_pad('#', 4) . str_pad('Photo#', 8) . str_pad('Filename', 40) . str_pad('Title', 30) . str_pad('Category', 15) . "Status\n";
echo str_repeat('-', 100) . "\n";
foreach ($results as $r) {
    echo str_pad($r[0], 4) . str_pad($r[1], 8) . str_pad($r[2], 40) . str_pad($r[3], 30) . str_pad($r[4], 15) . "$r[5]\n";
}

echo "\n=== Done ===\n";
