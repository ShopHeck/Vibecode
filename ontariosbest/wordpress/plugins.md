# Plugin List — OntariosBest.com

**All free versions only.**

## Core Plugins

| Plugin | Free Version | Purpose |
|--------|-------------|---------|
| Astra | Free | Theme (child theme handles customization) |
| Advanced Custom Fields (ACF) | Free | Custom fields for casino/listing CPTs |
| Rank Math | Free | SEO, schema markup, redirects |
| ThirstyAffiliates | Free | Affiliate link cloaking + tracking |
| WP Super Cache | Free | Caching + performance |
| Site Reviews | Free | Star ratings + review schema |
| Kadence Blocks | Free | Gutenberg page builder blocks |

## Security & Maintenance

| Plugin | Purpose |
|--------|---------|
| Wordfence | Security (free tier) |
| UpdraftPlus | Backups (free tier) |

## Utility

| Plugin | Purpose |
|--------|---------|
| WPForms Lite | Contact forms |
| TablePress | Comparison/data tables |
| Imagify | Image optimization (free tier) |
| MonsterInsights Lite | GA4 integration |
| Mailchimp for WP | Email list building |

---

## Installation Order

1. Astra theme + child theme (already deployed via deploy.sh)
2. Advanced Custom Fields — then import both JSON files from `acf/`
3. Rank Math (configure before adding content)
4. ThirstyAffiliates (set /go/ prefix, add affiliate link categories)
5. Kadence Blocks
6. Site Reviews
7. TablePress
8. WPForms Lite
9. Wordfence
10. UpdraftPlus
11. WP Super Cache (configure last, after everything else is working)
12. Remaining utility plugins
