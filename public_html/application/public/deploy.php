<?php
// Simple deployment script - triggers git pull on staging server
// Access via: https://new.apertureleadership.com/application/public/deploy.php?key=deploy123

$key = $_GET['key'] ?? '';
$correct_key = 'deploy123'; // Change this!

if ($key !== $correct_key) {
    die('Unauthorized. Provide correct key parameter.');
}

echo "<h2>Deployment Script</h2>";
echo "<pre>";

// Change to application directory
$app_dir = dirname(__FILE__, 2);
chdir($app_dir);

echo "Current directory: " . getcwd() . "\n";
echo "Pulling from git...\n\n";

// Run git pull
$output = [];
$return_var = 0;
exec('git pull origin feature/cinematic-redesign 2>&1', $output, $return_var);

foreach ($output as $line) {
    echo htmlspecialchars($line) . "\n";
}

echo "\nGit pull completed with return code: " . $return_var . "\n";

// Run the DB fix
echo "\n\nNow running DB fix for NULL dimensions...\n";
echo "Access fix_db.php separately at: " . $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . "/application/public/fix_db.php\n";

echo "</pre>";
echo "\n<p><strong>Done! Now go to <a href='fix_db.php'>fix_db.php</a> to fix the database.</strong></p>";
?>
