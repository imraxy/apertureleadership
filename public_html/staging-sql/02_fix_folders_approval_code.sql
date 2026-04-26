-- =============================================================================
-- FIX: Exclude NULL approval_code users from folders view
-- This prevents showing 67 garbage/bot users on /account/folders page
-- Run on staging first, then production after validation
-- =============================================================================

-- Option A: Delete garbage users with NULL approval_code (CLEANUP)
-- WARNING: Review data before deleting - some legitimate test users may exist
-- DELETE FROM users WHERE approval_code IS NULL AND id NOT IN (103, 104, 106, 107, 111, 112, 113, 116);

-- Option B: Soft disable garbage users (set status = 0)
-- UPDATE users SET status = 0 WHERE approval_code IS NULL;

-- Option C: View legitimate users only (for reference)
SELECT id, name, email, approval_code, status, created_at 
FROM users 
WHERE approval_code IS NOT NULL AND approval_code != ''
ORDER BY created_at;

-- Count garbage users
SELECT COUNT(*) as garbage_users FROM users WHERE approval_code IS NULL;