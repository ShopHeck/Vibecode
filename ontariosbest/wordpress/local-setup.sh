#!/usr/bin/env bash
# =============================================================================
# OntariosBest.com — Local WordPress Setup via Docker WP-CLI
# Run ONCE after: docker compose up -d
#
# Usage:
#   cd ontariosbest/
#   docker compose up -d
#   bash wordpress/local-setup.sh
# =============================================================================

set -euo pipefail

WP="docker compose exec -T wpcli wp --allow-root"

GREEN='\033[0;32m'; YELLOW='\033[1;33m'; NC='\033[0m'
log()  { echo -e "${GREEN}[✓]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }

echo ""
echo "=================================================="
echo "  OntariosBest — Local WordPress Bootstrap"
echo "=================================================="
echo ""

# Wait for WordPress to be ready
log "Waiting for WordPress to be available..."
for i in $(seq 1 30); do
    $WP core is-installed 2>/dev/null && break || sleep 3
    [ $i -eq 30 ] && { echo "WordPress never became ready. Check: docker compose logs wordpress"; exit 1; }
done

# Check if already installed
if $WP core is-installed 2>/dev/null; then
    warn "WordPress already installed — skipping core install"
else
    log "Installing WordPress core..."
    $WP core install \
        --url="http://localhost:8080" \
        --title="Ontario's Best (Local)" \
        --admin_user="admin" \
        --admin_password="admin123" \
        --admin_email="dev@ontariosbest.local" \
        --skip-email
fi

log "WordPress is ready!"

# Core settings
$WP option update permalink_structure "/%postname%/"
$WP option update timezone_string "America/Toronto"
$WP option update blogdescription "Ontario's Top-Rated Casinos, Travel, Entertainment & More"
$WP option update default_comment_status closed
$WP option update default_ping_status closed
$WP option update blogpublic 0
$WP rewrite flush --hard

# Clean defaults
$WP post delete 1 --force 2>/dev/null || true
$WP post delete 2 --force 2>/dev/null || true
$WP plugin delete hello 2>/dev/null || true
$WP term update category 1 --name="General" --slug="general"
log "Core configured"

# Install Astra
$WP theme is-installed astra 2>/dev/null || $WP theme install astra
log "Astra installed"

# Activate child theme (mounted from ./wordpress/theme/)
$WP theme activate ontariosbest
log "OntariosBest child theme activated"

# Install free plugins
for plugin in tablepress wpforms-lite updraftplus query-monitor redirection; do
    $WP plugin is-installed "$plugin" 2>/dev/null || \
        $WP plugin install "$plugin" --activate 2>/dev/null && \
        log "$plugin installed" || warn "Could not install $plugin"
done

# Install ACF free (for field group UI; replace with Pro on production)
$WP plugin is-installed "advanced-custom-fields" 2>/dev/null || \
    $WP plugin install advanced-custom-fields --activate
log "ACF installed"

# Import ACF field groups
for json in /acf/*.json; do
    $WP acf import --json_file="$json" 2>/dev/null && log "Imported: $json" || warn "Import $json manually in ACF > Tools"
done

# Create menus
$WP menu exists "Primary Menu" 2>/dev/null || $WP menu create "Primary Menu"
$WP menu exists "Footer Menu"  2>/dev/null || $WP menu create "Footer Menu"
$WP menu location assign "Primary Menu" primary 2>/dev/null || true
$WP menu location assign "Footer Menu"  footer  2>/dev/null || true

# Create required pages helper
create_page() {
    local title="$1" slug="$2" template="${3:-}" content="${4:-}"
    existing=$($WP post list --post_type=page --name="$slug" --field=ID 2>/dev/null | head -1)
    if [ -n "$existing" ]; then warn "Page '$title' already exists"; echo "$existing"; return; fi
    ID=$($WP post create --post_type=page --post_status=publish \
        --post_title="$title" --post_name="$slug" --post_content="$content" --porcelain 2>/dev/null)
    [ -n "$template" ] && $WP post meta update "$ID" _wp_page_template "$template" 2>/dev/null || true
    log "Created: $title (ID: $ID)"
    echo "$ID"
}

HOME_ID=$(create_page "Home"                 "home"                 ""                              "")
$(create_page "About"                "about"                "page-about.php"                "")
$(create_page "Contact"              "contact"              "page-contact.php"              "")
$(create_page "Responsible Gambling" "responsible-gambling" "page-responsible-gambling.php" "")
$(create_page "Privacy Policy"       "privacy-policy"       "page-legal.php"                "<h2>Privacy Policy</h2><p>Update this page with your full Privacy Policy.</p>")
$(create_page "Terms & Conditions"   "terms"                "page-legal.php"                "<h2>Terms and Conditions</h2><p>Update this page with your Terms.</p>")
$(create_page "Affiliate Disclosure" "affiliate-disclosure" "page-legal.php"                "<h2>Affiliate Disclosure</h2><p>OntariosBest.com earns commissions from affiliate links.</p>")
$(create_page "Advertise"            "advertise"            "page-advertise.php"            "")
$(create_page "Best Of Ontario"      "best-of"              ""                              "<p>Explore Ontario's best.</p>")

# Set front page
[ -n "$HOME_ID" ] && {
    $WP option update page_on_front "$HOME_ID"
    $WP option update show_on_front "page"
    log "Front page set"
}

# Add nav items
for label_url in "Casinos:/casinos/" "Travel:/travel/" "Restaurants:/restaurants/" "Entertainment:/entertainment/" "Services:/services/" "Blog:/blog/"; do
    label="${label_url%%:*}"
    url="${label_url##*:}"
    $WP menu item add-custom "Primary Menu" "$label" "http://localhost:8080$url" 2>/dev/null || true
done

$WP rewrite flush --hard
log "All done!"

echo ""
echo "=================================================="
echo "  Local site ready!"
echo ""
echo "  Site:     http://localhost:8080"
echo "  Admin:    http://localhost:8080/wp-admin"
echo "  User:     admin"
echo "  Password: admin123"
echo "  phpMyAdmin: http://localhost:8081"
echo ""
echo "  Theme files are live-mounted from ./wordpress/theme/"
echo "  Edit PHP/CSS files and refresh — no rebuild needed."
echo "=================================================="
echo ""
