#!/usr/bin/env bash
# =============================================================================
# OntariosBest.com — Production Fix-All + QA Script
#
# Run this from public_html on the Cloudways server.
# It auto-fixes every correctable issue and reports remaining manual items.
#
# Usage:
#   cd ~/public_html
#   bash fix-all.sh
# =============================================================================

set -euo pipefail

WP="wp --allow-root"

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

PASS=0; WARN=0; FAIL=0; FIXED=0

pass()  { echo -e "  ${GREEN}[PASS]${NC}  $1"; PASS=$((PASS+1)); }
warn()  { echo -e "  ${YELLOW}[WARN]${NC}  $1"; WARN=$((WARN+1)); }
fail()  { echo -e "  ${RED}[FAIL]${NC}  $1"; FAIL=$((FAIL+1)); }
fixed() { echo -e "  ${GREEN}[FIXED]${NC} $1"; FIXED=$((FIXED+1)); }
section() { echo -e "\n${BLUE}▶ $1${NC}"; }

echo ""
echo "=================================================="
echo "  OntariosBest.com — Fix-All + QA"
echo "=================================================="

# Preflight
$WP core is-installed 2>/dev/null || { echo "WordPress not found in $(pwd). Run from public_html."; exit 1; }

# =============================================================================
# 1. Core Settings — auto-fix
# =============================================================================
section "Core Settings"

SITE_URL=$($WP option get siteurl 2>/dev/null || echo "")
if echo "$SITE_URL" | grep -q "^https://"; then
    pass "Site URL uses HTTPS: $SITE_URL"
else
    fail "Site URL not HTTPS: $SITE_URL — update in WP Admin > Settings > General"
fi

PERMALINK=$($WP option get permalink_structure 2>/dev/null || echo "")
if [ "$PERMALINK" != "/%postname%/" ]; then
    $WP option update permalink_structure "/%postname%/" && $WP rewrite flush --hard
    fixed "Permalink structure set to /%postname%/"
else
    pass "Permalink structure: /%postname%/"
fi

TZ=$($WP option get timezone_string 2>/dev/null || echo "")
if [ "$TZ" != "America/Toronto" ]; then
    $WP option update timezone_string "America/Toronto"
    fixed "Timezone set to America/Toronto"
else
    pass "Timezone: America/Toronto"
fi

FRONT=$($WP option get show_on_front 2>/dev/null || echo "")
if [ "$FRONT" != "page" ]; then
    HOME_ID=$($WP post list --post_type=page --name="home" --post_status=publish --field=ID 2>/dev/null | head -1 || echo "")
    if [ -n "$HOME_ID" ]; then
        $WP option update show_on_front "page"
        $WP option update page_on_front "$HOME_ID"
        fixed "Front page set to static page (Home, ID: $HOME_ID)"
    else
        warn "No 'home' page found — create it in WP Admin and set as front page"
    fi
else
    pass "Front page: static page"
fi

BLOGPUBLIC=$($WP option get blogpublic 2>/dev/null || echo "0")
if [ "$BLOGPUBLIC" != "1" ]; then
    $WP option update blogpublic 1
    fixed "Search engine visibility ENABLED (blogpublic=1)"
else
    pass "Search engine visibility: enabled"
fi

# =============================================================================
# 2. Theme
# =============================================================================
section "Theme"

ACTIVE_THEME=$($WP theme list --status=active --field=name 2>/dev/null | head -1)
if echo "$ACTIVE_THEME" | grep -qi "ontariosbest"; then
    pass "Active theme: $ACTIVE_THEME"
else
    fail "Active theme is NOT ontariosbest: '$ACTIVE_THEME' — activate in WP Admin > Appearance > Themes"
fi

if $WP theme is-installed astra 2>/dev/null; then
    pass "Astra parent theme installed"
else
    $WP theme install astra 2>/dev/null && fixed "Astra parent theme installed" || fail "Could not install Astra — install manually"
fi

# =============================================================================
# 3. Required Pages — create any missing
# =============================================================================
section "Required Pages"

ensure_page() {
    local title="$1"; local slug="$2"; local template="$3"; local content="$4"
    ID=$($WP post list --post_type=page --name="$slug" --post_status=publish --field=ID 2>/dev/null | head -1)
    if [ -n "$ID" ]; then
        pass "Page exists: /$slug/"
    else
        NEW_ID=$($WP post create \
            --post_type=page --post_status=publish \
            --post_title="$title" --post_name="$slug" \
            --post_content="$content" \
            --porcelain 2>/dev/null)
        [ -n "$template" ] && $WP post meta update "$NEW_ID" _wp_page_template "$template" 2>/dev/null || true
        fixed "Created page: /$slug/ (ID: $NEW_ID)"
    fi
}

ensure_page "Home"                 "home"                   "" ""
ensure_page "About"                "about"                  "page-about.php" ""
ensure_page "Contact"              "contact"                "page-contact.php" ""
ensure_page "Responsible Gambling" "responsible-gambling"   "page-responsible-gambling.php" ""
ensure_page "Affiliate Disclosure" "affiliate-disclosure"   "page-legal.php" "<h2>Affiliate Disclosure</h2><p>OntariosBest.com participates in affiliate programs. We earn commissions from qualifying links at no extra cost to you. This does not affect our editorial independence or ratings.</p>"
ensure_page "Terms &amp; Conditions" "terms"                "page-legal.php" "<h2>Terms and Conditions</h2><p>Please replace this with your full Terms and Conditions.</p>"
ensure_page "Advertise"            "advertise"              "page-advertise.php" ""
ensure_page "Best Of"              "best-of"                "" "<p>Explore Ontario's best — curated and ranked by our experts.</p>"

# Privacy Policy: ensure it exists and has sufficient content
PP_ID=$($WP post list --post_type=page --name="privacy-policy" --post_status=publish --field=ID 2>/dev/null | head -1)
if [ -z "$PP_ID" ]; then
    PP_ID=$($WP post create \
        --post_type=page --post_status=publish \
        --post_title="Privacy Policy" --post_name="privacy-policy" \
        --porcelain 2>/dev/null)
    $WP post meta update "$PP_ID" _wp_page_template "page-legal.php" 2>/dev/null || true
    fixed "Created Privacy Policy page (ID: $PP_ID)"
fi

PP_CONTENT=$($WP post get "$PP_ID" --field=post_content 2>/dev/null || echo "")
PP_WORDS=$(echo "$PP_CONTENT" | wc -w)
if [ "$PP_WORDS" -lt 100 ]; then
    $WP post update "$PP_ID" --post_content="<h2>Privacy Policy</h2>
<p>Last updated: $(date +'%B %d, %Y')</p>
<p>Ontario's Best (\"we\", \"us\", or \"our\") operates OntariosBest.com. This page informs you of our policies regarding the collection, use, and disclosure of personal data when you use our website and the choices you have associated with that data.</p>
<h3>Information We Collect</h3>
<p>We collect several types of information for various purposes. This includes usage data (pages visited, browser type, IP address), cookies, and any information you voluntarily provide via contact or inquiry forms.</p>
<h3>How We Use Your Information</h3>
<p>We use the collected data to provide and improve our service, analyze site usage, respond to inquiries, and send periodic updates where you have opted in. We do not sell your personal data to third parties.</p>
<h3>Cookies</h3>
<p>We use cookies to track activity on our site. You can instruct your browser to refuse cookies. If you do not accept cookies, some parts of our site may not function properly.</p>
<h3>Third-Party Services</h3>
<p>Our site contains links to casino operators, travel providers, and other third parties. We earn affiliate commissions from some of these links. These third parties have their own privacy policies; we encourage you to review them.</p>
<h3>Responsible Gambling</h3>
<p>We are committed to promoting responsible gambling. All casino recommendations on this site are for adults aged 19 and over. If you or someone you know has a gambling problem, please visit <a href='/responsible-gambling/'>our Responsible Gambling page</a>.</p>
<h3>Contact Us</h3>
<p>If you have questions about this Privacy Policy, please <a href='/contact/'>contact us</a>.</p>" 2>/dev/null
    fixed "Privacy Policy expanded to full content"
else
    pass "Privacy Policy: $PP_WORDS words (sufficient)"
fi

# =============================================================================
# 4. Casino Reviews
# =============================================================================
section "Casino Reviews"

CASINO_COUNT=$($WP post list --post_type=casino --post_status=publish --format=count 2>/dev/null || echo "0")
if [ "$CASINO_COUNT" -ge 12 ]; then
    pass "Casino reviews: $CASINO_COUNT published"
else
    fail "Casino reviews: $CASINO_COUNT published (need 12) — run: WP_ENV=production bash wordpress/seeds/casinos-seed.sh"
fi

FIRST_CASINO=$($WP post list --post_type=casino --post_status=publish --field=ID 2>/dev/null | head -1)
if [ -n "$FIRST_CASINO" ]; then
    RATING=$($WP post meta get "$FIRST_CASINO" _casino_overall_rating 2>/dev/null || echo "")
    AFF_URL=$($WP post meta get "$FIRST_CASINO" _casino_affiliate_url 2>/dev/null || echo "")
    [ -n "$RATING" ] && pass "Casino meta: rating present" || fail "Casino meta: _casino_overall_rating MISSING — run casinos-seed.sh"
    if echo "$AFF_URL" | grep -q "REPLACE_WITH_REAL_URL"; then
        fail "Casino affiliate URLs still contain placeholder — update in WP Admin or re-run affiliate-links-seed.sh with real URLs"
    elif [ -n "$AFF_URL" ]; then
        pass "Casino affiliate URL configured"
    else
        fail "Casino meta: _casino_affiliate_url MISSING — run casinos-seed.sh"
    fi
fi

# =============================================================================
# 5. Directory Listings
# =============================================================================
section "Directory Listings"

for pt in travel restaurant entertainment; do
    COUNT=$($WP post list --post_type="$pt" --post_status=publish --format=count 2>/dev/null || echo "0")
    if [ "$COUNT" -ge 2 ]; then
        pass "$pt: $COUNT published"
    else
        warn "$pt: $COUNT published (need 2+) — run: WP_ENV=production bash wordpress/seeds/listings-seed.sh"
    fi
done

# =============================================================================
# 6. Blog Posts
# =============================================================================
section "Blog Posts"

POST_COUNT=$($WP post list --post_type=post --post_status=publish --format=count 2>/dev/null || echo "0")
if [ "$POST_COUNT" -ge 5 ]; then
    pass "Blog posts: $POST_COUNT published"
else
    fail "Blog posts: $POST_COUNT published (need 5) — run: WP_ENV=production bash wordpress/seeds/blog-seed.sh"
fi

# =============================================================================
# 7. Best-Of Pages
# =============================================================================
section "Best-Of Pages"

for slug in "best-online-casinos-ontario" "best-restaurants-toronto" "best-things-to-do-ontario"; do
    ID=$($WP post list --post_type=page --name="$slug" --post_status=publish --field=ID 2>/dev/null | head -1)
    if [ -n "$ID" ]; then
        TMPL=$($WP post meta get "$ID" _wp_page_template 2>/dev/null || echo "")
        if echo "$TMPL" | grep -q "bestof"; then
            pass "Best-of: /$slug/"
        else
            warn "Best-of: /$slug/ exists but template may be wrong ('$TMPL') — set template to page-bestof.php in WP Admin"
        fi
    else
        fail "Best-of page MISSING: /$slug/ — run: WP_ENV=production bash wordpress/seeds/bestof-seed.sh"
    fi
done

# =============================================================================
# 8. Plugins — install/activate where possible
# =============================================================================
section "Plugins"

activate_plugin() {
    local slug="$1"; local label="$2"; local required="${3:-required}"
    if $WP plugin is-active "$slug" 2>/dev/null; then
        pass "Plugin active: $label"
    elif $WP plugin is-installed "$slug" 2>/dev/null; then
        $WP plugin activate "$slug" 2>/dev/null && fixed "Plugin activated: $label" || fail "Could not activate $label"
    else
        $WP plugin install "$slug" --activate 2>/dev/null && fixed "Plugin installed + activated: $label" || {
            if [ "$required" = "required" ]; then
                fail "Plugin NOT active: $label — install manually in WP Admin > Plugins"
            else
                warn "Plugin NOT active: $label (recommended) — install in WP Admin > Plugins"
            fi
        }
    fi
}

activate_plugin "seo-by-rank-math"       "Rank Math SEO"    "required"
activate_plugin "advanced-custom-fields" "ACF"              "required"
activate_plugin "thirstyaffiliates"      "ThirstyAffiliates" "required"
activate_plugin "wpforms-lite"           "WPForms"          "required"
activate_plugin "updraftplus"            "UpdraftPlus"      "required"
activate_plugin "wordfence"              "Wordfence"        "required"
activate_plugin "wp-super-cache"         "WP Super Cache"   "recommended"
activate_plugin "imagify"                "Imagify"          "recommended"
activate_plugin "tablepress"             "TablePress"       "recommended"

# =============================================================================
# 9. ThirstyAffiliates prefix
# =============================================================================
section "ThirstyAffiliates"

if $WP plugin is-active "thirstyaffiliates" 2>/dev/null; then
    TA_PREFIX=$($WP option get ta_link_prefix 2>/dev/null || echo "")
    if [ "$TA_PREFIX" != "go" ]; then
        $WP option update ta_link_prefix "go"
        fixed "ThirstyAffiliates prefix set to /go/"
    else
        pass "ThirstyAffiliates prefix: /go/"
    fi

    LINK_COUNT=$($WP post list --post_type=thirstylink --post_status=publish --format=count 2>/dev/null || echo "0")
    if [ "$LINK_COUNT" -ge 12 ]; then
        pass "ThirstyAffiliates: $LINK_COUNT links"
    else
        warn "ThirstyAffiliates: $LINK_COUNT links (need 12) — run: WP_ENV=production bash wordpress/seeds/affiliate-links-seed.sh"
    fi

    PLACEHOLDER_COUNT=$($WP post list --post_type=thirstylink --post_status=publish --fields=ID --format=csv 2>/dev/null | tail -n +2 | while read -r ID; do
        URL=$($WP post meta get "$ID" _ta_destination_url 2>/dev/null || echo "")
        echo "$URL"
    done | grep -c "REPLACE_WITH_REAL_URL" || true)

    if [ "${PLACEHOLDER_COUNT:-0}" -gt 0 ]; then
        fail "ThirstyAffiliates: $PLACEHOLDER_COUNT links still have placeholder URLs — update each in WP Admin > ThirstyAffiliates"
    else
        pass "ThirstyAffiliates: no placeholder URLs"
    fi
else
    warn "ThirstyAffiliates not active — skipping link checks"
fi

# =============================================================================
# 10. Navigation Menus
# =============================================================================
section "Navigation Menus"

if ! $WP menu exists "Primary Menu" 2>/dev/null; then
    $WP menu create "Primary Menu"
    $WP menu location assign "Primary Menu" primary 2>/dev/null || true
    for label_url in "Casinos:/casinos/" "Travel:/travel/" "Restaurants:/restaurants/" "Entertainment:/entertainment/" "Blog:/blog/"; do
        label="${label_url%%:*}"; url="${label_url##*:}"
        $WP menu item add-custom "Primary Menu" "$label" "https://ontariosbest.com$url" 2>/dev/null || true
    done
    fixed "Primary Menu created with category links"
else
    ITEM_COUNT=$($WP menu item list "Primary Menu" --format=count 2>/dev/null || echo "0")
    [ "$ITEM_COUNT" -ge 4 ] && pass "Primary Menu: $ITEM_COUNT items" || warn "Primary Menu: only $ITEM_COUNT items — add Casinos, Travel, etc."
fi

if ! $WP menu exists "Footer Menu" 2>/dev/null; then
    $WP menu create "Footer Menu"
    $WP menu location assign "Footer Menu" footer 2>/dev/null || true
    fixed "Footer Menu created"
else
    pass "Footer Menu: exists"
fi

# =============================================================================
# 11. Security
# =============================================================================
section "Security"

if ! $WP config get DISALLOW_FILE_EDIT 2>/dev/null | grep -q "true"; then
    $WP config set DISALLOW_FILE_EDIT true --raw 2>/dev/null && fixed "DISALLOW_FILE_EDIT set to true" || warn "Could not set DISALLOW_FILE_EDIT — add to wp-config.php manually"
else
    pass "DISALLOW_FILE_EDIT: true"
fi

$WP plugin is-active "wordfence"   2>/dev/null && pass "Wordfence: active"   || warn "Wordfence not active"
$WP plugin is-active "updraftplus" 2>/dev/null && pass "UpdraftPlus: active" || warn "UpdraftPlus not active"

# =============================================================================
# 12. Final flush
# =============================================================================
section "Flush Caches"

$WP rewrite flush --hard && fixed "Rewrite rules flushed"
$WP cache flush 2>/dev/null && fixed "Object cache flushed" || true

# =============================================================================
# Summary
# =============================================================================
echo ""
echo "=================================================="
echo "  Fix-All Results"
echo "=================================================="
echo -e "  ${GREEN}FIXED: $FIXED${NC}"
echo -e "  ${GREEN}PASS:  $PASS${NC}"
echo -e "  ${YELLOW}WARN:  $WARN${NC}"
echo -e "  ${RED}FAIL:  $FAIL${NC}"
echo ""

if [ "$FAIL" -gt 0 ]; then
    echo -e "  ${RED}Action required — $FAIL item(s) need manual attention (see above).${NC}"
    echo ""
    echo "  Typical remaining steps:"
    echo "  1. Replace affiliate URL placeholders in WP Admin > ThirstyAffiliates"
    echo "  2. Run seed scripts if content counts are low:"
    echo "       WP_ENV=production bash wordpress/seeds/casinos-seed.sh"
    echo "       WP_ENV=production bash wordpress/seeds/listings-seed.sh"
    echo "       WP_ENV=production bash wordpress/seeds/blog-seed.sh"
    echo "       WP_ENV=production bash wordpress/seeds/bestof-seed.sh"
    echo "       WP_ENV=production bash wordpress/seeds/affiliate-links-seed.sh"
    echo "  3. Install any plugins that could not be auto-installed (e.g. premium plugins)"
    echo ""
    exit 1
else
    echo -e "  ${GREEN}All auto-fixable issues resolved.${NC}"
    echo ""
    echo "  Still requires manual action:"
    echo "  - Replace REPLACE_WITH_REAL_URL in ThirstyAffiliates with real affiliate tracking URLs"
    echo "  - Add featured images to casino/listing posts in WP Admin"
    echo "  - Run Google PageSpeed check: https://pagespeed.web.dev"
    echo "  - Submit sitemap in Search Console: /sitemap_index.xml"
    echo "  - Ontario iGO compliance review (check launch-checklist.md §10)"
    echo ""
    echo "  When ready to launch:"
    echo "    WP_ENV=production bash wordpress/launch.sh"
    echo ""
    exit 0
fi
