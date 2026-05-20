# Folder collaboration — product & technical plan

## Problem with registration-based groups

Group create/join on signup only worked for **new** accounts. Existing users could not start or join a session without re-registering. Access was tied to `users.approval_code`, which is opaque and not shareable in a controlled way.

## Direction (invite-based sessions)

Collaboration starts from **My folder** (`/account/folders`), not registration.

### User stories

1. **Solo folder** — Every logged-in user has their own folder and selections; chat is empty until they collaborate.
2. **Invite** — On My folder, search users by name/email, send an invite to collaborate.
3. **Accept** — Invitee accepts; both (and later all members) see **each member’s folder** in the same view and share **one chat thread** on image selections.
4. **Grow group** — Any member can invite more users; invites attach to the **same session**.
5. **Remove** — Any member can remove another member from the session (not delete their account).

### Data model (proposed)

| Table | Purpose |
|-------|---------|
| `collaboration_sessions` | `id`, `name` (optional), `created_by`, `created_at` |
| `collaboration_session_members` | `session_id`, `user_id`, `role` (`owner` / `member`), `joined_at` |
| `collaboration_invites` | `session_id`, `inviter_id`, `invitee_id`, `status` (`pending`/`accepted`/`declined`), `token`, timestamps |

**Deprecate:** `users.approval_code` for new flows. Migrate existing rows: users sharing the same code → one `collaboration_session` + members.

**Chat:** `chats.access_code` → `chats.session_id` (FK to `collaboration_sessions`). Scope messages to session.

**Folders:** `CartController@get_album_images` loads folders for all `user_id` in current session (already similar logic for shared `approval_code`).

### API / routes (sketch)

| Method | Route | Action |
|--------|-------|--------|
| GET | `/account/folders/collaborators` | List members + pending invites |
| GET | `/account/folders/users/search?q=` | Search users to invite |
| POST | `/account/folders/invites` | Send invite |
| POST | `/account/folders/invites/{id}/accept` | Accept |
| POST | `/account/folders/invites/{id}/decline` | Decline |
| DELETE | `/account/folders/members/{userId}` | Remove member |

Middleware: `auth` only; controller resolves “current session” from `collaboration_session_members` where `user_id = auth()->id()`.

### UI (My folder page)

- **Members** panel: avatars/names, “Remove” per row (confirm).
- **Invite** panel: typeahead search, “Invite” button; pending list with cancel.
- **Notifications:** badge or email for pending invites (phase 2).
- **Empty state:** “Invite someone to compare folders and chat about photos.”

### Permissions

- Must be logged in; cannot invite guests.
- Cannot invite self; no duplicate pending invite to same user/session.
- Removing a member: they lose session folders/chat view; their personal folder unchanged.
- Last owner leaving: transfer ownership or dissolve session (define in implementation).

### Phased delivery

| Phase | Scope |
|-------|--------|
| **0** | Remove group from registration; all users → albums after signup/login; My folder in nav for everyone. |
| **1–4 (done 2026-05-19)** | Migrations, `CollaborationService`, invite/search/accept/decline/remove/leave, folder + chat wired to session `chat_key`, legacy `approval_code` migrated on deploy. |
| **5** | Email/in-app notifications (optional) |

### Files likely touched

- `CartController`, `ChatsController`, `Chats` model
- `folder_list.blade.php` (+ partials for invite UI)
- New: `CollaborationSessionController`, policies, migrations
- Remove or no-op: `GroupSessionService`, `EnsureGroupSession` middleware

### Open questions for client

- Max members per session?
- Should removed users be re-invitable immediately?
- Session name visible to all members?
