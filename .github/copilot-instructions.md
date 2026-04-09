# Copilot Instructions — OntariosBest.com (Vibecode Repository)

## Project Overview

**OntariosBest.com** is a WordPress affiliate directory site targeting Ontario, Canada. It covers:
- **Online Casinos** (primary revenue driver — iGO-licensed operators only)
- **Travel & Tourism**
- **Restaurants**
- **Entertainment**
- **Services / Shopping**

Revenue comes from affiliate commissions (CPA/RevShare). All casino links must point to iGaming Ontario (iGO) licensed operators only.

---

## Repository Structure

```
Vibecode/
├── .github/
│   └── copilot-instructions.md     ← This file
├── CLAUDE.md                        ← Project plan + phase checklist (authoritative source of truth)
├── README.md
├── deploy_all.py                    ← Python script to deploy HTML pages via WP REST API
├── sftp_deploy.py                   ← SFTP-based file deployer
├── ontariosbest-*.html              ← Standalone HTML page mockups (root level)
├── ontariosbest_logo.png
├── ontariosbest_social_share.jpg
└── ontariosbest/
    ├── STRATEGY.md                  ← Full site strategy and revenue model
    ├── deploy.sh                    ← Cloudways (production) deployment script
    ├── docker-compose.yml           ← Local dev environment (WordPress + MySQL + WP-CLI + phpMyAdmin)
    ├── dist/
    │   └── ontariosbest-theme.zip   ← Pre-packaged child theme zip (upload to WP)
    └── wordpress/
        ├── local-setup.sh           ← Run once after `docker compose up -d`
        ├── launch-checklist.md      ← Step-by-step pre-launch checklist
        ├── launch.sh                ← Go-live script (enables indexing, purges caches, submits sitemap)
        ├── qa-check.sh              ← Pre-launch QA validation script
        ├── fix-all.sh               ← Bulk fix script
        ├── plugins.md               ← Plugin list and installation order (free versions)
        ├── setup-guide.md           ← Hosting, theme, and plugin setup guide
        ├── acf/
        │   ├── casino-fields.json   ← ACF field group for the `casino` CPT
        │   └── listing-fields.json  ← ACF field group for all other listing CPTs
        ├── content-briefs/
        │   └── brief-template.md    ← Editorial content brief template
        ├── seeds/                   ← WP-CLI bash scripts to seed content
        │   ├── casinos-seed.sh      ← Creates 12 iGO-licensed casino reviews
        │   ├── listings-seed.sh     ← Creates 8 directory listings (travel/restaurant/entertainment)
        │   ├── blog-seed.sh         ← Creates 5 foundational blog posts
        │   ├── bestof-seed.sh       ← Creates best-of landing pages
        │   ├── affiliate-links-seed.sh ← Adds ThirstyAffiliates links
        │   ├── city-hubs-seed.sh    ← Creates city hub pages (Toronto, Ottawa, Niagara Falls)
        │   └── phase1-casino-clusters-seed.sh ← 12 casino cluster post drafts
        └── theme/                   ← Astra child theme source files (PHP + CSS)
            ├── style.css            ← Design system (CSS variables, component styles)
            ├── functions.php        ← CPTs, taxonomies, schema markup, shortcodes, helpers
            ├── header.php
            ├── footer.php
            ├── front-page.php       ← Homepage template
            ├── home.php
            ├── single-casino.php    ← Casino review page
            ├── single-listing.php   ← Travel/restaurant/entertainment listing page
            ├── single-post.php      ← Blog post
            ├── archive.php          ← Blog archive
            ├── archive-listing.php  ← Listing archive (travel, restaurant, etc.)
            ├── template-casino-archive.php ← Casino directory index
            ├── template-city-hub.php       ← City hub page template
            ├── page-compare.php     ← Casino comparison tool
            ├── page-bestof.php      ← Best-of landing pages
            ├── page-about.php
            ├── page-contact.php
            ├── page-legal.php       ← Privacy, Terms, Affiliate Disclosure
            ├── page-responsible-gambling.php
            └── page-advertise.php
```

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| CMS | WordPress 6.4+ (PHP 8.2) |
| Parent Theme | Astra (free) |
| Child Theme | Custom (`ontariosbest`) — source in `ontariosbest/wordpress/theme/` |
| Local Dev | Docker Compose (WordPress + MySQL 8 + WP-CLI + phpMyAdmin) |
| Production Hosting | Cloudways (DigitalOcean, Toronto datacenter) |
| CDN / SSL | Cloudflare |
| Affiliate Links | ThirstyAffiliates (prefix `/go/`) |
| SEO | Rank Math |
| Custom Fields | Advanced Custom Fields (ACF) |
| Caching | WP Super Cache |
| Analytics | Google Analytics 4 (MonsterInsights) |
| Deployment | `deploy.sh` (Cloudways SSH) + `deploy_all.py` (WP REST API) |

---

## Local Development

```bash
cd ontariosbest/
docker compose up -d
bash wordpress/local-setup.sh

# URLs:
# Site:       http://localhost:8080
# WP Admin:   http://localhost:8080/wp-admin  (user: admin / pass: admin123)
# phpMyAdmin: http://localhost:8081
```

Theme files are **live-mounted** (`ontariosbest/wordpress/theme/` → `/var/www/html/wp-content/themes/ontariosbest`). Edit PHP/CSS and refresh — no rebuild needed.

**WP-CLI (local):**
```bash
docker compose exec -T wpcli wp --allow-root <command>
# Example:
docker compose exec -T wpcli wp --allow-root post list
docker compose exec -T wpcli wp --allow-root rewrite flush --hard
```

**WP-CLI (production via SSH on Cloudways):**
```bash
wp --allow-root <command>
```

---

## Content Seed Scripts

Run seeds in this order after setting up WordPress:
```bash
cd ontariosbest/
bash wordpress/seeds/casinos-seed.sh
bash wordpress/seeds/listings-seed.sh
bash wordpress/seeds/blog-seed.sh
bash wordpress/seeds/bestof-seed.sh
bash wordpress/seeds/affiliate-links-seed.sh
```

For production, set `WP_ENV=production` before running any seed script:
```bash
WP_ENV=production bash wordpress/seeds/casinos-seed.sh
```

**After seeding:** Add featured images manually in WP Admin.  
**Before production:** Replace all `REPLACE_WITH_REAL_URL` values in `affiliate-links-seed.sh` with real affiliate tracking URLs.

---

## Custom Post Types (CPTs)

Registered in `functions.php` → `ontariosbest_register_post_types()`:

| CPT Slug | Archive URL | Purpose |
|----------|-------------|---------|
| `casino` | `/casinos/` | Online casino reviews |
| `travel` | `/travel/` | Travel & tourism listings |
| `entertainment` | `/entertainment/` | Entertainment listings |
| `service` | `/services/` | Service listings |
| `restaurant` | `/restaurants/` | Restaurant listings |
| `shopping` | `/shopping/` | Shopping listings (Phase 2) |

---

## Taxonomies

Registered in `functions.php` → `ontariosbest_register_taxonomies()`:

| Taxonomy | Post Types | Purpose |
|----------|-----------|---------|
| `casino_feature` | casino | Casino features (e.g. Live Dealer, Mobile App, Crypto) |
| `payment_method` | casino | Payment methods |
| `travel_region` | travel | Travel regions |
| `entertainment_type` | entertainment | Entertainment types |
| `restaurant_cuisine` | restaurant | Cuisine types |
| `service_category` | service | Service categories |
| `listing_region` | restaurant, travel, entertainment, service, shopping | Shared region across listing CPTs |
| `city` | casino, restaurant, travel, entertainment | City hub filtering (Toronto, Ottawa, Niagara Falls) |

---

## Casino Custom Fields (ACF / Post Meta)

All meta keys are prefixed with `_casino_`:

| Meta Key | Type | Description |
|----------|------|-------------|
| `_casino_overall_rating` | Number (1.0–5.0) | Overall rating |
| `_casino_welcome_bonus` | Text | Welcome bonus description |
| `_casino_affiliate_url` | URL | ThirstyAffiliates cloaked `/go/` URL |
| `_casino_established` | Number | Year established |
| `_casino_license` | Text | License info |
| `_casino_min_deposit` | Text | Minimum deposit |
| `_casino_withdrawal_time` | Text | Withdrawal time |
| `_casino_score_games` | Number (1.0–5.0) | Games score |
| `_casino_score_bonuses` | Number (1.0–5.0) | Bonuses score |
| `_casino_score_ux` | Number (1.0–5.0) | UX score |
| `_casino_score_support` | Number (1.0–5.0) | Support score |
| `_casino_score_payments` | Number (1.0–5.0) | Payments score |
| `_casino_pros` | Textarea | Pros (one per line) |
| `_casino_cons` | Textarea | Cons (one per line) |
| `_casino_badge` | Text | Badge label (e.g. "Editor's Choice") |

Helper: `ob_casino_meta( '_casino_overall_rating', $post_id )` (defined in `functions.php`).

---

## Listing Custom Fields (ACF / Post Meta)

All meta keys are prefixed with `_listing_`:

| Meta Key | Type | Description |
|----------|------|-------------|
| `_listing_overall_rating` | Number (1.0–5.0) | Overall rating |
| `_listing_address` | Text | Street address |
| `_listing_phone` | Text | Phone number |
| `_listing_website` | URL | Website URL |
| `_listing_affiliate_url` | URL | Affiliate URL |
| `_listing_featured` | Boolean | Featured/pinned listing |
| `_listing_sponsored_label` | Text | Sponsorship badge label |

Helper: `ob_listing_meta( '_listing_overall_rating', $post_id )` (defined in `functions.php`).

---

## Design System (CSS Variables)

Defined in `style.css`:

| Variable | Value | Usage |
|----------|-------|-------|
| `--ob-primary` | `#C9A84C` | Champagne gold (primary accent) |
| `--ob-primary-dk` | `#B8973D` | Dark gold (hover states) |
| `--ob-gold-light` | `#E8C97A` | Gold highlight |
| `--ob-dark` | `#0D0D0D` | Near-black background |
| `--ob-dark-4` | `#1F1F1F` | Card background |

Fonts: **Playfair Display** (headings) + **DM Sans** (body), loaded via Google Fonts in `functions.php`.

---

## Theme PHP Helper Functions

Defined in `functions.php`:

- `ob_casino_meta( $key, $post_id )` — get casino post meta with fallback
- `ob_listing_meta( $key, $post_id )` — get listing post meta with fallback
- `ob_render_stars( $rating, $max = 5 )` — render star rating HTML (supports half stars)
- `ob_is_featured( $post_id )` — returns true if listing is featured/pinned
- `ob_sponsored_badge( $post_id )` — returns sponsorship badge label or false

---

## Production Deployment

```bash
# SSH into Cloudways server, then from public_html/:
bash deploy.sh
```

`deploy.sh` handles: WordPress configuration, theme installation, plugin installation, page creation, menu setup, Rank Math/ThirstyAffiliates configuration.

For deploying HTML pages via REST API:
```bash
pip install requests
python3 deploy_all.py
# Options: --pages-only | --images-only
```

For packaging the theme:
```bash
cd ontariosbest/wordpress/theme/
zip -r ../../dist/ontariosbest-theme.zip .
```

---

## Ontario iGaming Compliance Requirements

**These are mandatory — never remove or skip them:**

1. **Only link to iGO-licensed operators** — verify at https://igamingontario.ca/en/operator
2. **Every casino affiliate link must have:** `rel="nofollow noopener sponsored"` and `target="_blank"`
3. **19+ responsible gambling footer notice** on all casino pages (auto-injected by `functions.php` → `ontariosbest_rg_notice()`)
4. **Affiliate disclosure** prepended to all review/listing content (auto-injected by `functions.php` → `ontariosbest_affiliate_disclosure()`)
5. **`/responsible-gambling/` page** must remain published
6. **No false claims** about guaranteed winnings, specific return rates, or "risk-free" gambling
7. **Privacy Policy, Terms & Conditions, and Affiliate Disclosure** pages must be published

---

## Code Conventions

### PHP (WordPress theme)
- WordPress coding standards (tabs for indentation, Yoda conditions not required)
- All functions prefixed with `ontariosbest_` (hooked actions/filters) or `ob_` (utility helpers)
- Post meta keys prefixed with `_casino_` or `_listing_` (leading underscore hides from default Custom Fields UI)
- Schema markup output via `wp_head` hooks, JSON-LD format
- Use `esc_attr()`, `esc_html()`, `esc_url()` for output escaping
- `wp_json_encode()` with `JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT` for schema output

### Shell Scripts (seed scripts)
- `set -euo pipefail` at top of every script
- Use `WP_ENV=production` env var to switch between local Docker WP-CLI and production WP-CLI
- Local: `docker compose exec -T wpcli wp --allow-root`
- Production: `wp --allow-root`
- Helper functions: `log()` (green ✓), `warn()` (yellow !), `fail()` (red ✗ + exit)

### Affiliate Links
- All affiliate URLs go through ThirstyAffiliates cloaking (`/go/slug/`)
- Store raw affiliate URLs in `_casino_affiliate_url` or `_listing_affiliate_url` post meta
- Never hardcode raw affiliate URLs in theme templates — always use the cloaked `/go/` URL

---

## Key Files to Know First

When starting any task, read these files first:

1. `CLAUDE.md` — project plan, phase checklist, current status
2. `ontariosbest/STRATEGY.md` — site strategy, revenue model, content plan
3. `ontariosbest/wordpress/theme/functions.php` — CPTs, taxonomies, all helper functions
4. `ontariosbest/wordpress/theme/style.css` — design system CSS variables

---

## No Automated Tests

This repository has **no automated test suite**. Validation is done via:
- `ontariosbest/wordpress/qa-check.sh` — pre-launch QA script (checks pages, redirects, etc.)
- Manual QA on mobile (375px), tablet (768px), desktop (1024px+)
- Google PageSpeed Insights (target: 85+ mobile, 90+ desktop)
- Google Rich Results Test for casino review schema

There is **no CI/CD pipeline**. Deployment is manual via SSH + `deploy.sh` or the WP REST API deployer.

---

## Common Tasks & Where to Make Changes

| Task | Files to Modify |
|------|----------------|
| Add/modify a custom post type | `ontariosbest/wordpress/theme/functions.php` |
| Add/modify a taxonomy | `ontariosbest/wordpress/theme/functions.php` |
| Add a new theme template | `ontariosbest/wordpress/theme/` (follow WP template hierarchy naming) |
| Update styles | `ontariosbest/wordpress/theme/style.css` |
| Add casino meta fields | `ontariosbest/wordpress/acf/casino-fields.json` + `functions.php` helpers |
| Seed new content | `ontariosbest/wordpress/seeds/` (new `.sh` script or extend existing) |
| Update plugins list | `ontariosbest/wordpress/plugins.md` |
| Update launch steps | `ontariosbest/wordpress/launch-checklist.md` |

After modifying CPTs or taxonomies, always flush rewrite rules:
```bash
# Local:
docker compose exec -T wpcli wp --allow-root rewrite flush --hard
# Production:
wp --allow-root rewrite flush --hard
```

---

## Known Issues / Workarounds

- **WPForms form IDs:** `page-contact.php` and `page-advertise.php` contain placeholder `FORM_ID` values. Update these after creating forms in WP Admin → WPForms.
- **ACF Pro required in production:** The local setup uses ACF free. Production uses ACF Pro (must be manually installed — not available on wp.org). Free version is sufficient for field group UI; Pro adds repeater fields and advanced features.
- **Premium plugins must be installed manually:** Rank Math Pro, ACF Pro, and ThirstyAffiliates Pro cannot be installed via `deploy.sh` (not on wp.org). Install them via WP Admin → Plugins → Upload.
- **Search engines blocked by default:** `deploy.sh` sets `blogpublic 0`. Run `wp option update blogpublic 1 --allow-root` (or use `launch.sh`) when ready to go live.
- **ThirstyAffiliates affiliate URLs:** `affiliate-links-seed.sh` contains `REPLACE_WITH_REAL_URL` placeholders. Replace with real affiliate tracking URLs before running on production.
- **`deploy_all.py` credentials:** Contains hardcoded WordPress application password in plaintext. This is a development credential — rotate or remove before sharing.
