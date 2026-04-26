# ApertureLeadership Staging Environment - Fixes Applied

**Date:** April 22, 2026  
**Technician:** Hermes Agent

---

## Summary

Fixed SSH connectivity and admin password issues for the ApertureLeadership staging environment. Verified all 120 photos are properly uploaded and categorized.

---

## Issues Fixed

### 1. SSH Connection Issue

**Problem:** Could not connect via SSH using the wrong server IP (82.197.80.124)

**Solution:** Used the correct server IP: **185.224.137.209**

**Connection Details:**
```bash
ssh -p 65002 u285921350@185.224.137.209
Password: apertureSshPass@9
```

---

### 2. Admin Password Reset

**Problem:** Admin login at https://stg.apertureleadership.com/admin/login was not working with any known password

**Solution:** Reset admin password using PHP's `password_hash()` function and updated the database directly

**Steps:**
1. Generated bcrypt hash for password "Staging@2024":
   ```bash
   php -r 'echo password_hash("Staging@2024", PASSWORD_BCRYPT);'
   # Output: $2y$10$BaiM/GaV5HMJa4x20JUCCO9RHxuYV3Owfu.6DEXsGcU/R/81WJpM2
   ```

2. Updated database via SQL file:
   ```sql
   UPDATE admins SET password = "$2y$10$BaiM/GaV5HMJa4x20JUCCO9RHxuYV3Owfu.6DEXsGcU/R/81WJpM2" 
   WHERE email = "admin@admin.com";
   ```

**Current Admin Credentials:**
- **URL:** https://stg.apertureleadership.com/admin/login
- **Email:** admin@admin.com
- **Password:** Staging@2024

---

## Verification Results

### Album Categories (5 total)
| Category | ID | Photo Count |
|----------|-----|-------------|
| People | 1 | 32 |
| Architecture | 2 | 15 |
| Landscapes | 3 | 34 |
| Symbols | 4 | 35 |
| Culture | 5 | 4 |
| **Total** | | **120** |

### Admin Panel Access
✅ Successfully logged into admin dashboard  
✅ Verified Albums section shows 120 entries  
✅ All 5 categories are properly configured

---

## Database Details

**Staging Database:**
- Database: `u285921350_stg`
- Username: `u285921350_stg`
- Password: `cG*YJBoNlQG8`

**Key Tables:**
- `admins` - Admin user accounts (separate from `users` table)
- `album_categories` - Photo categories (People, Architecture, Landscapes, Symbols, Culture)
- `session_images` - Photo/album entries (120 total)

---

## Server Structure

```
/home/u285921350/
└── domains/
    └── stg.apertureleadership.com/
        └── public_html/
            ├── index.php (Laravel entry point)
            └── application/ (Laravel app code)
                ├── app/
                ├── config/
                ├── database/
                ├── resources/
                ├── routes/
                └── vendor/
```

---

## Notes for Future Reference

1. **Password Hash Generation:** When updating admin passwords, use PHP's native `password_hash()` function rather than Python bcrypt - Laravel expects the `$2y$` format.

2. **SSH Port:** Always use port 65002 for Hostinger SSH connections.

3. **Admin Table:** The `admins` table is separate from `users` table and has an `is_role` field that must be set to 1 for admin access.

4. **Category IDs:**
   - People = 1
   - Architecture = 2
   - Landscapes = 3
   - Symbols = 4
   - Culture = 5

---

## Files Modified

- Database: `u285921350_stg.admins` table (password field updated)

## Files Created

- `/tmp/update_pass.sql` (temporary, deleted after use)

---

## Status

✅ **COMPLETE** - All issues resolved, staging environment fully operational.
