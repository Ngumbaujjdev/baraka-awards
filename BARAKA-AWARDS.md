# Baraka Awards Kenya 2026

## Overview

Baraka Awards Kenya is an annual awards ceremony celebrating outstanding talent across entertainment, media, events, and humanitarian service in Kenya. The inaugural 2026 edition honours nominees across **21 award categories** grouped into **4 award clusters**.

- **Gala Night:** Saturday, 30th May 2026
- **Venue:** Southfield Mall, Mombasa Road, Nairobi
- **Time:** 6:00 PM – 11:00 PM (EAT)
- **Voting Period:** 24th April – 24th May 2026
- **Tickets:** from KES 500

---

## Ticket Tiers

| Tier | Price |
|------|-------|
| Regular | KES 500 |
| VIP | KES 1,000 |
| VVIP | KES 2,000 |

---

## Award Clusters & Categories

### 1. Humanitarian Cluster
| # | Category | Nominees |
|---|----------|----------|
| 1 | Humanitarian Champion of the Year | Chairman Richard Wambua, Chairman Jose Mweu, Selinah Muthui |
| 2 | Charity Group / Foundation of the Year | Digital AM Foundation, Home of Love, Ottomax Initiative Programme, Wendo Family Foundation |

### 2. Entertainment Cluster
| # | Category | Nominees |
|---|----------|----------|
| 3 | Female Gospel Artist of the Year | Alice Mwende, Christine Elijah, Ruth Mbithe, Queen Dee Mweene Syindu, Juliana Mumbua |
| 4 | Male Gospel Artist of the Year | Chuma Cha Mwea, Dan Musa, Kakiro Nguma, Rodgers Maundu, Julius Muli, Joshua Mwania Son |
| 5 | Urban Artist of the Year | Bonntezz Kenya, Nicki Mulla, Jaythree Music, Calypso Wannie Kenya, Majimbo Music (Mukaru) |
| 6 | Upcoming Urban Artist of the Year | Samedy KE, Kev Mafelis, Namsha Rekeys, Azaan Official 3 |
| 7 | Ohangla Artist of the Year | David Jelina, Mary Mukuyuni, Steve Kyalo (SK Mweene) |
| 8 | Benga Female Artist of the Year | Kanyangi Sisters, Kiti Kyumu Sisters, Kana Jessy (Masinga Sisters), Kana Dee (Kathonzweni Sisters) |
| 9 | AUDIO Producer of the Year | Producer Titoh, Producer Kwako, Producer Hosea (Songcraft Studios), Producer HB Musiq |
| 10 | VIDEO Producer of the Year | Director Antoh, Producer Willie Daktari |

### 3. Events Cluster
| # | Category | Nominees |
|---|----------|----------|
| 11 | DJ of the Year | DJ Dantez Kasee, DJ Emmanuel, DJ Gfire, DJ Rayme |
| 12 | MC of the Year | MC Asi, MC Chedas, MC Mshamba, MC Caleb Biden |
| 13 | Hyperman MC of the Year | MC Janet (Mwiitu Muombe), MC Issabellah (Sukali Sukali), MC Freddy (Mundu Wa Ngai), MC Lukwata |
| 14 | Events Manager / Planner of the Year | Radiant Decors & Events, Mega Decor, Mmax Events Ltd, Bridget Decors, Glory Creation & Events |

### 4. Media Cluster
| # | Category | Nominees |
|---|----------|----------|
| 15 | Digital Director of the Year | Director Msafi, Director Nicholas Muthui, Director Migel, Director Daniel Munyao |
| 16 | Photographer of the Year | Joshwa Ochieng, Kangi Media, Erycorn Media, Kartelostar Photography |
| 17 | Videographer of the Year | Dommy TV, Kleography Media, Worldlife Studios, Chris Media, Sam Wa Safi Media |
| 18 | Media Personality of the Year | Kalendi, Miss Maliih, Openstar TV, Flozzy (Ng'e Ikindi), Mwelu Precious, Eliza Wa Manondo |
| 19 | Tiktoker of the Year | Precious Mwikaa, Betz Willy, Livie, Musyoka Kyambi |
| 20 | Models of the Year | Jane Sesan, Verah Bae, Queen Fridah |
| 21 | Best Cake Baker of the Year | Dee Bakers, Super Fresh Cakes Bakers, Cakes by Moh 254, Tony Cakes, Dazzling Cakes N Treats, Mlei City Bakers |

---

## Key Dates

| Milestone | Date |
|-----------|------|
| Nominations closed | April 2026 |
| Voting opens | 24 April 2026 |
| Voting closes | 24 May 2026 |
| Gala Night | 30 May 2026 |

---

## Vote Bundles

| Bundle | Votes | Price |
|--------|-------|-------|
| Starter | 1 vote | KES 10 |
| Standard | 10 votes | KES 100 |
| Popular ⭐ | 50 votes | KES 500 |
| Power | 100 votes | KES 1,000 |

---

## Platform & Backend

| Item | Value |
|------|-------|
| Client slug | `baraka-awards` |
| Event slug | `baraka-awards-2026` |
| API base (local) | `http://localhost:8000` |
| API base (live) | `https://platform.tuqiohub.africa` |
| Frontend (local) | `http://localhost/baraka-awards` |
| Frontend (live) | `https://barakaawards.tuqiohub.africa` |
| Admin panel | `https://platform.tuqiohub.africa/login` |

### Seeders (run in order)

```bash
php artisan db:seed --class=BarakaClientSeeder
php artisan db:seed --class=BarakaNominees2026Seeder
php artisan db:seed --class=BarakaTickets2026Seeder
php artisan db:seed --class=BarakaVoteBundlesSeeder
php artisan db:seed --class=BarakaPromoteNomineesSeeder
```

All seeders are idempotent — safe to re-run.

---

## Contact

| | |
|-|---|
| Email | info@barakaawards.tuqiohub.africa |
| Phone / WhatsApp | +254 710 388 288 |
| Venue | Southfield Mall, Mombasa Road, Nairobi |
| Working Hours | Mon – Fri, 9 AM – 6 PM |

---

## Frontend Tech Stack

- PHP flat-file (no framework)
- Bootstrap 5 + custom CSS
- jQuery / Vanilla JS for AJAX
- cURL helpers: `tuqio_api()` (GET) and `tuqio_api_post()` (POST)
- Config: `config/config.php` — all constants defined here (SITE_URL, API_BASE, CLIENT_SLUG, SITE_PHONE, etc.)
