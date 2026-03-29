#!/usr/bin/env bash
# =============================================================================
# OntariosBest.com — Services Seed Script
#
# Creates 5 service listings across financial, legal, home, auto, and health.
# Run after listings-seed.sh.
#
# Local usage:
#   bash wordpress/seeds/services-seed.sh
#
# Production usage:
#   WP_ENV=production bash wordpress/seeds/services-seed.sh
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
echo "  OntariosBest — Services Seed"
echo "=================================================="
echo ""

$WP rewrite flush --hard 2>/dev/null || true

# ---------------------------------------------------------------------------
# Helper
# ---------------------------------------------------------------------------
create_service() {
    local title="$1"
    local slug="$2"
    local category="$3"
    local rating="$4"
    local address="$5"
    local phone="$6"
    local website="$7"
    local affiliate_url="$8"
    local pros="$9"
    local cons="${10}"
    local content="${11}"

    existing=$($WP post list --post_type=service --name="$slug" --field=ID 2>/dev/null | head -1)
    if [ -n "$existing" ]; then
        warn "Service '$title' already exists — skipping"
        return
    fi

    ID=$($WP post create \
        --post_type=service \
        --post_status=publish \
        --post_title="$title" \
        --post_name="$slug" \
        --post_excerpt="$title — expert-reviewed and ranked among Ontario's top $category providers." \
        --post_content="$content" \
        --porcelain 2>/dev/null)

    $WP post meta update "$ID" _listing_overall_rating "$rating"
    $WP post meta update "$ID" _listing_address "$address"
    $WP post meta update "$ID" _listing_phone "$phone"
    $WP post meta update "$ID" _listing_website "$website"
    $WP post meta update "$ID" _listing_affiliate_url "$affiliate_url"
    $WP post meta update "$ID" _listing_pros "$pros"
    $WP post meta update "$ID" _listing_cons "$cons"

    # Assign service_category taxonomy
    $WP term create service_category "$category" \
        --slug="$(echo "$category" | tr '[:upper:]' '[:lower:]' | tr ' ' '-')" 2>/dev/null || true
    $WP post term add "$ID" service_category "$category" 2>/dev/null || true

    log "Created service: $title (ID: $ID, category: $category)"
}

# ---------------------------------------------------------------------------
# Ensure service_category terms exist
# ---------------------------------------------------------------------------
for cat in "Financial Services" "Legal Services" "Home Services" "Auto Services" "Health & Wellness"; do
    $WP term create service_category "$cat" \
        --slug="$(echo "$cat" | tr '[:upper:]' '[:lower:]' | tr ' ' '-' | tr -d '&')" 2>/dev/null || true
done
log "service_category terms created"

# ---------------------------------------------------------------------------
# SERVICE 1 — Financial: Questrade (Online Investing)
# ---------------------------------------------------------------------------
create_service \
    "Questrade — Best Online Broker in Ontario" \
    "questrade-online-broker" \
    "Financial Services" \
    "4.8" \
    "5650 Yonge St Suite 1700, Toronto, ON M2M 4G3" \
    "1-888-783-7866" \
    "https://www.questrade.com" \
    "REPLACE_WITH_REAL_URL" \
    "No commission on ETF purchases
Low flat-rate stock trades (\$4.95–\$9.95)
Registered accounts: RRSP, TFSA, RESP, RRIF
Award-winning mobile app
Canadian-owned and CIPF-protected" \
    "No mutual funds available
Learning curve for beginners
No physical branches" \
    "<h2>Questrade Review — Ontario's Best Online Broker</h2>
<p>Questrade is Canada's leading independent online brokerage, and for good reason. Founded in 1999 and headquartered in Toronto, it has grown to manage over \$30 billion in assets for more than 250,000 accounts.</p>

<h3>Why Questrade Stands Out</h3>
<p>For Ontario investors, Questrade offers a compelling combination of low costs, a robust platform, and full support for registered accounts. ETF purchases are completely free — you only pay when you sell. Stock trades are a flat \$4.95 to \$9.95, well below what the big banks charge.</p>

<h3>Account Types</h3>
<p>Questrade supports all major registered accounts: RRSP, TFSA, RESP, RRIF, LIRA, and margin accounts. Opening an account takes about 15 minutes online and requires a \$1,000 minimum deposit.</p>

<h3>Platform & Tools</h3>
<p>The Questrade Trading platform and mobile app are well-designed and packed with research tools, real-time data, and portfolio analytics. Advanced traders can access Questrade Edge for active trading features.</p>

<h3>Our Verdict</h3>
<p>Questrade is the top pick for Ontario investors who want to minimize fees without sacrificing features. Whether you're building an RRSP or managing a TFSA, it's hard to beat.</p>"

# ---------------------------------------------------------------------------
# SERVICE 2 — Legal: Diamond & Diamond Lawyers
# ---------------------------------------------------------------------------
create_service \
    "Diamond & Diamond Lawyers — Personal Injury Ontario" \
    "diamond-diamond-lawyers" \
    "Legal Services" \
    "4.7" \
    "1 Yonge St Suite 1801, Toronto, ON M5E 1W7" \
    "1-800-567-HURT" \
    "https://www.diamondlaw.ca" \
    "REPLACE_WITH_REAL_URL" \
    "No win, no fee guarantee
24/7 availability for initial consultations
Offices across Ontario (Toronto, Ottawa, Hamilton, London)
Specialists in car accidents, slip and fall, and disability claims
Multi-lingual team" \
    "Focuses on personal injury only
Large firm — some clients prefer boutique experience" \
    "<h2>Diamond & Diamond Lawyers Review</h2>
<p>Diamond & Diamond is one of Ontario's most recognized personal injury law firms, handling thousands of cases across the province each year. Their 'no win, no fee' model means clients pay nothing unless compensation is recovered.</p>

<h3>Practice Areas</h3>
<p>The firm specializes in motor vehicle accidents, slip and fall injuries, long-term disability claims, medical malpractice, and workplace injuries. With offices in Toronto, Ottawa, Hamilton, and London, they serve clients across all of Ontario.</p>

<h3>Why We Recommend Them</h3>
<p>Beyond their strong track record, Diamond & Diamond offers free 24/7 consultations, a multilingual team, and direct access to senior lawyers. For Ontarians navigating the SABS (Statutory Accident Benefits Schedule) system, having experienced legal representation makes a significant difference in outcomes.</p>

<h3>Our Verdict</h3>
<p>If you've been injured in Ontario and need legal help, Diamond & Diamond should be your first call. The no-fee structure removes all financial risk from pursuing your claim.</p>"

# ---------------------------------------------------------------------------
# SERVICE 3 — Home Services: HomeStars (Home Improvement Platform)
# ---------------------------------------------------------------------------
create_service \
    "HomeStars — Best Home Services in Ontario" \
    "homestars-home-services" \
    "Home Services" \
    "4.6" \
    "123 Front St W, Toronto, ON M5J 2M2" \
    "1-877-488-0312" \
    "https://www.homestars.com" \
    "REPLACE_WITH_REAL_URL" \
    "Verified reviews from real homeowners
Best of HomeStars award winners vetted annually
Covers 300+ home improvement categories
Free to request quotes
Background-checked pros" \
    "Quality varies by contractor
Premium listings skew search results
Response time depends on contractor" \
    "<h2>HomeStars Review — Find Trusted Home Pros in Ontario</h2>
<p>HomeStars is Canada's largest network of home service professionals, connecting Ontario homeowners with verified contractors for everything from plumbing and electrical to landscaping and renos.</p>

<h3>How It Works</h3>
<p>Post your project, receive quotes from up to four vetted contractors, read verified reviews, and hire with confidence. Every pro on the platform has been background-checked, and reviews are verified to prevent fakes.</p>

<h3>Categories Covered</h3>
<p>HomeStars covers over 300 home improvement categories: HVAC, roofing, plumbing, electrical, painting, flooring, kitchen and bath renovations, landscaping, snow removal, and more.</p>

<h3>Best of HomeStars</h3>
<p>Each year, HomeStars recognizes the top-rated pros in every category with the \"Best of HomeStars\" award — a useful shortcut when choosing between contractors.</p>

<h3>Our Verdict</h3>
<p>HomeStars is the go-to platform for Ontario homeowners tackling any home project. The review system and vetting process add a layer of trust that generic search results can't match.</p>"

# ---------------------------------------------------------------------------
# SERVICE 4 — Auto: CARFAX Canada
# ---------------------------------------------------------------------------
create_service \
    "CARFAX Canada — Used Vehicle History Reports" \
    "carfax-canada-vehicle-history" \
    "Auto Services" \
    "4.8" \
    "100 York Blvd Suite 500, Richmond Hill, ON L4B 1J8" \
    "1-866-835-8612" \
    "https://www.carfax.ca" \
    "REPLACE_WITH_REAL_URL" \
    "Comprehensive Canadian vehicle history (lien, accident, odometer)
Integrates with dealer listings on AutoTrader and Kijiji
Instant online report delivery
Protects buyers from hidden damage or fraud
Lien check reveals outstanding loans" \
    "Cost per report (\$49.99 CAD)
Older vehicles may have limited history" \
    "<h2>CARFAX Canada Review — Know Before You Buy</h2>
<p>Buying a used car in Ontario without a CARFAX Canada report is a risk you don't need to take. For \$49.99, you get a full vehicle history report that can save you thousands — or reveal the deal of the century.</p>

<h3>What's in a Report</h3>
<p>A CARFAX Canada report includes: accident and damage history, odometer readings, number of previous owners, lien and loan status, province of registration history, total loss or theft records, and service and maintenance records (where reported).</p>

<h3>Why It Matters in Ontario</h3>
<p>Ontario has strict UVIP (Used Vehicle Information Package) requirements, but these don't capture everything CARFAX does. Lien checks are especially important — buying a car with an outstanding loan can leave you responsible for the debt.</p>

<h3>Our Verdict</h3>
<p>At \$49.99 per report or \$99.99 for unlimited reports for 21 days, CARFAX Canada is an essential purchase for any used car buyer in Ontario. One avoided lemon pays for dozens of reports.</p>"

# ---------------------------------------------------------------------------
# SERVICE 5 — Health: Maple (Virtual Healthcare)
# ---------------------------------------------------------------------------
create_service \
    "Maple — Virtual Doctor Visits Ontario" \
    "maple-virtual-healthcare" \
    "Health & Wellness" \
    "4.7" \
    "330 Bay St Suite 1400, Toronto, ON M5H 2S8" \
    "1-888-869-2373" \
    "https://www.getmaple.ca" \
    "REPLACE_WITH_REAL_URL" \
    "See a Canadian doctor in under 5 minutes (24/7)
OHIP-billed for Ontario residents (free for most visits)
Prescriptions sent directly to your pharmacy
Specialist referrals available
Mental health services and therapy" \
    "Not suitable for emergencies or physical exams
Wait times can increase during peak hours
Some specialist services cost extra" \
    "<h2>Maple Review — Virtual Healthcare for Ontario Residents</h2>
<p>Maple is Canada's leading virtual care platform, connecting Ontario patients with licensed Canadian doctors, nurse practitioners, and specialists — on-demand, 24/7, from any device.</p>

<h3>How It Works</h3>
<p>Download the app or visit the website, describe your symptoms, and you'll be connected to a doctor in under 5 minutes. For Ontario residents with OHIP coverage, most general practitioner visits are billed directly to the province — meaning no out-of-pocket cost.</p>

<h3>What Maple Treats</h3>
<p>Common colds, UTIs, skin conditions, mental health concerns, prescription renewals, sick notes, sexual health, and chronic disease management. Maple can also refer you to specialists including dermatologists, psychiatrists, and endocrinologists.</p>

<h3>Pricing</h3>
<p>OHIP-covered visits are free. For those without provincial coverage or for non-covered services, pay-per-visit is \$49 or a membership plan (\$29.99/month) gives unlimited general practitioner visits.</p>

<h3>Our Verdict</h3>
<p>Maple is transforming healthcare access for Ontarians. If you're tired of waiting weeks for a GP appointment or sitting in a walk-in clinic, Maple is the answer. Fast, convenient, and often free.</p>"

# ---------------------------------------------------------------------------
# Done
# ---------------------------------------------------------------------------

echo ""
echo "=================================================="
SERVICE_COUNT=$($WP post list --post_type=service --post_status=publish --format=count 2>/dev/null || echo "0")
echo "  Services seed complete!"
echo "  $SERVICE_COUNT service listings published at /services/"
echo ""
echo "  IMPORTANT: Replace REPLACE_WITH_REAL_URL in each"
echo "  service's _listing_affiliate_url meta with your"
echo "  real affiliate or lead-gen tracking URLs."
echo ""
echo "  Next steps:"
echo "  1. Add featured images (WP Admin > Services)"
echo "  2. Update affiliate URLs in WP Admin or ThirstyAffiliates"
echo "  3. Run: bash wordpress/seeds/affiliate-links-seed.sh"
echo "=================================================="
echo ""
