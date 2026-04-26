# Active context

## What AI in Cursor can do here

- Edit Laravel/PHP/Blade/JS in the repo; SQL templates; runbooks; Artisan commands **you** execute in the project terminal.
- Explain Hostinger steps, `.env` values, DB imports, and post-clone checks.
- Upload photos to staging via API when provided with files and mapping

## What AI cannot do

- Open Hostinger hPanel, an "inbuilt browser," or any logged-in session on your hosting account.
- SSH into Hostinger without your machine having access and you running commands.
- Accept passwords or API tokens pasted into chat (avoid sharing secrets).

## Completed work (2026-04-22)

- ✅ **Staging setup** - https://stg.apertureleadership.com is active
- ✅ **Photo upload** - 54 photos uploaded to staging with category mapping
  - Source: `wetransfer_new-aperture-photos_2026-04-08_1837/Small/`
  - Mapping: `photo-category-mapping.csv`
  - Categories: People(10), Architecture(13), Landscapes(6), Symbols(14), Culture(11)
- ✅ **Category rename** - Objects → Architecture
- ✅ **New Culture category** - Created and populated

## Queued work (from client email Apr 12-22, 2026)

See full details in `docs/client-email-website-updates.md`

### High Priority
1. **Solo user flow** - Direct to gallery instead of folders page
2. **Group session isolation** - Separate contexts, no cross-visibility
3. **Automatic group codes** - Self-managed, no admin approval

### Medium Priority
4. **Increase preview images** - 10 → 20 per category before login
5. **Deploy to production** - After staging verification

## Technical Notes

- SQL template ready: `public_html/staging-sql/01_categories_architecture_culture.sql`
- UX fix needed: `/account/folders` must not show all `approval_code IS NULL` users
- Folder is for group work (picking images + chat), solo users should browse only

## Next steps

1. Implement solo user flow (bypass folders, go to gallery)
2. Implement group isolation (automatic unique codes)
3. Increase preview image limit
4. Test on staging
5. Deploy to production
6. Send final cost to client
