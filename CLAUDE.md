# CLAUDE.md — OntariosBest.com Project Plan

This file tracks progress on the OntariosBest.com affiliate directory site.
See `ontariosbest/STRATEGY.md` for full strategy and `ontariosbest/wordpress/launch-checklist.md` for the deployment checklist.

**Deployment model:** Static HTML pages are the primary delivery mechanism, deployed via `sftp_deploy.py` to Cloudways subfolders. WordPress powers the CMS/blog/SEO layer underneath.

---

## Project Structure

```
Vibecode/
├── CLAUDE.md                               ← You are here
├── README.md
├── sftp_deploy.py                          ← Deploy HTML pages via SFTP to Cloudways subfolders
├── deploy_all.py                           ← Deploy HTML pages to WordPress via REST API + featured images
├── ontariosbest_logo.png
├── ontariosbest_social_share.jpg
│
├── ontariosbest-casinos.html               ← Casino review page (v1)
├── ontariosbest-poker.html                 ← Online poker guide (v1)
├── ontariosbest-sportsbetting.html         ← Sports betting guide
├── ontariosbest-hotels.html                ← Ontario hotels guide
├── ontariosbest-thingstodo.html            ← Things to do in Toronto
├── ontariosbest-toronto-guide.html         ← Toronto city guide
├── ontariosbest-prediction-markets.html    ← Prediction markets legality guide
├── ontariosbest-advertise.html             ← B2B advertise / get featured page
│
├── docs/superpowers/
│   ├── plans/2026-04-01-content-strategy-implementation.md
│   └── specs/2026-04-01-content-strategy-design.md
│
└── ontariosbest/
    ├── STRATEGY.md                         ← Full site strategy and revenue model
    ├── deploy.sh                           ← Cloudways WordPress deployment script
    ├── docker-compose.yml                  ← Local dev environment
    ├── dist/
    │   └── ontariosbest-theme.zip          ← Built theme package (upload to WP)
    │
    ├── Ontarios Best Assets/               ← V2 HTML pages (used by sftp_deploy.py)
    │   ├── ontariosbest-casinos-v2.html    ← Casino page v2 (green/teal theme)
    │   ├── ontariosbest-poker-v2.html      ← Poker page v2
    │   └── ontariosbest-sportsbetting-v2.html
    │
    └── wordpress/
        ├── local-setup.sh                  ← Run after docker compose up -d
        ├── launch-checklist.md             ← Step-by-step go-live checklist
        ├── plugins.md                      ← Plugin list and install order
        ├── setup-guide.md                  ← Hosting, theme, plugin setup guide
        ├── fix-all.sh                      ← Post-deploy fix script
        ├── qa-check.sh                     ← Pre-launch QA validation (13 categories)
        ├── launch.sh                       ← Go-live automation script
        ├── acf/
        │   ├── casino-fields.json          ← ACF field group for casino CPT
        │   └── listing-fields.json         ← ACF field group for all listing CPTs
        ├── content-briefs/
        │   └── brief-template.md           ← Editorial content brief template
        └── theme/                          ← Child theme source files (Astra child)
            ├── style.css
            ├── functions.php               ← CPTs, taxonomies, shortcodes, helpers
            ├── header.php
            ├── footer.php
            ├── front-page.php              ← Homepage template
            ├── home.php                    ← Blog posts index
            ├── single-casino.php           ← Casino review page + sticky CTA + FAQ accordion
            ├── single-listing.php          ← Travel/restaurant/entertainment page
            ├── single-post.php             ← Blog post
            ├── archive.php                 ← Blog archive
            ├── archive-listing.php         ← Listing archive (travel, etc.)
            ├── template-casino-archive.php ← Casino directory index
            ├── template-city-hub.php       ← City hub pages (Toronto, Ottawa, Niagara)
            ├── page-compare.php            ← Casino comparison tool
            ├── page-bestof.php             ← Best-of landing pages
            ├── page-about.php
            ├── page-contact.php
            ├── page-legal.php              ← Privacy, Terms, Affiliate Disclosure
            ├── page-responsible-gambling.php
            ├── page-advertise.php
            └── seeds/
                ├── casinos-seed.sh         ← 12 iGO casino reviews (READY — not yet run)
                ├── listings-seed.sh        ← 8 directory listings (READY — not yet run)
                ├── blog-seed.sh            ← 5 SEO blog posts (READY — not yet run)
                ├── bestof-seed.sh          ← 3 best-of landing pages (READY — not yet run)
                ├── affiliate-links-seed.sh ← ThirstyAffiliates links (update URLs first!)
                ├── city-hubs-seed.sh       ← 3 city hub pages (Toronto, Ottawa, Niagara)
                ├── phase1-casino-clusters-seed.sh ← 12 draft casino cluster posts
                └── services-seed.sh        ← 5 services listings
```

---

## Deployed Pages (sftp_deploy.py)

| Local File | Live URL |
|---|---|
| `Ontarios Best Assets/ontariosbest-casinos-v2.html` | ontariosbest.com/online-casinos/ |
| `Ontarios Best Assets/ontariosbest-poker-v2.html` | ontariosbest.com/poker/ |
| `ontariosbest-sportsbetting.html` | ontariosbest.com/sports-betting/ |
| `ontariosbest-prediction-markets.html` | ontariosbest.com/prediction-markets/ |
| `ontariosbest-hotels.html` | ontariosbest.com/hotels/ |
| `ontariosbest-thingstodo.html` | ontariosbest.com/things-to-do/toronto/ |
| `ontariosbest-toronto-guide.html` | ontariosbest.com/toronto/ |
| `ontariosbest-advertise.html` | ontariosbest.com/get-featured/ |

---

## Development Phases & Todo Status

### Phase 1: Hosting & Infrastructure
- [x] WordPress theme built (Astra child theme with all templates)
- [x] ACF field groups defined (casino-fields.json, listing-fields.json)
- [x] Docker local dev environment (docker-compose.yml + local-setup.sh)
- [x] Cloudways production deploy script (deploy.sh)
- [x] Theme zip packaged (dist/ontariosbest-theme.zip)
- [x] Register/confirm domain DNS → ontariosbest.com ✓ LIVE
- [x] Set up Cloudways server + WordPress install ✓ LIVE
- [x] Run deploy.sh on production server
- [x] Configure Cloudflare CDN + SSL
- [x] Connect Google Merchant Center ✓
- [x] Connect Google Analytics ✓

### Phase 2: Static HTML Pages
- [x] Casino review page built (ontariosbest-casinos-v2.html)
- [x] Poker guide built (ontariosbest-poker-v2.html)
- [x] Sports betting guide built (ontariosbest-sportsbetting.html)
- [x] Ontario hotels guide built (ontariosbest-hotels.html)
- [x] Things to do in Toronto built (ontariosbest-thingstodo.html)
- [x] Toronto city guide built (ontariosbest-toronto-guide.html)
- [x] Prediction markets guide built (ontariosbest-prediction-markets.html)
- [x] B2B advertise/get-featured page built (ontariosbest-advertise.html)
- [x] SFTP deploy script ready (sftp_deploy.py)
- [x] WordPress REST API deploy script ready (deploy_all.py)
- [ ] Deploy all pages to production: `python3 sftp_deploy.py`
- [ ] Verify each live URL loads correctly (see Deployed Pages table above)
- [ ] Confirm affiliate CTAs resolve to correct iGO-licensed destinations
- [ ] Add a homepage (ontariosbest.com root) — currently no root index.html

### Phase 3: WordPress Configuration
- [x] Install all plugins — **free versions only** (see wordpress/plugins.md — install in listed order)
- [x] Import ACF field groups (ACF → Tools → Import both JSON files)
- [x] Configure Rank Math (connect GSC, set schema types per CPT)
- [x] Configure ThirstyAffiliates (/go/ prefix, link categories)
- [x] Configure WP Super Cache (last — after all other plugins working)
- [x] Configure Wordfence + UpdraftPlus

### Phase 4: Content Seeding (WordPress)

> **Run seeds in order** (from `ontariosbest/` directory):
> ```bash
> bash wordpress/seeds/casinos-seed.sh
> bash wordpress/seeds/listings-seed.sh
> bash wordpress/seeds/blog-seed.sh
> bash wordpress/seeds/bestof-seed.sh
> bash wordpress/seeds/affiliate-links-seed.sh
> ```
> **Before running affiliate-links-seed.sh:** replace all `REPLACE_WITH_REAL_URL` values with real affiliate tracking URLs.
> After seeding, open WP Admin and add featured images to each post.

- [ ] Run casinos-seed.sh — creates 12 iGO casino reviews
- [ ] Run listings-seed.sh — creates 8 directory listings (3 travel, 3 restaurant, 2 entertainment)
- [ ] Run blog-seed.sh — creates 5 foundational SEO blog posts
- [ ] Run bestof-seed.sh — creates 3 best-of landing pages
- [ ] Update `REPLACE_WITH_REAL_URL` in affiliate-links-seed.sh, then run it
- [ ] Add featured images to all seeded posts in WP Admin
- [ ] Update `FORM_ID` / `ADVERTISE_FORM_ID` in page-contact.php and page-advertise.php

### Phase 5: Launch & QA
- [x] Pre-launch QA check script (wordpress/qa-check.sh — 13 validation categories)
- [x] Go-live launch script (wordpress/launch.sh — flips public, purges caches, submits sitemap)
- [ ] Run qa-check.sh on production — fix all FAILs before proceeding
- [ ] QA on mobile/desktop/tablet (375px, 768px, 1024px)
- [ ] Google PageSpeed 85+ mobile, 90+ desktop
- [ ] Submit sitemap to Search Console: /sitemap_index.xml
- [ ] Ontario casino compliance review (see launch-checklist §10)
- [ ] Run wordpress/launch.sh to go live

### Phase 6: Post-Launch Growth
- [ ] Monitor GA4 + Search Console for crawl errors and indexing status
- [ ] Set up rank tracking (Rank Math or external tool like Ahrefs/Semrush)
- [ ] Publish Phase 1 casino cluster posts (12 drafts ready — phase1-casino-clusters-seed.sh)
- [ ] Build city hub pages: Toronto, Ottawa, Niagara Falls (city-hubs-seed.sh)
- [ ] Add services listings (services-seed.sh)
- [ ] Apply for iGO casino affiliate programs; replace all `REPLACE_WITH_REAL_URL` placeholders
- [ ] A/B test CTA button copy on casino pages
- [ ] Weekly editorial workflow using content-briefs/brief-template.md
- [ ] Month 2+: Upgrade to pro plugins (Rank Math Pro, WP Rocket, Astra Pro) once revenue begins

---

## Local Development

```bash
cd ontariosbest/
docker compose up -d
bash wordpress/local-setup.sh

# Site:      http://localhost:8080
# WP Admin:  http://localhost:8080/wp-admin  (admin / admin123)
# phpMyAdmin: http://localhost:8081
```

Theme files are live-mounted — edit PHP/CSS and refresh, no rebuild needed.

---

## Production Deployment

```bash
# Deploy static HTML pages (primary site delivery):
python3 sftp_deploy.py

# Deploy HTML pages into WordPress as pages + featured images:
python3 deploy_all.py

# Deploy/update WordPress theme:
# SSH into Cloudways server, then from public_html/:
bash ontariosbest/deploy.sh
```

---

## Key Notes

- **Primary delivery is static HTML** via `sftp_deploy.py` — WordPress handles CMS, blog, and SEO
- **V2 HTML files** (casinos, poker) live in `ontariosbest/Ontarios Best Assets/` — these are what `sftp_deploy.py` deploys
- All casino affiliate links must point to **iGO-licensed operators only**
  Check: https://igamingontario.ca/en/operator
- Every page must show the **19+ responsible gambling footer notice**
- Affiliate links must have `rel="nofollow noopener sponsored"` and `target="_blank"`
- **`affiliate-links-seed.sh`** contains `REPLACE_WITH_REAL_URL` placeholders — update with real tracking URLs before running on production
- **WPForms placeholders:** `FORM_ID` in page-contact.php and `ADVERTISE_FORM_ID` in page-advertise.php — update after creating forms in WP Admin
- If static HTML pages conflict with WordPress routes, add `.htaccess` rewrites (see comment block in sftp_deploy.py)
