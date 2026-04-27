<?php
/**
 * Bulk Image Uploader for ApertureLeadership
 * 
 * This script uploads images directly to the database and file system
 * Uses mysqli for database connection (no PDO required)
 */

// Database Configuration
$DB_HOST = 'localhost';
$DB_DATABASE = 'u285921350_new';
$DB_USERNAME = 'u285921350_new';
$DB_PASSWORD = '@pnj0auE/2';

// Paths
$UPLOAD_BASE = __DIR__ . '/public_html/application/public/uploads/albums/';
$PHOTO_DIR = __DIR__ . '/wetransfer_new-aperture-photos_2026-04-08_1837/Small/';

// Category mapping
$CATEGORY_MAP = [
    'People' => 1,
    'Architecture' => 2,
    'Landscapes' => 3,
    'Symbols' => 4,
    'Culture' => 5,
];

// Connect to database
$conn = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected to database successfully\n\n";

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
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $title));
        
        // Check if image already exists in database
        $checkStmt = $conn->prepare("SELECT id FROM session_images WHERE title = ?");
        $checkStmt->bind_param("s", $title);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            $existing = $result->fetch_assoc();
            echo "  ⚠️  SKIPPED: Image already exists (ID: {$existing['id']})\n";
            $skippedCount++;
            continue;
        }
        $checkStmt->close();
        
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
        $now = date('Y-m-d H:i:s');
        $insertStmt = $conn->prepare("INSERT INTO session_images (album_category_id, title, description, slug, created_at, updated_at) VALUES (?, ?, NULL, ?, ?, ?)");
        $insertStmt->bind_param("issss", $categoryId, $title, $slug, $now, $now);
        
        if (!$insertStmt->execute()) {
            echo "  ❌ ERROR: Database insert failed: " . $insertStmt->error . "\n";
            $errorCount++;
            continue;
        }
        
        $recordId = $insertStmt->insert_id;
        $insertStmt->close();
        
        echo "  ✓ Database record created (ID: $recordId)\n";
        
        // Create upload directory
        $uploadPath = $UPLOAD_BASE . $recordId . '/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        // Generate unique filename
        $salt = bin2hex(openssl_random_pseudo_bytes(22));
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $newFilename = $salt . '.' . $extension;
        
        // Copy original image as-is (NO watermark processing)
        $mainPath = $uploadPath . $newFilename;
        if (!copy($sourcePath, $mainPath)) {
            echo "  ❌ ERROR: Failed to copy main image\n";
            $errorCount++;
            continue;
        }
        echo "  ✓ Main image saved: $newFilename\n";
        
        // Create small thumbnail (30% quality)
        $smallPath = $uploadPath . 'small_' . $newFilename;
        $smallQuality = 30;
        
        // Use GD to create smaller version
        $srcImage = null;
        if ($extension == 'jpg' || $extension == 'jpeg') {
            $srcImage = imagecreatefromjpeg($sourcePath);
        } elseif ($extension == 'png') {
            $srcImage = imagecreatefrompng($sourcePath);
        }
        
        if ($srcImage) {
            // Save with reduced quality
            imagejpeg($srcImage, $smallPath, $smallQuality);
            imagedestroy($srcImage);
            echo "  ✓ Thumbnail saved: small_$newFilename\n";
        } else {
            // Fallback: just copy
            copy($sourcePath, $smallPath);
            echo "  ⚠️  Thumbnail copied (no resize): small_$newFilename\n";
        }
        
        // Update database record with image paths
        $updateStmt = $conn->prepare("UPDATE session_images SET session_image = ?, thumbnail_image = ?, small_image = ?, width = ?, height = ?, shape = ? WHERE id = ?");
        $smallFilename = 'small_' . $newFilename;
        $updateStmt->bind_param("sssiisi", $newFilename, $newFilename, $smallFilename, $width, $height, $shape, $recordId);
        
        if (!$updateStmt->execute()) {
            echo "  ❌ ERROR: Database update failed: " . $updateStmt->error . "\n";
            $errorCount++;
            continue;
        }
        $updateStmt->close();
        
        echo "  ✓ Database updated\n";
        echo "  ✓ Upload complete!\n\n";
        $successCount++;
        
    } catch (Exception $e) {
        echo "  ❌ ERROR: " . $e->getMessage() . "\n\n";
        $errorCount++;
    }
}

$conn->close();

echo "\n========================================\n";
echo "UPLOAD COMPLETE\n";
echo "========================================\n";
echo "Success: $successCount\n";
echo "Skipped: $skippedCount\n";
echo "Errors: $errorCount\n";
echo "Total: " . count($images) . "\n";
