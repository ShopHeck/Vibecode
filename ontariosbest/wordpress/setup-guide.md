# WordPress Setup Guide — OntariosBest.com

## 1. Hosting Setup (Cloudways)

1. Create a Cloudways account at cloudways.com
2. Launch a new server:
   - Provider: DigitalOcean or Vultr
   - Size: 2GB RAM minimum (4GB recommended for growth)
   - Data Center: Toronto (for Ontario audience)
3. Create a new application → WordPress → latest version
4. Set primary domain to `ontariosbest.com`
5. Enable Cloudflare from the Cloudways dashboard (or manually via cloudflare.com)

---

## 2. WordPress Installation

### Initial Settings
- **Site Title**: Ontario's Best
- **Tagline**: Ontario's Top-Rated Casinos, Travel, Entertainment & More
- **Admin email**: use a real business email
- **Permalinks**: Settings → Permalinks → Post name (`/%postname%/`)
- **Timezone**: Toronto (America/Toronto)
- **Language**: English (Canada)

### DNS
Point your domain's A record to the Cloudways server IP.
Set up www redirect (www → non-www or vice versa, be consistent).

---

## 3. Theme Installation

1. Themes → Add New → Upload Theme → upload `astra.zip`
2. Activate Astra
3. Create a child theme folder `ontariosbest/` (or use the provided theme files)
4. Upload `style.css` and `functions.php` from `/wordpress/theme/`
5. Activate the OntariosBest child theme

---

## 4. Plugin Installation

Install plugins in this order (see `plugins.md` for full list):

```
1. Rank Math Pro
2. Astra Pro add-on (if using Astra Pro)
3. Kadence Blocks Pro
4. Directorist + add-ons
5. ThirstyAffiliates Pro
6. WP Review Pro
7. TablePress
8. WPForms Lite
9. MonsterInsights
10. UpdraftPlus
11. Wordfence
12. WP Rocket (configure LAST)
```

---

## 5. Rank Math Configuration

- Connect to Google Search Console
- Enable: Local SEO, Review schema, FAQ schema, Breadcrumbs
- Set default schema type per post type:
  - `casino` → Review
  - `travel`, `entertainment`, `service` → LocalBusiness
  - `post` → Article
- Enable XML sitemap
- Set up 404 monitor and redirects

---

## 6. Custom Post Types & Fields

The child theme `functions.php` registers these post types automatically:
- `casino` (slug: `/casinos/`)
- `travel` (slug: `/travel/`)
- `entertainment` (slug: `/entertainment/`)
- `service` (slug: `/services/`)

### Casino Custom Fields (add via ACF or manually)

| Meta Key | Label | Type |
|----------|-------|------|
| `_casino_overall_rating` | Overall Rating | Number (1.0–5.0) |
| `_casino_welcome_bonus` | Welcome Bonus | Text |
| `_casino_affiliate_url` | Affiliate URL | URL |
| `_casino_established` | Established Year | Number |
| `_casino_license` | License | Text |
| `_casino_min_deposit` | Min Deposit | Text |
| `_casino_withdrawal_time` | Withdrawal Time | Text |
| `_casino_score_games` | Score: Games | Number (1.0–5.0) |
| `_casino_score_bonuses` | Score: Bonuses | Number (1.0–5.0) |
| `_casino_score_ux` | Score: UX | Number (1.0–5.0) |
| `_casino_score_support` | Score: Support | Number (1.0–5.0) |
| `_casino_score_payments` | Score: Payments | Number (1.0–5.0) |
| `_casino_pros` | Pros (one per line) | Textarea |
| `_casino_cons` | Cons (one per line) | Textarea |
| `_casino_badge` | Badge (e.g. "Editor's Choice") | Text |

**Recommended:** Use **Advanced Custom Fields (ACF) Pro** to create a field group for `casino` post type — much easier than editing post meta manually.

---

## 7. ThirstyAffiliates Setup

1. Install ThirstyAffiliates Pro
2. Settings → ThirstyAffiliates:
   - Link prefix: `/go/`
   - Enable link cloaking
   - Enable click tracking
3. Create link categories: Casinos, Travel, Entertainment, Services
4. Add all affiliate links via ThirstyAffiliates → Add New Link
5. For casino links: enable geo-redirect to Ontario-specific landing pages if available

---

## 8. WP Rocket Configuration

Configure LAST (after everything else is working):
- Enable page caching
- Enable browser caching
- Enable GZIP compression
- Enable lazy loading for images
- Enable minify CSS/JS (test carefully — can break some plugins)
- Connect to Cloudflare if using it

---

## 9. Required Pages

Create these pages before launching:

| Page | Slug | Notes |
|------|------|-------|
| Home | `/` | Use Elementor/Kadence to build |
| About | `/about/` | Who we are, editorial standards |
| Contact | `/contact/` | WPForms contact form |
| Responsible Gambling | `/responsible-gambling/` | Required for casino compliance |
| Privacy Policy | `/privacy-policy/` | Required by law |
| Terms & Conditions | `/terms/` | Required |
| Affiliate Disclosure | `/affiliate-disclosure/` | FTC compliance |

---

## 10. Ontario Casino Compliance Checklist

Before going live with casino content:

- [ ] Footer has "19+ | Please gamble responsibly" notice on all pages
- [ ] Casino pages have responsible gambling disclaimer
- [ ] Affiliate disclosure on all review/listing pages
- [ ] Link to ConnexOntario (1-866-531-2600)
- [ ] Privacy Policy published
- [ ] Terms & Conditions published
- [ ] Affiliate Disclosure page published
- [ ] Only linking to iGO-licensed operators (check: igamingontario.ca/en/operator)
- [ ] No false claims about winnings, odds, or guaranteed bonuses

---

## 11. Analytics Setup

1. Create GA4 property at analytics.google.com
2. Install MonsterInsights or add GA4 tag directly
3. Set up conversions:
   - Click on affiliate link (ThirstyAffiliates tracks this)
   - Contact form submission
   - Newsletter signup
4. Add site to Google Search Console
5. Submit XML sitemap: `ontariosbest.com/sitemap_index.xml`

---

## 12. Launch Checklist

- [ ] All required pages created and published
- [ ] SSL certificate active (HTTPS)
- [ ] www redirect configured
- [ ] 10+ casino reviews published
- [ ] Homepage complete
- [ ] Navigation menus configured
- [ ] Footer configured (RG notice, links, copyright)
- [ ] UpdraftPlus backup configured (daily, off-site)
- [ ] Wordfence security scan clean
- [ ] PageSpeed score 80+ (mobile and desktop)
- [ ] Site submitted to Google Search Console
- [ ] Sitemap submitted
