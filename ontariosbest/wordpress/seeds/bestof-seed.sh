#!/usr/bin/env bash
# =============================================================================
# OntariosBest.com — Best-Of Pages Seed Script
#
# Creates 3 best-of landing pages using the "Best Of Landing Page" template.
# Each page auto-pulls listings by post_type, sorted by rating.
#
# Local usage:
#   bash wordpress/seeds/bestof-seed.sh
#
# Production usage:
#   WP_ENV=production bash wordpress/seeds/bestof-seed.sh
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
echo "  OntariosBest — Best-Of Pages Seed"
echo "=================================================="
echo ""

$WP rewrite flush --hard 2>/dev/null || true

# ---------------------------------------------------------------------------
# Helper: create_bestof_page
# Args: title slug hero_title hero_subtitle post_type limit intro content
# ---------------------------------------------------------------------------
create_bestof_page() {
    local title="$1"
    local slug="$2"
    local hero_title="$3"
    local hero_subtitle="$4"
    local post_type="$5"
    local limit="$6"
    local intro="$7"
    local content="$8"

    # Best-of pages live under /best-of/ — get or create parent
    PARENT_ID=$($WP post list --post_type=page --name="best-of" --field=ID 2>/dev/null | head -1)
    if [ -z "$PARENT_ID" ]; then
        PARENT_ID=$($WP post create \
            --post_type=page \
            --post_status=publish \
            --post_title="Best Of Ontario" \
            --post_name="best-of" \
            --post_content="<p>Explore Ontario's best — curated rankings across casinos, restaurants, entertainment, and travel.</p>" \
            --porcelain 2>/dev/null)
        log "Created parent page: Best Of Ontario (ID: $PARENT_ID)"
    fi

    existing=$($WP post list --post_type=page --name="$slug" --field=ID 2>/dev/null | head -1)
    if [ -n "$existing" ]; then
        warn "Best-of page '$title' already exists (ID: $existing) — skipping"
        return
    fi

    ID=$($WP post create \
        --post_type=page \
        --post_status=publish \
        --post_title="$title" \
        --post_name="$slug" \
        --post_parent="$PARENT_ID" \
        --post_content="$content" \
        --porcelain 2>/dev/null)

    # Page template
    $WP post meta update "$ID" _wp_page_template "page-bestof.php"

    # Best-Of display configuration
    $WP post meta update "$ID" _bestof_hero_title    "$hero_title"
    $WP post meta update "$ID" _bestof_hero_subtitle "$hero_subtitle"
    $WP post meta update "$ID" _bestof_post_type     "$post_type"
    $WP post meta update "$ID" _bestof_limit         "$limit"
    $WP post meta update "$ID" _bestof_intro         "$intro"
    $WP post meta update "$ID" _bestof_show_table    "1"

    # Rank Math SEO
    $WP post meta update "$ID" rank_math_title       "$title – Ontario's Best" 2>/dev/null || true
    $WP post meta update "$ID" rank_math_description "$hero_subtitle" 2>/dev/null || true

    log "Created best-of page: $title (ID: $ID) at /best-of/$slug/"
}

# ---------------------------------------------------------------------------
# Page 1: Best Online Casinos Ontario (casino post_type, limit 12)
# ---------------------------------------------------------------------------
create_bestof_page \
    "Best Online Casinos in Ontario" \
    "best-online-casinos-ontario" \
    "Best Online Casinos in Ontario 2026" \
    "Expert-ranked iGO-licensed Ontario casinos — compare bonuses, games, payouts, and more." \
    "casino" \
    "12" \
    "<strong>All casinos on this page are licensed by iGaming Ontario (iGO)</strong> and verified safe for Ontario players. Our rankings are based on independent testing of bonuses, game selection, mobile experience, payment speed, and customer support." \
    "<h2>How We Rank Ontario Casinos</h2>
<p>Our team tests every casino on this list with real money deposits. We evaluate:</p>
<ul>
<li><strong>Bonus value</strong> — headline match percentage AND wagering requirements</li>
<li><strong>Game library</strong> — total games, software providers, live casino quality</li>
<li><strong>Mobile experience</strong> — app quality, game availability on mobile</li>
<li><strong>Payment speed</strong> — deposit/withdrawal speed, supported methods</li>
<li><strong>Customer support</strong> — response time, quality of answers</li>
<li><strong>iGO compliance</strong> — all casinos must hold an active iGO license</li>
</ul>

<h2>Is Online Casino Gaming Legal in Ontario?</h2>
<p>Yes — since April 2022, Ontario has a fully regulated online casino market managed by <strong>iGaming Ontario (iGO)</strong>. Players aged 19+ can legally play at any casino holding an active iGO operating agreement. All casinos on this page are licensed and verified.</p>

<h2>Responsible Gambling</h2>
<p>Ontario's regulated market requires all licensed casinos to provide responsible gambling tools including deposit limits, loss limits, reality checks, and self-exclusion. If gambling is causing you problems, contact <strong>ConnexOntario: 1-866-531-2600</strong> (free, 24/7).</p>
<p><em>19+ only. Please gamble responsibly.</em></p>"

# ---------------------------------------------------------------------------
# Page 2: Best Restaurants Toronto (restaurant post_type, limit 8)
# ---------------------------------------------------------------------------
create_bestof_page \
    "Best Restaurants in Toronto" \
    "best-restaurants-toronto" \
    "Best Restaurants in Toronto 2026" \
    "From 54th-floor fine dining to neighbourhood gems — Toronto's top restaurants, independently reviewed." \
    "restaurant" \
    "8" \
    "Toronto has one of the most diverse restaurant scenes in North America. These are our picks for the city's best — ranked by food quality, service, value, and overall experience." \
    "<h2>Toronto's Restaurant Scene</h2>
<p>With over 8,000 restaurants representing virtually every cuisine on earth, Toronto consistently ranks among the world's best dining cities. These are the restaurants our team returns to again and again.</p>

<h2>Tips for Dining in Toronto</h2>
<ul>
<li><strong>Book ahead</strong> — top restaurants fill up weeks in advance, especially Friday and Saturday evenings</li>
<li><strong>Explore the neighbourhoods</strong> — Kensington Market, Ossington, Little Italy, Chinatown, and Leslieville each have distinct food identities</li>
<li><strong>Tipping</strong> — 18–20% is standard in Toronto; some restaurants now include service in the bill</li>
<li><strong>Prix fixe menus</strong> — many fine dining restaurants offer better value at lunch or with a set menu</li>
</ul>

<h2>More Ontario Dining</h2>
<p>Exploring beyond Toronto? See our <a href='/restaurant/beckta-ottawa/'>Ottawa fine dining picks</a> and <a href='/restaurant/bruce-wine-bar-thornbury/'>Georgian Bay wine country restaurants</a>.</p>"

# ---------------------------------------------------------------------------
# Page 3: Best Things to Do Ontario (entertainment post_type, limit 8)
# ---------------------------------------------------------------------------
create_bestof_page \
    "Best Things to Do in Ontario" \
    "best-things-to-do-ontario" \
    "Best Things to Do in Ontario 2026" \
    "Top-rated Ontario experiences — from world-class theatre to major league sports, independently reviewed." \
    "entertainment" \
    "8" \
    "Ontario offers some of Canada's most diverse entertainment experiences. These are our picks for the province's best — from Niagara to Ottawa, sports to theatre." \
    "<h2>Ontario Entertainment Guide</h2>
<p>Ontario is home to world-class entertainment across every category — sports, theatre, music, festivals, and attractions. Whether you're a Torontonian looking for a night out or a visitor exploring the province, Ontario consistently delivers.</p>

<h2>Plan Your Ontario Experience</h2>
<ul>
<li><strong>Stratford Festival</strong> — runs May to October; book early for summer weekends</li>
<li><strong>Toronto sports</strong> — Blue Jays (April–September/October), Raptors (October–April/June), Maple Leafs (October–April)</li>
<li><strong>Festivals</strong> — TIFF (September), Toronto Jazz Festival (June), Niagara on the Lake Shaw Festival (April–October)</li>
</ul>

<h2>More Ontario Activities</h2>
<p>Looking for travel and outdoor activities? Visit our <a href='/travel/'>Ontario travel directory</a> for the best destinations across the province.</p>"

$WP rewrite flush --hard 2>/dev/null || true

echo ""
echo "=================================================="
echo "  Best-of pages seed complete!"
echo ""
echo "  Pages created:"
echo "    /best-of/best-online-casinos-ontario/"
echo "    /best-of/best-restaurants-toronto/"
echo "    /best-of/best-things-to-do-ontario/"
echo ""
echo "  Next steps:"
echo "  1. Add featured images to each best-of page"
echo "  2. Run: bash wordpress/seeds/affiliate-links-seed.sh"
echo "=================================================="
echo ""
