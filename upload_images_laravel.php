<?php
/**
 * Bulk Image Uploader for ApertureLeadership
 * 
 * This script uses the Laravel application directly to upload images
 * Run from within the Laravel application directory
 */

require __DIR__ . '/public_html/application/vendor/autoload.php';
require __DIR__ . '/public_html/application/bootstrap/app.php';

$app = require_once __DIR__ . '/public_html/application/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

// Paths
$UPLOAD_BASE = __DIR__ . '/public_html/application/public/uploads/albums/';
$PHOTO_DIR = __DIR__ . '/wetransfer_new-aperture-photos_2026-04-08_1837/Small/';

// Read CSV mapping
$images = [];
$csvFile = fopen(__DIR__ . '/photo-category-mapping.csv', 'r');
$header = fgetcsv($csvFile);
while (($row = fgetcsv($csvFile)) !== false) {
    $images[] = [
        'filename' => $row[1],
        'category_id' => $row[2],
    ];
}
fclose($csvFile);

echo "Found " . count($images) . " images to upload\n";
echo "Starting upload process...\n\n";

$successCount = 0;
$errorCount = 0;
$skippedCount = 0;

foreach ($images as $index => $imageData) {
    $filename = $imageData['filename'];
    $categoryId = (int)$imageData['category_id'];
    $sourcePath = $PHOTO_DIR . $filename;
    
    echo "[$index] Processing: $filename\n";
    
    // Check if source file exists
    if (!file_exists($sourcePath)) {
        echo "  ❌ ERROR: Source file not found\n";
        $errorCount++;
        continue;
    }
    
    try {
        // Extract title from filename (remove extension)
        $title = pathinfo($filename, PATHINFO_FILENAME);
        $slug = Str::slug($title, '-');
        
        // Check if image already exists in database
        $existing = DB::table('session_images')->where('title', $title)->first();
        if ($existing) {
            echo "  ⚠️  SKIPPED: Image already exists (ID: {$existing->id})\n";
            $skippedCount++;
            continue;
        }
        
        // Get image info
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) {
            echo "  ❌ ERROR: Could not get image info\n";
            $errorCount++;
            continue;
        }
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        
        // Determine shape
        $maxWidth = $height * 1.5;
        $shape = ($maxWidth <= $width) ? 'rectangle' : '';
        
        // Create database record first
        $now = now();
        $recordId = DB::table('session_images')->insertGetId([
            'album_category_id' => $categoryId,
            'title' => $title,
            'description' => null,
            'slug' => $slug,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        
        echo "  ✓ Database record created (ID: $recordId)\n";
        
        // Create upload directory
        $uploadPath = $UPLOAD_BASE . $recordId . '/';
        if (!File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0777, true, true);
        }
        
        // Generate unique filename
        $salt = bin2hex(openssl_random_pseudo_bytes(22));
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $newFilename = $salt . '.' . $extension;
        
        // Copy original image as-is (NO watermark processing)
        $mainPath = $uploadPath . $newFilename;
        File::copy($sourcePath, $mainPath);
        echo "  ✓ Main image saved: $newFilename\n";
        
        // Create small thumbnail (30% quality) using GD
        $smallPath = $uploadPath . 'small_' . $newFilename;
        $smallQuality = 30;
        
        $srcImage = null;
        if ($extension == 'jpg' || $extension == 'jpeg') {
            $srcImage = imagecreatefromjpeg($sourcePath);
        } elseif ($extension == 'png') {
            $srcImage = imagecreatefrompng($sourcePath);
        }
        
        if ($srcImage) {
            imagejpeg($srcImage, $smallPath, $smallQuality);
            imagedestroy($srcImage);
            echo "  ✓ Thumbnail saved: small_$newFilename\n";
        } else {
            File::copy($sourcePath, $smallPath);
            echo "  ⚠️  Thumbnail copied (no resize): small_$newFilename\n";
        }
        
        // Update database record with image paths
        DB::table('session_images')
            ->where('id', $recordId)
            ->update([
                'session_image' => $newFilename,
                'thumbnail_image' => $newFilename,
                'small_image' => 'small_' . $newFilename,
                'width' => $width,
                'height' => $height,
                'shape' => $shape,
            ]);
        
        echo "  ✓ Database updated\n";
        echo "  ✓ Upload complete!\n\n";
        $successCount++;
        
    } catch (Exception $e) {
        echo "  ❌ ERROR: " . $e->getMessage() . "\n\n";
        $errorCount++;
    }
}

echo "\n========================================\n";
echo "UPLOAD COMPLETE\n";
echo "========================================\n";
echo "Success: $successCount\n";
echo "Skipped: $skippedCount\n";
echo "Errors: $errorCount\n";
echo "Total: " . count($images) . "\n";
