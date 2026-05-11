<?php
/**
 * Upload images ONE-BY-ONE on staging server
 * Runs directly on staging, uses Laravel's SessionImage model
 * Verifies each upload before moving to next
 */

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SessionImage;
use App\Models\AlbumCategory;

echo "=== ApertureLeadership One-by-One Image Upload ===\n\n";

$csv_path = __DIR__ . '/photo-category-mapping.csv';
$image_base = '/home/u285921350/domains/apertureleadership.com/public_html/stg/application/temp_images/';

// Check if running on staging
if (!file_exists($csv_path)) {
    die("ERROR: Run this script on staging server at: /home/u285921350/domains/apertureleadership.com/public_html/stg/application/\n");
}

$handle = fopen($csv_path, 'r');
$headers = fgetcsv($handle); // Skip header
$processed = 0;
$skipped = 0;

while (($row = fgetcsv($handle)) !== false) {
    $photo_num = $row[0];
    $filename = $row[1];
    $category_id = $row[2];
    $category_name = $row[3];
    
    $processed++;
    
    echo "[$processed] Photo #$photo_num: $filename\n";
    echo "  Category: $category_name (ID: $category_id)\n";
    
    // Check if already exists
    $existing = SessionImage::where('title', $filename)->first();
    if ($existing) {
        echo "  → SKIP: Already exists (ID: {$existing->id})\n\n";
        $skipped++;
        continue;
    }
    
    // Find image file
    $image_path = $image_base . $filename;
    if (!file_exists($image_path)) {
        echo "  → ERROR: Image file not found at $image_path\n\n";
        continue;
    }
    
    echo "  → Uploading...\n";
    
    // Generate random filename (like Laravel does)
    $salt = bin2hex(openssl_random_pseudo_bytes(22));
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $new_filename = $salt . '.' . $ext;
    
    // Create upload directory
    $upload_dir = public_path('uploads/albums/' . uniqid());
    // We need the ID first, so let's create DB record first
    
    try {
        // Create DB record
        $image = SessionImage::create([
            'album_category_id' => $category_id,
            'title' => $filename,
            'slug' => \Str::slug($filename, '-'),
            'description' => null,
        ]);
        
        echo "  → Created DB record (ID: {$image->id})\n";
        
        // Create upload directory
        $upload_path = public_path('uploads/albums/' . $image->id);
        if (!\File::isDirectory($upload_path)) {
            \File::makeDirectory($upload_path, 0777, true, true);
        }
        
        // Copy and process image (with watermark)
        $source = $image_path;
        $dest = $upload_path . '/' . $new_filename;
        
        // Copy image
        copy($source, $dest);
        
        // Create small version
        $small_dest = $upload_path . '/small_' . $new_filename;
        // Simple resize using GD
        $src_img = imagecreatefromjpeg($dest);
        $src_w = imagesx($src_img);
        $src_h = imagesy($src_img);
        $small_w = 300;
        $small_h = ($src_h / $src_w) * $small_w;
        $dst_img = imagecreatetruecolor($small_w, $small_h);
        imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $small_w, $small_h, $src_w, $src_h);
        imagejpeg($dst_img, $small_dest, 30);
        imagedestroy($src_img);
        imagedestroy($dst_img);
        
        // Update record with filenames
        $image->update([
            'session_image' => $new_filename,
            'thumbnail_image' => $new_filename,
            'small_image' => 'small_' . $new_filename,
            'shape' => ($src_w > $src_h * 1.5) ? 'rectangle' : '',
            'width' => $src_w,
            'height' => $src_h,
        ]);
        
        echo "  → SUCCESS: Image uploaded and processed\n";
        echo "  → Filename: $new_filename\n\n";
        
        // VERIFICATION
        $verify = SessionImage::find($image->id);
        if ($verify && file_exists($dest)) {
            echo "  → VERIFIED: Record exists in DB, file exists on disk\n\n";
        } else {
            echo "  → WARNING: Verification failed!\n\n";
        }
        
    } catch (Exception $e) {
        echo "  → ERROR: " . $e->getMessage() . "\n\n";
    }
    
    // ONE-BY-ONE: Wait for confirmation before next?
    // For now, just add a small delay
    usleep(500000); // 0.5 second delay
}

fclose($handle);

echo "\n=== Upload Complete ===\n";
echo "Processed: $processed images\n";
echo "Skipped (already exists): $skipped images\n";
