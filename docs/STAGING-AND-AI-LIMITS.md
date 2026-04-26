# Staging setup and AI assistance limits

## Purpose

Any agent or developer working in Cursor on this repo should assume:

1. **No direct Hostinger access** — Cursor agents do not receive an interactive browser, hPanel session, or Hostinger SSH on your account. They cannot “log in and configure staging for you.”

2. **Staging is a hosting task** — Create subdomain, copy `public_html` tree (or equivalent), create MySQL DB, import dump, edit `public_html/application/.env`, SSL, optional directory password. See the **appendix** in `public_html/discussion-with-hostinger-agent.md` for Laravel-specific notes (not WordPress).

3. **Optional automation** — If you use Hostinger’s API/CLI (often VPS-oriented), Terraform, or a script **on your machine** with your token, an agent can help **author** scripts/commands; **you** run them.

4. **Secrets** — Do not paste hPanel passwords or API tokens into chat.

## Related files

- `public_html/discussion-with-hostinger-agent.md` — Hostinger chat transcript + Laravel staging appendix  
- `public_html/staging-sql/01_categories_architecture_culture.sql` — category rename + Culture insert (staging DB only)  
- `memory-bank/activeContext.md` — current focus and boundaries  
