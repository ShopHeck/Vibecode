# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

**OntariosBest.com** — a live WordPress affiliate directory at ontariosbest.com targeting Ontario residents. Primary revenue is iGO-licensed casino affiliate commissions. The repo contains the WordPress child theme, ACF field definitions, deployment scripts, and page content.

Current status: Site is live. Theme active. All plugins installed. All required pages published. Waiting on casino affiliate program approvals (applied, 1–7 day turnaround). Content creation is the active phase.

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

Ready-to-paste content for WP Admin is in `wordpress/content/`:
- `privacy-policy.md` — PIPEDA-compliant, paste into Privacy Policy page (Legal Page template)
- `terms-and-conditions.md` — paste into Terms & Conditions page (Legal Page template)
- `affiliate-disclosure.md` — FTC + ASC compliant, paste into Affiliate Disclosure page
- `about.md` — editorial standards + mission, paste into About page

---

## Active Development Branch

`claude/setup-vibecode-cli-QkD85`
