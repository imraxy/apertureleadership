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
GitHub milestone: https://github.com/imraxy/apertureleadership/milestone/1  

**Staging release branch:** `release/stg` — merge all stg work here first; merge to `main` only after sign-off.  
Feature branches (`fix/andy-6-*`, `fix/andy-12-*`, `fix/andy-13-14-*`) are merged into `release/stg`. Open: #15 production deploy, #16 invoice.

### High Priority
1. **Folder collaboration** — Implemented and deployed to staging (2026-05-19): invite/search/accept/decline/remove/leave on My folder; shared folders + session chat. See `docs/folder-collaboration-plan.md`.
2. **Production deploy** (#15) after client sign-off on staging collaboration flow.

### Medium Priority
3. **Deploy to production** — After staging verification (#15, #16 invoice)

## Technical Notes

- SQL template ready: `public_html/staging-sql/01_categories_architecture_culture.sql`
- UX fix needed: `/account/folders` must not show all `approval_code IS NULL` users
- Folder is for group work (picking images + chat), solo users should browse only

## Next steps

1. Deploy phase 0 (no registration groups) to staging; verify register/login/folder nav
2. Implement `collaboration_sessions` + invite UI per plan doc
3. Migrate legacy `approval_code` users into sessions
4. Production deploy + client invoice
