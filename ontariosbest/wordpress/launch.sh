#!/usr/bin/env bash
# =============================================================================
# OntariosBest.com — Go-Live Launch Script
#
# Run AFTER qa-check.sh passes with zero FAILs.
# This script flips the site public, submits the sitemap, and
# purges all caches.
#
# Usage:
#   WP_ENV=production bash wordpress/launch.sh
#
# ⚠ This is a one-way operation — it enables search engine indexing.
# Confirm you are ready before running.
# =============================================================================

set -euo pipefail

if [ "${WP_ENV:-local}" = "production" ]; then
    WP="wp --allow-root"
else
    WP="docker compose exec -T wpcli wp --allow-root"
fi

GREEN='\033[0;32m'; YELLOW='\033[1;33m'; RED='\033[0;31m'; NC='\033[0m'
log()  { echo -e "${GREEN}[✓]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }
fail() { echo -e "${RED}[✗]${NC} $1"; exit 1; }

SITE_URL=$($WP option get siteurl 2>/dev/null || echo "")

echo ""
echo "=================================================="
echo "  OntariosBest.com — GO-LIVE Launch"
echo "=================================================="
echo ""
echo "  Site: $SITE_URL"
echo ""

# Safety check: QA must have been run
echo -e "${YELLOW}Have you run qa-check.sh and confirmed all checks PASS?${NC}"
echo -e "Type 'yes' to continue: \c"
read -r CONFIRM
if [ "$CONFIRM" != "yes" ]; then
    echo "Launch cancelled. Run: WP_ENV=production bash wordpress/qa-check.sh"
    exit 0
fi

echo ""

# ---------------------------------------------------------
# 1. Enable search engine indexing
# ---------------------------------------------------------
log "Enabling search engine indexing..."
$WP option update blogpublic 1
log "Search engines can now index the site"

# ---------------------------------------------------------
# 2. Flush rewrite rules
# ---------------------------------------------------------
log "Flushing rewrite rules..."
$WP rewrite flush --hard
log "Rewrite rules flushed"

# ---------------------------------------------------------
# 3. Purge WP Rocket cache (if installed)
# ---------------------------------------------------------
if $WP plugin is-active "wp-rocket" 2>/dev/null; then
    log "Clearing WP Rocket cache..."
    $WP rocket clean --confirm 2>/dev/null && log "WP Rocket cache cleared" || warn "Could not clear WP Rocket cache via CLI — clear manually in WP Rocket settings"
else
    warn "WP Rocket not active — skip cache clear"
fi

# ---------------------------------------------------------
# 4. Flush WordPress object cache
# ---------------------------------------------------------
log "Flushing WordPress object cache..."
$WP cache flush 2>/dev/null || true
log "Object cache flushed"

# ---------------------------------------------------------
# 5. Ping Rank Math to regenerate sitemap
# ---------------------------------------------------------
if $WP plugin is-active "rank-math-seo" 2>/dev/null; then
    log "Regenerating Rank Math sitemap..."
    $WP eval "if(class_exists('RankMath\Sitemap\Sitemap')){ do_action('rank_math/sitemap/ping'); }" 2>/dev/null || true
    log "Sitemap regenerated — submit to Google Search Console: ${SITE_URL}/sitemap_index.xml"
else
    warn "Rank Math not active — submit sitemap manually once installed"
fi

# ---------------------------------------------------------
# 6. Set HTTPS siteurl and home (final confirmation)
# ---------------------------------------------------------
CURRENT_URL=$($WP option get siteurl)
if echo "$CURRENT_URL" | grep -q "^https://"; then
    log "HTTPS confirmed: $CURRENT_URL"
else
    fail "Site URL is not HTTPS: $CURRENT_URL — configure SSL before launch"
fi

# ---------------------------------------------------------
# 7. Verify Wordfence firewall mode
# ---------------------------------------------------------
if $WP plugin is-active "wordfence" 2>/dev/null; then
    log "Wordfence: active (verify firewall mode in WP Admin > Wordfence)"
fi

# ---------------------------------------------------------
# Done
# ---------------------------------------------------------
echo ""
echo "=================================================="
echo -e "  ${GREEN}🚀 Site is LIVE!${NC}"
echo "=================================================="
echo ""
echo "  $SITE_URL"
echo ""
echo "Post-launch checklist:"
echo "  [ ] Submit sitemap: ${SITE_URL}/sitemap_index.xml → Search Console"
echo "  [ ] Verify GA4 realtime report is recording visits"
echo "  [ ] Test one affiliate click end-to-end (click → /go/ redirect → casino)"
echo "  [ ] Test contact form submission → email received"
echo "  [ ] Check Wordfence scan (WP Admin > Wordfence > Scan)"
echo "  [ ] Verify UpdraftPlus backup runs tonight"
echo "  [ ] Check Cloudflare proxy status is active (orange cloud)"
echo "  [ ] Run Google PageSpeed Insights on homepage and one casino review"
echo "      Target: 85+ mobile, 90+ desktop"
echo "  [ ] Google Rich Results Test on one casino review:"
echo "      https://search.google.com/test/rich-results"
echo "  [ ] Monitor Search Console for crawl errors (check in 24h)"
echo ""
echo "  See: wordpress/launch-checklist.md for the full checklist"
echo ""
