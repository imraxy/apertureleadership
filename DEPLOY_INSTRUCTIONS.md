# ApertureLeadership - Fix Deployment Instructions

## Issues Fixed:
1. ✅ **Button text not visible** - "Add to Folder" button now shows text instead of just icon
2. ✅ **Double watermark** - Removed HTML watermark from upload process
3. ✅ **Image replacement command** - Ready to re-upload all images without duplicate watermarks

## Steps to Deploy:

### Step 1: SSH into the server
```bash
ssh -p 65002 u285921350@185.224.137.209
# Enter password when prompted
```

### Step 2: Navigate to project directory
```bash
cd /home/u285921350/domains/apertureleadership.com/public_html/new
```

### Step 3: Pull latest code
```bash
git pull origin feature/cinematic-redesign
```

### Step 4: Clear caches
```bash
cd application
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

### Step 5: Replace all images (removes double watermarks)
```bash
php artisan images:replace-all
```

This will:
- Delete all existing gallery images
- Re-upload them without the HTML watermark
- Keep only the original embedded watermark

### Step 6: Verify
Visit: https://new.apertureleadership.com/albums

Check:
1. "Add to Folder" buttons show text (not just icon)
2. Images have only ONE watermark (not two)
3. Gallery layout looks good

## What Was Changed:

### 1. albums.blade.php
- Fixed "Add to Folder" button to show text "Add to Folder" with + icon
- Button appears on hover with gold background and dark text

### 2. SessionimagesController.php
- Commented out watermark insertion code
- Images now upload without additional HTML watermark

### 3. ReplaceGalleryImages.php (New Command)
- Deletes all existing images
- Re-uploads from source files
- No duplicate watermarks

## Troubleshooting:

If images still show double watermarks after running the command:
1. Check browser cache (Ctrl+F5 to hard refresh)
2. Verify the command completed successfully
3. Check that source images in `wetransfer_new-aperture-photos_2026-04-08_1837/Small/` only have one watermark
