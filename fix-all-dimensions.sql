-- Fix ALL photos with missing dimensions across ALL categories
-- This prevents division by zero errors in the albums view

-- First, let's see which photos have missing dimensions
SELECT album_category_id, COUNT(*) as missing_count
FROM session_images 
WHERE width IS NULL OR width = 0 OR height IS NULL OR height = 0
GROUP BY album_category_id;

-- Fix all missing dimensions with default values
UPDATE session_images 
SET width = 800, height = 600, updated_at = NOW()
WHERE width IS NULL OR width = 0 OR height IS NULL OR height = 0;

-- Verify the fix
SELECT album_category_id, COUNT(*) as total_photos,
       SUM(CASE WHEN width IS NULL OR width = 0 THEN 1 ELSE 0 END) as missing_width,
       SUM(CASE WHEN height IS NULL OR height = 0 THEN 1 ELSE 0 END) as missing_height
FROM session_images 
GROUP BY album_category_id;
