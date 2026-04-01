#!/usr/bin/env bash
# =============================================================================
# OntariosBest.com — City Hubs Seed Script
#
# Creates city hub pages and assigns existing listings to city taxonomy terms.
#
# Local usage:
#   bash wordpress/seeds/city-hubs-seed.sh
#
# Production usage:
#   WP_ENV=production bash wordpress/seeds/city-hubs-seed.sh
# =============================================================================

set -euo pipefail

if [ "${WP_ENV:-local}" = "production" ]; then
    WP="wp --allow-root"
else
    WP="docker compose exec -T wpcli wp --allow-root"
fi

GREEN='\033[0;32m'; YELLOW='\033[1;33m'; NC='\033[0m'
log()  { echo -e "${GREEN}[✓]${NC} $1"; }
warn() { echo -e "${YELLOW}[!]${NC} $1"; }

echo ""
echo "=================================================="
echo "  OntariosBest — City Hubs Seed"
echo "=================================================="
echo ""

$WP rewrite flush --hard 2>/dev/null || true

# ---------------------------------------------------------------------------
# 1. Create Ontario parent page
# ---------------------------------------------------------------------------
ONTARIO_ID=$($WP post list --post_type=page --name="ontario" --field=ID 2>/dev/null | head -1)
if [ -z "$ONTARIO_ID" ]; then
    ONTARIO_ID=$($WP post create \
        --post_type=page \
        --post_status=publish \
        --post_title="Ontario" \
        --post_name="ontario" \
        --post_content="<p>Explore Ontario's best cities — curated guides to casinos, dining, travel, and entertainment.</p>" \
        --porcelain 2>/dev/null)
    log "Created Ontario parent page (ID: $ONTARIO_ID)"
else
    warn "Ontario parent page already exists (ID: $ONTARIO_ID) — skipping"
fi

# ---------------------------------------------------------------------------
# 2. Helper: create city hub page
# ---------------------------------------------------------------------------
create_city_hub() {
    local name="$1"
    local slug="$2"
    local subtitle="$3"
    local city_term="$4"

    existing=$($WP post list --post_type=page --name="$slug" --field=ID 2>/dev/null | head -1)
    if [ -n "$existing" ]; then
        warn "City hub '$name' already exists (ID: $existing) — skipping"
        return
    fi

    ID=$($WP post create \
        --post_type=page \
        --post_status=publish \
        --post_title="Best of $name" \
        --post_name="$slug" \
        --post_parent="$ONTARIO_ID" \
        --post_content="" \
        --porcelain 2>/dev/null)

    $WP post meta update "$ID" _wp_page_template "template-city-hub.php" 2>/dev/null

    $WP post meta update "$ID" _city_name "$name" 2>/dev/null
    $WP post meta update "$ID" _city_slug "$city_term" 2>/dev/null
    $WP post meta update "$ID" _city_subtitle "$subtitle" 2>/dev/null

    # Rank Math SEO meta
    $WP post meta update "$ID" rank_math_title "Best of $name — Casinos, Dining, Travel & Entertainment | Ontario's Best" 2>/dev/null || true
    $WP post meta update "$ID" rank_math_description "Discover the best of $name — top-rated casinos, restaurants, things to do, and entertainment, independently reviewed." 2>/dev/null || true
    $WP post meta update "$ID" rank_math_focus_keyword "best of $name" 2>/dev/null || true

    log "Created city hub: Best of $name (ID: $ID, URL: /ontario/$slug/)"
}

# ---------------------------------------------------------------------------
# 3. Create city taxonomy terms
# ---------------------------------------------------------------------------
$WP term create city "Toronto" --slug="toronto" 2>/dev/null || true
log "City term: Toronto"
$WP term create city "Ottawa" --slug="ottawa" 2>/dev/null || true
log "City term: Ottawa"
$WP term create city "Niagara Falls" --slug="niagara-falls" 2>/dev/null || true
log "City term: Niagara Falls"

# ---------------------------------------------------------------------------
# 4. Create hub pages
# ---------------------------------------------------------------------------
create_city_hub \
    "Toronto" \
    "toronto" \
    "Ontario's largest city has it all — top-rated casinos, world-class restaurants, live music, sports, and endless things to do." \
    "toronto"

create_city_hub \
    "Ottawa" \
    "ottawa" \
    "Canada's capital is packed with culture, incredible dining, and attractions. Here are Ottawa's very best, independently reviewed." \
    "ottawa"

create_city_hub \
    "Niagara Falls" \
    "niagara-falls" \
    "More than the falls — Niagara offers world-class casinos, award-winning restaurants, and unforgettable experiences on both sides of the border." \
    "niagara-falls"

# ---------------------------------------------------------------------------
# 5. Assign existing listings to city terms
# ---------------------------------------------------------------------------

# Niagara Falls travel listing
NIAGARA_ID=$($WP post list --post_type=travel --name="niagara-falls-ontario" --field=ID 2>/dev/null | head -1)
if [ -n "$NIAGARA_ID" ]; then
    $WP post term set "$NIAGARA_ID" city niagara-falls 2>/dev/null || true
    log "Assigned Niagara Falls travel listing to city: niagara-falls"
fi

# Ottawa travel listing
OTTAWA_TRAVEL_ID=$($WP post list --post_type=travel --name="ottawa-ontario" --field=ID 2>/dev/null | head -1)
if [ -n "$OTTAWA_TRAVEL_ID" ]; then
    $WP post term set "$OTTAWA_TRAVEL_ID" city ottawa 2>/dev/null || true
    log "Assigned Ottawa travel listing to city: ottawa"
fi

# Canoe Restaurant — Toronto
CANOE_ID=$($WP post list --post_type=restaurant --name="canoe-restaurant-bar-toronto" --field=ID 2>/dev/null | head -1)
if [ -n "$CANOE_ID" ]; then
    $WP post term set "$CANOE_ID" city toronto 2>/dev/null || true
    log "Assigned Canoe Restaurant to city: toronto"
fi

# Beckta Dining & Wine — Ottawa
BECKTA_ID=$($WP post list --post_type=restaurant --name="beckta-dining-wine-ottawa" --field=ID 2>/dev/null | head -1)
if [ -n "$BECKTA_ID" ]; then
    $WP post term set "$BECKTA_ID" city ottawa 2>/dev/null || true
    log "Assigned Beckta Dining to city: ottawa"
fi

# Toronto Blue Jays — Rogers Centre (entertainment)
JAYS_ID=$($WP post list --post_type=entertainment --name="toronto-blue-jays-rogers-centre" --field=ID 2>/dev/null | head -1)
if [ -n "$JAYS_ID" ]; then
    $WP post term set "$JAYS_ID" city toronto 2>/dev/null || true
    log "Assigned Toronto Blue Jays to city: toronto"
fi

# Stratford Festival — mark as ontario (not city-specific)
# Skip city assignment — Stratford is its own city, not in our 3 hubs yet

echo ""
log "City hubs seed complete."
echo ""
echo "  Next steps:"
echo "  1. Verify hub pages at /ontario/toronto/, /ontario/ottawa/, /ontario/niagara-falls/"
echo "  2. Listings will appear once assigned to city terms above"
echo "  3. Set _ob_sponsored_tier=featured on listings to activate featured slots"
