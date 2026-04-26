# Staging Fix - Alternative Approaches

## SSH Connection Issue
**Status:** Permission denied with password `apertureSshPass@9`

**Possible causes:**
1. SSH not enabled in Hostinger hPanel
2. Password typo (extra space, wrong characters)
3. IP address restriction
4. Key-based authentication required

## Alternative Fix Methods

### Method 1: Hostinger hPanel File Manager
1. Login to https://www.hostinger.com/hpanel
2. Go to "Files" → "File Manager"
3. Navigate to `domains/stg.apertureleadership.com/public_html/`
4. Upload `fix-staging-db.php`
5. Visit: https://stg.apertureleadership.com/fix-staging-db.php
6. Delete file after execution

### Method 2: Hostinger hPanel PHPMyAdmin
1. Login to hPanel
2. Go to "Databases" → "PHPMyAdmin"
3. Select database: `u285921350_stg`
4. Run these SQL commands:

```sql
-- 1. Rename Objects -> Architecture
UPDATE album_categories 
SET name = 'Architecture', slug = 'architecture', updated_at = NOW() 
WHERE id = 2;

-- 2. Create Culture category
INSERT INTO album_categories (name, slug, is_order, status, created_at, updated_at) 
VALUES ('Culture', 'culture', 5, 1, NOW(), NOW());

-- 3. Reset admin password (Staging@2024)
UPDATE admins 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE email = 'admin@admin.com';

-- 4. Verify
SELECT id, name, slug FROM album_categories WHERE status = 1 ORDER BY id;
```

### Method 3: FTP Upload
1. Use FileZilla or similar FTP client
2. Connect to: `82.197.80.124` (port 21)
3. Use same credentials: `u285921350` / `apertureSshPass@9`
4. Upload `fix-staging-db.php` to public_html
5. Visit URL to execute

### Method 4: Enable SSH in hPanel
1. Login to hPanel
2. Go to "Advanced" → "SSH Access"
3. Enable SSH and note the port
4. Try SSH connection again

## Recommended Next Steps

1. **Try Method 2 (PHPMyAdmin)** - Fastest, no file upload needed
2. If that doesn't work, try **Method 1 (File Manager)**
3. Once DB is fixed, verify admin login works
4. Then verify all photos are in correct categories

## Verification Commands

After fixes, run these to verify:

```sql
-- Check all categories
SELECT id, name, slug, status FROM album_categories ORDER BY id;

-- Check photo counts
SELECT c.name, COUNT(s.id) as photos 
FROM album_categories c 
LEFT JOIN session_images s ON c.id = s.album_category_id 
GROUP BY c.id;

-- Check total photos
SELECT COUNT(*) FROM session_images;

-- Check admin
SELECT email, status FROM admins WHERE email = 'admin@admin.com';
```
