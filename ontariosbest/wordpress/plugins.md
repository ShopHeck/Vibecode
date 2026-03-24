# Plugin List — OntariosBest.com

## Core (All Free)

| Plugin | WordPress.org Slug | Purpose |
|--------|--------------------|---------|
| Astra | `astra` | Parent theme — fast, SEO-friendly |
| Advanced Custom Fields | `advanced-custom-fields` | Casino + listing custom fields |
| Rank Math SEO | `seo-by-rank-math` | SEO, schema markup, sitemap, redirects |
| Kadence Blocks | `kadence-blocks` | Page builder (Gutenberg) |
| ThirstyAffiliates | `thirstyaffiliates` | Affiliate link cloaking + click tracking |
| TablePress | `tablepress` | Comparison and data tables |
| WPForms Lite | `wpforms-lite` | Contact forms |
| LiteSpeed Cache | `litespeed-cache` | Caching + performance (free, no-brainer on most hosts) |
| UpdraftPlus | `updraftplus` | Backups (Google Drive / Dropbox) |
| Wordfence | `wordfence` | Security scanning + firewall |
| Imagify | `imagify` | Image optimization |
| MonsterInsights Lite | `google-analytics-for-wordpress` | GA4 integration |

## Installation Order

1. Astra theme + OntariosBest child theme
2. Rank Math SEO (configure before adding content)
3. Advanced Custom Fields → import `acf/casino-fields.json` + `acf/listing-fields.json`
4. Kadence Blocks
5. ThirstyAffiliates (link prefix: `/go/`)
6. TablePress
7. WPForms Lite
8. Wordfence
9. UpdraftPlus
10. Imagify
11. MonsterInsights Lite
12. LiteSpeed Cache **last** (after everything else is confirmed working)

## Pro Upgrades — Only If Needed Later

| Plugin | Pro Feature Worth Paying For |
|--------|------------------------------|
| Rank Math Pro | Local SEO module, advanced review schema |
| ThirstyAffiliates Pro | Geo-redirects (Ontario-specific landing pages) |
| UpdraftPlus Premium | Automated remote backup scheduling |

---

## Notes

- **WP Rocket**: No free tier — replaced by LiteSpeed Cache (free, comparable performance)
- **WP Review Pro**: Replaced by Rank Math free schema markup (Review schema on casino post type)
- **Directorist**: Not needed — custom post types + ACF fields handle listings natively via the theme
- **ACF Free**: All field types used (`text`, `number`, `textarea`, `url`, `select`, `wysiwyg`, `true_false`) are in the free version
