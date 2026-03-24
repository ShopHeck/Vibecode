# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

**OntariosBest.com** — a live WordPress affiliate directory at ontariosbest.com targeting Ontario residents. Primary revenue is iGO-licensed casino affiliate commissions. The repo contains the WordPress child theme, ACF field definitions, deployment scripts, and page content.

## Current Status (March 24, 2026)

### Done ✅
- Site live at ontariosbest.com — Cloudways, SSL, HTTPS
- Astra + OntariosBest child theme active
- All plugins installed and configured (Rank Math, ACF Pro, WPForms, Imagify, ThirstyAffiliates, Kadence Blocks, etc.)
- All required pages live (About, Contact, Privacy Policy, Terms, Affiliate Disclosure, Responsible Gambling, Advertise)
- All 12 casino review content files written: `wordpress/content/casinos/`
- All 5 blog post content files written: `wordpress/content/blog/`
- Legal page content files written: `wordpress/content/` (privacy-policy, terms, affiliate-disclosure, about)
- Casino affiliate applications submitted to all 12 operators (approvals pending 1–7 days)
- Theme zip rebuilt with SVG social icon fix (re-upload needed — see below)

### Needs Doing — Next Session
1. **Write `ontariosbest/publish-content.sh`** — WP-CLI script to publish all 12 casino reviews + 5 blog posts from the content files. This is the top priority. See spec below.
2. **Update `ontariosbest/cloudways-setup.sh`** — add copy + run of publish-content.sh after deploy.sh
3. **Re-upload theme zip** — fixes F/I/T social icon placeholders in footer. Download `dist/ontariosbest-theme.zip` from repo, upload via WP Admin → Appearance → Themes → Add New → Upload (replace current)
4. **Set ThirstyAffiliates placeholder links** — 12 casino `/go/slug/` links pointing to casino homepages until affiliate approvals arrive
5. **Analytics** — Create GA4 property, connect MonsterInsights, submit sitemap to Search Console

### Affiliate Link Placeholder URLs (set in ThirstyAffiliates)
| Slug | Placeholder |
|------|------------|
| /go/betmgm/ | https://betmgm.ca |
| /go/draftkings/ | https://draftkings.ca |
| /go/fanduel/ | https://fanduel.ca |
| /go/bet99/ | https://bet99.com |
| /go/bet365/ | https://bet365.ca |
| /go/unibet/ | https://unibet.ca |
| /go/888casino/ | https://888casino.com/ontario |
| /go/pointsbet/ | https://pointsbet.ca |
| /go/leovegas/ | https://leovegas.ca |
| /go/jackpot-city/ | https://jackpotcity.ca |
| /go/spin-casino/ | https://spincasino.com |
| /go/ruby-fortune/ | https://rubyfortune.com |

---

## publish-content.sh — Spec for Next Session

**File to create:** `ontariosbest/publish-content.sh`
**Runs from:** WordPress webroot (`public_html/`) via `wp --allow-root`
**Idempotent:** Yes — creates posts if missing, updates if existing

### ACF meta keys used by `single-casino.php`
```
_casino_overall_rating   _casino_badge            _casino_welcome_bonus
_casino_affiliate_url    _casino_established      _casino_license
_casino_min_deposit      _casino_withdrawal_time  _casino_score_games
_casino_score_bonuses    _casino_score_ux         _casino_score_support
_casino_score_payments   _casino_pros             _casino_cons
```
ACF field keys (for reference pointer stored at `__casino_[name]`):
`field_casino_overall_rating`, `field_casino_badge`, `field_casino_welcome_bonus`,
`field_casino_affiliate_url`, `field_casino_established`, `field_casino_license`,
`field_casino_min_deposit`, `field_casino_withdrawal_time`, `field_casino_score_games`,
`field_casino_score_bonuses`, `field_casino_score_ux`, `field_casino_score_support`,
`field_casino_score_payments`, `field_casino_pros`, `field_casino_cons`

### Script structure
1. Create taxonomy terms: `casino_feature` (15 terms), `payment_method` (7 terms), blog categories (3)
2. Write HTML content to temp files via single-quoted heredocs (`'HTMLEOF'` — no variable expansion)
3. For each casino: `wp post create` or `wp post update`, then `wp post meta update` for all ACF fields, then `wp post term set`
4. For each blog post: `wp post create` or `wp post update`, set Rank Math meta, set category
5. Update legal pages (Privacy Policy, Terms, Affiliate Disclosure, About) with full content from content files

### 12 Casino Data (slug → rating, badge, bonus, affiliate URL, established, min deposit, withdraw, scores)
| Slug | Rating | Badge | Bonus | Est |
|------|--------|-------|-------|-----|
| betmgm-ontario | 4.5 | Editor's Choice | 100% up to $200 | 2022 |
| draftkings-ontario | 4.3 | Best for Sports Fans | $50 on $5 Deposit | 2022 |
| fanduel-ontario | 4.4 | Top Rated | Up to $200 | 2022 |
| bet99-ontario | 4.1 | Best Canadian Brand | 100% up to $500 | 2020 |
| bet365-ontario | 4.6 | Biggest Game Library | Up to $200 | 2022 |
| unibet-ontario | 4.1 | Most Responsible | $10 No Deposit | 2022 |
| 888casino-ontario | 4.0 | Est. 1997 | 100% up to $200 | 2022 |
| pointsbet-ontario | 3.9 | Best Odds | Up to $200 | 2022 |
| leovegas-ontario | 4.5 | Best Mobile Casino | 100% up to $500 + 200 FS | 2022 |
| jackpot-city-ontario | 4.1 | Canadian Classic | 100% up to $1,600 | 1998 |
| spin-casino-ontario | 4.0 | Slots Specialist | 100% up to $1,000 | 2001 |
| ruby-fortune-ontario | 3.9 | Classic Choice | 100% up to $750 | 2003 |

### Run command (content only, after site is set up)
```bash
ssh master@147.182.159.124 -p 22
cd /home/1604690.cloudwaysapps.com/hagyftbksy/public_html
bash publish-content.sh
```

---

## Local Development

```bash
# Start local WordPress environment
cd ontariosbest
docker compose up -d

# WordPress runs at http://localhost:8080
# phpMyAdmin at http://localhost:8081 (user: wp / wp_local_password)

# Run WP-CLI commands
docker compose exec wpcli wp <command>

# Import ACF field groups
docker compose exec wpcli wp acf import --json_file=/acf/casino-fields.json
docker compose exec wpcli wp acf import --json_file=/acf/listing-fields.json

# Stop (keep data)
docker compose down

# Stop + wipe database
docker compose down -v
```

The child theme directory (`ontariosbest/wordpress/theme/`) is mounted live into the container — file changes reflect immediately without rebuilding.

## Deploying Theme to Production

After editing any theme PHP or CSS file:

```bash
cd ontariosbest/wordpress
zip -r ontariosbest-theme.zip theme/
cp ontariosbest-theme.zip ../dist/ontariosbest-theme.zip
```

Then in WP Admin → Appearance → Themes → Add New → Upload Theme → replace current with uploaded.

Both `wordpress/ontariosbest-theme.zip` and `dist/ontariosbest-theme.zip` should be kept in sync.

---

## Repository Structure

```
ontariosbest/
├── wordpress/
│   ├── theme/              WordPress child theme (Astra parent)
│   ├── acf/                ACF field group JSON exports
│   ├── content/            Ready-to-paste page content (legal pages, About)
│   ├── ontariosbest-theme.zip
│   ├── launch-checklist.md
│   ├── plugins.md
│   └── setup-guide.md
├── dist/
│   └── ontariosbest-theme.zip
├── docker-compose.yml      Local dev environment
├── deploy.sh               Cloudways production deployment via WP-CLI
├── cloudways-setup.sh      Server bootstrap
└── STRATEGY.md
```

---

## Theme Architecture

Child theme of **Astra**. All customization lives in `wordpress/theme/`.

**Template routing** follows standard WordPress template hierarchy:

| Template | Handles |
|---|---|
| `front-page.php` | Homepage |
| `single-casino.php` | Individual casino review |
| `single-listing.php` | Travel / restaurant / entertainment / service listings |
| `single-post.php` | Blog posts |
| `archive-casino.php` | Casino directory index |
| `archive-listing.php` | Non-casino listing archives |
| `page-compare.php` | Casino comparison tool (`/casinos/compare/`) |
| `page-bestof.php` | Best-of aggregator pages |
| `page-advertise.php` | Advertise page |
| `page-legal.php` | Privacy Policy / Terms / Affiliate Disclosure |
| `page-responsible-gambling.php` | RG compliance page (self-contained, no WP content needed) |
| `page-about.php` | About page |
| `page-contact.php` | Contact form (WPForms shortcode — update `CONTACT_FORM_ID`) |

**Custom post types** (registered in `functions.php`): `casino`, `travel`, `entertainment`, `service`, `restaurant`, `shopping`

**Custom taxonomies**: `casino_feature`, `payment_method` (casino); `travel_region`, `entertainment_type`, `restaurant_cuisine`, `service_category`, `listing_region` (shared non-casino)

**ACF fields** drive all review-specific data (ratings, bonus details, pros/cons, affiliate URL, address, phone, scores). Field group definitions are in `acf/casino-fields.json` and `acf/listing-fields.json`. Never hardcode these field keys — always reference ACF docs or the JSON exports.

**Affiliate links** are all cloaked through ThirstyAffiliates at `/go/[slug]/`. Never use raw affiliate URLs in templates or content — always reference a ThirstyAffiliates link. Update the destination URL in ThirstyAffiliates admin when approvals arrive; all content updates automatically.

---

## Ontario iGaming Compliance — Non-Negotiable Rules

Every casino-related template and piece of content must follow these rules:

1. **Only link to iGO-licensed operators.** Verify at igamingontario.ca/en/operator before adding any casino.
2. **19+ notice** must appear on every page — it's in `footer.php` sitewide.
3. **Affiliate disclosure** must appear above content on all casino review pages — it's in `single-casino.php`.
4. **All casino affiliate links** must carry `rel="nofollow noopener sponsored"` and `target="_blank"`.
5. **No claims** of guaranteed winnings, specific return rates, or "risk-free" gambling anywhere in content or templates.
6. **Responsible gambling** resources (ConnexOntario 1-866-531-2600) must be linked from all casino pages.

Violating these rules can terminate affiliate relationships and cause iGO compliance issues.

---

## Content Files

All content is in `wordpress/content/` — **do not paste manually**, use `publish-content.sh` (to be built):

| File | WP destination |
|------|---------------|
| `casinos/betmgm-ontario.md` | Casino post — betmgm-ontario |
| `casinos/draftkings-ontario.md` | Casino post — draftkings-ontario |
| `casinos/fanduel-ontario.md` | Casino post — fanduel-ontario |
| `casinos/bet99-ontario.md` | Casino post — bet99-ontario |
| `casinos/bet365-ontario.md` | Casino post — bet365-ontario |
| `casinos/unibet-ontario.md` | Casino post — unibet-ontario |
| `casinos/888casino-ontario.md` | Casino post — 888casino-ontario |
| `casinos/pointsbet-ontario.md` | Casino post — pointsbet-ontario |
| `casinos/leovegas-ontario.md` | Casino post — leovegas-ontario |
| `casinos/jackpot-city-ontario.md` | Casino post — jackpot-city-ontario |
| `casinos/spin-casino-ontario.md` | Casino post — spin-casino-ontario |
| `casinos/ruby-fortune-ontario.md` | Casino post — ruby-fortune-ontario |
| `blog/best-online-casinos-ontario-2026.md` | Post — hub page, links to all reviews |
| `blog/how-to-choose-online-casino-ontario.md` | Post — casino guide |
| `blog/ontario-igaming-what-you-need-to-know.md` | Post — explainer |
| `blog/best-things-to-do-ontario-weekend.md` | Post — Ontario travel |
| `blog/best-restaurants-toronto-2026.md` | Post — Toronto restaurants |
| `privacy-policy.md` | Page — /privacy-policy/ (Legal Page template) |
| `terms-and-conditions.md` | Page — /terms/ (Legal Page template) |
| `affiliate-disclosure.md` | Page — /affiliate-disclosure/ (Legal Page template) |
| `about.md` | Page — /about/ (About template) |

---

## Active Development Branch

`claude/setup-vibecode-cli-QkD85`
