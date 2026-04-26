#!/bin/bash
# Git Auto-Deploy Script for new.apertureleadership.com
# This script pulls the latest changes from GitHub when triggered

DEPLOY_DIR="/home/u285921350/domains/apertureleadership.com/public_html/new"
BRANCH="feature/cinematic-redesign"
LOG_FILE="/home/u285921350/.logs/deploy-new.log"

# Create log directory if it doesn't exist
mkdir -p /home/u285921350/.logs

# Log deployment start
echo "[$(date)] Starting deployment..." >> "$LOG_FILE"

# Change to deploy directory
cd "$DEPLOY_DIR" || exit 1

# Fetch latest changes
git fetch origin "$BRANCH" >> "$LOG_FILE" 2>&1

# Check if there are new changes
LOCAL=$(git rev-parse HEAD)
REMOTE=$(git rev-parse origin/$BRANCH)

if [ "$LOCAL" != "$REMOTE" ]; then
    echo "[$(date)] New changes detected. Deploying..." >> "$LOG_FILE"
    
    # Pull the latest changes
    git pull origin "$BRANCH" >> "$LOG_FILE" 2>&1
    
    # Clear Laravel caches (if applicable)
    if [ -f "application/artisan" ]; then
        cd application
        php artisan cache:clear 2>/dev/null || true
        php artisan view:clear 2>/dev/null || true
        php artisan config:cache 2>/dev/null || true
        cd ..
    fi
    
    echo "[$(date)] Deployment completed successfully!" >> "$LOG_FILE"
else
    echo "[$(date)] No new changes." >> "$LOG_FILE"
fi
