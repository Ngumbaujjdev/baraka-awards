# MEMA Whitelabel Subsite — Setup Plan

## What We Are Creating

| Folder | Local URL | Production URL | Based On |
|---|---|---|---|
| `mema-frontend/` | `http://localhost/mema-frontend` | `mema.tuqiohub.africa` | Copy of `tuqio-frontend/` |
| `mema-platform/` | `http://localhost:8002` | `memaplatform.tuqiohub.africa` | Copy of `v1-events-backend/` |

Same `tuqio_hub` database. Only branding changes — colors, logo, site name.

---

## Step 1 — Copy the folders

```bash
cp -r /Applications/MAMP/htdocs/tuqio-frontend /Applications/MAMP/htdocs/mema-frontend
cp -r /Applications/MAMP/htdocs/v1-events-backend /Applications/MAMP/htdocs/mema-platform
```

Init fresh git repos:

```bash
rm -rf /Applications/MAMP/htdocs/mema-frontend/.git
rm -rf /Applications/MAMP/htdocs/mema-platform/.git
cd /Applications/MAMP/htdocs/mema-frontend && git init && git add . && git commit -m "init: MEMA frontend — whitelabelled from tuqio-frontend"
cd /Applications/MAMP/htdocs/mema-platform && git init && git add . && git commit -m "init: MEMA platform — whitelabelled from v1-events-backend"
```

---

## Step 2 — mema-frontend: `config/config.php`

File: `/Applications/MAMP/htdocs/mema-frontend/config/config.php`

| Setting | Old Value | New Value |
|---|---|---|
| `$isNew` host detection | `tuqiohub.africa` | `mema.tuqiohub.africa` |
| `SITE_URL` local | `http://localhost/tuqio-frontend` | `http://localhost/mema-frontend` |
| `SITE_URL` production | `https://tuqiohub.africa` | `https://mema.tuqiohub.africa` |
| `API_BASE` local | `http://localhost:8000` | `http://localhost:8002` |
| `API_BASE` production | `https://platform.tuqiohub.africa` | `https://memaplatform.tuqiohub.africa` |
| `SITE_NAME` | `"Tuqio Hub"` | `"MEMA Awards"` |
| `ADMIN_EMAIL` | `info@tuqiohub.africa` | `info@memaawards.co.ke` |
| `TUQIO_NAVY` | `#1e1548` | **← MEMA primary color (user to provide)** |
| `TUQIO_RED` | `#ed1c24` | **← MEMA accent color (user to provide)** |
| Social links | Tuqio handles | MEMA social handles |

---

## Step 3 — mema-frontend: CSS color replacements

Replace all Tuqio color occurrences in `assets/css/` and `.php` files:

| Old (Tuqio) | Replace With |
|---|---|
| `#1e1548` | MEMA primary |
| `#2d1f6b` | MEMA primary light |
| `#15102e` | MEMA primary dark |
| `#ed1c24` | MEMA accent |
| `#c41820` | MEMA accent dark |

---

## Step 4 — mema-frontend: logos and text

- Drop MEMA logo files into `assets/images/logo/` (same filenames as Tuqio logos)
- Replace `favicon.ico` / `favicon.png` with MEMA favicon
- Update `includes/header*.php`, `includes/footer*.php` — replace "Tuqio Hub" text with "MEMA Awards"
- Update `includes/footer*.php` — replace Tuqio social links with MEMA social links

---

## Step 5 — mema-platform: `.env`

File: `/Applications/MAMP/htdocs/mema-platform/.env`

| Key | Old | New |
|---|---|---|
| `APP_NAME` | `"Tuqio Hub"` | `"MEMA Awards Platform"` |
| `APP_URL` | `http://localhost:8000` | `http://localhost:8002` |
| `FRONTEND_URL` | `http://localhost/tuqio-frontend` | `http://localhost/mema-frontend` |
| `APP_KEY` | existing | regenerate → `php artisan key:generate` |
| `DB_DATABASE` | `tuqio_hub` | `tuqio_hub` (**same — no change**) |
| `DB_USERNAME` | `root` | `root` (**same**) |
| `DB_PASSWORD` | `root` | `root` (**same**) |

---

## Step 6 — mema-platform: admin panel branding

### Logo

File: `resources/views/layouts/app.blade.php`

- Find the logo `<img>` tag and update `src` to point to MEMA logo
- Drop MEMA logo into `public/assets/images/logo/`

### Colors

Grep and replace Tuqio colors in:

- `public/assets/css/` — all admin template CSS files
- `resources/views/` — any inline color references

Same color substitution table as Step 3.

### Site name

Replace all "Tuqio Hub" in `resources/views/` with "MEMA Awards Platform":

- Page `<title>` tags
- Meta tags
- Sidebar/header text

---

## Step 7 — mema-platform: bootstrap commands

```bash
cd /Applications/MAMP/htdocs/mema-platform
php artisan key:generate
php artisan storage:link
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

Run the platform:

```bash
php artisan serve --port=8002
```

---

## Step 8 — Remove inventory-v1 submodule

Not needed for MEMA:

```bash
cd /Applications/MAMP/htdocs/mema-platform
git rm --cached inventory-v1
rm -rf inventory-v1
```

---

## Files Changed Summary

### mema-frontend

| File | Change |
|---|---|
| `config/config.php` | URLs, site name, colors, social links |
| `assets/css/*.css` | Color variable replacements |
| `assets/images/logo/*` | MEMA logo files (user drops in) |
| `favicon.*` | MEMA favicon (user drops in) |
| `includes/header*.php` | Site name text |
| `includes/footer*.php` | Site name, social links |

### mema-platform

| File | Change |
|---|---|
| `.env` | APP_NAME, APP_URL, FRONTEND_URL, APP_KEY |
| `resources/views/layouts/app.blade.php` | Logo, site name |
| `public/assets/css/*.css` | Color replacements |
| `inventory-v1/` | Removed |

---

## Verification Checklist

- [ ] `http://localhost/mema-frontend` loads homepage
- [ ] `http://localhost:8002/login` loads MEMA-branded admin login
- [ ] API call `http://localhost:8002/api/public/events` returns MEMA event data
- [ ] MEMA Gala 2026 event shows on frontend homepage
- [ ] Logo and colors are correct on both frontend and platform
- [ ] No "Tuqio Hub" text visible anywhere on the MEMA site

---

## What User Needs to Provide Before Final Branding

1. MEMA primary hex color
2. MEMA accent hex color
3. MEMA logo files (PNG + SVG preferred)
4. MEMA favicon
5. MEMA social media handles (Instagram, Facebook, Twitter/X, TikTok)
6. MEMA contact email
