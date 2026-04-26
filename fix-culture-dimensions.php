<?php
/**
 * Fix missing width/height for Culture category photos
 * Run this via SSH or upload to server and execute
 */

require __DIR__ . '/../application/vendor/autoload.php';

$app = require_once __DIR__ . '/../application/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

echo "Found " . count($photos) . " photos with missing dimensions in Culture category\n";

foreach ($photos as $photo) {
    $imagePath = public_path('uploads/albums/' . $photo->id . '/' . $photo->session_image);
    
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
            
            echo "Updated photo ID {$photo->id}: {$photo->title} - {$width}x{$height}\n";
        } else {
            echo "ERROR: Could not get image size for ID {$photo->id}: {$photo->title}\n";
            
            // Set default dimensions to prevent division by zero
            DB::table('session_images')
                ->where('id', $photo->id)
                ->update([
                    'width' => 800,
                    'height' => 600,
                    'updated_at' => now()
                ]);
            echo "Set default dimensions (800x600) for ID {$photo->id}\n";
        }
    } else {
        echo "ERROR: File not found for ID {$photo->id}: {$imagePath}\n";
        
        // Set default dimensions to prevent division by zero
        DB::table('session_images')
            ->where('id', $photo->id)
            ->update([
                'width' => 800,
                'height' => 600,
                'updated_at' => now()
            ]);
        echo "Set default dimensions (800x600) for ID {$photo->id}\n";
    }
}

echo "\nDone! Culture category should now work.\n";
