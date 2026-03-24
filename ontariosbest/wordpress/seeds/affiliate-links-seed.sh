#!/usr/bin/env bash
# =============================================================================
# OntariosBest.com — ThirstyAffiliates Link Seed Script
#
# Creates all affiliate links in ThirstyAffiliates via WP-CLI.
# ThirstyAffiliates stores links as CPT 'thirstylink'.
#
# Prerequisites:
#   - ThirstyAffiliates Pro installed and activated
#   - Real affiliate URLs must be substituted before running in production
#     (search for REPLACE_WITH_REAL_URL and update each entry)
#
# Local usage:
#   bash wordpress/seeds/affiliate-links-seed.sh
#
# Production usage:
#   WP_ENV=production bash wordpress/seeds/affiliate-links-seed.sh
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
info() { echo -e "    $1"; }

echo ""
echo "=================================================="
echo "  OntariosBest — ThirstyAffiliates Link Seed"
echo "=================================================="
echo ""

# Check ThirstyAffiliates is active
if ! $WP plugin is-active thirstyaffiliates 2>/dev/null && \
   ! $WP plugin is-active thirstyaffiliates-pro 2>/dev/null; then
    warn "ThirstyAffiliates not active — creating placeholder links only"
    warn "Install ThirstyAffiliates Pro, then re-run this script"
fi

# ---------------------------------------------------------------------------
# Helper: create_ta_link
# Creates a ThirstyAffiliates link (thirstylink CPT) via WP-CLI.
#
# ThirstyAffiliates link meta keys:
#   _ta_destination_url    — the real affiliate destination URL
#   _ta_slug               — the /go/slug/ path (without /go/)
#   _ta_link_type          — 'standard' | '301' | '302'
#   _ta_nofollow           — '1' to add rel=nofollow
#   _ta_new_window         — '1' to open in new tab
#   _ta_sponsored          — '1' to add rel=sponsored
#   _ta_link_category      — term ID(s) for link categories
# ---------------------------------------------------------------------------
create_ta_link() {
    local title="$1"
    local slug="$2"
    local destination="$3"
    local category="$4"

    existing=$($WP post list --post_type=thirstylink --name="$slug" --field=ID 2>/dev/null | head -1)
    if [ -n "$existing" ]; then
        warn "Link '$title' already exists (ID: $existing) — skipping"
        return
    fi

    ID=$($WP post create \
        --post_type=thirstylink \
        --post_status=publish \
        --post_title="$title" \
        --post_name="$slug" \
        --porcelain 2>/dev/null)

    $WP post meta update "$ID" _ta_destination_url "$destination"
    $WP post meta update "$ID" _ta_slug           "$slug"
    $WP post meta update "$ID" _ta_link_type      "301"
    $WP post meta update "$ID" _ta_nofollow       "1"
    $WP post meta update "$ID" _ta_new_window     "1"
    $WP post meta update "$ID" _ta_sponsored      "1"

    # Assign link category
    if [ -n "$category" ]; then
        $WP post term add "$ID" "thirstylink-category" "$category" 2>/dev/null || true
    fi

    # Also update the casino review post's affiliate URL to use the /go/ cloaked link
    CASINO_ID=$($WP post list --post_type=casino --name="${slug}" --field=ID 2>/dev/null | head -1)
    if [ -n "$CASINO_ID" ]; then
        $WP post meta update "$CASINO_ID" _casino_affiliate_url "/go/$slug/" 2>/dev/null || true
    fi

    log "Created affiliate link: /go/$slug/ → $title"
    info "Destination: $destination"
}

# ---------------------------------------------------------------------------
# Ensure ThirstyAffiliates link categories exist
# ---------------------------------------------------------------------------
for cat in "Casinos" "Travel" "Entertainment" "Services" "Shopping"; do
    $WP term create thirstylink-category "$cat" \
        --slug="$(echo "$cat" | tr '[:upper:]' '[:lower:]')" 2>/dev/null || true
done
log "Link categories created: Casinos, Travel, Entertainment, Services, Shopping"

# ---------------------------------------------------------------------------
# ThirstyAffiliates settings (via WP options)
# ---------------------------------------------------------------------------
$WP option update ta_link_prefix "go" 2>/dev/null || true
log "ThirstyAffiliates link prefix set to /go/"

# ---------------------------------------------------------------------------
# CASINO AFFILIATE LINKS
#
# ACTION REQUIRED: Replace each REPLACE_WITH_REAL_URL with your actual
# affiliate tracking URL from each casino's affiliate program.
# ---------------------------------------------------------------------------
echo ""
log "Creating casino affiliate links..."

create_ta_link \
    "BetMGM Ontario" \
    "betmgm" \
    "REPLACE_WITH_REAL_URL" \
    "Casinos"
# Sign up: https://affiliates.betmgm.com

create_ta_link \
    "DraftKings Ontario" \
    "draftkings" \
    "REPLACE_WITH_REAL_URL" \
    "Casinos"
# Sign up: https://affiliates.draftkings.com

create_ta_link \
    "FanDuel Ontario" \
    "fanduel" \
    "REPLACE_WITH_REAL_URL" \
    "Casinos"
# Sign up: https://affiliates.fanduel.com

create_ta_link \
    "Bet99" \
    "bet99" \
    "REPLACE_WITH_REAL_URL" \
    "Casinos"
# Sign up: https://www.bet99affiliates.com

create_ta_link \
    "PointsBet Ontario" \
    "pointsbet" \
    "REPLACE_WITH_REAL_URL" \
    "Casinos"
# Sign up: https://affiliates.pointsbet.com

create_ta_link \
    "Unibet Ontario" \
    "unibet" \
    "REPLACE_WITH_REAL_URL" \
    "Casinos"
# Sign up: https://affiliates.unibet.com

create_ta_link \
    "888casino Ontario" \
    "888casino" \
    "REPLACE_WITH_REAL_URL" \
    "Casinos"
# Sign up: https://affiliates.888casino.com

create_ta_link \
    "bet365 Ontario" \
    "bet365" \
    "REPLACE_WITH_REAL_URL" \
    "Casinos"
# Sign up: https://www.bet365affiliates.com

create_ta_link \
    "LeoVegas Ontario" \
    "leovegas" \
    "REPLACE_WITH_REAL_URL" \
    "Casinos"
# Sign up: https://www.leovegasaffiliates.com

create_ta_link \
    "Jackpot City" \
    "jackpot-city" \
    "REPLACE_WITH_REAL_URL" \
    "Casinos"
# Sign up: https://www.rewardaffiliates.com (Jackpot City is part of Reward Affiliates)

create_ta_link \
    "Spin Casino" \
    "spin-casino" \
    "REPLACE_WITH_REAL_URL" \
    "Casinos"
# Sign up: https://www.rewardaffiliates.com

create_ta_link \
    "Ruby Fortune" \
    "ruby-fortune" \
    "REPLACE_WITH_REAL_URL" \
    "Casinos"
# Sign up: https://www.rewardaffiliates.com

# ---------------------------------------------------------------------------
# TRAVEL AFFILIATE LINKS
# ---------------------------------------------------------------------------
echo ""
log "Creating travel affiliate links..."

create_ta_link \
    "Booking.com — Niagara Falls Hotels" \
    "booking-niagara" \
    "REPLACE_WITH_REAL_URL" \
    "Travel"
# Sign up: https://www.booking.com/affiliate-program.html

create_ta_link \
    "Booking.com — Muskoka" \
    "booking-muskoka" \
    "REPLACE_WITH_REAL_URL" \
    "Travel"

create_ta_link \
    "Booking.com — Ottawa Hotels" \
    "booking-ottawa" \
    "REPLACE_WITH_REAL_URL" \
    "Travel"

create_ta_link \
    "Expedia — Ontario Packages" \
    "expedia-ontario" \
    "REPLACE_WITH_REAL_URL" \
    "Travel"
# Sign up: https://developers.expediagroup.com/docs/products/affiliate

create_ta_link \
    "VRBO — Ontario Cottages" \
    "vrbo-ontario" \
    "REPLACE_WITH_REAL_URL" \
    "Travel"
# Sign up: https://www.vrbo.com/affiliate-program

# ---------------------------------------------------------------------------
# ENTERTAINMENT AFFILIATE LINKS
# ---------------------------------------------------------------------------
echo ""
log "Creating entertainment affiliate links..."

create_ta_link \
    "Ticketmaster — Blue Jays Tickets" \
    "tickets-bluejays" \
    "REPLACE_WITH_REAL_URL" \
    "Entertainment"
# Sign up: https://www.ticketmaster.ca/help/affiliate-program

create_ta_link \
    "Stratford Festival — Tickets" \
    "tickets-stratford" \
    "REPLACE_WITH_REAL_URL" \
    "Entertainment"
# Direct: https://www.stratfordfestival.ca

create_ta_link \
    "Ticketmaster — Ontario Events" \
    "tickets-ontario" \
    "REPLACE_WITH_REAL_URL" \
    "Entertainment"

$WP rewrite flush --hard 2>/dev/null || true

echo ""
echo "=================================================="
echo "  Affiliate links seed complete!"
echo ""
echo "  IMPORTANT — Action Required:"
echo "  Replace all REPLACE_WITH_REAL_URL values with your"
echo "  actual affiliate tracking URLs from each program."
echo ""
echo "  Affiliate Programs to Join:"
echo "  Casinos  → see comments in this script for each URL"
echo "  Travel   → Booking.com, Expedia, VRBO affiliate portals"
echo "  Events   → Ticketmaster affiliate program"
echo ""
echo "  After updating URLs, re-run this script or update"
echo "  links in WP Admin → ThirstyAffiliates."
echo ""
echo "  All /go/[slug]/ URLs already assigned to casino posts."
echo "=================================================="
echo ""
