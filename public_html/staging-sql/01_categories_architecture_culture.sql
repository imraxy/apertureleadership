-- =============================================================================
-- STAGING ONLY — run after DB backup. Verify IDs match your `album_categories`.
-- Live DB: People=1, Objects=2, Landscapes=3, Symbols=4 (from migration dump).
-- =============================================================================

-- 1) Rename "Objects" → "Architecture" (same row id=2; all images in category 2 stay)
UPDATE `album_categories`
SET
  `name` = 'Architecture',
  `slug` = 'architecture',
  `updated_at` = NOW()
WHERE `id` = 2;

-- 2) Add "Culture" (adjust is_order if your admin uses it for tab sort)
INSERT INTO `album_categories` (`name`, `slug`, `is_order`, `status`, `created_at`, `updated_at`)
VALUES ('Culture', 'culture', 5, 1, NOW(), NOW());

-- 3) Reallocate specific OLD images (optional — fill IDs from spreadsheet / admin).
-- Example: move three images from Architecture (was Objects, id=2) to Culture (new id=5):
-- UPDATE `session_images` SET `album_category_id` = 5, `updated_at` = NOW() WHERE `id` IN (101, 102, 103);
--
-- Example: balance into Landscapes (id=3):
-- UPDATE `session_images` SET `album_category_id` = 3, `updated_at` = NOW() WHERE `id` IN (...);

-- After INSERT, confirm new Culture id:
-- SELECT id, name, slug FROM album_categories ORDER BY id;
