<?php
/**
 * Emergency Fix - Update photo dimensions
 * Simple script to fix NULL width/height values
 */

// Database configuration
$host = 'localhost';
$db   = 'u285921350_stg';
$user = 'u285921350_stg';
$pass = 'cG*YJBoNlQG8';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully.\n\n";
    
    // Count photos with missing dimensions
    $stmt = $pdo->query("
        SELECT COUNT(*) as count 
        FROM session_images 
        WHERE width IS NULL OR width = 0 OR height IS NULL OR height = 0
    ");
    $result = $stmt->fetch();
    $missingCount = $result['count'];
    
    echo "Found {$missingCount} photos with missing dimensions.\n\n";
    
    if ($missingCount > 0) {
        // Show which photos are affected
        $stmt = $pdo->query("
            SELECT id, title, album_category_id, width, height 
            FROM session_images 
            WHERE width IS NULL OR width = 0 OR height IS NULL OR height = 0
            ORDER BY id DESC
            LIMIT 20
        ");
        $photos = $stmt->fetchAll();
        
        echo "Affected photos:\n";
        foreach ($photos as $photo) {
            echo "  ID {$photo['id']}: {$photo['title']} (Cat: {$photo['album_category_id']}) - W:{$photo['width']}, H:{$photo['height']}\n";
        }
        echo "\n";
        
        // Fix the photos
        $stmt = $pdo->prepare("
            UPDATE session_images 
            SET width = :width, height = :height, updated_at = NOW()
            WHERE id = :id
        ");
        
        $fixed = 0;
        foreach ($photos as $photo) {
            $stmt->execute([
                ':width' => 800,
                ':height' => 600,
                ':id' => $photo['id']
            ]);
            $fixed++;
            echo "Fixed ID {$photo['id']}: {$photo['title']}\n";
        }
        
        echo "\n✓ Fixed {$fixed} photos.\n";
        
        // Verify
        $stmt = $pdo->query("
            SELECT COUNT(*) as count 
            FROM session_images 
            WHERE width IS NULL OR width = 0 OR height IS NULL OR height = 0
        ");
        $result = $stmt->fetch();
        $remaining = $result['count'];
        
        echo "\nRemaining photos with missing dimensions: {$remaining}\n";
        
        if ($remaining == 0) {
            echo "\n✅ ALL PHOTOS FIXED! Culture category should now work.\n";
        }
    } else {
        echo "✅ No photos with missing dimensions found.\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n<strong>DELETE THIS FILE AFTER USE!</strong>\n";
