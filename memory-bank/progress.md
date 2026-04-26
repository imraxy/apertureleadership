# Progress

| Area | Status |
|------|--------|
| Memory bank | Initialized 2026-04 |
| Staging (`stg.`) | ⚠️ Issues Found - See action plan |
| Category SQL template | `public_html/staging-sql/01_categories_architecture_culture.sql` |
| Andy 54 images upload | ✅ Uploaded (verification pending DB fixes) |
| Photo category mapping | ✅ Completed - See `photo-category-mapping.csv` |
| Folders page null `approval_code` leak | Identified; code fix pending |

## Issues Found on Staging (2026-04-22)

| Issue | Status | Action Required |
|-------|--------|-----------------|
| Admin login | ❌ Not working | Reset password via DB |
| Objects → Architecture | ❌ Not renamed | Run SQL update |
| Culture category | ❌ Missing | Create in DB |
| Photo assignments | ⚠️ Unverified | Verify after DB fixes |

**Action Plan:** `staging-action-plan.md`
**Fix Script:** `fix-staging-db.php` (ready to upload)

## Photo Upload Details

**Source:** `wetransfer_new-aperture-photos_2026-04-08_1837/Small/` (54 photos)

**Upload Status:** Script reported 54 success, 0 errors

**Expected Categories:**
- People: 10 photos
- Architecture: 13 photos (renamed from Objects)
- Landscapes: 6 photos
- Symbols: 14 photos
- Culture: 11 photos (NEW - needs to be created)

## Pending Client Requests

See `docs/client-email-website-updates.md`

| Request | Status | Priority |
|---------|--------|----------|
| Fix staging categories | ⏳ BLOCKED | Critical |
| Increase preview 10→20 | ⏳ Pending | Medium |
| Solo user flow | ⏳ Pending | High |
| Group isolation | ⏳ Pending | High |
