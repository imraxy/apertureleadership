<?php
/**
 * Fix missing width/height for Culture category photos
 * Upload this to public_html and access via browser
 * DELETE AFTER USE!
 */

require __DIR__ . '/application/vendor/autoload.php';

$app = require_once __DIR__ . '/application/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "<h1>Fixing Culture Category Photos</h1>";
echo "<pre>";

// Get all Culture category photos (ID = 5) with missing dimensions
$photos = DB::table('session_images')
    ->where('album_category_id', 5)
    ->where(function ($query) {
        $query->whereNull('width')
              ->orWhere('width', 0)
              ->orWhereNull('height')
              ->orWhere('height', 0);
    })
    ->get();

echo "Found " . count($photos) . " photos with missing dimensions in Culture category\n\n";

$fixed = 0;
$errors = 0;

foreach ($photos as $photo) {
    $imagePath = public_path('uploads/albums/' . $photo->id . '/' . $photo->session_image);
    
    echo "Processing ID {$photo->id}: {$photo->title}\n";
    echo "  Path: {$imagePath}\n";
    
    if (file_exists($imagePath)) {
        $imageInfo = getimagesize($imagePath);
        if ($imageInfo) {
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            
            DB::table('session_images')
                ->where('id', $photo->id)
                ->update([
                    'width' => $width,
                    'height' => $height,
                    'updated_at' => now()
                ]);
            
            echo "  ✓ Updated: {$width}x{$height}\n";
            $fixed++;
        } else {
            echo "  ✗ Could not get image size\n";
            
            // Set default dimensions
            DB::table('session_images')
                ->where('id', $photo->id)
                ->update([
                    'width' => 800,
                    'height' => 600,
                    'updated_at' => now()
                ]);
            echo "  → Set default: 800x600\n";
            $fixed++;
        }
    } else {
        echo "  ✗ File not found\n";
        
        // Set default dimensions
        DB::table('session_images')
            ->where('id', $photo->id)
            ->update([
                'width' => 800,
                'height' => 600,
                'updated_at' => now()
            ]);
        echo "  → Set default: 800x600\n";
        $fixed++;
    }
    echo "\n";
}

echo "\n";
echo "========================================\n";
echo "SUMMARY\n";
echo "========================================\n";
echo "Total photos checked: " . count($photos) . "\n";
echo "Photos fixed: {$fixed}\n";
echo "Errors: {$errors}\n";
echo "\n";
echo "<strong>Culture category should now work!</strong>\n";
echo "<strong>DELETE THIS FILE AFTER USE!</strong>\n";
echo "</pre>";
