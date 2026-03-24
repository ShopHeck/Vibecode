#!/usr/bin/env bash
# =============================================================================
# OntariosBest.com — Blog Post Seed Script
#
# Creates 5 foundational SEO blog posts targeting Ontario keywords.
# Run after listings-seed.sh.
#
# Local usage:
#   bash wordpress/seeds/blog-seed.sh
#
# Production usage:
#   WP_ENV=production bash wordpress/seeds/blog-seed.sh
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
echo "  OntariosBest — Blog Post Seed"
echo "=================================================="
echo ""

$WP rewrite flush --hard 2>/dev/null || true

# Ensure blog category exists
$WP term create category "Casino Guides" --slug=casino-guides 2>/dev/null || true
$WP term create category "Ontario Travel" --slug=ontario-travel 2>/dev/null || true
$WP term create category "Ontario Dining" --slug=ontario-dining 2>/dev/null || true
$WP term create category "iGaming Ontario" --slug=igaming-ontario 2>/dev/null || true
log "Blog categories created"

# ---------------------------------------------------------------------------
# Helper: create_post
# ---------------------------------------------------------------------------
create_post() {
    local title="$1"
    local slug="$2"
    local excerpt="$3"
    local category="$4"
    local content="$5"
    local seo_title="$6"
    local seo_desc="$7"

    existing=$($WP post list --post_type=post --name="$slug" --field=ID 2>/dev/null | head -1)
    if [ -n "$existing" ]; then
        warn "Post '$title' already exists — skipping"
        return
    fi

    ID=$($WP post create \
        --post_type=post \
        --post_status=publish \
        --post_title="$title" \
        --post_name="$slug" \
        --post_excerpt="$excerpt" \
        --post_content="$content" \
        --porcelain 2>/dev/null)

    # Assign category
    $WP post term set "$ID" category "$category" 2>/dev/null || true

    # Rank Math SEO meta (if Rank Math is active)
    $WP post meta update "$ID" rank_math_title "$seo_title" 2>/dev/null || true
    $WP post meta update "$ID" rank_math_description "$seo_desc" 2>/dev/null || true
    $WP post meta update "$ID" rank_math_focus_keyword "${slug//-/ }" 2>/dev/null || true

    log "Created post: $title (ID: $ID)"
}

# ---------------------------------------------------------------------------
# Post 1: Best Online Casinos in Ontario 2026 (Hub Post)
# ---------------------------------------------------------------------------
create_post \
    "Best Online Casinos in Ontario 2026" \
    "best-online-casinos-ontario-2026" \
    "Our experts have tested and ranked the best online casinos licensed by iGaming Ontario. Find the top casino for bonuses, games, and payouts." \
    "Casino Guides" \
    "<p>Finding the best online casino in Ontario takes more than a quick Google search. With iGaming Ontario (iGO) now regulating the province's online gambling market, there are dozens of licensed casinos competing for your business — and quality varies significantly.</p>

<p>Our team has tested every major iGO-licensed casino on the key factors Ontario players care about: welcome bonuses, game selection, payment options, mobile experience, and customer support.</p>

<h2>Top Online Casinos in Ontario — Quick Rankings</h2>

<ol>
<li><strong><a href='/casinos/betmgm-ontario/'>BetMGM Ontario</a></strong> — Editor's Choice: Best overall casino</li>
<li><strong><a href='/casinos/bet365-ontario/'>bet365 Ontario</a></strong> — Most trusted brand, fastest withdrawals</li>
<li><strong><a href='/casinos/leovegas-ontario/'>LeoVegas Ontario</a></strong> — Best mobile casino app</li>
<li><strong><a href='/casinos/draftkings-ontario/'>DraftKings Ontario</a></strong> — Biggest welcome bonus (up to \$2,000)</li>
<li><strong><a href='/casinos/unibet-ontario/'>Unibet Ontario</a></strong> — Best for transparency and fair terms</li>
<li><strong><a href='/casinos/fanduel-ontario/'>FanDuel Ontario</a></strong> — Best casino + sportsbook combo</li>
<li><strong><a href='/casinos/888casino-ontario/'>888casino Ontario</a></strong> — Most established brand (since 1997)</li>
<li><strong><a href='/casinos/bet99/'>Bet99</a></strong> — Best Canadian-founded casino</li>
</ol>

<h2>What Makes an Ontario Casino the 'Best'?</h2>

<h3>1. iGO License (Non-Negotiable)</h3>
<p>Only play at casinos licensed by <strong>iGaming Ontario (iGO)</strong>. This license ensures the casino follows Ontario's strict consumer protection rules, including responsible gambling tools, fair game auditing, and secure banking. Every casino on this list is iGO-licensed.</p>

<h3>2. Welcome Bonus Value</h3>
<p>A great welcome bonus isn't just about the headline number. Look at the <strong>wagering requirements</strong> (lower is better), the <strong>time limit</strong> to meet the wagering requirement, and whether the bonus covers slots only or all games.</p>

<h3>3. Game Selection</h3>
<p>The best Ontario casinos offer 500+ games from top software providers including Evolution Gaming (live dealer), NetEnt, Microgaming, Pragmatic Play, and IGT. Look for a strong live casino section if that's important to you.</p>

<h3>4. Payment Methods</h3>
<p>As an Ontario player, you want <strong>Interac</strong> support (Canadian bank transfers). Most top casinos also accept Visa, Mastercard, PayPal, and iDebit. Fast withdrawals (1–3 business days) are a sign of a quality operator.</p>

<h3>5. Mobile Experience</h3>
<p>Most Ontario players access casino games on their phone. Look for a dedicated iOS/Android app or a well-optimized mobile website. LeoVegas is the current leader for mobile experience in Ontario.</p>

<h2>Understanding iGaming Ontario (iGO)</h2>

<p>In April 2022, Ontario became the first province in Canada to launch a regulated online casino and sports betting market. The Alcohol and Gaming Commission of Ontario (AGCO) oversees the market, with iGaming Ontario (iGO) as the market operator.</p>

<p>Before iGO, Ontario players accessed offshore (unregulated) sites. Now they have access to a growing list of licensed, regulated operators who must:</p>
<ul>
<li>Verify player age (19+)</li>
<li>Offer responsible gambling tools (deposit limits, self-exclusion)</li>
<li>Have their games audited for fairness</li>
<li>Maintain segregated player funds</li>
<li>Operate transparent bonus terms</li>
</ul>

<h2>Ontario Problem Gambling Resources</h2>
<p>If gambling is causing problems, help is available. Contact <strong>ConnexOntario at 1-866-531-2600</strong> (free, 24/7) or visit <a href='/responsible-gambling/'>our responsible gambling page</a> for more resources.</p>

<p><em>19+ only. Please gamble responsibly.</em></p>" \
    "Best Online Casinos Ontario 2026 – Ontario's Best" \
    "Expert rankings of the best iGO-licensed online casinos in Ontario for 2026. Compare bonuses, games, and payouts from BetMGM, bet365, LeoVegas, DraftKings, and more."

# ---------------------------------------------------------------------------
# Post 2: How to Choose an Online Casino in Ontario
# ---------------------------------------------------------------------------
create_post \
    "How to Choose an Online Casino in Ontario" \
    "how-to-choose-online-casino-ontario" \
    "New to Ontario online casinos? Our step-by-step guide helps you find the right iGO-licensed casino for your playing style and budget." \
    "Casino Guides" \
    "<p>Choosing an online casino in Ontario doesn't have to be complicated — but there are a few key things to know before you sign up. This guide walks you through exactly what to look for as an Ontario player.</p>

<h2>Step 1: Verify the Casino is iGO-Licensed</h2>

<p>This is the most important step. Only play at casinos licensed by <strong>iGaming Ontario (iGO)</strong>. You can verify a casino's license by:</p>
<ul>
<li>Checking the casino's footer for the iGO logo and license number</li>
<li>Visiting the <a href='https://igamingontario.ca/en/operator' rel='nofollow noopener' target='_blank'>official iGO operator list</a></li>
</ul>
<p>Offshore (unlicensed) casinos have no obligation to pay you, protect your data, or offer fair games. Licensed casinos do.</p>

<h2>Step 2: Understand the Welcome Bonus</h2>

<p>Every Ontario casino offers a welcome bonus — typically a percentage match on your first deposit. Here's what to check:</p>

<ul>
<li><strong>Match percentage</strong> — 100% is standard; 200% is generous</li>
<li><strong>Maximum bonus</strong> — how much extra money you can claim</li>
<li><strong>Wagering requirement</strong> — how many times you must play through the bonus before withdrawing (20x is great; 40x+ is high)</li>
<li><strong>Time limit</strong> — usually 30 days to meet the wagering requirement</li>
<li><strong>Game eligibility</strong> — slots typically contribute 100%, table games often contribute less</li>
</ul>

<h2>Step 3: Check the Game Selection</h2>

<p>Think about what you actually want to play:</p>
<ul>
<li><strong>Slots player?</strong> — look for 500+ slots from quality providers (NetEnt, Pragmatic Play, Microgaming)</li>
<li><strong>Live casino player?</strong> — look for Evolution Gaming as the live dealer provider (best in the industry)</li>
<li><strong>Table game player?</strong> — check for multiple variants of blackjack, roulette, and baccarat</li>
<li><strong>Sports bettor too?</strong> — consider DraftKings, FanDuel, or bet365 for a combined sportsbook + casino</li>
</ul>

<h2>Step 4: Check Payment Options</h2>

<p>As a Canadian player, ensure the casino supports:</p>
<ul>
<li><strong>Interac</strong> — the easiest, fastest method for Canadian bank transfers</li>
<li><strong>Visa/Mastercard</strong> — widely accepted, but some banks block gambling transactions</li>
<li><strong>PayPal</strong> — fast and convenient (BetMGM, bet365, DraftKings)</li>
</ul>
<p>Check the <strong>withdrawal processing time</strong> — the best Ontario casinos process withdrawals within 1–3 business days.</p>

<h2>Step 5: Test Customer Support Before You Need It</h2>

<p>Before depositing, send a test message to the casino's live chat to check:</p>
<ul>
<li>Response speed (under 2 minutes is great)</li>
<li>Knowledgeable answers (not just scripted responses)</li>
<li>24/7 availability</li>
</ul>

<h2>Our Top Recommendations</h2>
<ul>
<li><strong>Best overall:</strong> <a href='/casinos/betmgm-ontario/'>BetMGM Ontario</a></li>
<li><strong>Best bonus:</strong> <a href='/casinos/draftkings-ontario/'>DraftKings Ontario</a> (up to \$2,000)</li>
<li><strong>Best mobile:</strong> <a href='/casinos/leovegas-ontario/'>LeoVegas Ontario</a></li>
<li><strong>Most trusted:</strong> <a href='/casinos/bet365-ontario/'>bet365 Ontario</a></li>
</ul>

<p><em>19+ only. Please gamble responsibly. ConnexOntario: 1-866-531-2600.</em></p>" \
    "How to Choose an Online Casino in Ontario – Ontario's Best" \
    "Step-by-step guide to choosing the right iGO-licensed online casino in Ontario. Learn what to look for in bonuses, games, payments, and support."

# ---------------------------------------------------------------------------
# Post 3: Ontario iGaming — What You Need to Know
# ---------------------------------------------------------------------------
create_post \
    "Ontario iGaming: What You Need to Know" \
    "ontario-igaming-what-you-need-to-know" \
    "Ontario launched Canada's first regulated online casino market in 2022. Here's everything you need to know about iGaming Ontario, how it works, and what it means for players." \
    "iGaming Ontario" \
    "<p>In April 2022, Ontario became the first province in Canada to launch a fully regulated, competitive online gaming market. This was a significant change — and a positive one — for Ontario players who had previously been playing on unregulated offshore sites.</p>

<h2>What is iGaming Ontario (iGO)?</h2>

<p><strong>iGaming Ontario (iGO)</strong> is the crown agency responsible for conducting and managing Ontario's online gaming market. It operates under the Alcohol and Gaming Commission of Ontario (AGCO), which sets the rules every licensed operator must follow.</p>

<p>iGO entered into agreements with private operators — allowing them to legally offer online casino games, poker, and sports betting to Ontario residents aged 19+.</p>

<h2>What Changed in April 2022?</h2>

<p>Before April 4, 2022, Ontario players could only legally gamble online through <strong>OLG.ca</strong> — the Ontario Lottery and Gaming Corporation's online platform. Offshore sites operated in a legal grey area.</p>

<p>After April 4, 2022:</p>
<ul>
<li>Private operators can legally offer online gambling to Ontario residents</li>
<li>Players have access to dozens of licensed, regulated platforms</li>
<li>Licensed operators must follow strict consumer protection rules</li>
<li>Offshore sites that accept Ontario players operate illegally (AGCO enforcement is ongoing)</li>
</ul>

<h2>What Protections Do Players Have?</h2>

<p>Every iGO-licensed casino must:</p>
<ul>
<li>Verify players are 19+ before allowing gambling</li>
<li>Offer mandatory responsible gambling tools (deposit limits, reality checks, self-exclusion)</li>
<li>Have all games independently audited for fairness (RNG certification)</li>
<li>Maintain player funds in segregated accounts (separate from operating funds)</li>
<li>Process withdrawals within reasonable timeframes</li>
<li>Display responsible gambling messaging on all pages</li>
<li>Not advertise to minors or make false claims about winnings</li>
</ul>

<h2>How to Verify a Casino is iGO-Licensed</h2>

<p>The easiest way is to check the <a href='https://igamingontario.ca/en/operator' rel='nofollow noopener' target='_blank'>official iGaming Ontario operator list</a>. Every licensed operator is listed there. You can also look for the iGO logo in the casino's footer.</p>

<h2>The Responsible Gambling Framework</h2>

<p>Ontario has one of the most comprehensive responsible gambling frameworks in the world. Key programs include:</p>
<ul>
<li><strong>GameSense</strong> — information and resources integrated into licensed casino sites</li>
<li><strong>ConnexOntario</strong> — free, 24/7 mental health and gambling helpline: <strong>1-866-531-2600</strong></li>
<li><strong>Self-exclusion</strong> — players can exclude themselves from all iGO-licensed sites simultaneously</li>
</ul>

<h2>Which Casinos Are iGO-Licensed?</h2>

<p>See our complete list of <a href='/best-of/best-online-casinos-ontario/'>best iGO-licensed casinos in Ontario</a>, including full reviews and bonus comparisons.</p>

<p><em>19+ only. Gambling can be addictive. Please play responsibly.</em></p>" \
    "Ontario iGaming Explained: What Players Need to Know – Ontario's Best" \
    "Everything Ontario players need to know about iGaming Ontario (iGO) — how the regulated market works, player protections, and how to find licensed casinos."

# ---------------------------------------------------------------------------
# Post 4: Best Things to Do in Ontario This Weekend
# ---------------------------------------------------------------------------
create_post \
    "Best Things to Do in Ontario This Weekend" \
    "best-things-to-do-ontario-weekend" \
    "From Niagara Falls to Muskoka to the Stratford Festival, Ontario has incredible weekend getaway options for every interest and budget." \
    "Ontario Travel" \
    "<p>Ontario is one of Canada's most diverse provinces for weekend travel. Whether you're after natural wonders, cultural experiences, cottage relaxation, or city exploration, Ontario has something extraordinary within a few hours of almost anywhere.</p>

<h2>1. Niagara Falls — The Classic</h2>
<p>A day trip or weekend at <a href='/travel/niagara-falls/'>Niagara Falls</a> never disappoints. Take the boat tour to the base of Horseshoe Falls, walk behind the falls, and end the evening watching the illuminated falls from a restaurant patio. If you're feeling lucky, <strong>Niagara Fallsview Casino</strong> is steps away.</p>
<p><strong>Drive time from Toronto:</strong> 90 minutes via QEW</p>

<h2>2. Muskoka Lakes — Cottage Country</h2>
<p><a href='/travel/muskoka-lakes/'>Muskoka</a> is quintessential Ontario — granite rock, pine trees, and pristine lakes. Book a cottage for the weekend and spend your days swimming, boating, and kayaking. The towns of Bracebridge and Gravenhurst offer good restaurants and boutique shopping.</p>
<p><strong>Drive time from Toronto:</strong> 2 hours via Highway 400</p>

<h2>3. Stratford Festival — World-Class Theatre</h2>
<p>The <a href='/entertainment/stratford-festival/'>Stratford Festival</a> is one of North America's most acclaimed theatre experiences. A day trip from Toronto or a weekend in the charming town of Stratford combines excellent theatre with great dining. Book tickets months in advance for summer weekends.</p>
<p><strong>Drive time from Toronto:</strong> 1.5 hours via Highway 401</p>

<h2>4. Ottawa — Canada's Capital</h2>
<p>A weekend in <a href='/travel/ottawa-ontario/'>Ottawa</a> offers world-class museums (most free), Parliament Hill tours, the Rideau Canal, and the vibrant ByWard Market. In winter, skate the world's largest naturally frozen skating rink.</p>
<p><strong>Drive time from Toronto:</strong> 4.5 hours via Highway 401</p>

<h2>5. Prince Edward County — Wine Country</h2>
<p>'The County' has emerged as one of Ontario's most exciting wine and culinary destinations. The Hillier wine sub-region produces exceptional Pinot Noir and Chardonnay. Sandbanks Provincial Park has beach quality that rivals the Caribbean. Book well in advance for summer weekends.</p>
<p><strong>Drive time from Toronto:</strong> 2.5 hours via Highway 401</p>

<h2>6. Algonquin Provincial Park — The Great Outdoors</h2>
<p>For wilderness camping, canoeing, and wildlife viewing, Algonquin Provincial Park is Ontario's crown jewel. The Highway 60 corridor offers accessible camping and hiking, while the interior lakes are accessible by canoe for more remote experiences. Moose sightings are common at dusk.</p>
<p><strong>Drive time from Toronto:</strong> 2.5 hours via Highway 400</p>

<p>For more Ontario travel ideas, explore our <a href='/travel/'>full travel directory</a>.</p>" \
    "Best Things to Do in Ontario This Weekend – Ontario's Best" \
    "Top weekend getaways in Ontario: Niagara Falls, Muskoka cottage country, Stratford Festival, Ottawa, Prince Edward County wine country, and Algonquin Park."

# ---------------------------------------------------------------------------
# Post 5: Best Restaurants in Toronto 2026
# ---------------------------------------------------------------------------
create_post \
    "Best Restaurants in Toronto 2026" \
    "best-restaurants-toronto-2026" \
    "From rooftop fine dining to neighbourhood gems, Toronto's restaurant scene is world-class. Our experts pick the best restaurants in Toronto for 2026." \
    "Ontario Dining" \
    "<p>Toronto has one of the most diverse and exciting restaurant scenes in North America. With over 8,000 restaurants representing virtually every cuisine on earth, narrowing it down is no small task. Here are our picks for the best restaurants in Toronto in 2026.</p>

<h2>Fine Dining</h2>

<h3><a href='/restaurant/canoe-toronto/'>Canoe Restaurant & Bar</a> — Financial District</h3>
<p>Perched on the 54th floor of the TD Bank Tower with panoramic city and lake views, Canoe has defined Canadian fine dining since 1995. The seasonal menu celebrates the best Canadian ingredients — Quebec duck, Nova Scotia lobster, Ontario lamb — prepared at the highest level. Essential for a special occasion.</p>
<p><strong>Price:</strong> \$\$\$\$ | <strong>Best for:</strong> Special occasions, business dinners, anniversaries</p>

<h3>Alo — Queen West</h3>
<p>Consistently ranked as one of Canada's best restaurants, Alo offers a tasting menu in an intimate, elegant setting above Queen Street West. French technique meets Canadian ingredients. Book months in advance.</p>
<p><strong>Price:</strong> \$\$\$\$ | <strong>Best for:</strong> Tasting menu enthusiasts</p>

<h2>Modern Canadian</h2>

<h3>Actinolite — Ossington</h3>
<p>Chef Justin Cournoyer's restaurant is a love letter to Ontario ingredients. Everything on the menu comes from within the province — foraged mushrooms, heritage breed meats, local dairy. The results are remarkable.</p>
<p><strong>Price:</strong> \$\$\$ | <strong>Best for:</strong> Ontario food enthusiasts, farm-to-table</p>

<h2>Best Brunch</h2>

<h3>The Federal — Parkdale</h3>
<p>The Federal's weekend brunch is one of Toronto's most beloved traditions. Classic brunch dishes done exceptionally well, with natural wines and great coffee. Expect a wait — arrive early or book ahead.</p>
<p><strong>Price:</strong> \$\$ | <strong>Best for:</strong> Weekend brunch, casual dining</p>

<h2>Best Pizza</h2>

<h3>Descendant Detroit Style Pizza — Riverside</h3>
<p>If you haven't tried Detroit-style pizza (square, crispy edges, cheese to the edge), Descendant will convert you. The rotating specialty pizzas are creative without being gimmicky.</p>
<p><strong>Price:</strong> \$\$ | <strong>Best for:</strong> Casual dining, pizza lovers</p>

<h2>Best Sushi</h2>

<h3>Zen Japanese Restaurant — North York</h3>
<p>Zen is consistently ranked as Toronto's best sushi restaurant. Chef Yoshihiko Kousaka trained in Japan and brings authentic omakase to Toronto. Book the omakase counter experience for the full expression.</p>
<p><strong>Price:</strong> \$\$\$\$ | <strong>Best for:</strong> Sushi omakase, Japanese cuisine enthusiasts</p>

<h2>Best Value</h2>

<h3>Banh Mi Boys — Multiple Locations</h3>
<p>For exceptional food at exceptional value, Banh Mi Boys serves the best Vietnamese sandwiches in the city alongside creative tacos and kimchi fries. Under \$15 for a memorable lunch.</p>
<p><strong>Price:</strong> \$ | <strong>Best for:</strong> Lunch, budget dining</p>

<p>Explore more Ontario dining in our <a href='/restaurant/'>restaurant directory</a>.</p>" \
    "Best Restaurants in Toronto 2026 – Ontario's Best" \
    "The best restaurants in Toronto for 2026 — from Canoe's rooftop fine dining to neighbourhood gems. Expert picks across every price point and cuisine."

$WP rewrite flush --hard 2>/dev/null || true

echo ""
echo "=================================================="
echo "  Blog seed complete!"
echo "  5 foundational blog posts created at /blog/"
echo ""
echo "  Next steps:"
echo "  1. Add featured images to all posts"
echo "  2. Update Rank Math SEO meta if Rank Math is active"
echo "  3. Add affiliate links via ThirstyAffiliates"
echo "  4. Complete remaining launch-checklist.md items"
echo "=================================================="
echo ""
