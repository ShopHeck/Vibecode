#!/usr/bin/env bash
# =============================================================================
# OntariosBest.com — Casino Review Seed Script
#
# Creates 12 iGO-licensed Ontario casino review posts with full metadata.
# Run this after local-setup.sh (local) or deploy.sh (production).
#
# Local usage:
#   bash wordpress/seeds/casinos-seed.sh
#
# Production usage (via SSH on Cloudways):
#   WP_ENV=production bash wordpress/seeds/casinos-seed.sh
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
echo "  OntariosBest — Casino Review Seed"
echo "=================================================="
echo ""

# Register taxonomies are available (flush rewrite)
$WP rewrite flush --hard 2>/dev/null || true

# ---------------------------------------------------------------------------
# Helper: create_casino
# Args: title slug bonus rating games_score bonus_score ux_score support_score payments_score established min_deposit withdrawal pros cons badge affiliate_url
# ---------------------------------------------------------------------------
create_casino() {
    local title="$1"
    local slug="$2"
    local bonus="$3"
    local rating="$4"
    local score_games="$5"
    local score_bonuses="$6"
    local score_ux="$7"
    local score_support="$8"
    local score_payments="$9"
    local established="${10}"
    local min_deposit="${11}"
    local withdrawal="${12}"
    local pros="${13}"
    local cons="${14}"
    local badge="${15}"
    local affiliate_url="${16}"
    local content="${17}"

    # Check if post already exists
    existing=$($WP post list --post_type=casino --name="$slug" --field=ID 2>/dev/null | head -1)
    if [ -n "$existing" ]; then
        warn "Casino '$title' already exists (ID: $existing) — skipping"
        return
    fi

    ID=$($WP post create \
        --post_type=casino \
        --post_status=publish \
        --post_title="$title" \
        --post_name="$slug" \
        --post_excerpt="Read our expert review of $title Ontario. Find out about bonuses, games, payments, and more." \
        --post_content="$content" \
        --porcelain 2>/dev/null)

    # Core meta
    $WP post meta update "$ID" _casino_overall_rating "$rating"
    $WP post meta update "$ID" _casino_welcome_bonus "$bonus"
    $WP post meta update "$ID" _casino_affiliate_url "$affiliate_url"
    $WP post meta update "$ID" _casino_established "$established"
    $WP post meta update "$ID" _casino_license "iGaming Ontario (iGO)"
    $WP post meta update "$ID" _casino_min_deposit "$min_deposit"
    $WP post meta update "$ID" _casino_withdrawal_time "$withdrawal"

    # Score breakdown
    $WP post meta update "$ID" _casino_score_games "$score_games"
    $WP post meta update "$ID" _casino_score_bonuses "$score_bonuses"
    $WP post meta update "$ID" _casino_score_ux "$score_ux"
    $WP post meta update "$ID" _casino_score_support "$score_support"
    $WP post meta update "$ID" _casino_score_payments "$score_payments"

    # Pros / Cons (newline-separated)
    $WP post meta update "$ID" _casino_pros "$pros"
    $WP post meta update "$ID" _casino_cons "$cons"

    # Badge (optional)
    [ -n "$badge" ] && $WP post meta update "$ID" _casino_badge "$badge"

    # Taxonomy: casino_feature terms
    $WP post term add "$ID" casino_feature "Welcome Bonus" "Live Casino" "Mobile Friendly" 2>/dev/null || true

    log "Created casino: $title (ID: $ID)"
}

# ---------------------------------------------------------------------------
# Ensure taxonomies exist
# ---------------------------------------------------------------------------
$WP term create casino_feature "Welcome Bonus"      --slug=welcome-bonus      2>/dev/null || true
$WP term create casino_feature "Live Casino"        --slug=live-casino        2>/dev/null || true
$WP term create casino_feature "Mobile Friendly"    --slug=mobile-friendly    2>/dev/null || true
$WP term create casino_feature "No Deposit Bonus"   --slug=no-deposit-bonus   2>/dev/null || true
$WP term create casino_feature "Fast Withdrawal"    --slug=fast-withdrawal    2>/dev/null || true
$WP term create casino_feature "Crypto Accepted"    --slug=crypto-accepted    2>/dev/null || true

$WP term create payment_method "Visa"           --slug=visa           2>/dev/null || true
$WP term create payment_method "Mastercard"     --slug=mastercard     2>/dev/null || true
$WP term create payment_method "Interac"        --slug=interac        2>/dev/null || true
$WP term create payment_method "PayPal"         --slug=paypal         2>/dev/null || true
$WP term create payment_method "Apple Pay"      --slug=apple-pay      2>/dev/null || true
$WP term create payment_method "iDebit"         --slug=idebit         2>/dev/null || true
$WP term create payment_method "Bitcoin"        --slug=bitcoin        2>/dev/null || true

log "Taxonomy terms created"

# ---------------------------------------------------------------------------
# Casino 1: BetMGM Ontario
# ---------------------------------------------------------------------------
create_casino \
    "BetMGM Ontario" \
    "betmgm-ontario" \
    "100% up to \$1,000 + 200 Free Spins" \
    "4.8" \
    "4.9" "4.8" "4.7" "4.6" "4.8" \
    "2022" "\$10" "1–3 business days" \
    "Huge game library (1,500+ slots)
Generous welcome bonus
Excellent live casino section
Fast Interac withdrawals
24/7 live chat support" \
    "Wagering requirements are 25x
Some withdrawal methods slower than others" \
    "Editor's Choice" \
    "https://ontariosbest.com/go/betmgm/" \
    "<h2>BetMGM Ontario Review</h2>
<p>BetMGM Ontario is one of the most recognizable names in the Canadian online casino market, backed by the global MGM Resorts brand. Fully licensed by iGaming Ontario (iGO), it offers Ontario players a safe, regulated gaming environment with an enormous selection of casino games.</p>

<h3>Welcome Bonus</h3>
<p>New players at BetMGM Ontario can claim a <strong>100% deposit match up to \$1,000 plus 200 free spins</strong> on selected slots. The 25x wagering requirement is competitive by Ontario standards, and the 30-day expiry gives you plenty of time to clear it.</p>

<h3>Game Selection</h3>
<p>With over 1,500 slots, 50+ live dealer tables, and a strong poker room, BetMGM Ontario has one of the deepest game libraries available to Ontario players. Top software providers include NetEnt, Evolution Gaming, IGT, and Playtech.</p>

<h3>Banking</h3>
<p>BetMGM Ontario accepts Interac, Visa, Mastercard, PayPal, and iDebit. Minimum deposit is \$10. Withdrawals typically process within 1–3 business days, with Interac being the fastest option.</p>

<h3>Mobile Experience</h3>
<p>The BetMGM app is available for both iOS and Android, offering the full casino experience on mobile. The interface is polished and the app rarely experiences performance issues.</p>

<h3>Customer Support</h3>
<p>Support is available 24/7 via live chat and email. Response times are consistently fast, and agents are knowledgeable about Ontario-specific regulatory questions.</p>

<h3>Verdict</h3>
<p>BetMGM Ontario earns its reputation as one of Ontario's top online casinos. The combination of a massive game library, trustworthy brand, and generous bonuses make it our top pick for most players.</p>"

# ---------------------------------------------------------------------------
# Casino 2: DraftKings Ontario
# ---------------------------------------------------------------------------
create_casino \
    "DraftKings Ontario" \
    "draftkings-ontario" \
    "100% Deposit Match up to \$2,000" \
    "4.7" \
    "4.6" "4.9" "4.8" "4.5" "4.7" \
    "2022" "\$5" "1–2 business days" \
    "Massive welcome bonus up to \$2,000
Excellent sportsbook integration
Clean, modern interface
Low \$5 minimum deposit
Strong mobile app" \
    "Game library smaller than some competitors
Bonus wagering requirements can be high" \
    "Best Bonus" \
    "https://ontariosbest.com/go/draftkings/" \
    "<h2>DraftKings Ontario Review</h2>
<p>DraftKings made its name in daily fantasy sports before expanding into the regulated Ontario iGaming market. Their Ontario casino combines a solid slots and live dealer offering with one of the best sportsbooks available to Canadian players.</p>

<h3>Welcome Bonus</h3>
<p>DraftKings Ontario's welcome offer is one of the most generous available: a <strong>100% deposit match up to \$2,000</strong>. This is ideal for players who plan to deposit a larger amount upfront. Wagering requirements apply — check the current terms on the DraftKings site.</p>

<h3>Game Selection</h3>
<p>DraftKings offers 800+ slots and a strong live casino powered by Evolution Gaming. While not the largest library, the quality is high and new games are added regularly.</p>

<h3>Banking</h3>
<p>Accepts Interac, Visa, Mastercard, and PayPal. The \$5 minimum deposit is one of the lowest in Ontario, making it accessible for casual players.</p>

<h3>Mobile Experience</h3>
<p>The DraftKings app is polished and feature-rich, supporting both casino and sports betting in one place. Available on iOS and Android.</p>

<h3>Verdict</h3>
<p>DraftKings Ontario is ideal for players who want a combined sportsbook and casino experience with a generous welcome bonus. The brand's reputation and Ontario license make it a trustworthy choice.</p>"

# ---------------------------------------------------------------------------
# Casino 3: FanDuel Ontario
# ---------------------------------------------------------------------------
create_casino \
    "FanDuel Ontario" \
    "fanduel-ontario" \
    "\$1,000 No-Sweat First Bet + Casino Bonus" \
    "4.6" \
    "4.5" "4.7" "4.8" "4.6" "4.6" \
    "2022" "\$10" "1–3 business days" \
    "Trusted major brand
Smooth cross-platform experience
Excellent live dealer section
Strong customer support" \
    "Casino game library is mid-sized
Bonus structure can be complex" \
    "" \
    "https://ontariosbest.com/go/fanduel/" \
    "<h2>FanDuel Ontario Review</h2>
<p>FanDuel is one of North America's largest gaming brands, and their Ontario casino brings that same level of quality to Canadian players. Licensed by iGaming Ontario, FanDuel Ontario offers a regulated, trustworthy gaming environment.</p>

<h3>Welcome Bonus</h3>
<p>FanDuel Ontario regularly offers competitive welcome promotions for new casino players. Check the FanDuel site for the latest offer, as promotions are updated frequently.</p>

<h3>Game Selection</h3>
<p>FanDuel Ontario offers 700+ casino games including slots, table games, and an excellent live casino section. Evolution Gaming powers the live dealer tables.</p>

<h3>Banking</h3>
<p>Accepts Interac, Visa, Mastercard, and online banking. Withdrawals typically take 1–3 business days.</p>

<h3>Verdict</h3>
<p>FanDuel Ontario is a solid choice for players who value brand trust, a smooth cross-platform experience, and a strong live casino section.</p>"

# ---------------------------------------------------------------------------
# Casino 4: Bet99
# ---------------------------------------------------------------------------
create_casino \
    "Bet99" \
    "bet99" \
    "100% up to \$500 First Deposit Bonus" \
    "4.4" \
    "4.3" "4.5" "4.4" "4.3" "4.5" \
    "2020" "\$10" "1–5 business days" \
    "Canadian-born brand
Competitive sports betting odds
Solid casino game selection
Interac deposits and withdrawals" \
    "Smaller casino library vs major brands
Live casino selection limited" \
    "" \
    "https://ontariosbest.com/go/bet99/" \
    "<h2>Bet99 Review</h2>
<p>Bet99 is a Canadian-founded online sportsbook and casino, making it a natural fit for Ontario players seeking a locally-oriented gaming experience. Fully licensed by iGaming Ontario, Bet99 focuses on competitive sports betting odds alongside a growing casino offering.</p>

<h3>Welcome Bonus</h3>
<p>New players can claim a <strong>100% first deposit bonus up to \$500</strong>. Wagering requirements apply — review the current terms at Bet99.</p>

<h3>Game Selection</h3>
<p>Bet99 offers a solid mix of slots and table games from reputable providers including NetEnt and Pragmatic Play. The sportsbook is particularly strong, covering all major sports and esports.</p>

<h3>Verdict</h3>
<p>Bet99 is a strong option for Ontario players who prioritize sports betting but also want a decent casino on the side. The Canadian focus and iGO license make it a trustworthy platform.</p>"

# ---------------------------------------------------------------------------
# Casino 5: PointsBet Ontario
# ---------------------------------------------------------------------------
create_casino \
    "PointsBet Ontario" \
    "pointsbet-ontario" \
    "Up to \$900 in Bonus Bets" \
    "4.3" \
    "4.2" "4.4" "4.5" "4.3" "4.3" \
    "2022" "\$10" "2–5 business days" \
    "Unique PointsBetting feature
Strong sportsbook
Clean mobile app
iGO licensed" \
    "Casino games selection is limited
Not ideal if casino is your primary focus" \
    "" \
    "https://ontariosbest.com/go/pointsbet/" \
    "<h2>PointsBet Ontario Review</h2>
<p>PointsBet is an innovative sports betting platform that has expanded into Ontario's regulated iGaming market. Their unique PointsBetting format — where winnings scale with how right (or wrong) you are — sets them apart from traditional sportsbooks.</p>

<h3>Casino Games</h3>
<p>While PointsBet Ontario does offer casino games, their primary strength is sports betting. Casino players who want variety may prefer a more casino-focused platform.</p>

<h3>Verdict</h3>
<p>PointsBet Ontario is best suited for sports bettors who want occasional casino play. The innovative PointsBetting format is a genuine differentiator for sports fans.</p>"

# ---------------------------------------------------------------------------
# Casino 6: Unibet Ontario
# ---------------------------------------------------------------------------
create_casino \
    "Unibet Ontario" \
    "unibet-ontario" \
    "\$500 Casino Bonus" \
    "4.5" \
    "4.5" "4.4" "4.6" "4.7" "4.5" \
    "2022" "\$10" "1–3 business days" \
    "European pedigree and strong track record
Excellent customer support
Good game variety
Clear bonus terms" \
    "Interface feels less modern than some competitors
Promotions less frequent than top rivals" \
    "" \
    "https://ontariosbest.com/go/unibet/" \
    "<h2>Unibet Ontario Review</h2>
<p>Unibet is a trusted European gaming brand with decades of experience that has entered the Ontario market through iGaming Ontario. Known for transparency and player-friendly terms, Unibet is a reliable choice for Ontario casino players.</p>

<h3>Welcome Bonus</h3>
<p>Unibet Ontario offers a \$500 casino bonus for new players. Terms are clear and fair compared to the industry average.</p>

<h3>Game Selection</h3>
<p>Unibet offers 600+ games from top providers including NetEnt, Microgaming, and Evolution Gaming. The live casino section is particularly strong.</p>

<h3>Customer Support</h3>
<p>Unibet's support team is one of the most responsive in Ontario, with live chat available during extended hours and email support always available.</p>

<h3>Verdict</h3>
<p>Unibet Ontario is an excellent choice for players who value transparency, a trusted brand, and solid customer support. The European heritage shows in the quality of the platform.</p>"

# ---------------------------------------------------------------------------
# Casino 7: 888casino Ontario
# ---------------------------------------------------------------------------
create_casino \
    "888casino Ontario" \
    "888casino-ontario" \
    "200% up to \$200 Welcome Bonus" \
    "4.4" \
    "4.4" "4.3" "4.4" "4.5" "4.3" \
    "2022" "\$10" "1–3 business days" \
    "One of the world's most established online casinos
Proprietary game software
Strong poker integration
Consistent promotions" \
    "Bonus amount lower than some competitors
Some proprietary games not available elsewhere (good or bad depending on preference)" \
    "" \
    "https://ontariosbest.com/go/888casino/" \
    "<h2>888casino Ontario Review</h2>
<p>888casino is one of the world's oldest and most established online casinos, operating since 1997. Their Ontario offering is fully licensed by iGO and brings decades of experience to Canadian players.</p>

<h3>Welcome Bonus</h3>
<p>New Ontario players can claim a <strong>200% welcome bonus up to \$200</strong>. While the maximum bonus is lower than some competitors, the 200% match rate provides excellent value for smaller deposits.</p>

<h3>Game Selection</h3>
<p>888casino offers 500+ games, including many proprietary titles developed in-house. Their live casino, powered by Evolution Gaming, is excellent.</p>

<h3>Verdict</h3>
<p>888casino Ontario is a solid choice backed by one of the most trusted names in online gambling. The proprietary software and long track record make it stand out.</p>"

# ---------------------------------------------------------------------------
# Casino 8: bet365 Ontario
# ---------------------------------------------------------------------------
create_casino \
    "bet365 Ontario" \
    "bet365-ontario" \
    "Up to \$500 in Bet Credits" \
    "4.7" \
    "4.7" "4.6" "4.8" "4.7" "4.8" \
    "2022" "\$10" "1–2 business days" \
    "World's largest online gambling company
Exceptional sportsbook
Outstanding live streaming
Fast Interac withdrawals
Top-tier live casino" \
    "Welcome bonus is bet credits, not cash
Heavy focus on sports may overwhelm pure casino players" \
    "Most Trusted" \
    "https://ontariosbest.com/go/bet365/" \
    "<h2>bet365 Ontario Review</h2>
<p>bet365 is the world's largest online gambling company, and their Ontario platform is a direct reflection of that scale. Fully licensed by iGaming Ontario, bet365 Ontario delivers a world-class experience for both sports bettors and casino players.</p>

<h3>Welcome Bonus</h3>
<p>New players can earn up to \$500 in bet credits. Note that bet credits are different from bonus cash — they must be used for wagering and winnings from bet credits are withdrawable.</p>

<h3>Game Selection</h3>
<p>bet365 Ontario offers 700+ casino games including an outstanding live dealer section. The sportsbook covers virtually every sport and league worldwide with live streaming.</p>

<h3>Banking</h3>
<p>bet365 Ontario accepts Interac, Visa, Mastercard, and PayPal. Withdrawals are among the fastest in Ontario, with Interac often processing within 24 hours.</p>

<h3>Verdict</h3>
<p>bet365 Ontario is the gold standard for combined sportsbook and casino play. If you want the most complete gambling experience from the most trusted brand in the world, bet365 is it.</p>"

# ---------------------------------------------------------------------------
# Casino 9: LeoVegas Ontario
# ---------------------------------------------------------------------------
create_casino \
    "LeoVegas Ontario" \
    "leovegas-ontario" \
    "100% up to \$1,000 + 200 Free Spins" \
    "4.6" \
    "4.7" "4.6" "4.8" "4.5" "4.6" \
    "2022" "\$10" "1–2 business days" \
    "Mobile-first design — best app in Ontario
Huge live casino (100+ tables)
Regular ongoing promotions
Fast withdrawals" \
    "Desktop interface less polished than mobile
Some slots load slowly on older devices" \
    "Best Mobile" \
    "https://ontariosbest.com/go/leovegas/" \
    "<h2>LeoVegas Ontario Review</h2>
<p>LeoVegas has built its reputation as the 'King of Mobile Casino', and their Ontario platform lives up to that name. Fully licensed by iGaming Ontario, LeoVegas Ontario is the best choice for players who primarily play on their phone.</p>

<h3>Welcome Bonus</h3>
<p>New Ontario players can claim a <strong>100% deposit match up to \$1,000 plus 200 free spins</strong>. This is one of the most competitive welcome packages available in Ontario.</p>

<h3>Mobile Experience</h3>
<p>The LeoVegas app sets the standard for mobile casino gaming in Ontario. The interface is intuitive, loads fast, and the full game library is accessible on mobile — including live dealer tables.</p>

<h3>Live Casino</h3>
<p>LeoVegas offers one of Ontario's largest live casino selections, with 100+ live tables powered by Evolution Gaming. Blackjack, roulette, baccarat, and game shows all feature prominently.</p>

<h3>Verdict</h3>
<p>LeoVegas Ontario is the top pick for mobile-first casino players. The combination of the best mobile app, generous bonus, and massive live casino selection is hard to beat.</p>"

# ---------------------------------------------------------------------------
# Casino 10: Jackpot City
# ---------------------------------------------------------------------------
create_casino \
    "Jackpot City" \
    "jackpot-city" \
    "Up to \$1,600 Welcome Bonus" \
    "4.3" \
    "4.4" "4.5" "4.2" "4.2" "4.3" \
    "2022" "\$10" "3–5 business days" \
    "Generous multi-deposit welcome package
Large Microgaming slot library
Established brand (since 1998)
Good loyalty program" \
    "Older interface design
Withdrawals slower than newer platforms
Customer support wait times can be longer" \
    "" \
    "https://ontariosbest.com/go/jackpot-city/" \
    "<h2>Jackpot City Ontario Review</h2>
<p>Jackpot City is one of Canada's most recognized online casino brands, operating since 1998. Their Ontario platform is licensed by iGaming Ontario and powered by Microgaming — one of the industry's most prolific game developers.</p>

<h3>Welcome Bonus</h3>
<p>Jackpot City offers a four-part welcome package totaling up to \$1,600 across your first four deposits. Each deposit is matched 100% up to \$400.</p>

<h3>Game Selection</h3>
<p>As a Microgaming-powered casino, Jackpot City has an enormous library of slots including progressive jackpots like Mega Moolah — one of the world's most famous jackpot slots.</p>

<h3>Verdict</h3>
<p>Jackpot City Ontario is a solid choice for players who love slots, particularly Microgaming's progressive jackpot titles. The long-standing reputation and iGO license provide peace of mind.</p>"

# ---------------------------------------------------------------------------
# Casino 11: Spin Casino
# ---------------------------------------------------------------------------
create_casino \
    "Spin Casino" \
    "spin-casino" \
    "Up to \$1,000 Welcome Bonus" \
    "4.2" \
    "4.3" "4.3" "4.2" "4.1" "4.2" \
    "2022" "\$10" "3–5 business days" \
    "Strong Microgaming slot library
Loyalty rewards program
Straightforward bonus structure" \
    "Interface feels dated
Fewer live dealer options than top rivals
Slower withdrawals" \
    "" \
    "https://ontariosbest.com/go/spin-casino/" \
    "<h2>Spin Casino Ontario Review</h2>
<p>Spin Casino is a Microgaming-powered online casino licensed by iGaming Ontario. A sister site to Jackpot City, Spin Casino focuses primarily on slots and offers a solid loyalty program for regular players.</p>

<h3>Welcome Bonus</h3>
<p>New players can claim up to \$1,000 across their first three deposits, with each deposit matched 100% up to \$333.</p>

<h3>Game Selection</h3>
<p>Spin Casino's library is dominated by Microgaming slots — over 500 titles including progressive jackpots. Table game selection is adequate but not the casino's strength.</p>

<h3>Verdict</h3>
<p>Spin Casino Ontario is best for slot enthusiasts who enjoy Microgaming titles and want a straightforward, no-frills casino experience with a decent welcome bonus.</p>"

# ---------------------------------------------------------------------------
# Casino 12: Ruby Fortune
# ---------------------------------------------------------------------------
create_casino \
    "Ruby Fortune" \
    "ruby-fortune" \
    "Up to \$750 Welcome Bonus" \
    "4.1" \
    "4.1" "4.2" "4.1" "4.0" "4.1" \
    "2022" "\$10" "3–7 business days" \
    "Long-established brand
Microgaming powered (large slot library)
Easy-to-use interface
Regular promotions for existing players" \
    "Slowest withdrawals on this list
Smaller live casino section
Customer support hours limited" \
    "" \
    "https://ontariosbest.com/go/ruby-fortune/" \
    "<h2>Ruby Fortune Ontario Review</h2>
<p>Ruby Fortune is one of the original Microgaming casinos, operating since 2003. Their Ontario platform is licensed by iGaming Ontario and offers a classic, straightforward casino experience for players who prefer established brands.</p>

<h3>Welcome Bonus</h3>
<p>New Ontario players can claim up to \$750 across their first three deposits, with 100% matches on each deposit up to \$250.</p>

<h3>Game Selection</h3>
<p>Ruby Fortune is powered exclusively by Microgaming, offering 500+ slots including the famous Mega Moolah progressive jackpot. Table games and a smaller live casino section are also available.</p>

<h3>Verdict</h3>
<p>Ruby Fortune Ontario suits players who want a familiar, established brand with a large Microgaming slot library. It's not the flashiest casino in Ontario, but it's reliable and well-respected.</p>"

# ---------------------------------------------------------------------------
# Assign payment methods to all casinos
# ---------------------------------------------------------------------------
log "Assigning payment method terms..."
for slug in betmgm-ontario draftkings-ontario fanduel-ontario bet99 pointsbet-ontario unibet-ontario 888casino-ontario bet365-ontario leovegas-ontario jackpot-city spin-casino ruby-fortune; do
    ID=$($WP post list --post_type=casino --name="$slug" --field=ID 2>/dev/null | head -1)
    if [ -n "$ID" ]; then
        $WP post term add "$ID" payment_method "Interac" "Visa" "Mastercard" 2>/dev/null || true
    fi
done

# Add PayPal to specific casinos
for slug in betmgm-ontario draftkings-ontario bet365-ontario leovegas-ontario; do
    ID=$($WP post list --post_type=casino --name="$slug" --field=ID 2>/dev/null | head -1)
    [ -n "$ID" ] && $WP post term add "$ID" payment_method "PayPal" 2>/dev/null || true
done

$WP rewrite flush --hard 2>/dev/null || true

echo ""
echo "=================================================="
echo "  Casino seed complete!"
echo "  12 casino reviews created at /casinos/"
echo ""
echo "  Next steps:"
echo "  1. Add featured images for each casino (WP Admin > Casinos)"
echo "  2. Create ThirstyAffiliates links for each /go/[slug]/ URL"
echo "  3. Run: bash wordpress/seeds/listings-seed.sh"
echo "=================================================="
echo ""
