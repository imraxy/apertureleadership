<?php
// Use mysqli to fix NULL dimensions

$host = 'localhost';
$db   = 'u285921350_new';
$user  = 'u285921350_new';
$pass  = '@pnj0auE/2';

try {
    $mysqli = new mysqli($host, $user, $pass, $db);
    
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error . "\n");
    }
    
    // Update NULL or zero width/height
    $sql = "UPDATE session_images SET width=800, height=600 WHERE width IS NULL OR width=0 OR height IS NULL OR height=0";
    
    if ($mysqli->query($sql)) {
        $affected = $mysqli->affected_rows;
        echo "Updated {$affected} rows with default dimensions (800x600).\n";
    } else {
        echo "Error updating: " . $mysqli->error . "\n";
    }
    
    // Verify
    $result = $mysqli->query("SELECT COUNT(*) as cnt FROM session_images WHERE width IS NULL OR width=0 OR height IS NULL OR height=0");
    $row = $result->fetch_assoc();
    echo "Remaining rows with NULL/zero dimensions: " . $row['cnt'] . "\n";
    
    // Show sample
    $result = $mysqli->query("SELECT id, title, width, height FROM session_images LIMIT 5");
    echo "\nSample rows after update:\n";
    while ($row = $result->fetch_assoc()) {
        echo "ID: {$row['id']}, Title: {$row['title']}, Width: {$row['width']}, Height: {$row['height']}\n";
    }
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
