# CLAUDE.md — OntariosBest.com Project Plan

This file tracks progress on the OntariosBest.com WordPress affiliate directory site.
See `ontariosbest/STRATEGY.md` for full strategy and `ontariosbest/wordpress/launch-checklist.md` for the deployment checklist.

---

## Project Structure

```
Vibecode/
├── CLAUDE.md                          ← You are here
├── README.md
└── ontariosbest/
    ├── STRATEGY.md                    ← Full site strategy and revenue model
    ├── deploy.sh                      ← Cloudways production deployment script
    ├── docker-compose.yml             ← Local dev environment
    ├── dist/
    │   └── ontariosbest-theme.zip    ← Built theme package (upload to WP)
    └── wordpress/
        ├── local-setup.sh            ← Run after docker compose up -d
        ├── launch-checklist.md       ← Step-by-step go-live checklist
        ├── plugins.md                ← Plugin list and install order
        ├── setup-guide.md            ← Hosting, theme, plugin setup guide
        ├── acf/
        │   ├── casino-fields.json    ← ACF field group for casino CPT
        │   └── listing-fields.json   ← ACF field group for all listing CPTs
        └── theme/                    ← Child theme source files (Astra child)
            ├── style.css
            ├── functions.php         ← CPTs, taxonomies, shortcodes, helpers
            ├── header.php
            ├── footer.php
            ├── front-page.php        ← Homepage template
            ├── single-casino.php     ← Casino review page
            ├── single-listing.php    ← Travel/restaurant/entertainment page
            ├── single-post.php       ← Blog post
            ├── archive.php           ← Blog archive
            ├── archive-listing.php   ← Listing archive (travel, etc.)
            ├── template-casino-archive.php ← Casino directory index
            ├── page-compare.php      ← Casino comparison tool
            ├── page-bestof.php       ← Best-of landing pages
            ├── page-about.php
            ├── page-contact.php
            ├── page-legal.php        ← Privacy, Terms, Affiliate Disclosure
            ├── page-responsible-gambling.php
            └── page-advertise.php
```

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

### Phase 2: WordPress Configuration
- [x] Install all plugins — **free versions only** (see wordpress/plugins.md — install in listed order)
- [x] Import ACF field groups (ACF → Tools → Import both JSON files)
- [x] Configure Rank Math (connect GSC, set schema types per CPT)
- [x] Configure ThirstyAffiliates (/go/ prefix, link categories)
- [x] Configure WP Super Cache (last — after all other plugins working)
- [x] Configure Wordfence + UpdraftPlus

### Phase 3: Content
- [x] **Add 12 casino reviews** (seed script: wordpress/seeds/casinos-seed.sh)
- [x] **Add 8 directory listings** (3 travel, 3 restaurant, 2 entertainment — wordpress/seeds/listings-seed.sh)
- [x] **Write 5 foundational blog posts** (wordpress/seeds/blog-seed.sh)
- [x] Add best-of pages (3 minimum — wordpress/seeds/bestof-seed.sh)
- [x] Implement affiliate links in ThirstyAffiliates (wordpress/seeds/affiliate-links-seed.sh)

> **Run seeds in order:**
> ```bash
> bash wordpress/seeds/casinos-seed.sh
> bash wordpress/seeds/listings-seed.sh
> bash wordpress/seeds/blog-seed.sh
> bash wordpress/seeds/bestof-seed.sh
> bash wordpress/seeds/affiliate-links-seed.sh
> ```
> Then open WP Admin and add featured images to each post.
> In affiliate-links-seed.sh, replace all `REPLACE_WITH_REAL_URL` values with your real affiliate tracking URLs before running in production.

### Phase 4: Launch
- [x] Pre-launch QA check script (wordpress/qa-check.sh — validates all checklist items)
- [x] Go-live launch script (wordpress/launch.sh — flips public, purges caches, submits sitemap)
- [ ] Run qa-check.sh on production — fix all FAILs before proceeding
- [ ] QA on mobile/desktop/tablet (375px, 768px, 1024px)
- [ ] Google PageSpeed 85+ mobile, 90+ desktop
- [ ] Submit sitemap to Search Console: /sitemap_index.xml
- [ ] Ontario casino compliance review (see launch-checklist §10)
- [ ] Run wordpress/launch.sh to go live

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
# SSH into Cloudways server, then from public_html/:
bash deploy.sh
```

---

## Key Notes

- All casino affiliate links must point to **iGO-licensed operators only**
  Check: https://igamingontario.ca/en/operator
- Every page must show the **19+ responsible gambling footer notice**
- Affiliate links must have `rel="nofollow noopener sponsored"` and `target="_blank"`
- WPForms form IDs are placeholder `FORM_ID` in page-contact.php and page-advertise.php — update after creating forms in WP admin
