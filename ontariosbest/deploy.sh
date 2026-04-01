#!/usr/bin/env bash
# =============================================================================
# OntariosBest.com — Cloudways Deployment Script
# Run this via SSH on your Cloudways server after WordPress is installed.
#
# Usage:
#   ssh master@your-server-ip -p 22
#   cd /path/to/your/wordpress/public_html
#   bash deploy.sh
#
# Prerequisites:
#   - WordPress already installed via Cloudways dashboard
#   - WP-CLI available (pre-installed on Cloudways)
#   - This script in the same directory as WordPress (public_html/)
# =============================================================================

set -euo pipefail

WP="wp --allow-root"
SITE_URL="https://ontariosbest.com"
SITE_TITLE="Ontario's Best"
ADMIN_USER="ob_admin"
ADMIN_EMAIL="hello@ontariosbest.com"
THEME_ZIP="ontariosbest-theme.zip"
ACF_DIR="acf"

# Colors
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

log()  { echo -e "${GREEN}[✓]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }
fail() { echo -e "${RED}[✗]${NC} $1"; exit 1; }

echo ""
echo "=================================================="
echo "  OntariosBest.com WordPress Deployment"
echo "=================================================="
echo ""

# ---------------------------------------------------------
# 1. Preflight checks
# ---------------------------------------------------------

command -v wp >/dev/null 2>&1 || fail "WP-CLI not found. Install it first: https://wp-cli.org"
$WP core is-installed 2>/dev/null || fail "WordPress not installed in this directory. Run from public_html/."
log "WordPress installation found"

# ---------------------------------------------------------
# 2. Core WordPress configuration
# ---------------------------------------------------------

log "Configuring WordPress settings..."

$WP option update blogname "$SITE_TITLE"
$WP option update blogdescription "Ontario's Top-Rated Casinos, Travel, Entertainment & More"
$WP option update timezone_string "America/Toronto"
$WP option update date_format "F j, Y"
$WP option update time_format "g:i a"
$WP option update start_of_week 0
$WP option update permalink_structure "/%postname%/"
$WP option update default_comment_status "closed"
$WP option update default_ping_status "closed"
$WP option update blogpublic 0   # Search engines blocked during setup — flip to 1 at launch

# Flush rewrite rules after permalink change
$WP rewrite flush --hard
log "Core settings configured"

# ---------------------------------------------------------
# 3. Clean up defaults
# ---------------------------------------------------------

log "Cleaning up default content..."

# Delete Hello World post and sample page
$WP post delete 1 --force 2>/dev/null || true
$WP post delete 2 --force 2>/dev/null || true

# Delete default plugins
$WP plugin delete hello 2>/dev/null || true
$WP plugin delete akismet 2>/dev/null || true

# Rename Uncategorized to General
$WP term update category 1 --name="General" --slug="general"

log "Default content cleaned"

# ---------------------------------------------------------
# 4. Install Astra parent theme
# ---------------------------------------------------------

log "Installing Astra theme..."

if $WP theme is-installed astra 2>/dev/null; then
    warn "Astra already installed — skipping update to avoid breaking changes"
else
    $WP theme install astra
fi

log "Astra installed"

# ---------------------------------------------------------
# 5. Install OntariosBest child theme
# ---------------------------------------------------------

log "Installing OntariosBest child theme..."

if [ ! -f "$THEME_ZIP" ]; then
    fail "Theme zip not found: $THEME_ZIP — copy it to this directory first."
fi

THEMES_DIR="$(pwd)/wp-content/themes"
mkdir -p "$THEMES_DIR/ontariosbest"
unzip -o "$THEME_ZIP" -d "$THEMES_DIR/ontariosbest" >/dev/null 2>&1

$WP theme activate ontariosbest
log "OntariosBest child theme activated"

# ---------------------------------------------------------
# 6. Install free plugins (available from wp.org)
# ---------------------------------------------------------

log "Installing free plugins from WordPress.org..."

FREE_PLUGINS=(
    "tablepress"
    "wpforms-lite"
    "updraftplus"
    "wordfence"
    "imagify"
    "query-monitor"
    "redirection"
    "wp-super-cache"
)

for plugin in "${FREE_PLUGINS[@]}"; do
    if $WP plugin is-installed "$plugin" 2>/dev/null; then
        $WP plugin activate "$plugin" 2>/dev/null || true
        warn "$plugin already installed, activated"
    else
        $WP plugin install "$plugin" --activate 2>/dev/null && log "$plugin installed" || warn "Could not install $plugin"
    fi
done

warn "Premium plugins (Rank Math Pro, ACF Pro, ThirstyAffiliates Pro) must be installed manually via WordPress admin."

# ---------------------------------------------------------
# 7. Import ACF field groups
# ---------------------------------------------------------

if [ -d "$ACF_DIR" ]; then
    if $WP plugin is-installed "advanced-custom-fields" 2>/dev/null || \
       $WP plugin is-installed "advanced-custom-fields-pro" 2>/dev/null; then
        log "Importing ACF field groups..."
        for json in "$ACF_DIR"/*.json; do
            # ACF import via WP option update (direct import)
            $WP acf import --json_file="$json" 2>/dev/null && log "Imported: $json" || warn "Could not import $json via CLI — import manually in ACF > Tools"
        done
    else
        warn "ACF not installed yet — import field groups after installing ACF Pro (ACF > Tools > Import)"
    fi
fi

# ---------------------------------------------------------
# 8. Create navigation menus
# ---------------------------------------------------------

log "Creating navigation menus..."

$WP menu create "Primary Menu" 2>/dev/null || true
$WP menu location assign "Primary Menu" primary 2>/dev/null || true

$WP menu create "Footer Menu" 2>/dev/null || true
$WP menu location assign "Footer Menu" footer 2>/dev/null || true

log "Menus created"

# ---------------------------------------------------------
# 9. Create required pages
# ---------------------------------------------------------

log "Creating required pages..."

create_page() {
    local title="$1"
    local slug="$2"
    local template="$3"
    local content="$4"

    existing=$(wp post list --post_type=page --name="$slug" --field=ID --allow-root 2>/dev/null | head -1)
    if [ -n "$existing" ]; then
        warn "Page '$title' already exists (ID: $existing) — skipping"
        return
    fi

    local args="--post_type=page --post_status=publish --post_title=\"$title\" --post_name=\"$slug\""
    if [ -n "$template" ]; then
        ID=$($WP post create --post_type=page --post_status=publish \
            --post_title="$title" --post_name="$slug" \
            --post_content="$content" \
            --porcelain 2>/dev/null)
        $WP post meta update "$ID" _wp_page_template "$template" 2>/dev/null || true
    else
        ID=$($WP post create --post_type=page --post_status=publish \
            --post_title="$title" --post_name="$slug" \
            --post_content="$content" \
            --porcelain 2>/dev/null)
    fi
    log "Created page: $title (ID: $ID, slug: /$slug/)"
    echo "$ID"
}

HOME_ID=$(create_page "Home" "home" "" "")
ABOUT_ID=$(create_page "About" "about" "page-about.php" "")
CONTACT_ID=$(create_page "Contact" "contact" "page-contact.php" "")
RG_ID=$(create_page "Responsible Gambling" "responsible-gambling" "page-responsible-gambling.php" "")
PP_ID=$(create_page "Privacy Policy" "privacy-policy" "page-legal.php" "<h2>Privacy Policy</h2><p>Last updated: $(date +"%B %d, %Y"). Please update this page with your full Privacy Policy.</p>")
TERMS_ID=$(create_page "Terms & Conditions" "terms" "page-legal.php" "<h2>Terms and Conditions</h2><p>Please update this page with your full Terms and Conditions.</p>")
DISC_ID=$(create_page "Affiliate Disclosure" "affiliate-disclosure" "page-legal.php" "<h2>Affiliate Disclosure</h2><p>OntariosBest.com participates in affiliate programs and earns commissions from qualifying purchases. This does not affect our editorial independence or ratings.</p>")
ADV_ID=$(create_page "Advertise" "advertise" "page-advertise.php" "")
BESTOF_ID=$(create_page "Best Of Ontario" "best-of" "" "<p>Explore Ontario's best — curated and ranked by our experts.</p>")

# Set home page as front page
if [ -n "$HOME_ID" ]; then
    $WP option update page_on_front "$HOME_ID"
    $WP option update show_on_front "page"
    log "Front page set to Home"
fi

# Add pages to primary menu
for page_id in "$ABOUT_ID" "$CONTACT_ID" "$ADV_ID"; do
    [ -n "$page_id" ] && $WP menu item add-post "Primary Menu" "$page_id" 2>/dev/null || true
done

# Add pages to footer menu
for page_id in "$ABOUT_ID" "$CONTACT_ID" "$ADV_ID" "$PP_ID" "$TERMS_ID" "$DISC_ID" "$RG_ID"; do
    [ -n "$page_id" ] && $WP menu item add-post "Footer Menu" "$page_id" 2>/dev/null || true
done

log "Pages created and added to menus"

# ---------------------------------------------------------
# 10. Add primary category nav items (custom links)
# ---------------------------------------------------------

log "Adding category links to Primary Menu..."

declare -A NAV_ITEMS=(
    ["Casinos"]="/casinos/"
    ["Travel"]="/travel/"
    ["Restaurants"]="/restaurants/"
    ["Entertainment"]="/entertainment/"
    ["Services"]="/services/"
    ["Blog"]="/blog/"
)

for label in "Casinos" "Travel" "Restaurants" "Entertainment" "Services" "Blog"; do
    url="${NAV_ITEMS[$label]}"
    $WP menu item add-custom "Primary Menu" "$label" "$SITE_URL$url" 2>/dev/null || true
done

log "Category nav items added"

# ---------------------------------------------------------
# 11. Register nav menu locations in theme
# ---------------------------------------------------------

# Note: nav menu locations are registered in header.php/functions.php
# This step ensures the theme's menu locations are linked
$WP eval "
    register_nav_menus([
        'primary' => 'Primary Menu',
        'footer'  => 'Footer Menu',
    ]);
" --allow-root 2>/dev/null || true

# ---------------------------------------------------------
# 12. Basic SEO / Rank Math placeholders
# ---------------------------------------------------------

# If Rank Math is installed, configure basics
if $WP plugin is-installed "seo-by-rank-math" 2>/dev/null || \
   $WP plugin is-installed "rank-math-seo" 2>/dev/null; then
    log "Configuring Rank Math basics..."
    $WP option update rank_math_general_settings '{"breadcrumbs":"on","noindex_empty_taxonomies":"on"}' --format=json 2>/dev/null || true
else
    warn "Rank Math Pro not installed yet — install manually"
fi

# ---------------------------------------------------------
# 13. Performance & security basics
# ---------------------------------------------------------

log "Configuring performance and security basics..."

# Disable XML-RPC
$WP option update enable_xmlrpc 0 2>/dev/null || true

# Disable file editing in admin
$WP config set DISALLOW_FILE_EDIT true --raw 2>/dev/null || true

# Set image sizes for theme
$WP option update thumbnail_size_w 300
$WP option update thumbnail_size_h 200
$WP option update thumbnail_crop 1
$WP option update medium_size_w 600
$WP option update medium_size_h 400
$WP option update large_size_w 1200
$WP option update large_size_h 800

log "Performance and security basics configured"

# ---------------------------------------------------------
# 14. ThirstyAffiliates configuration
# ---------------------------------------------------------

if $WP plugin is-installed "thirstyaffiliates" 2>/dev/null; then
    log "Configuring ThirstyAffiliates..."
    $WP option update ta_link_prefix "go" 2>/dev/null || true
    $WP option update ta_link_prefix_category "" 2>/dev/null || true
    $WP option update ta_stats_enabled "yes" 2>/dev/null || true
    log "ThirstyAffiliates configured (prefix: /go/)"
fi

# ---------------------------------------------------------
# 15. Enable search engine visibility (for launch)
# ---------------------------------------------------------

warn "Search engines are currently BLOCKED during setup. Run the following when ready to launch:"
echo ""
echo "  wp option update blogpublic 1 --allow-root"
echo ""

# ---------------------------------------------------------
# 16. Final flush
# ---------------------------------------------------------

$WP rewrite flush --hard
$WP cache flush 2>/dev/null || true
log "Rewrite rules flushed"

# ---------------------------------------------------------
# Done
# ---------------------------------------------------------

echo ""
echo "=================================================="
echo -e "  ${GREEN}Deployment Complete!${NC}"
echo "=================================================="
echo ""
echo "Next steps:"
echo "  1. Install premium plugins (Rank Math Pro, ACF Pro, ThirstyAffiliates Pro) via WP admin"
echo "  2. Import ACF field groups: ACF > Tools > Import > upload acf/casino-fields.json and acf/listing-fields.json"
echo "  3. Add affiliate links in ThirstyAffiliates"
echo "  4. Configure Rank Math Pro (connect Search Console, set schema types)"
echo "  5. Add content: 12 casino reviews, 8 listings, 5 blog posts"
echo "  6. Configure WP Super Cache LAST"
echo "  7. Run: wp option update blogpublic 1 --allow-root (to enable indexing)"
echo ""
echo "  Site URL: $SITE_URL"
echo "  WP Admin: $SITE_URL/wp-admin"
echo ""
echo "  See: ontariosbest/wordpress/launch-checklist.md for full checklist"
echo ""
