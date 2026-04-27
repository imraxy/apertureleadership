#!/bin/bash
# Deployment and Image Replacement Script for ApertureLeadership

echo "========================================"
echo "APERTURE LEADERSHIP DEPLOYMENT"
echo "========================================"
echo ""

# SSH Connection Details
SSH_USER="u285921350"
SSH_HOST="185.224.137.209"
SSH_PORT="65002"
REMOTE_DIR="/home/u285921350/domains/apertureleadership.com/public_html/new"

echo "Step 1: Connecting to server and deploying code..."
echo "----------------------------------------"

# Create deployment commands
ssh -p $SSH_PORT $SSH_USER@$SSH_HOST << 'EOF'
    cd /home/u285921350/domains/apertureleadership.com/public_html/new
    
    echo "Pulling latest code from feature/cinematic-redesign..."
    git pull origin feature/cinematic-redesign
    
    echo "Clearing Laravel caches..."
    php artisan cache:clear
    php artisan view:clear
    php artisan config:clear
    
    echo "Code deployed successfully!"
EOF

echo ""
echo "Step 2: Replacing gallery images (removing double watermarks)..."
echo "----------------------------------------"

ssh -p $SSH_PORT $SSH_USER@$SSH_HOST << 'EOF'
    cd /home/u285921350/domains/apertureleadership.com/public_html/new/application
    
    echo "Running image replacement command..."
    php artisan images:replace-all
    
    echo "Image replacement complete!"
EOF

echo ""
echo "========================================"
echo "DEPLOYMENT COMPLETE!"
echo "========================================"
echo ""
echo "Changes deployed:"
echo "  ✓ Fixed 'Add to Folder' button text visibility"
echo "  ✓ Removed HTML watermark from upload process"
echo "  ✓ Replaced all gallery images without double watermarks"
echo ""
echo "Visit: https://new.apertureleadership.com/albums"
