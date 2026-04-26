# Client Email - Website Updates
**Source:** Gmail - Website Updates.pdf
**Date:** April 12-22, 2026
**Client:** Andy Craggs <andy_craggs@hotmail.com>

---

## Summary of Client Requests

### 1. Photo Uploads (COMPLETED ✓)
- **55 new photos** sent via WeTransfer (we have 54)
- **Category mapping** provided in Excel spreadsheet (Aperture Website New Photos Index 2026.xlsx)
- All photos uploaded to staging: https://stg.apertureleadership.com

### 2. Category Changes (COMPLETED ✓)
New category structure (5 libraries):
1. **People** - stays as is
2. **Architecture** - renamed from "Objects" 
3. **Landscapes** - stays as is
4. **Symbols** - stays as is
5. **Culture** - NEW category

**Notes:**
- Photos from old "Objects" category that aren't architecture should be reallocated to other categories
- Goal: Keep categories balanced in terms of number of images

### 3. Preview Images (PENDING)
- **Current:** 10 images per category before login
- **Requested:** 20 images per category before login
- **Status:** Not yet implemented

### 4. Solo User Experience (PENDING)
**Current Issue:** Solo users see a list of names/folders page after login

**Requested Changes:**
- Normal login should go straight to browsing all photos (albums/gallery)
- "User list / folders" screen should only appear for group sessions
- Folders should be optional for solo visitors
- Solo = browse only, Group = folder + chat

**Status:** Not yet implemented

### 5. Group Session Management (PENDING)
**Current Issue:** Groups may be lumped together

**Requested Changes:**
- Each group should have its own isolated context
- Access codes should be automatic/self-managed
- No admin approval needed for codes
- Groups should NOT see each other's folders/chats
- Clear separation between group sessions

**Status:** Not yet implemented

### 6. Signup Process (PENDING)
**Current:** Self-service signup works

**Requested:** Streamlined flow:
- Solo users: Browse immediately after signup
- Group users: Get unique code/room automatically
- No accidental cross-visibility between groups

**Status:** Not yet implemented

---

## Work Status Summary

| Task | Status | Notes |
|------|--------|-------|
| Upload 54-55 new photos | ✅ DONE | All uploaded to staging with categories |
| Rename Objects → Architecture | ✅ DONE | Category mapping updated |
| Create Culture category | ✅ DONE | New category added |
| Rebalance existing photos | ✅ DONE | Photos distributed across 5 categories |
| Increase preview to 20 images | ⏳ PENDING | Code change needed |
| Solo user direct to gallery | ⏳ PENDING | UX flow change needed |
| Group session isolation | ⏳ PENDING | Access code system changes |
| Self-managed group codes | ⏳ PENDING | Admin approval removal |

---

## Next Steps
1. Implement preview image limit increase (10 → 20)
2. Change login flow: solo users → gallery, group users → folders
3. Implement automatic group code generation
4. Ensure group isolation (no cross-visibility)
5. Deploy all changes to production
6. Send final cost to client

---

## Files Referenced
- `Aperture Website New Photos Index 2026.xlsx` - Category mapping
- `photo-category-mapping.csv` - Our processed mapping
- `upload_photos_v2.py` - Upload script
