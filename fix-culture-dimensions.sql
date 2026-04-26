
-- Fix Culture category photos with missing dimensions
-- Set default dimensions to prevent division by zero

UPDATE session_images 
SET width = 800, height = 600, updated_at = NOW()
WHERE album_category_id = 5 
AND (width IS NULL OR width = 0 OR height IS NULL OR height = 0);

-- Verify the fix
SELECT id, title, width, height 
FROM session_images 
WHERE album_category_id = 5 
ORDER BY id DESC 
LIMIT 20;
