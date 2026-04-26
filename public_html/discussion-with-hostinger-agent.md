> **Note (project, 2026-04):** Cursor AI cannot operate Hostinger hPanel or a logged-in browser on your behalf. Staging must be done in hPanel/SSH by you, Hostinger support, or another **local** workflow. See repo root `docs/STAGING-AND-AI-LIMITS.md` and `memory-bank/activeContext.md`.

---

Hello there

12:50

Updated memory
Hey! Good to see you.

I've already had a look at your setup, you're running three sites on your Business Web Hosting plan:

🏢 apertureleadership.com, with a professional branded email to match
🌐 raxy.life
🔗 earn-online.raxy.life
That's an interesting mix, looks like you've got at least two distinct projects going on here. I want to dig into whichever one matters most to you right now and find the highest-impact thing we can improve.

Pick a site and I'll run a full check on it:

<quick-action>Analyze apertureleadership.com</quick-action>
<quick-action>Analyze raxy.life</quick-action>
<quick-action>Analyze earn-online.raxy.life</quick-action>

12:50
Analyze apertureleadership.com

12:50
On it, let me pull up the site now.


Read webpage
Interesting, the site is live but there's clearly more to explore. Let me dig deeper into the inner pages too.


Read webpage

Searched the web

Read webpage

Read webpage
Alright, I've got a solid picture of what Aperture is. Here's my full read:

12:51
🔍 Aperture Leadership, Site Audit
What it is: A visual metaphor card system for leadership facilitation, used in coaching, team workshops, and organizational strategy sessions. Beautiful concept, clearly built from real-world experience (photos from Sarawak, Ethiopia, Japan, Chile…). The London Business School quote is gold.

12:51
✅ What's Working
Strong core concept, the product is genuinely differentiated. Visual metaphor cards for leadership dialogue is a niche with real demand
Image library is live, the albums page shows a rich portfolio of world-class photography
Methodology is thorough, the Guidelines page has deep, credible content for Individuals, Teams, and Organizations
12:51
❌ Critical Issues I Found
1. 🚨 Broken contact link
The contact page on the Guidelines section links to pinkcitycalling.com/aperture/contact, a completely different domain. Anyone trying to reach you gets lost. This is costing you leads right now.

2. 🏠 Homepage is almost empty
The homepage has one paragraph, one testimonial, and a "Some text some message…" placeholder that's still live. That placeholder text is visible to every visitor. First impressions are everything, and right now, the homepage undersells a genuinely impressive product.

3. 🔒 Albums require login
The image library is locked behind a login wall, which makes sense for clients, but there's no visible free preview, no teaser gallery, and no clear CTA explaining how to get access. New visitors hit a dead end.

4. 📉 Zero SEO presence
No meta description, no structured content Google can read easily. When I searched for you, the result showed the bare minimum. You're invisible to facilitators, coaches, and L&D professionals who are actively searching for tools like this.

5. 💰 No visible pricing or offer
There's no clear path to buy, license, or book a session. What does it cost? How do facilitators get a deck? How do organizations license the virtual tool? None of that is answered.

12:51
🎯 The #1 Priority
Fix that broken contact link and the placeholder text, today. Those two things are actively damaging your credibility with every visitor. Everything else is a growth opportunity, but those are open wounds.

12:51
What do you want to tackle next? I'd say the homepage copy is the highest-leverage fix after that, a strong homepage could turn this into a proper lead-generation machine. I can work with Quill to draft you something sharp, or I can dig into the SEO gap with Scout so the right people actually find you first.

Which direction matters most to you right now?

12:51
setup a complete clone as a staging website with all services included like database etc, show me the plan first how will you do, use a subdomain for the staging website

12:54
Great question, let me lay out a clear plan before we touch anything.

🗺️ Staging Clone Plan for apertureleadership.com
A staging site is a private copy of your live site where you can test changes safely before pushing them live. Here's exactly how we'd set it up:

12:54
📋 Overview
Item	Detail
Staging URL	staging.apertureleadership.com
Hosting	Same Business Web Hosting account
Database	Full MySQL clone of your live DB
Files	Complete copy of all WordPress files
Email	Disabled on staging (to prevent accidental sends)
Access	Password-protected (so only you can see it)
12:54
🔢 Step-by-Step Plan
Step 1, Create the subdomain

Go to hPanel → Domains → Subdomains
Create staging.apertureleadership.com
Point it to a new folder e.g. /public_html/staging
Step 2, Clone the database

Go to hPanel → Databases → phpMyAdmin
Export your live database as a .sql file
Create a new database (e.g. u285921350_staging)
Import the .sql dump into the new database
Step 3, Clone the files

Go to hPanel → File Manager
Copy everything from /public_html/ into /public_html/staging/
This includes all WordPress core files, themes, plugins, and uploads
Step 4, Update wp-config.php

Open /public_html/staging/wp-config.php
Update these three lines to point to the new staging database:
define('DB_NAME', 'u285921350_staging');
define('DB_USER', 'your_staging_db_user');
define('DB_PASSWORD', 'your_staging_db_password');
Step 5, Fix the site URLs in the database

Run a search-and-replace on the staging DB
Replace https://apertureleadership.com → https://staging.apertureleadership.com
Best done with a plugin like Better Search Replace or via phpMyAdmin
Step 6, Password-protect the staging site

Go to hPanel → Advanced → Password Protect Directories
Lock /public_html/staging so only you can access it
This prevents Google from indexing it and keeps it private
Step 7, Disable emails on staging

Install WP Mail SMTP or add this line to wp-config.php to stop WordPress from sending real emails from the staging environment:
define('WPMS_ON', true);
Or simply disable any email plugins on the staging side
12:54
⚠️ Things to Watch Out For
SSL certificate, you may need to enable SSL for the subdomain separately in hPanel (it's free via Let's Encrypt)
Plugins with license keys, some premium plugins tie licenses to a domain; you may need to deactivate on staging or use a dev license
WooCommerce / payment gateways, if you have any, switch them to sandbox/test mode on staging
12:54
⏱️ Estimated Time
Task	Time
Create subdomain	2 min
DB export + import	5–10 min
File copy	10–20 min (depends on site size)
Config + URL fix	10 min
Password protect + SSL	5 min
Total	~45 min
12:54
This is entirely doable within your current Business Web Hosting plan, no upgrades needed. Everything runs on the same server.

Want me to walk you through each step live, one at a time? Or is there a specific step you'd like to start with?

12:54
this is not a WP its a custom build on laraval, use stg. subdomain can you do all these steps for me?

12:57
Appreciate you flagging that, changes things a bit. Since it's a custom Laravel build, the process is slightly different from WordPress (no wp-config, no plugins, we work directly with .env and Artisan).

However, I have to be straight with you here:

I can't directly access your server, File Manager, phpMyAdmin, or hPanel to execute these steps myself. I don't have the ability to SSH in, run commands, or click through your control panel on your behalf.

What I can do is:

✅ Give you the exact commands to run, copy-paste ready
✅ Walk you through every step live, one at a time
✅ Troubleshoot anything that goes wrong in real time
✅ Adapt every instruction specifically to your Laravel setup and your Hostinger account (u285921350)
12:57
Here's the revised plan for Laravel + stg.apertureleadership.com:

Item	Detail
Staging URL	stg.apertureleadership.com
Document Root	/public_html/stg/public (Laravel serves from /public)
Database	New MySQL DB cloned from live
Config	Separate .env file for staging
Queue/Jobs	Disabled or set to sync driver
Mail	Set to log driver (no real emails sent)
SSL	Free Let's Encrypt via hPanel
12:57
Ready to go step by step? Start by opening your hPanel and I'll guide you through Step 1, creating the stg.apertureleadership.com subdomain and setting the document root correctly for Laravel.

Just say "ready" and we'll go.

---

## Appendix (Rakesh / Cursor): Laravel staging on Hostinger — not WordPress

The Hostinger plan above first described **WordPress** (`wp-config`, plugins). This project is **Laravel** with `index.php` at the site web root and the app in `application/` (see repo `public_html/index.php` → `application/bootstrap/app.php`).

### Staging layout (recommended)

| Item | Detail |
|------|--------|
| Subdomain | `stg.apertureleadership.com` (or `staging.…`) |
| Folder | e.g. `/public_html/stg/` — **full copy** of production `public_html` (same structure: `index.php`, `application/`, `content/`, `uploads/` if present at root, etc.) |
| Document root | The folder that contains **`index.php`** for this app (often the subdomain root itself, **not** `…/public` like a stock Laravel `public/` deploy). |
| Database | New MySQL DB; import a **fresh export** of live data (or migrate). |
| Config | Edit **`application/.env`** on staging only: `APP_URL`, `DB_*`, `APP_ENV=staging`, `APP_DEBUG=true` (only while testing). |
| Mail | `MAIL_MAILER=log` (or `array`) so staging never emails real users. |
| Queue | `QUEUE_CONNECTION=sync` unless you use queues. |
| Cache | After URL change: `php artisan config:clear` / `cache:clear` from `application/` if you have SSH. |
| SSL | hPanel → SSL for the subdomain. |
| Privacy | Password-protect the subdomain directory in hPanel **or** HTTP basic auth so Google does not index staging. |

### After clone: URL and assets

- Search/replace in the **staging database** (carefully) for any absolute URLs pointing at production, if stored in `settings` / content tables.
- Confirm `APP_URL=https://stg.apertureleadership.com` matches how you load the site.

### What Cursor / dev work cannot do from here

- Hostinger **hPanel**, **File Manager**, **phpMyAdmin**, and **WeTransfer downloads** are on your machine/account; the agent cannot SSH into Hostinger for you.
- **Andy’s 55 new files and the XLS** are not in this repo until you place them (e.g. unzip under a local or server folder and share the path, or add the XLS to the project).

---

## Andy’s request: categories + 55 new photos (do on staging first)

**Client goals (summary):**

1. **People** — unchanged.  
2. **Objects** — rename to **Architecture**; move non-architecture images to other categories as judged, keep counts roughly balanced.  
3. **Landscapes**, **Symbols** — unchanged.  
4. **Culture** — **new** category; assign new photos per XLS; use Culture for former Objects images that are not architecture.

**In the database (current dump):** `album_categories` ids `1=People`, `2=Objects`, `3=Landscapes`, `4=Symbols`. Images live in `session_images.album_category_id`.

**Automated / safe first step:** SQL template in repo:

`public_html/staging-sql/01_categories_architecture_culture.sql`

- Renames row `id=2` to Architecture (slug `architecture`).  
- Inserts **Culture** (confirm new `id` after insert — often `5`).  
- Add your own `UPDATE session_images … WHERE id IN (…)` lines once you have image IDs from admin or the spreadsheet.

**Reallocation and 55 uploads (manual / semi-manual):**

- **Per-image moves:** Admin → Albums → edit each session image and change category, **or** bulk `UPDATE` by `session_images.id` once the XLS lists titles/IDs.  
- **New photos:** Admin → Albums → Add Album (category + title + file) matches `SessionimagesController@store` (watermark, thumbnails). **55 images** = repetitive in UI unless we add a batch import script later (possible follow-up).  
- **Titles:** DB has `unique` on `session_images.title` — each upload needs a **distinct title** (e.g. derive from filename + suffix if needed).

**What to send / attach next for maximum help:**

1. Path or copy of the **XLS** (category column + filename or title per row).  
2. After WeTransfer download: folder of **55 JPGs** (or confirm they’re only on the server).  
3. Staging **URL** and confirmation **Culture** row `id` after running the SQL on staging.

Then we can: finalize SQL for bulk `session_images` moves, optionally add a small Artisan command for batch import, and mirror the same steps on **live** after sign-off.