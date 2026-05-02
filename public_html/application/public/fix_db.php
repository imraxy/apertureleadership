<?php
// Temporary script to fix database issues - REMOVE AFTER USE

header('Content-Type: text/plain');

// Database credentials for new.apertureleadership.com
$host = 'localhost';
$db   = 'u285921350_new';
$user  = 'u285921350_new';
$pass  = '@pnj0auE/2';

try {
    $mysqli = new mysqli($host, $user, $pass, $db);
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error . "\n");
    }
    
    echo "Connected to database successfully.\n\n";
    
    // Fix 1: Update NULL or zero width/height
    $sql = "UPDATE session_images SET width=800, height=600 WHERE width IS NULL OR width=0 OR height IS NULL OR height=0";
    
    if ($mysqli->query($sql)) {
        $affected = $mysqli->affected_rows;
        echo "Fix 1: Updated {$affected} rows with default dimensions (800x600).\n";
    } else {
        echo "Fix 1 Error: " . $mysqli->error . "\n";
    }
    
    // Verify
    $result = $mysqli->query("SELECT COUNT(*) as cnt FROM session_images WHERE width IS NULL OR width=0 OR height IS NULL OR height=0");
    $row = $result->fetch_assoc();
    echo "Remaining rows with NULL/zero dimensions: " . $row['cnt'] . "\n\n";
    
    // Show sample
    $result = $mysqli->query("SELECT id, title, width, height FROM session_images LIMIT 5");
    echo "Sample rows after update:\n";
    while ($row = $result->fetch_assoc()) {
        echo "ID: {$row['id']}, Title: {$row['title']}, W: {$row['width']}, H: {$row['height']}\n";
    }
    
    $mysqli->close();
    
    echo "\n\nIMPORTANT: Delete this file after use!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
