<?php
/**
 * Staging Database Updates Script
 * Run this to fix categories and admin access
 */

// Database configuration from staging .env
$host = 'localhost';
$db   = 'u285921350_stg';
$user = 'u285921350_stg';
$pass = 'cG*YJBoNlQG8';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully\n\n";
    
    // 1. Rename Objects -> Architecture
    echo "1. Renaming 'Objects' to 'Architecture'...\n";
    $stmt = $pdo->prepare("UPDATE album_categories SET name = 'Architecture', slug = 'architecture', updated_at = NOW() WHERE id = 2");
    $stmt->execute();
    $affected = $stmt->rowCount();
    echo "   Updated $affected row(s)\n\n";
    
    // 2. Create Culture category if it doesn't exist
    echo "2. Creating 'Culture' category...\n";
    $stmt = $pdo->prepare("SELECT id FROM album_categories WHERE slug = 'culture'");
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        $stmt = $pdo->prepare("INSERT INTO album_categories (name, slug, is_order, status, created_at, updated_at) VALUES ('Culture', 'culture', 5, 1, NOW(), NOW())");
        $stmt->execute();
        $cultureId = $pdo->lastInsertId();
        echo "   Created Culture category with ID: $cultureId\n\n";
    } else {
        $cultureId = $stmt->fetchColumn();
        echo "   Culture category already exists with ID: $cultureId\n\n";
    }
    
    // 3. Verify categories
    echo "3. Current categories:\n";
    $stmt = $pdo->query("SELECT id, name, slug FROM album_categories WHERE status = 1 ORDER BY id");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   ID {$row['id']}: {$row['name']} ({$row['slug']})\n";
    }
    echo "\n";
    
    // 4. Count photos per category
    echo "4. Photo counts per category:\n";
    $stmt = $pdo->query("
        SELECT c.name, COUNT(s.id) as count 
        FROM album_categories c 
        LEFT JOIN session_images s ON c.id = s.album_category_id 
        WHERE c.status = 1
        GROUP BY c.id, c.name 
        ORDER BY c.id
    ");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "   {$row['name']}: {$row['count']} photos\n";
    }
    echo "\n";
    
    // 5. Reset admin password
    echo "5. Resetting admin password...\n";
    $newPassword = 'Staging@2024';
    $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 10]);
    
    $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE email = 'admin@admin.com'");
    $stmt->execute([$hash]);
    $affected = $stmt->rowCount();
    
    if ($affected > 0) {
        echo "   Password reset successful for admin@admin.com\n";
        echo "   New password: $newPassword\n\n";
    } else {
        echo "   No admin user found with email admin@admin.com\n";
        echo "   Checking available admins...\n";
        $stmt = $pdo->query("SELECT id, email FROM admins LIMIT 5");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "   Found: {$row['email']}\n";
        }
        echo "\n";
    }
    
    // 6. Check total photos
    echo "6. Total photos in database:\n";
    $stmt = $pdo->query("SELECT COUNT(*) FROM session_images");
    $total = $stmt->fetchColumn();
    echo "   Total: $total photos\n\n";
    
    // 7. Check recent uploads (today)
    echo "7. Photos uploaded today:\n";
    $stmt = $pdo->query("SELECT COUNT(*) FROM session_images WHERE DATE(created_at) = CURDATE()");
    $today = $stmt->fetchColumn();
    echo "   Today: $today photos\n\n";
    
    echo "=== Updates Complete ===\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
