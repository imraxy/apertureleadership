<?php
/**
 * GitHub Webhook Receiver for Auto-Deployment
 * Place this file in the web root and configure GitHub webhook to point to it
 * 
 * GitHub Webhook URL: https://new.apertureleadership.com/deploy.php
 * Content type: application/json
 * Secret: (optional but recommended)
 */

// Configuration
$DEPLOY_SCRIPT = '/home/u285921350/deploy-new.sh';
$LOG_FILE = '/home/u285921350/.logs/webhook.log';
$BRANCH = 'feature/cinematic-redesign';

// Create log directory
@mkdir(dirname($LOG_FILE), 0755, true);

// Log the request
$payload = file_get_contents('php://input');
$headers = getallheaders();
$event = $headers['X-GitHub-Event'] ?? $_SERVER['HTTP_X_GITHUB_EVENT'] ?? 'unknown';

logMessage("Webhook received - Event: $event");

// Only process push events
if ($event !== 'push') {
    logMessage("Ignoring non-push event: $event");
    http_response_code(200);
    echo "OK - Ignored $event event\n";
    exit;
}

// Parse payload
$data = json_decode($payload, true);
if (!$data) {
    logMessage("Failed to parse JSON payload");
    http_response_code(400);
    echo "Error: Invalid JSON\n";
    exit;
}

// Check if it's the correct branch
$pushedBranch = $data['ref'] ?? '';
if ($pushedBranch !== "refs/heads/$BRANCH") {
    logMessage("Ignoring push to branch: $pushedBranch");
    http_response_code(200);
    echo "OK - Ignored push to $pushedBranch\n";
    exit;
}

// Log push details
$commit = $data['after'] ?? 'unknown';
$author = $data['pusher']['name'] ?? 'unknown';
logMessage("Deploying branch: $BRANCH, commit: $commit, author: $author");

// Execute deployment script
$output = [];
$returnCode = 0;
exec("bash $DEPLOY_SCRIPT 2>&1", $output, $returnCode);

// Log output
foreach ($output as $line) {
    logMessage("Deploy: $line");
}

if ($returnCode === 0) {
    logMessage("Deployment successful!");
    http_response_code(200);
    echo "OK - Deployment successful\n";
} else {
    logMessage("Deployment failed with code: $returnCode");
    http_response_code(500);
    echo "Error - Deployment failed\n";
}

function logMessage($message) {
    global $LOG_FILE;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($LOG_FILE, "[$timestamp] $message\n", FILE_APPEND | LOCK_EX);
}
