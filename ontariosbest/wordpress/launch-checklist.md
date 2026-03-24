# OntariosBest.com — Launch Checklist

Work through this checklist top to bottom before going live.
Each section must be fully complete before moving to the next.

> **Last reviewed:** 2026-03-24
> **Status:** Theme live at ontariosbest.com — working on content & plugins

---

## 1. Hosting & DNS

- [x] Cloudways account created
- [x] WordPress application created on the server
- [x] Primary domain set to `ontariosbest.com`
- [x] SSL / HTTPS working — site loads at ontariosbest.com
- [ ] Domain A record confirmed pointing to Cloudways server IP
- [ ] www redirect configured (www → non-www or vice versa — pick one)
- [ ] Cloudflare nameservers set (if using Cloudflare proxy)
- [ ] Cloudflare CDN enabled
- [ ] Staging environment created (Cloudways one-click clone)

---

## 2. WordPress Core Setup

- [x] WordPress installed and running
- [x] Site Title: `Ontario's Best` ✓ (visible in browser tab)
- [x] Admin account created (logged in as Michaelheckert)
- [ ] Admin username: confirm it is NOT "admin"
- [ ] Tagline: `Ontario's Top-Rated Casinos, Travel, Entertainment & More`
- [x] Settings → Permalinks → Post name `/%postname%/` ✓ (URLs working)
- [ ] Settings → General → Timezone: `America/Toronto`
- [ ] Settings → General → Language: `English (Canada)`
- [ ] Settings → Reading → "Discourage search engines" is **UNCHECKED** before launch
- [ ] Default category renamed from "Uncategorized" to "General"
- [ ] Sample page and Hello World post deleted

---

## 3. Theme Installation

- [x] Astra theme installed and activated ✓
- [ ] Astra Pro add-on installed and license key entered
- [x] OntariosBest child theme uploaded and activated ✓
- [x] Hero, navigation, category grid rendering on desktop ✓
- [ ] ~~Hamburger menu icon broken on mobile~~ — **fixed in latest commit** (re-upload theme zip)
- [ ] ~~Social icons showing letters in footer~~ — **fixed in latest commit** (re-upload theme zip)
- [ ] Verify mobile hero renders after theme re-upload
- [ ] Custom logo uploaded (Appearance → Customize → Site Identity)
- [x] Primary nav menu assigned — Casinos, Travel, Restaurants, Entertainment, Services, Blog ✓
- [ ] Footer menu created and assigned to "Footer" location
  - Items: About, Contact, Advertise, Privacy Policy, Terms, Affiliate Disclosure, Responsible Gambling
- [ ] Social media URLs updated in footer.php (currently `#` placeholders)

---

## 4. Plugin Installation (in this order)

- [x] **Rank Math SEO** — installed and active ✓ (visible in admin bar)
- [x] **Advanced Custom Fields Pro** — installed, ACF fields imported ✓
- [x] **WPForms** — installed and active ✓ (visible in admin bar)
- [x] **Imagify** — installed and active ✓ (visible in admin bar)
- [ ] **Rank Math Pro** — enter license key (upgrade from free if needed)
- [ ] **Kadence Blocks Pro** — install, activate
- [ ] **ThirstyAffiliates Pro** — install, activate (configure in step 6)
- [ ] **WP Review Pro** — install, activate
- [ ] **TablePress** — install, activate
- [ ] **MonsterInsights** — install, activate (configure in step 7)
- [ ] **Mailchimp for WP** — install, activate (connect Mailchimp account)
- [ ] **UpdraftPlus** — install, activate (configure in step 9)
- [ ] **Wordfence** — install, activate (configure in step 9)
- [ ] **WP Rocket** — install, activate LAST (configure after everything else works)

---

## 5. Required Pages (Create in WP Admin → Pages → Add New)

For each page: set the correct template, publish, then add to the footer menu.

| Page Title            | Slug                    | Template                      | Content |
|-----------------------|-------------------------|-------------------------------|---------|
| Home                  | (set via Reading settings) | Default (front-page.php auto) | — |
| About                 | `/about/`               | About                         | Edit in WP admin |
| Contact               | `/contact/`             | Contact                       | Replace `CONTACT_FORM_ID` with WPForms ID |
| Responsible Gambling  | `/responsible-gambling/`| Responsible Gambling          | No content needed — template is self-contained |
| Privacy Policy        | `/privacy-policy/`      | Legal Page                    | Paste full Privacy Policy text |
| Terms & Conditions    | `/terms/`               | Legal Page                    | Paste full Terms text |
| Affiliate Disclosure  | `/affiliate-disclosure/`| Legal Page                    | Paste Affiliate Disclosure text |
| Advertise             | `/advertise/`           | Advertise                     | Replace `ADVERTISE_FORM_ID` with WPForms ID |
| Best Of (index)       | `/best-of/`             | Default                       | Brief intro + links to best-of pages |

- [ ] Home page set as front page: Settings → Reading → Static page → select Home
- [ ] All 8 required pages published (not draft)
- [ ] All pages accessible via their slugs
- [ ] Pages added to menus

---

## 6. Affiliate Link Setup (ThirstyAffiliates)

- [ ] Link prefix set to `/go/` (ThirstyAffiliates → Settings → General)
- [ ] Link cloaking enabled
- [ ] Click tracking enabled
- [ ] Google Analytics event tracking enabled (ThirstyAffiliates → Settings → Statistics)
- [ ] Link categories created: Casinos, Travel, Entertainment, Services, Shopping
- [ ] Affiliate accounts joined (see list below)
- [ ] Links added for each joined program:

**Casino programs to join (iGO-licensed only):**
- [ ] BetMGM Ontario
- [ ] DraftKings Ontario
- [ ] FanDuel Ontario
- [ ] Bet99
- [ ] PointsBet Ontario
- [ ] Unibet Ontario
- [ ] 888casino Ontario
- [ ] bet365 Ontario
- [ ] LeoVegas Ontario
- [ ] Jackpot City
- [ ] Spin Casino
- [ ] Ruby Fortune

**Travel programs to join:**
- [ ] Booking.com Partner Programme
- [ ] Expedia Affiliate Network
- [ ] Hotels.com Affiliate Program

**Entertainment:**
- [ ] Ticketmaster Affiliate Program
- [ ] StubHub Affiliate Program

- [ ] Auto-link keywords enabled for casino brand names (ThirstyAffiliates → Settings → Automatic Linking)
- [ ] Verify each `/go/[slug]/` link redirects correctly

---

## 7. Analytics & SEO

### Google Analytics 4
- [ ] GA4 property created at analytics.google.com
- [ ] MonsterInsights connected to GA4 (MonsterInsights → Settings → Connect)
- [ ] Affiliate click tracking enabled (MonsterInsights → Publisher → Affiliate Links: `/go/`)
- [ ] Custom events configured in GA4:
  - `affiliate_click` (ThirstyAffiliates + MonsterInsights handle this automatically)
  - `click_to_call` (confirm `data-ga-event` attributes fire — check in GA4 DebugView)
  - `form_submit` (WPForms → Settings → Google Analytics)

### Rank Math
- [ ] Setup wizard completed (Rank Math → Setup Wizard)
- [ ] Connected to Google Search Console
- [ ] SEO title template set: `%title% – Ontario's Best`
- [ ] Default schema types set per post type:
  - `casino` → Review
  - `restaurant`, `travel`, `entertainment`, `service` → LocalBusiness
  - `post` → Article
  - `page` → WebPage
- [ ] XML sitemap enabled and accessible at `/sitemap_index.xml`
- [ ] Breadcrumbs enabled
- [ ] 404 monitor enabled
- [ ] Robots.txt: confirm `Disallow:` does NOT block `/casinos/`, `/travel/`, or blog

### Google Search Console
- [ ] Site verified in Search Console
- [ ] Sitemap submitted: `https://ontariosbest.com/sitemap_index.xml`
- [ ] No crawl errors on initial inspection

---

## 8. Phase 1 Content (Minimum Required to Launch)

All content must be published — not draft — before launch.

### Casino Reviews (12 minimum)
Each review requires:
- [ ] Post title = casino name
- [ ] Featured image = casino logo (400×200px, white/transparent background)
- [ ] Excerpt = 1–2 sentence summary
- [ ] Post content = full review (600+ words)
- [ ] All ACF fields filled: rating, bonus, affiliate URL, all scores, pros, cons
- [ ] `casino_feature` and `payment_method` taxonomy terms assigned
- [ ] Affiliate URL = ThirstyAffiliates cloaked `/go/[name]/` link

Target casinos:
- [ ] BetMGM Ontario
- [ ] DraftKings Ontario
- [ ] FanDuel Ontario
- [ ] Bet99
- [ ] PointsBet Ontario
- [ ] Unibet Ontario
- [ ] 888casino Ontario
- [ ] bet365 Ontario
- [ ] LeoVegas Ontario
- [ ] Jackpot City
- [ ] Spin Casino
- [ ] Ruby Fortune

### Other Listings (8 minimum)
- [ ] 3 Travel listings (Niagara Falls, Muskoka resort, Ottawa destination)
- [ ] 3 Restaurant listings (top Toronto or Ottawa picks)
- [ ] 2 Entertainment listings (Ontario attractions or events)

Each listing requires:
- [ ] Featured image
- [ ] Excerpt
- [ ] Post content (300+ words)
- [ ] All ACF fields: rating, address, phone, website, scores, pros, cons
- [ ] `listing_region` taxonomy term assigned
- [ ] Affiliate URL or `_listing_phone` filled

### Blog Posts (5 minimum)
- [ ] "Best Online Casinos in Ontario 2026" (hub post — links to all casino reviews)
- [ ] "How to Choose an Online Casino in Ontario"
- [ ] "Ontario iGaming: What You Need to Know"
- [ ] "Best Things to Do in Ontario This Weekend"
- [ ] "Best Restaurants in Toronto 2026"

Each post requires:
- [ ] Featured image
- [ ] 600+ words
- [ ] Internal links to relevant listing pages
- [ ] Category assigned
- [ ] Rank Math SEO title and meta description filled

### Best-Of Pages (3 minimum)
- [ ] `/best-of/best-online-casinos-ontario/` (template: Best Of, post_type: casino, limit: 12)
- [ ] `/best-of/best-restaurants-toronto/` (template: Best Of, post_type: restaurant, limit: 8)
- [ ] `/best-of/best-things-to-do-ontario/` (template: Best Of, post_type: entertainment, limit: 8)

---

## 9. Performance & Security

### WP Rocket (configure last)
- [ ] Page caching enabled
- [ ] Browser caching enabled
- [ ] GZIP compression enabled
- [ ] Lazy load images enabled
- [ ] Minify CSS enabled (test — disable if it breaks layout)
- [ ] Minify JS enabled (test — disable if it breaks functionality)
- [ ] Cloudflare add-on configured (if using Cloudflare)
- [ ] Preload cache enabled

### Cloudflare
- [ ] Proxy enabled (orange cloud)
- [ ] SSL/TLS mode: Full (Strict)
- [ ] Auto-minify: CSS, JavaScript, HTML — enabled
- [ ] Cache level: Standard
- [ ] Rocket Loader: OFF (can break WordPress scripts)

### UpdraftPlus
- [ ] Remote storage connected: Google Drive or Amazon S3
- [ ] Backup schedule: Daily for files, Daily for database
- [ ] Retention: Keep last 7 backups
- [ ] Test backup: run manual backup, confirm files appear in remote storage

### Wordfence
- [ ] Initial scan run — zero issues
- [ ] Firewall enabled (Learning Mode → Enabled)
- [ ] Login security: limit login attempts, 2FA for admin account
- [ ] Email alerts configured

### General Security
- [ ] WordPress admin username is NOT "admin"
- [ ] Strong admin password (20+ chars)
- [ ] File permissions: wp-config.php is 600
- [ ] Default WordPress login URL changed (via iThemes Security or similar) — optional

---

## 10. Ontario Casino Compliance

This section must be 100% complete before publishing any casino content.

- [ ] Responsible Gambling page published at `/responsible-gambling/`
- [ ] Footer RG notice visible on ALL pages: "19+ | Gambling can be addictive. ConnexOntario: 1-866-531-2600"
- [ ] Affiliate disclosure appears above content on all casino reviews
- [ ] Every casino affiliate link has `rel="nofollow noopener sponsored"` — verify in browser inspector
- [ ] Every casino affiliate link opens in `target="_blank"` — verify
- [ ] All casino affiliate links verified against iGO licensed operator list: `igamingontario.ca/en/operator`
- [ ] Privacy Policy published
- [ ] Terms & Conditions published
- [ ] Affiliate Disclosure page published
- [ ] No content claims guaranteed winnings, specific return rates, or "risk-free" gambling

---

## 11. Pre-Launch QA

### Functional
- [ ] Homepage loads correctly — all sections visible
- [ ] Category grid links all work
- [ ] At least one casino review loads — score box, CTA bar, affiliate link visible
- [ ] "Play Now" / "Claim Bonus" button on casino review redirects correctly via `/go/`
- [ ] Casino archive at `/casinos/` loads — ranked list visible, filters work
- [ ] Casino comparison tool at `/casinos/compare/?c1=ID&c2=ID` renders comparison table
- [ ] A best-of page loads and pulls correct listings
- [ ] Travel/Restaurant/Entertainment archive loads
- [ ] A listing review page loads — score, CTA, details table visible
- [ ] Blog archive at `/blog/` loads — posts grid visible, pagination works
- [ ] A blog post loads — sidebar with top casinos visible
- [ ] Contact form submits — confirmation message shown, email received
- [ ] Advertising form at `/advertise/` submits and sends email
- [ ] Footer links all work (Privacy, Terms, Affiliate Disclosure, RG, About, Contact)
- [ ] Search from hero bar returns results

### Mobile QA (test at 375px, 768px, 1024px)
- [ ] Homepage — hero, category grid readable and tappable
- [ ] Mobile nav opens and closes correctly
- [ ] Casino card — "Play Now" button visible and tappable
- [ ] Casino comparison table scrolls horizontally on mobile
- [ ] Blog post — readable, sidebar stacks below content
- [ ] Footer — columns stack on small screens

### SEO
- [ ] Google Rich Results Test passes for one casino review URL
- [ ] Sitemap accessible: `https://ontariosbest.com/sitemap_index.xml`
- [ ] Robots.txt accessible: `https://ontariosbest.com/robots.txt` — no critical paths blocked
- [ ] No duplicate `<title>` tags (check source of homepage and a review page)
- [ ] Canonical tags present (Rank Math adds these automatically)
- [ ] Breadcrumbs render correctly on review and archive pages

### Performance
- [ ] Google PageSpeed Insights — mobile score **85+**
- [ ] Google PageSpeed Insights — desktop score **90+**
- [ ] No images over 200KB (Imagify should handle this)
- [ ] No render-blocking resources that can't be deferred
- [ ] HTTPS — no mixed content warnings (check browser console)

---

## 12. Go-Live

- [ ] Settings → Reading → "Discourage search engines" is **UNCHECKED**
- [ ] Cloudflare cache purged
- [ ] WP Rocket cache cleared
- [ ] Sitemap re-submitted to Google Search Console
- [ ] GA4 realtime report — confirm traffic is being recorded
- [ ] Test one affiliate click end-to-end: click → redirect → GA4 `affiliate_click` event fires
- [ ] Announce on social media (if accounts set up)
- [ ] Monitor Wordfence and Cloudflare for first 24 hours

---

## Post-Launch (Week 1)

- [ ] Check Search Console for any crawl errors
- [ ] Verify first affiliate clicks are tracked in ThirstyAffiliates reports
- [ ] Check GA4 — confirm top pages, bounce rate, affiliate click conversion paths
- [ ] Fix any mobile issues reported by real users
- [ ] Schedule next batch of content (Phase 2 planning)

---

## Notes

- Always test changes on **staging** before applying to production
- Keep UpdraftPlus backups running — verify weekly
- Check iGO licensed operator list monthly — remove any casinos that lose their license
- Refresh casino review scores quarterly
- WPForms form IDs to update: `page-advertise.php` and `page-contact.php` both have placeholder `FORM_ID` values — update after creating forms in WPForms admin
