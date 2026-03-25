#!/usr/bin/env bash
# =============================================================================
# OntariosBest.com — Pre-Launch QA Check Script
#
# Validates all critical launch requirements via WP-CLI before going live.
# Run on the production server after all content and plugins are configured.
#
# Usage:
#   WP_ENV=production bash wordpress/qa-check.sh
#
# Exit codes:
#   0 = all checks passed
#   1 = one or more FAIL checks — do not launch until resolved
# =============================================================================

set -euo pipefail

if [ "${WP_ENV:-local}" = "production" ]; then
    WP="wp --allow-root"
else
    WP="docker compose exec -T wpcli wp --allow-root"
fi

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

PASS=0
WARN=0
FAIL=0

pass() { echo -e "  ${GREEN}[PASS]${NC} $1"; PASS=$((PASS+1)); }
warn() { echo -e "  ${YELLOW}[WARN]${NC} $1"; WARN=$((WARN+1)); }
fail() { echo -e "  ${RED}[FAIL]${NC} $1"; FAIL=$((FAIL+1)); }
section() { echo -e "\n${BLUE}▶ $1${NC}"; }

echo ""
echo "=================================================="
echo "  OntariosBest.com — Pre-Launch QA Check"
echo "=================================================="

# =============================================================================
# 1. WordPress Core
# =============================================================================
section "WordPress Core"

if $WP core is-installed 2>/dev/null; then
    pass "WordPress is installed"
else
    fail "WordPress is NOT installed"
fi

SITE_URL=$($WP option get siteurl 2>/dev/null || echo "")
if echo "$SITE_URL" | grep -q "https://"; then
    pass "Site URL uses HTTPS: $SITE_URL"
else
    fail "Site URL does not use HTTPS: $SITE_URL"
fi

PERMALINK=$($WP option get permalink_structure 2>/dev/null || echo "")
if [ "$PERMALINK" = "/%postname%/" ]; then
    pass "Permalink structure: /%postname%/"
else
    fail "Permalink structure incorrect: '$PERMALINK' (expected /%postname%/)"
fi

TZ=$($WP option get timezone_string 2>/dev/null || echo "")
if [ "$TZ" = "America/Toronto" ]; then
    pass "Timezone: America/Toronto"
else
    warn "Timezone: '$TZ' (expected America/Toronto)"
fi

FRONT=$($WP option get show_on_front 2>/dev/null || echo "")
if [ "$FRONT" = "page" ]; then
    pass "Front page: set to static page"
else
    warn "Front page: set to '$FRONT' (expected 'page')"
fi

BLOGPUBLIC=$($WP option get blogpublic 2>/dev/null || echo "0")
if [ "$BLOGPUBLIC" = "1" ]; then
    pass "Search engine visibility: ENABLED (indexing on)"
else
    fail "Search engine visibility: BLOCKED — run: wp option update blogpublic 1"
fi

# =============================================================================
# 2. Theme
# =============================================================================
section "Theme"

ACTIVE_THEME=$($WP theme list --status=active --field=name 2>/dev/null | head -1)
if echo "$ACTIVE_THEME" | grep -qi "ontariosbest"; then
    pass "Active theme: $ACTIVE_THEME"
else
    fail "Active theme is NOT ontariosbest child theme: '$ACTIVE_THEME'"
fi

if $WP theme is-installed astra 2>/dev/null; then
    pass "Astra parent theme installed"
else
    fail "Astra parent theme NOT installed"
fi

# =============================================================================
# 3. Required Pages
# =============================================================================
section "Required Pages"

check_page() {
    local title="$1"
    local slug="$2"
    ID=$($WP post list --post_type=page --name="$slug" --post_status=publish --field=ID 2>/dev/null | head -1)
    if [ -n "$ID" ]; then
        pass "Page published: /$slug/ ($title)"
    else
        fail "Page MISSING or not published: /$slug/ ($title)"
    fi
}

check_page "Home"                  "home"
check_page "About"                 "about"
check_page "Contact"               "contact"
check_page "Responsible Gambling"  "responsible-gambling"
check_page "Privacy Policy"        "privacy-policy"
check_page "Terms & Conditions"    "terms"
check_page "Affiliate Disclosure"  "affiliate-disclosure"
check_page "Advertise"             "advertise"
check_page "Best Of"               "best-of"

# =============================================================================
# 4. Casino Reviews
# =============================================================================
section "Casino Reviews"

CASINO_COUNT=$($WP post list --post_type=casino --post_status=publish --format=count 2>/dev/null || echo "0")
if [ "$CASINO_COUNT" -ge 12 ]; then
    pass "Casino reviews: $CASINO_COUNT published (minimum 12)"
else
    fail "Casino reviews: $CASINO_COUNT published (need at least 12)"
fi

# Check required meta on first casino
FIRST_CASINO=$($WP post list --post_type=casino --post_status=publish --field=ID 2>/dev/null | head -1)
if [ -n "$FIRST_CASINO" ]; then
    RATING=$($WP post meta get "$FIRST_CASINO" _casino_overall_rating 2>/dev/null || echo "")
    BONUS=$($WP post meta get "$FIRST_CASINO" _casino_welcome_bonus 2>/dev/null || echo "")
    AFF_URL=$($WP post meta get "$FIRST_CASINO" _casino_affiliate_url 2>/dev/null || echo "")

    [ -n "$RATING" ] && pass "Casino meta: _casino_overall_rating present" || fail "Casino meta: _casino_overall_rating MISSING"
    [ -n "$BONUS" ]  && pass "Casino meta: _casino_welcome_bonus present"  || warn "Casino meta: _casino_welcome_bonus empty"
    [ -n "$AFF_URL" ] && pass "Casino meta: _casino_affiliate_url present"  || fail "Casino meta: _casino_affiliate_url MISSING"

    # Check affiliate URL is NOT the placeholder
    if echo "$AFF_URL" | grep -q "REPLACE_WITH_REAL_URL"; then
        fail "Casino affiliate URL still contains placeholder 'REPLACE_WITH_REAL_URL'"
    elif [ -n "$AFF_URL" ]; then
        pass "Casino affiliate URL looks configured"
    fi
fi

# =============================================================================
# 5. Directory Listings
# =============================================================================
section "Directory Listings"

for post_type in travel restaurant entertainment; do
    COUNT=$($WP post list --post_type="$post_type" --post_status=publish --format=count 2>/dev/null || echo "0")
    if [ "$COUNT" -ge 2 ]; then
        pass "$post_type listings: $COUNT published"
    else
        warn "$post_type listings: only $COUNT published (recommend 2+)"
    fi
done

# =============================================================================
# 6. Blog Posts
# =============================================================================
section "Blog Posts"

POST_COUNT=$($WP post list --post_type=post --post_status=publish --format=count 2>/dev/null || echo "0")
if [ "$POST_COUNT" -ge 5 ]; then
    pass "Blog posts: $POST_COUNT published (minimum 5)"
else
    fail "Blog posts: $POST_COUNT published (need at least 5)"
fi

# =============================================================================
# 7. Best-Of Pages
# =============================================================================
section "Best-Of Pages"

for slug in "best-online-casinos-ontario" "best-restaurants-toronto" "best-things-to-do-ontario"; do
    ID=$($WP post list --post_type=page --name="$slug" --post_status=publish --field=ID 2>/dev/null | head -1)
    if [ -n "$ID" ]; then
        TEMPLATE=$($WP post meta get "$ID" _wp_page_template 2>/dev/null || echo "")
        if echo "$TEMPLATE" | grep -q "bestof"; then
            pass "Best-of page: /$slug/ (template: page-bestof.php)"
        else
            warn "Best-of page: /$slug/ exists but template may be wrong ('$TEMPLATE')"
        fi
    else
        fail "Best-of page MISSING: /$slug/"
    fi
done

# =============================================================================
# 8. Plugins
# =============================================================================
section "Plugins"

check_plugin() {
    local slug="$1"
    local label="$2"
    local required="${3:-required}"
    if $WP plugin is-active "$slug" 2>/dev/null; then
        pass "Plugin active: $label"
    else
        if [ "$required" = "required" ]; then
            fail "Plugin NOT active: $label ($slug)"
        else
            warn "Plugin NOT active: $label ($slug) — recommended"
        fi
    fi
}

check_plugin "rank-math-seo"          "Rank Math Pro"          "required"
check_plugin "advanced-custom-fields" "ACF Pro"                "required"
check_plugin "thirstyaffiliates"      "ThirstyAffiliates Pro"  "required"
check_plugin "wpforms-lite"           "WPForms"                "required"
check_plugin "updraftplus"            "UpdraftPlus"            "required"
check_plugin "wordfence"              "Wordfence"              "required"
check_plugin "imagify"                "Imagify"                "recommended"
check_plugin "tablepress"             "TablePress"             "recommended"
check_plugin "wp-rocket"              "WP Rocket"              "recommended"

# =============================================================================
# 9. Ontario Casino Compliance
# =============================================================================
section "Ontario Casino Compliance (iGO Requirements)"

# Check Responsible Gambling page exists and is published
RG_ID=$($WP post list --post_type=page --name="responsible-gambling" --post_status=publish --field=ID 2>/dev/null | head -1)
if [ -n "$RG_ID" ]; then
    pass "Responsible Gambling page published at /responsible-gambling/"
else
    fail "Responsible Gambling page MISSING or not published"
fi

# Check Privacy Policy page
PP_ID=$($WP post list --post_type=page --name="privacy-policy" --post_status=publish --field=ID 2>/dev/null | head -1)
if [ -n "$PP_ID" ]; then
    CONTENT=$($WP post get "$PP_ID" --field=post_content 2>/dev/null || echo "")
    WORD_COUNT=$(echo "$CONTENT" | wc -w)
    if [ "$WORD_COUNT" -gt 100 ]; then
        pass "Privacy Policy: published with content ($WORD_COUNT words)"
    else
        fail "Privacy Policy: published but content is too short ($WORD_COUNT words) — add full policy"
    fi
else
    fail "Privacy Policy page MISSING or not published"
fi

# Check Affiliate Disclosure page
AD_ID=$($WP post list --post_type=page --name="affiliate-disclosure" --post_status=publish --field=ID 2>/dev/null | head -1)
[ -n "$AD_ID" ] && pass "Affiliate Disclosure page published" || fail "Affiliate Disclosure page MISSING"

# Check Terms page
TERMS_ID=$($WP post list --post_type=page --name="terms" --post_status=publish --field=ID 2>/dev/null | head -1)
[ -n "$TERMS_ID" ] && pass "Terms & Conditions page published" || fail "Terms & Conditions page MISSING"

# Check casino affiliate links for nofollow/sponsored
FIRST_CASINO=$($WP post list --post_type=casino --post_status=publish --field=ID 2>/dev/null | head -1)
if [ -n "$FIRST_CASINO" ]; then
    CONTENT=$($WP post get "$FIRST_CASINO" --field=post_content 2>/dev/null || echo "")
    if echo "$CONTENT" | grep -q "nofollow"; then
        pass "Casino content: contains nofollow link attributes"
    else
        warn "Casino content: no nofollow found — verify affiliate links have rel='nofollow noopener sponsored'"
    fi
fi

# =============================================================================
# 10. ThirstyAffiliates Configuration
# =============================================================================
section "ThirstyAffiliates"

if $WP plugin is-active "thirstyaffiliates" 2>/dev/null; then
    TA_PREFIX=$($WP option get ta_link_prefix 2>/dev/null || echo "")
    if [ "$TA_PREFIX" = "go" ]; then
        pass "ThirstyAffiliates link prefix: /go/"
    else
        warn "ThirstyAffiliates prefix: '$TA_PREFIX' (expected 'go')"
    fi

    LINK_COUNT=$($WP post list --post_type=thirstylink --post_status=publish --format=count 2>/dev/null || echo "0")
    if [ "$LINK_COUNT" -ge 12 ]; then
        pass "ThirstyAffiliates links: $LINK_COUNT created (minimum 12 for casinos)"
    else
        warn "ThirstyAffiliates links: only $LINK_COUNT created (need 12+ for all casinos)"
    fi

    # Check for placeholder URLs
    PLACEHOLDER_COUNT=$($WP post list --post_type=thirstylink --post_status=publish --fields=ID --format=csv 2>/dev/null | while read ID; do
        URL=$($WP post meta get "$ID" _ta_destination_url 2>/dev/null || echo "")
        echo "$URL"
    done | grep -c "REPLACE_WITH_REAL_URL" || true)

    if [ "${PLACEHOLDER_COUNT:-0}" -gt 0 ]; then
        fail "ThirstyAffiliates: $PLACEHOLDER_COUNT links still have placeholder URLs — update before launch"
    else
        pass "ThirstyAffiliates: no placeholder URLs detected"
    fi
else
    warn "ThirstyAffiliates not active — skipping link checks"
fi

# =============================================================================
# 11. Menus
# =============================================================================
section "Navigation Menus"

if $WP menu exists "Primary Menu" 2>/dev/null; then
    ITEM_COUNT=$($WP menu item list "Primary Menu" --format=count 2>/dev/null || echo "0")
    if [ "$ITEM_COUNT" -ge 4 ]; then
        pass "Primary Menu: $ITEM_COUNT items"
    else
        warn "Primary Menu: only $ITEM_COUNT items (expected Casinos, Travel, etc.)"
    fi
else
    fail "Primary Menu NOT found"
fi

if $WP menu exists "Footer Menu" 2>/dev/null; then
    pass "Footer Menu: exists"
else
    warn "Footer Menu: NOT found"
fi

# =============================================================================
# 12. Security
# =============================================================================
section "Security"

# Check DISALLOW_FILE_EDIT
if $WP config get DISALLOW_FILE_EDIT 2>/dev/null | grep -q "true"; then
    pass "DISALLOW_FILE_EDIT: true (file editing disabled)"
else
    warn "DISALLOW_FILE_EDIT: not set — recommended for production"
fi

if $WP plugin is-active "wordfence" 2>/dev/null; then
    pass "Wordfence: active"
else
    warn "Wordfence: NOT active — enable before launch"
fi

if $WP plugin is-active "updraftplus" 2>/dev/null; then
    pass "UpdraftPlus: active"
else
    warn "UpdraftPlus: NOT active — configure backups before launch"
fi

# =============================================================================
# 13. Sitemap
# =============================================================================
section "Sitemap"

if $WP plugin is-active "rank-math-seo" 2>/dev/null; then
    SITEMAP_ENABLED=$($WP option get "rank-math-options-general" 2>/dev/null | grep -c "sitemap" || echo "0")
    pass "Rank Math: active (verify sitemap at /sitemap_index.xml manually)"
else
    warn "Rank Math Pro not active — configure sitemap manually"
fi

# =============================================================================
# Results Summary
# =============================================================================
echo ""
echo "=================================================="
echo "  QA Results"
echo "=================================================="
echo -e "  ${GREEN}PASS: $PASS${NC}"
echo -e "  ${YELLOW}WARN: $WARN${NC}"
echo -e "  ${RED}FAIL: $FAIL${NC}"
echo ""

if [ "$FAIL" -gt 0 ]; then
    echo -e "  ${RED}⚠ NOT READY TO LAUNCH — fix $FAIL failing check(s) above${NC}"
    echo ""
    exit 1
elif [ "$WARN" -gt 0 ]; then
    echo -e "  ${YELLOW}✓ LAUNCH WITH CAUTION — $WARN warning(s) should be reviewed${NC}"
    echo ""
    exit 0
else
    echo -e "  ${GREEN}✓ ALL CHECKS PASSED — ready to launch!${NC}"
    echo ""
    echo "  Run: WP_ENV=production bash wordpress/launch.sh"
    echo ""
    exit 0
fi
