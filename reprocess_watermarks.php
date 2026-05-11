<?php
require __DIR__ . '/vendor/autoload.php';
use Intervention\Image\ImageManagerStatic as Image;

// Database connection
$host = 'localhost';
$db   = 'u285921350_new';
$user = 'u285921350_new';
$pass = '@pnj0auE/2';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connected to database\n";
    
    // Get all session images
    $stmt = $pdo->query("SELECT id, session_image, small_image FROM session_images");
    $images = $stmt->fetchAll();
    
    echo "Found " . count($images) . " images to process\n";
    
    $watermarkPath = __DIR__ . '/public/copyright-img.png';
    
    foreach ($images as $img) {
        $id = $img['id'];
        $filename = $img['session_image'];
        $smallFilename = $img['small_image'];
        
        $imagePath = __DIR__ . "/public/uploads/albums/$id/$filename";
        $smallImagePath = $smallFilename ? __DIR__ . "/public/uploads/albums/$id/$smallFilename" : null;
        
        // Re-process main image
        if (file_exists($imagePath)) {
            $image = Image::make($imagePath);
            $image->insert($watermarkPath, 'bottom', 15, 15);
            $image->save($imagePath, 80);
            echo "Processed: $filename\n";
        }
        
        // Re-process small image if exists
        if ($smallImagePath && file_exists($smallImagePath)) {
            $small = Image::make($smallImagePath);
            $small->insert($watermarkPath, 'bottom', 10, 10);
            $small->save($smallImagePath, 80);
            echo "Processed small: $smallFilename\n";
        }
    }
    
    echo "All images re-processed with new smaller watermark!\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
