#!/usr/bin/env bash
# =============================================================================
# OntariosBest.com — Phase 1 Casino Cluster Posts Seed
#
# Creates 12 casino cluster post drafts for Weeks 1-4 of the content calendar.
# Posts are saved as DRAFT — expand content in WP Admin before publishing.
#
# Local usage:
#   bash wordpress/seeds/phase1-casino-clusters-seed.sh
#
# Production usage:
#   WP_ENV=production bash wordpress/seeds/phase1-casino-clusters-seed.sh
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
echo "  OntariosBest — Phase 1 Casino Cluster Posts"
echo "=================================================="
echo ""

$WP rewrite flush --hard 2>/dev/null || true

$WP term create category "Casino Guides" --slug=casino-guides 2>/dev/null || true
$WP term create category "iGaming Ontario" --slug=igaming-ontario 2>/dev/null || true

create_cluster() {
    local title="$1"
    local slug="$2"
    local excerpt="$3"
    local category="$4"
    local focus_kw="$5"
    local seo_title="$6"
    local seo_desc="$7"
    local content="$8"

    existing=$($WP post list --post_type=post --name="$slug" --field=ID 2>/dev/null | head -1)
    if [ -n "$existing" ]; then
        warn "Post '$title' already exists — skipping"
        return
    fi

    ID=$($WP post create \
        --post_type=post \
        --post_status=draft \
        --post_title="$title" \
        --post_name="$slug" \
        --post_excerpt="$excerpt" \
        --post_content="$content" \
        --porcelain 2>/dev/null)

    $WP post term set "$ID" category "$category" 2>/dev/null || true
    $WP post meta update "$ID" rank_math_title "$seo_title" 2>/dev/null || true
    $WP post meta update "$ID" rank_math_description "$seo_desc" 2>/dev/null || true
    $WP post meta update "$ID" rank_math_focus_keyword "$focus_kw" 2>/dev/null || true

    log "Created draft: $title (ID: $ID)"
}

# Week 1
create_cluster \
    "Fastest Payout Online Casinos Ontario 2026" \
    "fastest-payout-online-casinos-ontario" \
    "Which Ontario online casinos pay out the fastest? We tested withdrawal times across all iGO-licensed operators." \
    "casino-guides" \
    "fastest payout online casinos Ontario" \
    "Fastest Payout Online Casinos Ontario 2026 — Instant Withdrawals Ranked" \
    "We tested withdrawal speeds at every iGO-licensed Ontario online casino. Here are the fastest-paying operators, ranked by average payout time." \
    "<h2>The Fastest Paying Ontario Online Casinos (Quick List)</h2>
<p>We tested withdrawal processing times across all major iGO-licensed operators.</p>
<h2>How We Tested Payout Speed</h2>
<p>Our methodology: we submitted test withdrawals via e-Transfer, Visa Debit, and crypto (where available) at each casino and recorded the time from request to funds received.</p>
<h2>BetMGM Ontario — Payout Time Breakdown</h2>
<h2>DraftKings Ontario — Payout Time Breakdown</h2>
<h2>FanDuel Ontario — Payout Time Breakdown</h2>
<h2>What Affects Withdrawal Speed?</h2>
<p>Payment method, KYC verification status, and casino processing windows all affect how fast you receive funds.</p>
<h2>Tips to Get Your Winnings Faster</h2>
<p>Complete KYC verification before you need to withdraw. Use e-Transfer or crypto for the fastest processing.</p>"

create_cluster \
    "Best Casino Bonuses with No Wagering Requirements Ontario" \
    "best-casino-bonuses-no-wagering-requirements-ontario" \
    "Most Ontario casino bonuses come with wagering requirements. These are the rare ones that don't." \
    "casino-guides" \
    "casino bonuses no wagering requirements Ontario" \
    "Best No-Wagering Casino Bonuses Ontario 2026 — Keep What You Win" \
    "Wagering requirements can make bonuses almost worthless. Here are the best Ontario casino bonuses with no playthrough requirements." \
    "<h2>What Are Wagering Requirements?</h2>
<p>A wagering requirement means you must bet your bonus a set number of times before withdrawing. A \$100 bonus with 30x wagering means betting \$3,000 before cashing out.</p>
<h2>Best No-Wagering Bonuses at Ontario Casinos</h2>
<h2>Low-Wagering Alternatives Worth Considering</h2>
<h2>How to Spot a Genuinely Good Casino Bonus</h2>
<p>Check the terms carefully: wagering requirements, game restrictions, time limits, and maximum cashout caps all affect the real value of a bonus offer.</p>
<h2>Are No-Wagering Bonuses Legal in Ontario?</h2>
<p>Yes — iGO-licensed Ontario casinos set their own bonus terms within AGCO guidelines.</p>"

create_cluster \
    "Best Mobile Casino Apps Ontario 2026" \
    "best-mobile-casino-apps-ontario" \
    "We tested every Ontario casino's mobile app on iOS and Android. Here's how they rank for performance, game selection, and ease of use." \
    "casino-guides" \
    "best mobile casino apps Ontario" \
    "Best Mobile Casino Apps Ontario 2026 — iOS and Android Ranked" \
    "Tested on iPhone and Android: we rank every iGO-licensed Ontario casino mobile app on performance, game selection, and user experience." \
    "<h2>Best Ontario Casino Apps at a Glance</h2>
<h2>BetMGM Ontario App — Full Review</h2>
<h2>DraftKings Ontario App — Full Review</h2>
<h2>FanDuel Ontario App — Full Review</h2>
<h2>What Makes a Great Casino App?</h2>
<p>Speed, game variety, deposit/withdrawal access, and reliability are the four things that matter most.</p>
<h2>iOS vs Android — Any Differences?</h2>
<p>Most Ontario casino apps are near-identical across platforms, but a few have features exclusive to one OS.</p>"

# Week 2
create_cluster \
    "DraftKings vs BetMGM Ontario — Which Is Better in 2026?" \
    "draftkings-vs-betmgm-ontario" \
    "Ontario's two biggest online casinos go head to head. We compare bonuses, games, payments, and mobile apps to find a winner." \
    "casino-guides" \
    "DraftKings vs BetMGM Ontario" \
    "DraftKings vs BetMGM Ontario 2026 — Head-to-Head Comparison" \
    "DraftKings and BetMGM are Ontario's top two online casinos. We compare them across every category to help you choose." \
    "<h2>DraftKings vs BetMGM Ontario — Quick Verdict</h2>
<h2>Welcome Bonus Comparison</h2>
<h2>Game Selection</h2>
<h2>Mobile App Experience</h2>
<h2>Payment Methods and Withdrawal Speed</h2>
<h2>Customer Support</h2>
<h2>Which Should You Choose?</h2>
<p>Choose DraftKings if you want the biggest welcome bonus and the best sports betting integration. Choose BetMGM if live dealer games and VIP rewards are a priority.</p>"

create_cluster \
    "Best Live Dealer Casinos Ontario 2026" \
    "best-live-dealer-casinos-ontario" \
    "Ontario's top live dealer casino experiences — real dealers, real tables, streamed to your phone or desktop." \
    "casino-guides" \
    "best live dealer casinos Ontario" \
    "Best Live Dealer Casinos Ontario 2026 — Real Dealers, Real Money" \
    "The best live dealer casino experiences in Ontario, ranked by table variety, stream quality, bet limits, and dealer quality." \
    "<h2>Best Live Dealer Casinos in Ontario — Ranked</h2>
<h2>What Is Live Dealer Casino Gaming?</h2>
<p>Live dealer games use real dealers in a professional studio, streamed in HD to your device. You place bets via on-screen controls in real time.</p>
<h2>Live Blackjack Ontario — Best Tables</h2>
<h2>Live Roulette Ontario — Best Tables</h2>
<h2>Live Baccarat Ontario — Best Tables</h2>
<h2>Evolution Gaming vs Playtech — Who Powers Ontario's Best Live Tables?</h2>
<h2>What to Look for in a Live Dealer Casino</h2>"

create_cluster \
    "iGO Licensed Ontario Casinos — Complete List 2026" \
    "igo-licensed-ontario-casinos-2026" \
    "Every online casino currently licensed by iGaming Ontario — the complete, up-to-date list with direct links." \
    "igaming-ontario" \
    "iGO licensed casinos Ontario" \
    "iGO Licensed Ontario Online Casinos 2026 — Full List and How to Check" \
    "The complete list of all online casinos licensed by iGaming Ontario (iGO). Updated 2026. Only play at licensed operators." \
    "<h2>What Is iGaming Ontario?</h2>
<p>iGaming Ontario is the provincial subsidiary of the OLG that oversees the regulated online casino market launched in April 2022.</p>
<h2>Full List of iGO-Licensed Ontario Online Casinos</h2>
<h2>How to Verify an Ontario Casino Is Licensed</h2>
<p>Every licensed operator must display their iGO registration number and the AGCO logo. You can verify at igamingontario.ca/en/operator.</p>
<h2>What Happens If You Play at an Unlicensed Casino?</h2>
<p>Unlicensed casinos in Ontario are illegal. You have no consumer protection — withdrawals can be refused and disputes have no regulatory recourse.</p>"

# Week 3
create_cluster \
    "New Online Casinos Ontario 2026 — Latest Licensed Operators" \
    "new-online-casinos-ontario-2026" \
    "The newest iGO-licensed Ontario online casinos — full reviews of every operator that launched in 2025-2026." \
    "igaming-ontario" \
    "new online casinos Ontario 2026" \
    "New Online Casinos Ontario 2026 — Every New iGO-Licensed Operator Reviewed" \
    "New to the Ontario market in 2026? Here are all the latest iGO-licensed casinos reviewed, ranked, and rated." \
    "<h2>New Ontario Casinos in 2026 — Quick List</h2>
<h2>What to Check Before Trying a New Casino</h2>
<p>Verify iGO licensing, read the bonus terms carefully, and check payment method availability before depositing at any new operator.</p>
<h2>Are New Casinos Safe in Ontario?</h2>
<p>Yes — all licensed Ontario operators are regulated by the AGCO and must meet strict player protection standards regardless of when they launched.</p>"

create_cluster \
    "Best Slots at Ontario Online Casinos 2026" \
    "best-slots-ontario-online-casinos" \
    "The top slot games available at Ontario's licensed online casinos — highest RTP, best bonuses, and most popular titles." \
    "casino-guides" \
    "best slots Ontario online casinos" \
    "Best Online Slots Ontario 2026 — Highest RTP and Biggest Jackpots" \
    "The best slot games at Ontario's licensed online casinos, ranked by RTP, bonus features, and jackpot size." \
    "<h2>Best Online Slots in Ontario — Top Picks</h2>
<h2>Highest RTP Slots Available in Ontario</h2>
<p>RTP (Return to Player) is the percentage of wagers a slot pays back over time. Ontario slots typically range from 94-97% RTP.</p>
<h2>Best Progressive Jackpot Slots Ontario</h2>
<h2>Best New Slot Releases at Ontario Casinos</h2>
<h2>How to Choose a Slot Game</h2>"

create_cluster \
    "Safest Online Casinos Ontario — How to Spot a Trustworthy Operator" \
    "safest-online-casinos-ontario" \
    "All iGO-licensed Ontario casinos are safe — but some are safer than others. Here's what to look for." \
    "igaming-ontario" \
    "safest online casinos Ontario" \
    "Safest Online Casinos Ontario 2026 — Trustworthy iGO-Licensed Operators" \
    "How to identify the safest, most trustworthy Ontario online casinos. All licensed operators are regulated, but these go above and beyond." \
    "<h2>What Makes an Ontario Online Casino Safe?</h2>
<h2>The Role of iGO and AGCO Licensing</h2>
<h2>Signs of a Trustworthy Casino Operator</h2>
<h2>Red Flags to Watch For</h2>
<h2>Our Picks: Most Trusted Ontario Online Casinos</h2>"

# Week 4
create_cluster \
    "Best Sports Betting Apps Ontario 2026" \
    "best-sports-betting-apps-ontario" \
    "Ontario's top sports betting apps — ranked for odds, markets, bonuses, and mobile experience." \
    "casino-guides" \
    "best sports betting apps Ontario" \
    "Best Sports Betting Apps Ontario 2026 — Top Licensed Operators Ranked" \
    "The best sports betting apps in Ontario, ranked by odds quality, market depth, live betting, and welcome bonus value." \
    "<h2>Best Ontario Sports Betting Apps — Quick Ranking</h2>
<h2>DraftKings Ontario Sportsbook Review</h2>
<h2>FanDuel Ontario Sportsbook Review</h2>
<h2>BetMGM Ontario Sportsbook Review</h2>
<h2>Bet99 Sportsbook Review</h2>
<h2>What to Look for in a Sports Betting App</h2>
<h2>Is Sports Betting Legal in Ontario?</h2>
<p>Yes. Single-game sports betting has been legal in Ontario since August 2021, and the regulated iGO market launched in April 2022.</p>"

create_cluster \
    "FanDuel vs Bet99 Ontario — Which Sportsbook Wins?" \
    "fanduel-vs-bet99-ontario" \
    "FanDuel is the global giant. Bet99 is the Canadian specialist. We compare them head-to-head for Ontario bettors." \
    "casino-guides" \
    "FanDuel vs Bet99 Ontario" \
    "FanDuel vs Bet99 Ontario 2026 — Full Comparison for Canadian Bettors" \
    "FanDuel brings global scale. Bet99 brings Canadian-focused odds and markets. We compare both to find the best choice for Ontario sports bettors." \
    "<h2>FanDuel vs Bet99 — Quick Verdict</h2>
<h2>Welcome Bonus Comparison</h2>
<h2>Sports Markets and Odds Comparison</h2>
<h2>Canadian Sports Coverage (CFL, OHL, Curling)</h2>
<h2>Live Betting Experience</h2>
<h2>Mobile App Comparison</h2>
<h2>Who Should Choose FanDuel?</h2>
<h2>Who Should Choose Bet99?</h2>"

create_cluster \
    "Ontario Online Casino Deposit Methods — Complete Guide 2026" \
    "ontario-online-casino-deposit-methods" \
    "Every deposit method accepted at Ontario's licensed online casinos — fees, limits, processing times, and our recommendations." \
    "casino-guides" \
    "Ontario online casino deposit methods" \
    "Ontario Online Casino Deposit Methods 2026 — Fees, Limits and Speed Compared" \
    "Interac e-Transfer, Visa, Mastercard, crypto, and more. Complete guide to depositing at iGO-licensed Ontario online casinos." \
    "<h2>Payment Methods Accepted at Ontario Online Casinos</h2>
<h2>Interac e-Transfer — The Best Option for Canadians</h2>
<h2>Visa and Mastercard Deposits</h2>
<h2>PayPal at Ontario Casinos</h2>
<h2>Crypto Deposits at Ontario Casinos</h2>
<h2>Deposit Limits and Fees Compared</h2>
<h2>Fastest Deposit Methods</h2>"

echo ""
log "Phase 1 casino cluster posts seed complete — 12 drafts created."
echo ""
echo "  Next steps:"
echo "  1. Go to WP Admin -> Posts -> Drafts"
echo "  2. Expand each post using AI (target word count in each scaffold)"
echo "  3. Add author byline, iGO badge, and internal links before publishing"
echo "  4. Publish in order: Week 1 posts first"
