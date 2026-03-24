#!/usr/bin/env bash
# =============================================================================
# OntariosBest.com — Directory Listings Seed Script
#
# Creates 8 directory listings across travel, restaurant, and entertainment.
# Run after casinos-seed.sh.
#
# Local usage:
#   bash wordpress/seeds/listings-seed.sh
#
# Production usage:
#   WP_ENV=production bash wordpress/seeds/listings-seed.sh
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
echo "  OntariosBest — Directory Listings Seed"
echo "=================================================="
echo ""

$WP rewrite flush --hard 2>/dev/null || true

# ---------------------------------------------------------------------------
# Helper: create_listing
# Args: post_type title slug rating address phone website pros cons content region
# ---------------------------------------------------------------------------
create_listing() {
    local post_type="$1"
    local title="$2"
    local slug="$3"
    local rating="$4"
    local address="$5"
    local phone="$6"
    local website="$7"
    local pros="$8"
    local cons="$9"
    local content="${10}"
    local region="${11}"

    existing=$($WP post list --post_type="$post_type" --name="$slug" --field=ID 2>/dev/null | head -1)
    if [ -n "$existing" ]; then
        warn "$post_type '$title' already exists — skipping"
        return
    fi

    ID=$($WP post create \
        --post_type="$post_type" \
        --post_status=publish \
        --post_title="$title" \
        --post_name="$slug" \
        --post_excerpt="Discover $title — one of Ontario's top picks in our curated directory." \
        --post_content="$content" \
        --porcelain 2>/dev/null)

    $WP post meta update "$ID" _listing_overall_rating "$rating"
    $WP post meta update "$ID" _listing_address "$address"
    $WP post meta update "$ID" _listing_phone "$phone"
    $WP post meta update "$ID" _listing_website "$website"
    $WP post meta update "$ID" _listing_pros "$pros"
    $WP post meta update "$ID" _listing_cons "$cons"

    [ -n "$region" ] && {
        $WP term create listing_region "$region" --slug="$(echo "$region" | tr '[:upper:]' '[:lower:]' | tr ' ' '-')" 2>/dev/null || true
        $WP post term add "$ID" listing_region "$region" 2>/dev/null || true
    }

    log "Created $post_type: $title (ID: $ID)"
}

# ---------------------------------------------------------------------------
# Ensure listing_region taxonomy terms
# ---------------------------------------------------------------------------
for region in "Niagara Region" "Muskoka" "Ottawa" "Toronto" "Ontario"; do
    $WP term create listing_region "$region" --slug="$(echo "$region" | tr '[:upper:]' '[:lower:]' | tr ' ' '-')" 2>/dev/null || true
done
log "listing_region terms created"

# ---------------------------------------------------------------------------
# TRAVEL LISTINGS (3)
# ---------------------------------------------------------------------------

# Travel 1: Niagara Falls
create_listing \
    "travel" \
    "Niagara Falls, Ontario" \
    "niagara-falls" \
    "4.9" \
    "Niagara Falls, ON L2E 6T2" \
    "+1-905-356-6061" \
    "https://www.niagarafalls.ca" \
    "One of the world's most iconic natural wonders
Accessible year-round with different seasonal experiences
Casino Niagara and Niagara Fallsview Casino nearby
Wide variety of hotels from budget to luxury
Family-friendly attractions (Clifton Hill, Journey Behind the Falls)" \
    "Touristy main strip can feel commercialized
Peak summer crowds
Parking can be expensive" \
    "<h2>Niagara Falls, Ontario — Travel Guide</h2>
<p>Niagara Falls is Ontario's most visited destination and one of the most spectacular natural wonders in the world. Straddling the Ontario-New York border, the falls draw over 12 million visitors annually — and for good reason.</p>

<h3>The Falls</h3>
<p>There are actually three waterfalls at Niagara: <strong>Horseshoe Falls</strong> (the largest, on the Canadian side), American Falls, and Bridal Veil Falls. Horseshoe Falls is 57 metres tall and 670 metres wide, with over 168,000 cubic metres of water flowing over it every minute.</p>

<h3>Getting There</h3>
<p>Niagara Falls is a 90-minute drive from Toronto via the QEW highway. GO Train service from Toronto Union Station is also available, with seasonal service directly to Niagara Falls station.</p>

<h3>Where to Stay</h3>
<p>Accommodation ranges from budget motels on Lundy's Lane to the iconic Sheraton Fallsview Hotel and the luxury Marriott on the Falls. For the best views, book a room with a falls-facing window — worth the splurge.</p>

<h3>Things to Do</h3>
<ul>
<li><strong>Journey Behind the Falls</strong> — tunnels carved into the rock behind Horseshoe Falls</li>
<li><strong>Hornblower/Niagara City Cruises</strong> — boat tours to the base of the falls</li>
<li><strong>Niagara SkyWheel</strong> — observation wheel on Clifton Hill</li>
<li><strong>Niagara-on-the-Lake</strong> — charming wine country town 20 minutes away</li>
<li><strong>Casino Niagara</strong> and <strong>Niagara Fallsview Casino</strong> — two major casinos steps from the falls</li>
</ul>

<h3>Best Time to Visit</h3>
<p>Summer (June–August) is peak season with the best weather but largest crowds. Fall foliage (October) is stunning and less crowded. The Winter Festival of Lights (November–January) illuminates the entire gorge.</p>" \
    "Niagara Region"

# Travel 2: Muskoka
create_listing \
    "travel" \
    "Muskoka Lakes, Ontario" \
    "muskoka-lakes" \
    "4.8" \
    "Muskoka District, ON" \
    "+1-705-645-5264" \
    "https://discovermuskoka.ca" \
    "Iconic Ontario cottage country
Three pristine lakes: Muskoka, Rosseau, Joseph
World-class resorts (JW Marriott The Rosseau, Windermere House)
Excellent boating, swimming, fishing
Charming towns: Bracebridge, Gravenhurst, Huntsville" \
    "Peak summer season very expensive
Limited public access to lakes (mostly private cottages)
2-hour drive from Toronto can feel long on busy weekends" \
    "<h2>Muskoka Lakes — Ontario's Cottage Country</h2>
<p>Muskoka is the heart of Ontario cottage country — a 6,475 square kilometre region of pristine lakes, granite rock, and towering pines just two hours north of Toronto. For generations, Ontario families have escaped the city heat for Muskoka summers.</p>

<h3>The Lakes</h3>
<p>Muskoka is defined by three major lakes: <strong>Lake Muskoka</strong>, <strong>Lake Rosseau</strong>, and <strong>Lake Joseph</strong>. These crystal-clear lakes are perfect for swimming, boating, fishing, and kayaking.</p>

<h3>Where to Stay</h3>
<p>Accommodation ranges from budget-friendly motels in Bracebridge to iconic luxury resorts:</p>
<ul>
<li><strong>JW Marriott The Rosseau Muskoka</strong> — flagship luxury resort on Lake Rosseau</li>
<li><strong>Windermere House</strong> — historic lakeside resort since 1870</li>
<li><strong>Taboo Muskoka</strong> — golf and lakeside resort</li>
</ul>
<p>Renting a cottage through VRBO or Airbnb is also popular — book months in advance for peak summer weeks.</p>

<h3>Things to Do</h3>
<ul>
<li>Boat tours on the lakes</li>
<li>Gravenhurst Wharf and Muskoka Steamships</li>
<li>Santa's Village amusement park (Bracebridge)</li>
<li>Muskoka Arts & Crafts (summer art shows)</li>
<li>Algonquin Provincial Park (90 minutes north)</li>
</ul>" \
    "Muskoka"

# Travel 3: Ottawa
create_listing \
    "travel" \
    "Ottawa, Ontario" \
    "ottawa-ontario" \
    "4.7" \
    "Ottawa, ON K1A 0A9" \
    "+1-613-239-5000" \
    "https://ottawatourism.ca" \
    "Canada's capital — world-class museums (mostly free)
Rideau Canal (UNESCO Heritage Site — skating in winter)
Rich history and architecture
Vibrant ByWard Market food scene
Easy to explore on foot or by bike" \
    "Weather can be extremely cold in winter
Some museums closed on Mondays
Slightly more expensive accommodation than other Ontario cities" \
    "<h2>Ottawa, Ontario — Canada's Capital City</h2>
<p>Ottawa is one of Canada's most underrated travel destinations. As the nation's capital, it offers world-class museums, stunning architecture, and a vibrant food and arts scene — much of it free or very affordable.</p>

<h3>Top Attractions</h3>
<ul>
<li><strong>Parliament Hill</strong> — free guided tours of Canada's seat of government</li>
<li><strong>Canadian Museum of History</strong> — across the river in Gatineau, QC</li>
<li><strong>National Gallery of Canada</strong> — featuring the iconic spider sculpture</li>
<li><strong>Rideau Canal</strong> — UNESCO World Heritage Site; the world's largest naturally frozen skating rink in winter</li>
<li><strong>ByWard Market</strong> — Ottawa's historic outdoor market with restaurants, bars, and boutiques</li>
</ul>

<h3>Getting There</h3>
<p>Ottawa is a 4.5-hour drive from Toronto, or accessible via VIA Rail (4.5–5 hours). Ottawa Macdonald-Cartier International Airport has direct flights from most major Canadian cities.</p>

<h3>Best Time to Visit</h3>
<p>Spring (May) brings Tulip Festival — 300,000+ tulips in bloom. Summer offers festivals and outdoor activities. Winter on the Rideau Canal is a uniquely Canadian experience.</p>" \
    "Ottawa"

# ---------------------------------------------------------------------------
# RESTAURANT LISTINGS (3)
# ---------------------------------------------------------------------------

# Restaurant 1: Canoe Toronto
create_listing \
    "restaurant" \
    "Canoe Restaurant & Bar — Toronto" \
    "canoe-toronto" \
    "4.8" \
    "66 Wellington St W, 54th Floor, Toronto, ON M5K 1H6" \
    "+1-416-364-0054" \
    "https://www.canoerestaurant.com" \
    "Breathtaking 54th-floor views of Toronto
Iconic Canadian cuisine with local ingredients
Exceptional wine program
Perfect for special occasions
Award-winning service" \
    "Very expensive (fine dining price point)
Advance reservation required
Dress code (smart casual minimum)" \
    "<h2>Canoe Restaurant & Bar — Toronto Review</h2>
<p>Perched on the 54th floor of the TD Bank Tower in Toronto's financial district, Canoe is one of Canada's most celebrated fine dining restaurants. Since 1995, it has defined what Canadian cuisine means at the highest level.</p>

<h3>The Food</h3>
<p>Executive Chef Ron McKinlay leads a team committed to celebrating Canadian ingredients. Expect dishes like Quebec duck, Nova Scotia lobster, and Ontario lamb — prepared with French technique and modern Canadian sensibility. The tasting menu is the best way to experience the full range.</p>

<h3>The Views</h3>
<p>The panoramic views from the 54th floor are extraordinary. On a clear day, you can see across Lake Ontario to the U.S. The floor-to-ceiling windows make every seat a window seat.</p>

<h3>Wine & Cocktails</h3>
<p>The wine program is exceptional, with a strong Canadian selection alongside French and Italian classics. The cocktail menu celebrates Canadian spirits including Ontario craft distilleries.</p>

<h3>Verdict</h3>
<p>Canoe is essential for anyone who wants to experience the pinnacle of Canadian fine dining. Plan ahead — reservations book up weeks in advance for weekend dinners.</p>" \
    "Toronto"

# Restaurant 2: Beckta Ottawa
create_listing \
    "restaurant" \
    "Beckta Dining & Wine — Ottawa" \
    "beckta-ottawa" \
    "4.7" \
    "150 Elgin St, Ottawa, ON K2P 1L4" \
    "+1-613-238-7063" \
    "https://www.beckta.com" \
    "Ottawa's most acclaimed fine dining restaurant
Exceptional Canadian wine list
Intimate, elegant atmosphere
Outstanding tasting menu
Warm, knowledgeable service" \
    "Fine dining price point
Reservations essential
Limited parking nearby" \
    "<h2>Beckta Dining & Wine — Ottawa Review</h2>
<p>Beckta has been Ottawa's most acclaimed fine dining restaurant since 2003. Chef Stephen Beckta has created an institution that celebrates Canadian ingredients, Canadian wine, and Canadian hospitality.</p>

<h3>The Menu</h3>
<p>The seasonal menu changes regularly to reflect the best available Canadian ingredients. The tasting menu (6–8 courses with optional wine pairings) is the recommended way to experience Beckta fully.</p>

<h3>Canadian Wine Program</h3>
<p>Beckta is particularly celebrated for its Canadian wine program — one of the most comprehensive collections of VQA Ontario and BC wines available in a restaurant setting.</p>

<h3>Verdict</h3>
<p>For a special occasion in Ottawa, Beckta is the first call to make. Book early — especially for weekend dinners when tables fill weeks in advance.</p>" \
    "Ottawa"

# Restaurant 3: Eigensinn Farm, Collingwood
create_listing \
    "restaurant" \
    "The Bruce Wine Bar — Thornbury" \
    "bruce-wine-bar-thornbury" \
    "4.6" \
    "591 Bruce St S, Thornbury, ON N0H 2P0" \
    "+1-519-599-5552" \
    "https://www.thebruce.ca" \
    "Charming Georgian Bay destination
Local Ontario wine and craft beer focus
Fresh, seasonal farm-to-table menu
Beautiful Blue Mountain setting
Relaxed, unpretentious atmosphere" \
    "Small town — limited accommodation nearby
Seasonal hours (call ahead in winter)
Reservations recommended for dinner" \
    "<h2>The Bruce Wine Bar — Thornbury, Ontario Review</h2>
<p>Tucked in the charming village of Thornbury near Georgian Bay, The Bruce Wine Bar is a favourite destination for cottage country visitors and wine enthusiasts from across Ontario. The focus is squarely on Ontario wine, local food, and relaxed hospitality.</p>

<h3>Ontario Wine Focus</h3>
<p>The Bruce curates one of the best Ontario-focused wine lists in the province, with particular strength in Niagara Peninsula and Prince Edward County producers. Staff are knowledgeable and passionate about helping guests discover new favourites.</p>

<h3>The Food</h3>
<p>The menu is seasonal and locally sourced, featuring producers from Grey-Bruce County and surrounding regions. Charcuterie boards, local cheeses, and farm-fresh mains pair beautifully with the wine selection.</p>

<h3>Verdict</h3>
<p>The Bruce Wine Bar is a must-visit for anyone spending time in the Blue Mountain-Collingwood-Thornbury area. It captures what Ontario wine country hospitality is all about.</p>" \
    "Ontario"

# ---------------------------------------------------------------------------
# ENTERTAINMENT LISTINGS (2)
# ---------------------------------------------------------------------------

# Entertainment 1: Toronto Blue Jays
create_listing \
    "entertainment" \
    "Toronto Blue Jays — Rogers Centre" \
    "toronto-blue-jays" \
    "4.6" \
    "1 Blue Jays Way, Toronto, ON M5V 1J1" \
    "+1-416-341-1000" \
    "https://www.mlb.com/blue-jays" \
    "Canada's only MLB team
Iconic Rogers Centre location in downtown Toronto
Exciting atmosphere for a baseball game
Easy transit access (Union Station)
Good food options inside the stadium" \
    "Tickets can be expensive for premium seats
Rogers Centre artificial turf surface (not real grass)
Dome can feel sterile compared to outdoor stadiums" \
    "<h2>Toronto Blue Jays at Rogers Centre — Experience Guide</h2>
<p>Catching a Toronto Blue Jays game at Rogers Centre is one of Ontario's great sporting experiences. As Canada's only Major League Baseball team, the Blue Jays draw passionate fans from across the province.</p>

<h3>The Venue</h3>
<p>Rogers Centre (formerly SkyDome) is a landmark in downtown Toronto, just steps from CN Tower and Toronto's waterfront. The retractable roof means games proceed rain or shine — a major advantage in Ontario's unpredictable spring weather.</p>

<h3>Getting There</h3>
<p>Rogers Centre is directly connected to Union Station via the PATH underground walkway, making transit the easiest way to get there. Parking downtown is expensive and limited.</p>

<h3>Tickets</h3>
<p>Tickets are available through the Blue Jays official site and Ticketmaster. For best value, look for weekday games against non-division rivals. The 500 level offers good sightlines at lower prices than the 100 level.</p>

<h3>The Experience</h3>
<p>A Blue Jays game is as much a social event as a sporting one. The stadium atmosphere is electric for key games, and the food options have improved significantly in recent years with local food vendors now present.</p>" \
    "Toronto"

# Entertainment 2: Stratford Festival
create_listing \
    "entertainment" \
    "Stratford Festival" \
    "stratford-festival" \
    "4.9" \
    "55 Queen St, Stratford, ON N5A 6V2" \
    "+1-800-567-1600" \
    "https://www.stratfordfestival.ca" \
    "World-renowned Shakespeare festival (since 1953)
Multiple theatre venues in one charming town
Exceptional production quality
Charming town with great restaurants and shops
A uniquely Ontario cultural experience" \
    "Tickets sell out months in advance for popular shows
2-hour drive from Toronto
Town gets very busy during peak festival season (May–October)" \
    "<h2>Stratford Festival — Ontario's World-Class Theatre Experience</h2>
<p>The Stratford Festival is one of the most celebrated theatre festivals in North America and a point of immense pride for Ontario. Founded in 1953 by Tyrone Guthrie, the festival has grown into a cultural institution that draws over 500,000 visitors annually to the small town of Stratford, Ontario.</p>

<h3>The Shows</h3>
<p>While Shakespeare remains central to the festival's identity, the programming has expanded to include musicals, contemporary drama, and classic plays from other periods. Each season typically features 12–14 productions across four theatres.</p>

<h3>The Theatres</h3>
<ul>
<li><strong>Festival Theatre</strong> — the flagship thrust-stage theatre, the most distinctive design</li>
<li><strong>Avon Theatre</strong> — traditional proscenium stage, perfect for musicals</li>
<li><strong>Tom Patterson Theatre</strong> — intimate new venue on the Avon River bank</li>
<li><strong>Studio Theatre</strong> — experimental and emerging work</li>
</ul>

<h3>Planning Your Visit</h3>
<p>The festival runs May through October. Book tickets early — popular shows sell out months in advance. Pair the theatre with dinner at one of Stratford's excellent restaurants for a full day trip from Toronto.</p>

<h3>Verdict</h3>
<p>The Stratford Festival is a world-class cultural experience and one of Ontario's great tourism treasures. If you've never been, this season is the perfect time to go.</p>" \
    "Ontario"

$WP rewrite flush --hard 2>/dev/null || true

echo ""
echo "=================================================="
echo "  Listings seed complete!"
echo "  3 travel, 3 restaurant, 2 entertainment listings created"
echo ""
echo "  Next steps:"
echo "  1. Add featured images (WP Admin > each post type)"
echo "  2. Run: bash wordpress/seeds/blog-seed.sh"
echo "=================================================="
echo ""
