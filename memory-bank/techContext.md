# Tech context

| Item | Location / notes |
|------|-------------------|
| Laravel | `public_html/application/` (`artisan`, `app/`, `.env`) |
| Front controller | `public_html/index.php` → `application/bootstrap/app.php` |
| Album categories | Table `album_categories`; images `session_images.album_category_id` |
| Admin albums | Routes under `admin/albums`, `SessionimagesController` for uploads |
| Windows / IIS | `public_html/web.config` may exist for hosting |

**Hosting:** Hostinger Business Web; primary domain apertureleadership.com. Hostinger’s generic docs often assume WordPress—this stack is Laravel (see discussion appendix).

**Mail on staging:** `MAIL_MAILER=log` in staging `.env`.
