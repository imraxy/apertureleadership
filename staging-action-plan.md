# Staging Site Issues - Action Plan
**Date:** April 22, 2026

## Current Status

### Issues Confirmed
1. ❌ **Admin login not working** - Password incorrect
2. ❌ **"Objects" category not renamed** - Should be "Architecture"
3. ❌ **"Culture" category missing** - Returns 404
4. ⚠️ **Photo uploads verified** - 54 photos uploaded but category assignments need verification

---

## Required Actions

### Action 1: Fix Database Categories

**SQL Commands Needed:**

```sql
-- 1. Rename Objects -> Architecture
UPDATE album_categories 
SET name = 'Architecture', slug = 'architecture', updated_at = NOW() 
WHERE id = 2;

-- 2. Create Culture category
INSERT INTO album_categories (name, slug, is_order, status, created_at, updated_at) 
VALUES ('Culture', 'culture', 5, 1, NOW(), NOW());

-- 3. Verify categories
SELECT id, name, slug FROM album_categories WHERE status = 1 ORDER BY id;

-- 4. Check photo counts
SELECT c.name, COUNT(s.id) as photo_count 
FROM album_categories c 
LEFT JOIN session_images s ON c.id = s.album_category_id 
WHERE c.status = 1
GROUP BY c.id, c.name 
ORDER BY c.id;
```

**How to Execute:**
- Option A: SSH into server and run mysql commands
- Option B: Use Hostinger hPanel PHPMyAdmin
- Option C: Upload and run `fix-staging-db.php` script

---

### Action 2: Reset Admin Password

**SQL Command:**
```sql
-- Reset admin password
UPDATE admins 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE email = 'admin@admin.com';
-- Password hash is for: 'Staging@2024'
```

**Alternative:** Create new admin user
```sql
INSERT INTO admins (name, email, password, status, created_at, updated_at)
VALUES ('Admin', 'admin@admin.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, NOW(), NOW());
```

---

### Action 3: Verify Photo Assignments

After categories are fixed, verify:
- All 54 new photos are in the database
- Photos are assigned to correct categories per `photo-category-mapping.csv`
- Category counts are balanced

**Expected Distribution:**
- People: 10 photos
- Architecture: 13 photos (including #44 and #54)
- Landscapes: 6 photos
- Symbols: 14 photos
- Culture: 11 photos

---

## Access Options

### Option 1: Hostinger hPanel (Recommended)
1. Login to https://www.hostinger.com/hpanel
2. Go to "Databases" → "PHPMyAdmin"
3. Select staging database: `u285921350_stg`
4. Run SQL commands above

### Option 2: SSH Access
```bash
ssh -p 65002 u285921350@82.197.80.124
mysql -u u285921350_stg -p'cG*YJBoNlQG8' u285921350_stg
# Then run SQL commands
```

### Option 3: Upload PHP Script
1. Upload `fix-staging-db.php` to `public_html/` via File Manager
2. Access: https://stg.apertureleadership.com/fix-staging-db.php
3. Script will execute database updates automatically
4. **DELETE script after execution for security**

---

## Verification Checklist

After fixes are applied:

- [ ] Admin login works (admin@admin.com / Staging@2024)
- [ ] Navigation shows "Architecture" not "Objects"
- [ ] Culture category accessible at /albums/culture
- [ ] All 5 categories show in navigation
- [ ] Photo counts match expected distribution
- [ ] All 54 new photos visible in admin
- [ ] Individual photos assigned to correct categories

---

## Files Ready for Deployment

1. `fix-staging-db.php` - Database fix script
2. `photo-category-mapping.csv` - Photo to category mapping
3. `public_html/staging-sql/01_categories_architecture_culture.sql` - SQL template

---

## Next Steps

1. **Get server access** (hPanel or SSH)
2. **Execute database fixes**
3. **Verify all changes**
4. **Test admin login**
5. **Update documentation**
6. **Proceed with production deployment**
