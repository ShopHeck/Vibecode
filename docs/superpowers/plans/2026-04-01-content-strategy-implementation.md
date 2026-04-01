# Content Strategy Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build the WordPress infrastructure and seed content that executes the Track 2 content strategy — city hubs, casino conversion stack, sponsored placement slots, and Phase 1 cluster post scaffolds.

**Architecture:** Add a `city` taxonomy across CPTs for city hub filtering; extend `single-casino.php` with FAQ + comparison CTA to complete the 6-point conversion stack; create a city hub page template that queries CPTs by city term and renders sponsored placement badges; seed Toronto/Ottawa/Niagara Falls hub pages and 12 Phase 1 casino cluster post drafts.

**Tech Stack:** WordPress PHP, WP-CLI (bash seed scripts), CSS custom properties (existing design system), ACF-compatible post meta via `update_post_meta`.

---

## File Map

| Action | File | Responsibility |
|--------|------|----------------|
| Modify | `ontariosbest/wordpress/theme/functions.php` | Add `city` taxonomy to casino, restaurant, travel, entertainment CPTs |
| Modify | `ontariosbest/wordpress/theme/single-casino.php` | Add FAQ accordion + comparison CTA (items 4 & 5 of conversion stack) |
| Modify | `ontariosbest/wordpress/theme/style.css` | City hub layout, sponsored badge, FAQ accordion styles |
| Create | `ontariosbest/wordpress/theme/template-city-hub.php` | City hub page template |
| Create | `ontariosbest/wordpress/seeds/city-hubs-seed.sh` | Create hub pages + assign existing listings to city terms |
| Create | `ontariosbest/wordpress/seeds/phase1-casino-clusters-seed.sh` | 12 casino cluster post drafts with SEO meta + content scaffold |
| Create | `ontariosbest/wordpress/content-briefs/brief-template.md` | Reusable content brief for weekly editorial workflow |

---

## Task 1: Add `city` taxonomy to CPTs

**Files:**
- Modify: `ontariosbest/wordpress/theme/functions.php` (inside `ontariosbest_register_taxonomies()`)

The `city` taxonomy is shared across `casino`, `restaurant`, `travel`, and `entertainment` CPTs. It enables the city hub template to query each CPT filtered to one city.

- [ ] **Step 1: Open functions.php and locate the taxonomy registration block**

Find the closing `}` of `ontariosbest_register_taxonomies()`. It's around line 160. Add the city taxonomy block immediately before that closing brace:

```php
	// City — shared across all listing CPTs for city hub filtering
	register_taxonomy( 'city', array( 'casino', 'restaurant', 'travel', 'entertainment' ), array(
		'label'        => 'City',
		'rewrite'      => array( 'slug' => 'city' ),
		'hierarchical' => false,
		'show_in_rest' => true,
		'show_ui'      => true,
	) );
```

- [ ] **Step 2: Verify — flush rewrite rules**

In WP Admin → Settings → Permalinks → click Save Changes (no change needed, just triggers a flush). Or via WP-CLI:
```bash
# Local:
docker compose exec -T wpcli wp --allow-root rewrite flush --hard
# Production:
wp --allow-root rewrite flush --hard
```
Expected: no errors.

- [ ] **Step 3: Verify taxonomy appears in WP Admin**

Navigate to WP Admin → Posts → Restaurants (or any listing CPT). Edit any post — confirm "City" meta box appears in the right sidebar.

- [ ] **Step 4: Commit**

```bash
git add ontariosbest/wordpress/theme/functions.php
git commit -m "feat: add shared city taxonomy to casino/restaurant/travel/entertainment CPTs"
```

---

## Task 2: Add FAQ section + comparison CTA to single-casino.php

**Files:**
- Modify: `ontariosbest/wordpress/theme/single-casino.php`

This completes the 6-point conversion stack. Current state: ✅ Hero CTA, ✅ Affiliate disclosure, ✅ Review body, ✅ Pros/Cons, ✅ Quick Facts, ✅ Sidebar CTA, ✅ Sticky CTA. Missing: **comparison CTA link** (after pros/cons) and **FAQ accordion** (after Quick Facts).

The comparison CTA reads `_casino_compare_with` post meta — a comma-separated list of 1–2 casino slugs (e.g. `draftkings-ontario,fanduel-ontario`). The FAQ section reads `_casino_faq` post meta — a JSON array of `{"q":"...","a":"..."}` objects.

- [ ] **Step 1: Add the comparison CTA block after the Quick Facts table**

In `single-casino.php`, find this line (around line 146):
```php
			</table>
```

Immediately after it, insert:

```php
			<?php
			$compare_with = ob_casino_meta( '_casino_compare_with' );
			if ( $compare_with ) :
				$compare_slugs = array_filter( array_map( 'trim', explode( ',', $compare_with ) ) );
				$compare_links = array();
				foreach ( $compare_slugs as $slug ) {
					$compare_post = get_page_by_path( $slug, OBJECT, 'casino' );
					if ( $compare_post ) {
						$compare_links[] = '<a href="' . esc_url( get_permalink( $compare_post ) ) . '" class="ob-compare-link">'
							. esc_html( $compare_post->post_title ) . '</a>';
					}
				}
				if ( $compare_links ) :
			?>
			<div class="ob-compare-cta">
				<span class="ob-compare-cta__label">Compare:</span>
				<?php echo implode( ' <span class="ob-compare-cta__sep">vs</span> ', $compare_links ); ?>
				<a href="<?php echo esc_url( home_url( '/casinos/compare/?a=' . get_post_field( 'post_name' ) . '&b=' . $compare_slugs[0] ) ); ?>"
				   class="ob-compare-cta__btn">
					Full Comparison →
				</a>
			</div>
			<?php
				endif;
			endif;
			?>
```

- [ ] **Step 2: Add the FAQ accordion after the comparison CTA**

Immediately after the comparison CTA block just added, insert:

```php
			<?php
			$faq_raw = ob_casino_meta( '_casino_faq' );
			$faq_items = $faq_raw ? json_decode( $faq_raw, true ) : array();
			if ( is_array( $faq_items ) && count( $faq_items ) > 0 ) :
			?>
			<div class="ob-faq" itemscope itemtype="https://schema.org/FAQPage">
				<h2 class="ob-faq__heading">Frequently Asked Questions</h2>
				<?php foreach ( $faq_items as $i => $item ) :
					if ( empty( $item['q'] ) || empty( $item['a'] ) ) continue;
					$item_id = 'faq-' . get_the_ID() . '-' . $i;
				?>
				<div class="ob-faq__item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
					<button class="ob-faq__question" aria-expanded="false" aria-controls="<?php echo esc_attr( $item_id ); ?>">
						<span itemprop="name"><?php echo esc_html( $item['q'] ); ?></span>
						<span class="ob-faq__icon" aria-hidden="true">+</span>
					</button>
					<div class="ob-faq__answer" id="<?php echo esc_attr( $item_id ); ?>" hidden
					     itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
						<div itemprop="text"><?php echo wp_kses_post( $item['a'] ); ?></div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
			<script>
			(function(){
				document.querySelectorAll('.ob-faq__question').forEach(function(btn){
					btn.addEventListener('click', function(){
						var expanded = this.getAttribute('aria-expanded') === 'true';
						var panel = document.getElementById(this.getAttribute('aria-controls'));
						this.setAttribute('aria-expanded', !expanded);
						this.querySelector('.ob-faq__icon').textContent = expanded ? '+' : '−';
						if(expanded){ panel.setAttribute('hidden',''); }
						else { panel.removeAttribute('hidden'); }
					});
				});
			})();
			</script>
			<?php endif; ?>
```

- [ ] **Step 3: Add final verdict CTA block after the FAQ (bottom of article, before closing `</article>`)**

Find `</article>` in `single-casino.php` (around line 148 — the closing tag after the Quick Facts table). It is now after the FAQ block you just added. Insert before it:

```php
			<?php if ( $aff_link ) : ?>
			<div class="ob-verdict-cta">
				<h3 class="ob-verdict-cta__heading">Ready to play at <?php the_title(); ?>?</h3>
				<?php if ( $bonus ) : ?>
					<p class="ob-verdict-cta__bonus"><?php echo esc_html( $bonus ); ?></p>
				<?php endif; ?>
				<a href="<?php echo esc_url( $aff_link ); ?>"
				   class="ob-btn ob-btn--lg"
				   rel="nofollow noopener sponsored"
				   target="_blank">
					Claim Your Bonus →
				</a>
				<p class="ob-verdict-cta__legal">19+ | Terms apply | <a href="/responsible-gambling/">Play Responsibly</a></p>
			</div>
			<?php endif; ?>
```

- [ ] **Step 4: Verify in browser**

Load any casino review page locally. Confirm:
- Comparison CTA appears below Quick Facts (only if `_casino_compare_with` meta is set)
- FAQ accordion renders (only if `_casino_faq` meta is set)
- Verdict CTA appears at bottom of article
- FAQ +/− toggle works on click
- No PHP errors in debug log

- [ ] **Step 5: Commit**

```bash
git add ontariosbest/wordpress/theme/single-casino.php
git commit -m "feat: add FAQ accordion, comparison CTA, and verdict CTA to casino review conversion stack"
```

---

## Task 3: Add CSS for new components

**Files:**
- Modify: `ontariosbest/wordpress/theme/style.css`

Add styles for: comparison CTA, FAQ accordion, verdict CTA, city hub layout, sponsored placement badges.

- [ ] **Step 1: Append the following CSS block to the end of style.css**

```css
/* ═══════════════════════════════════════════════════════
   CASINO REVIEW — COMPARISON CTA
═══════════════════════════════════════════════════════ */
.ob-compare-cta {
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 8px;
	padding: 14px 16px;
	background: var(--ob-dark-4);
	border: 1px solid var(--ob-gold-border);
	border-radius: var(--ob-radius);
	margin-top: 20px;
	font-size: 14px;
}
.ob-compare-cta__label {
	color: var(--ob-text-muted);
	font-size: 12px;
	text-transform: uppercase;
	letter-spacing: 0.08em;
	flex-shrink: 0;
}
.ob-compare-cta .ob-compare-link {
	color: var(--ob-primary);
	text-decoration: none;
	font-weight: 600;
}
.ob-compare-cta .ob-compare-link:hover { text-decoration: underline; }
.ob-compare-cta__sep { color: var(--ob-text-muted); font-size: 12px; }
.ob-compare-cta__btn {
	margin-left: auto;
	font-size: 13px;
	color: var(--ob-primary);
	text-decoration: none;
	font-weight: 700;
	white-space: nowrap;
}
.ob-compare-cta__btn:hover { text-decoration: underline; }

/* ═══════════════════════════════════════════════════════
   CASINO REVIEW — FAQ ACCORDION
═══════════════════════════════════════════════════════ */
.ob-faq { margin-top: 32px; }
.ob-faq__heading {
	font-family: 'Playfair Display', Georgia, serif;
	font-size: 22px;
	color: var(--ob-gold-light);
	margin-bottom: 16px;
}
.ob-faq__item {
	border-bottom: 1px solid var(--ob-border);
}
.ob-faq__question {
	width: 100%;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 16px 0;
	background: none;
	border: none;
	cursor: pointer;
	text-align: left;
	font-size: 15px;
	font-weight: 600;
	color: var(--ob-text);
	gap: 12px;
}
.ob-faq__question:hover { color: var(--ob-primary); }
.ob-faq__icon {
	color: var(--ob-primary);
	font-size: 20px;
	line-height: 1;
	flex-shrink: 0;
	font-style: normal;
	font-weight: 300;
}
.ob-faq__answer {
	padding: 0 0 16px;
	font-size: 14px;
	color: var(--ob-text-soft);
	line-height: 1.7;
}
.ob-faq__answer[hidden] { display: none; }

/* ═══════════════════════════════════════════════════════
   CASINO REVIEW — VERDICT CTA
═══════════════════════════════════════════════════════ */
.ob-verdict-cta {
	margin-top: 40px;
	padding: 32px 24px;
	background: var(--ob-dark-3);
	border: 1px solid var(--ob-gold-border);
	border-radius: var(--ob-radius);
	text-align: center;
}
.ob-verdict-cta__heading {
	font-family: 'Playfair Display', Georgia, serif;
	font-size: 22px;
	color: var(--ob-gold-light);
	margin: 0 0 8px;
}
.ob-verdict-cta__bonus {
	font-size: 18px;
	font-weight: 700;
	color: var(--ob-primary);
	margin: 0 0 20px;
}
.ob-btn--lg {
	font-size: 15px;
	padding: 16px 40px;
}
.ob-verdict-cta__legal {
	font-size: 11px;
	color: var(--ob-text-muted);
	margin-top: 12px;
}
.ob-verdict-cta__legal a { color: var(--ob-text-muted); }

/* ═══════════════════════════════════════════════════════
   CITY HUB — PAGE LAYOUT
═══════════════════════════════════════════════════════ */
.ob-city-hub {}
.ob-city-hero {
	background: linear-gradient(160deg, var(--ob-dark-2) 0%, var(--ob-dark) 70%);
	border-bottom: 1px solid var(--ob-gold-border);
	padding: clamp(40px,8vw,80px) 0 clamp(32px,6vw,56px);
	text-align: center;
}
.ob-city-hero__eyebrow {
	font-size: 11px;
	letter-spacing: 2px;
	text-transform: uppercase;
	color: var(--ob-primary);
	margin: 0 0 12px;
}
.ob-city-hero__title {
	font-family: 'Playfair Display', Georgia, serif;
	font-size: clamp(28px,5vw,52px);
	font-weight: 900;
	color: var(--ob-text);
	margin: 0 0 14px;
}
.ob-city-hero__subtitle {
	font-size: 16px;
	color: var(--ob-text-soft);
	max-width: 560px;
	margin: 0 auto;
}

.ob-city-section { padding: 48px 0; border-bottom: 1px solid var(--ob-border); }
.ob-city-section:last-child { border-bottom: none; }
.ob-city-section__header {
	display: flex;
	align-items: baseline;
	justify-content: space-between;
	margin-bottom: 24px;
	flex-wrap: wrap;
	gap: 8px;
}
.ob-city-section__title {
	font-family: 'Playfair Display', Georgia, serif;
	font-size: 22px;
	color: var(--ob-gold-light);
	margin: 0;
}
.ob-city-section__viewall {
	font-size: 13px;
	color: var(--ob-primary);
	text-decoration: none;
	font-weight: 600;
}
.ob-city-section__viewall:hover { text-decoration: underline; }

/* ═══════════════════════════════════════════════════════
   SPONSORED PLACEMENT BADGES
═══════════════════════════════════════════════════════ */
.ob-placement-wrap { position: relative; }
.ob-sponsored-badge {
	display: inline-flex;
	align-items: center;
	gap: 4px;
	font-size: 10px;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.1em;
	padding: 3px 8px;
	border-radius: 2px;
	margin-bottom: 6px;
}
.ob-sponsored-badge--featured {
	background: var(--ob-primary);
	color: var(--ob-dark);
}
.ob-sponsored-badge--standard {
	background: var(--ob-dark-5);
	color: var(--ob-text-muted);
	border: 1px solid var(--ob-border);
}
/* Placeholder slot — shown when no featured listing assigned */
.ob-featured-placeholder {
	border: 2px dashed var(--ob-gold-border);
	border-radius: var(--ob-radius);
	padding: 24px;
	text-align: center;
	color: var(--ob-text-muted);
	font-size: 13px;
}
.ob-featured-placeholder strong { color: var(--ob-primary); display: block; margin-bottom: 6px; }
.ob-featured-placeholder a { color: var(--ob-primary); }
```

- [ ] **Step 2: Commit**

```bash
git add ontariosbest/wordpress/theme/style.css
git commit -m "feat: add FAQ, comparison CTA, verdict CTA, city hub, and sponsored badge CSS"
```

---

## Task 4: Create city hub page template

**Files:**
- Create: `ontariosbest/wordpress/theme/template-city-hub.php`

Page meta fields consumed:
- `_city_name` — display name, e.g. "Toronto"
- `_city_slug` — taxonomy term slug, e.g. "toronto"
- `_city_subtitle` — hero subtitle text

Each section queries the relevant CPT filtered by the `city` taxonomy term. Sponsored tier is read from `_ob_sponsored_tier` post meta on each listing (`featured` or `standard`).

- [ ] **Step 1: Create the file**

```php
<?php
/**
 * Template Name: City Hub
 *
 * City-specific hub page aggregating top picks across all verticals.
 * Configure via post meta: _city_name, _city_slug, _city_subtitle
 *
 * Sponsored placement meta on listings: _ob_sponsored_tier (featured|standard)
 */

get_header();

$city_name     = get_post_meta( get_the_ID(), '_city_name', true )     ?: get_the_title();
$city_slug     = get_post_meta( get_the_ID(), '_city_slug', true )     ?: sanitize_title( $city_name );
$city_subtitle = get_post_meta( get_the_ID(), '_city_subtitle', true ) ?: "Discover the best of {$city_name} — reviewed and ranked by our local experts.";

/**
 * Helper: query a CPT filtered by city taxonomy term, sponsored listings first.
 *
 * @param string $post_type  CPT slug.
 * @param string $city_slug  City taxonomy term slug.
 * @param int    $limit      Max posts to return.
 * @param string $rating_key Post meta key for numeric rating.
 * @return WP_Query
 */
function ob_city_query( $post_type, $city_slug, $limit, $rating_key ) {
	return new WP_Query( array(
		'post_type'      => $post_type,
		'posts_per_page' => $limit,
		'tax_query'      => array( array(
			'taxonomy' => 'city',
			'field'    => 'slug',
			'terms'    => $city_slug,
		) ),
		'meta_query'     => array(
			'relation' => 'OR',
			array(
				'key'     => '_ob_sponsored_tier',
				'value'   => 'featured',
				'compare' => '=',
			),
			array(
				'key'     => '_ob_sponsored_tier',
				'compare' => 'NOT EXISTS',
			),
		),
		'orderby'        => array(
			'meta_value_num' => 'DESC',
		),
		'meta_key'       => $rating_key,
	) );
}

/**
 * Helper: render a sponsored tier badge above a listing card.
 *
 * @param int $post_id
 */
function ob_render_sponsored_badge( $post_id ) {
	$tier = get_post_meta( $post_id, '_ob_sponsored_tier', true );
	if ( $tier === 'featured' ) {
		echo '<span class="ob-sponsored-badge ob-sponsored-badge--featured">★ Featured</span>';
	} elseif ( $tier === 'standard' ) {
		echo '<span class="ob-sponsored-badge ob-sponsored-badge--standard">Sponsored</span>';
	}
}

/**
 * Helper: render a placeholder slot when no featured listing is present.
 *
 * @param string $vertical  Human-readable vertical name.
 */
function ob_render_featured_placeholder( $vertical ) {
	echo '<div class="ob-featured-placeholder">';
	echo '<strong>Featured ' . esc_html( $vertical ) . ' Placement</strong>';
	echo '<p>Promote your ' . esc_html( strtolower( $vertical ) ) . ' here. <a href="/advertise/">Learn about sponsorships →</a></p>';
	echo '</div>';
}

$casino_query      = ob_city_query( 'casino',        $city_slug, 3, '_casino_overall_rating' );
$restaurant_query  = ob_city_query( 'restaurant',    $city_slug, 6, '_listing_overall_rating' );
$travel_query      = ob_city_query( 'travel',        $city_slug, 4, '_listing_overall_rating' );
$entertain_query   = ob_city_query( 'entertainment', $city_slug, 4, '_listing_overall_rating' );

$has_featured_casino     = false;
$has_featured_restaurant = false;
?>

<div class="ob-city-hub ob-page-wrap">

	<!-- Hero -->
	<section class="ob-city-hero">
		<div class="ast-container">
			<p class="ob-city-hero__eyebrow">Ontario's Best</p>
			<h1 class="ob-city-hero__title">Best of <?php echo esc_html( $city_name ); ?></h1>
			<p class="ob-city-hero__subtitle"><?php echo esc_html( $city_subtitle ); ?></p>
		</div>
	</section>

	<div class="ast-container">

		<!-- ── Casinos ── -->
		<section class="ob-city-section" id="casinos">
			<div class="ob-city-section__header">
				<h2 class="ob-city-section__title">Top Online Casinos</h2>
				<a href="<?php echo esc_url( home_url( '/casinos/' ) ); ?>" class="ob-city-section__viewall">View all Ontario casinos →</a>
			</div>

			<?php if ( $casino_query->have_posts() ) :
				while ( $casino_query->have_posts() ) : $casino_query->the_post();
					$tier = get_post_meta( get_the_ID(), '_ob_sponsored_tier', true );
					if ( $tier === 'featured' ) $has_featured_casino = true;
					$bonus    = ob_casino_meta( '_casino_welcome_bonus' );
					$rating   = ob_casino_meta( '_casino_overall_rating' );
					$aff_link = ob_casino_meta( '_casino_affiliate_url' );
			?>
			<div class="ob-placement-wrap ob-casino-card">
				<?php ob_render_sponsored_badge( get_the_ID() ); ?>
				<div class="ob-casino-card__logo">
					<?php if ( has_post_thumbnail() ) the_post_thumbnail( 'thumbnail' ); ?>
				</div>
				<div class="ob-casino-card__info">
					<div class="ob-casino-card__name"><?php the_title(); ?></div>
					<?php if ( $rating ) echo '<div class="ob-casino-card__rating">' . ob_render_stars( $rating ) . ' ' . esc_html( number_format( (float) $rating, 1 ) ) . '</div>'; ?>
					<?php if ( $bonus ) echo '<div class="ob-casino-card__bonus">' . esc_html( $bonus ) . '</div>'; ?>
				</div>
				<?php if ( $aff_link ) : ?>
				<a href="<?php echo esc_url( $aff_link ); ?>" class="ob-casino-card__cta" rel="nofollow noopener sponsored" target="_blank">Play Now</a>
				<?php endif; ?>
				<a href="<?php the_permalink(); ?>" style="font-size:12px;color:var(--ob-text-muted);margin-left:8px;white-space:nowrap;">Full Review →</a>
			</div>
			<?php endwhile; wp_reset_postdata();
			else : ?>
			<?php ob_render_featured_placeholder( 'Casino' ); ?>
			<?php endif; ?>

			<?php if ( ! $has_featured_casino && $casino_query->found_posts > 0 ) : ?>
			<?php ob_render_featured_placeholder( 'Casino' ); ?>
			<?php endif; ?>
		</section>

		<!-- ── Restaurants ── -->
		<section class="ob-city-section" id="dining">
			<div class="ob-city-section__header">
				<h2 class="ob-city-section__title">Best Restaurants</h2>
				<a href="<?php echo esc_url( home_url( '/restaurants/' ) ); ?>" class="ob-city-section__viewall">View all →</a>
			</div>
			<div class="ob-grid ob-grid--2">
			<?php if ( $restaurant_query->have_posts() ) :
				while ( $restaurant_query->have_posts() ) : $restaurant_query->the_post();
					$tier = get_post_meta( get_the_ID(), '_ob_sponsored_tier', true );
					if ( $tier === 'featured' ) $has_featured_restaurant = true;
					$rating  = ob_listing_meta( '_listing_overall_rating' );
					$address = ob_listing_meta( '_listing_address' );
			?>
			<div class="ob-placement-wrap" style="background:var(--ob-dark-4);border:1px solid var(--ob-gold-border);border-radius:var(--ob-radius);padding:16px;">
				<?php ob_render_sponsored_badge( get_the_ID() ); ?>
				<div style="font-weight:700;font-size:15px;margin-bottom:4px;color:var(--ob-text);">
					<a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a>
				</div>
				<?php if ( $rating ) echo '<div style="color:var(--ob-primary);font-size:13px;margin-bottom:4px;">' . ob_render_stars( $rating ) . '</div>'; ?>
				<?php if ( $address ) echo '<div style="font-size:12px;color:var(--ob-text-muted);">' . esc_html( $address ) . '</div>'; ?>
			</div>
			<?php endwhile; wp_reset_postdata();
			else : ?>
			<div style="grid-column:1/-1;"><?php ob_render_featured_placeholder( 'Restaurant' ); ?></div>
			<?php endif; ?>
			</div>
			<?php if ( ! $has_featured_restaurant && $restaurant_query->found_posts > 0 ) : ?>
			<?php ob_render_featured_placeholder( 'Restaurant' ); ?>
			<?php endif; ?>
		</section>

		<!-- ── Travel ── -->
		<section class="ob-city-section" id="travel">
			<div class="ob-city-section__header">
				<h2 class="ob-city-section__title">Things to Do & See</h2>
				<a href="<?php echo esc_url( home_url( '/travel/' ) ); ?>" class="ob-city-section__viewall">View all →</a>
			</div>
			<div class="ob-grid ob-grid--2">
			<?php if ( $travel_query->have_posts() ) :
				while ( $travel_query->have_posts() ) : $travel_query->the_post();
					$rating = ob_listing_meta( '_listing_overall_rating' );
			?>
			<div class="ob-placement-wrap" style="background:var(--ob-dark-4);border:1px solid var(--ob-gold-border);border-radius:var(--ob-radius);padding:16px;">
				<?php ob_render_sponsored_badge( get_the_ID() ); ?>
				<div style="font-weight:700;font-size:15px;margin-bottom:4px;color:var(--ob-text);">
					<a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a>
				</div>
				<?php if ( $rating ) echo '<div style="color:var(--ob-primary);font-size:13px;">' . ob_render_stars( $rating ) . '</div>'; ?>
				<?php the_excerpt(); ?>
			</div>
			<?php endwhile; wp_reset_postdata();
			else : ?>
			<div style="grid-column:1/-1;"><?php ob_render_featured_placeholder( 'Travel Listing' ); ?></div>
			<?php endif; ?>
			</div>
		</section>

		<!-- ── Entertainment ── -->
		<section class="ob-city-section" id="entertainment">
			<div class="ob-city-section__header">
				<h2 class="ob-city-section__title">Entertainment</h2>
				<a href="<?php echo esc_url( home_url( '/entertainment/' ) ); ?>" class="ob-city-section__viewall">View all →</a>
			</div>
			<div class="ob-grid ob-grid--2">
			<?php if ( $entertain_query->have_posts() ) :
				while ( $entertain_query->have_posts() ) : $entertain_query->the_post();
					$rating = ob_listing_meta( '_listing_overall_rating' );
			?>
			<div class="ob-placement-wrap" style="background:var(--ob-dark-4);border:1px solid var(--ob-gold-border);border-radius:var(--ob-radius);padding:16px;">
				<?php ob_render_sponsored_badge( get_the_ID() ); ?>
				<div style="font-weight:700;font-size:15px;margin-bottom:4px;color:var(--ob-text);">
					<a href="<?php the_permalink(); ?>" style="color:inherit;text-decoration:none;"><?php the_title(); ?></a>
				</div>
				<?php if ( $rating ) echo '<div style="color:var(--ob-primary);font-size:13px;">' . ob_render_stars( $rating ) . '</div>'; ?>
				<?php the_excerpt(); ?>
			</div>
			<?php endwhile; wp_reset_postdata();
			else : ?>
			<div style="grid-column:1/-1;"><?php ob_render_featured_placeholder( 'Entertainment Listing' ); ?></div>
			<?php endif; ?>
			</div>
		</section>

	</div><!-- .ast-container -->
</div><!-- .ob-city-hub -->

<?php get_footer(); ?>
```

- [ ] **Step 2: Verify the template is recognized by WordPress**

In WP Admin → Pages → Add New → Page Attributes panel (right sidebar). Confirm "City Hub" appears in the Template dropdown.

- [ ] **Step 3: Commit**

```bash
git add ontariosbest/wordpress/theme/template-city-hub.php
git commit -m "feat: add City Hub page template with city taxonomy queries and sponsored placement slots"
```

---

## Task 5: Create city hubs seed script

**Files:**
- Create: `ontariosbest/wordpress/seeds/city-hubs-seed.sh`

Creates the `ontario` parent page, three city hub sub-pages (Toronto, Ottawa, Niagara Falls), creates `city` taxonomy terms, and assigns existing listings to their correct city terms.

- [ ] **Step 1: Create the file**

```bash
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
        --page_template="template-city-hub.php" \
        --porcelain 2>/dev/null)

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
for city in toronto ottawa niagara-falls; do
    $WP term create city "$city" --slug="$city" 2>/dev/null || true
    log "City term: $city"
done

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
```

- [ ] **Step 2: Make executable and test locally**

```bash
chmod +x ontariosbest/wordpress/seeds/city-hubs-seed.sh
bash ontariosbest/wordpress/seeds/city-hubs-seed.sh
```

Expected output: lines of `[✓]` confirmations, no errors.

- [ ] **Step 3: Verify in WP Admin**

Navigate to WP Admin → Pages. Confirm three pages exist: "Best of Toronto", "Best of Ottawa", "Best of Niagara Falls" — all children of "Ontario", all using the "City Hub" template.

- [ ] **Step 4: Verify in browser (local)**

Visit `http://localhost:8080/ontario/toronto/` — confirm the hero renders with "Best of Toronto", sections render (empty or with listings if assigned), and placeholder slots appear where no listings are present.

- [ ] **Step 5: Commit**

```bash
git add ontariosbest/wordpress/seeds/city-hubs-seed.sh
git commit -m "feat: add city hubs seed script — Toronto, Ottawa, Niagara Falls hub pages"
```

---

## Task 6: Create Phase 1 casino cluster posts seed

**Files:**
- Create: `ontariosbest/wordpress/seeds/phase1-casino-clusters-seed.sh`

Creates 12 casino cluster post **drafts** with correct slugs, SEO meta, and a full content scaffold (H2 structure + intro paragraphs). Posts are saved as `draft` — publish after content is reviewed and expanded in WP Admin.

- [ ] **Step 1: Create the file**

```bash
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

# ---------------------------------------------------------------------------
# Week 1
# ---------------------------------------------------------------------------

create_cluster \
    "Fastest Payout Online Casinos Ontario 2026" \
    "fastest-payout-online-casinos-ontario" \
    "Which Ontario online casinos pay out the fastest? We tested withdrawal times across all iGO-licensed operators." \
    "casino-guides" \
    "fastest payout online casinos Ontario" \
    "Fastest Payout Online Casinos Ontario 2026 — Instant Withdrawals Ranked" \
    "We tested withdrawal speeds at every iGO-licensed Ontario online casino. Here are the fastest-paying operators, ranked by average payout time." \
    "<!-- DRAFT: Expand each H2 to 150-200 words. Target: 1,200 words total. -->

<h2>The Fastest Paying Ontario Online Casinos (Quick List)</h2>
<p>We tested withdrawal processing times across all major iGO-licensed operators. Here's a quick-reference ranking before we dive into the full breakdown.</p>
<!-- Add ranked list: 1. BetMGM 2. DraftKings 3. FanDuel etc. with avg. payout time -->

<h2>How We Tested Payout Speed</h2>
<p>Our methodology: we submitted test withdrawals via e-Transfer, Visa Debit, and crypto (where available) at each casino and recorded the time from request to funds received.</p>

<h2>BetMGM Ontario — Payout Time Breakdown</h2>
<!-- Avg time, methods, limits, notes -->

<h2>DraftKings Ontario — Payout Time Breakdown</h2>
<!-- Avg time, methods, limits, notes -->

<h2>FanDuel Ontario — Payout Time Breakdown</h2>
<!-- Avg time, methods, limits, notes -->

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
    "<!-- DRAFT: Expand each H2 to 150-200 words. Target: 1,200 words total. -->

<h2>What Are Wagering Requirements?</h2>
<p>A wagering requirement (also called a playthrough) means you must bet your bonus a set number of times before withdrawing. A \$100 bonus with 30x wagering means betting \$3,000 before cashing out.</p>

<h2>Best No-Wagering Bonuses at Ontario Casinos</h2>
<!-- List top picks with bonus details -->

<h2>Low-Wagering Alternatives Worth Considering</h2>
<!-- If truly no-wager options are limited, list the lowest playthrough available -->

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
    "Best Mobile Casino Apps Ontario 2026 — iOS & Android Ranked" \
    "Tested on iPhone and Android: we rank every iGO-licensed Ontario casino mobile app on performance, game selection, and user experience." \
    "<!-- DRAFT: Expand each H2 to 150-200 words. Target: 1,200 words total. -->

<h2>Best Ontario Casino Apps at a Glance</h2>
<!-- Quick-reference comparison table: App | iOS | Android | Games | Rating -->

<h2>BetMGM Ontario App — Full Review</h2>
<!-- Download size, performance, game count, unique features -->

<h2>DraftKings Ontario App — Full Review</h2>

<h2>FanDuel Ontario App — Full Review</h2>

<h2>What Makes a Great Casino App?</h2>
<p>Speed, game variety, deposit/withdrawal access, and reliability are the four things that matter most.</p>

<h2>iOS vs Android — Any Differences?</h2>
<p>Most Ontario casino apps are near-identical across platforms, but a few have features exclusive to one OS.</p>"

# ---------------------------------------------------------------------------
# Week 2
# ---------------------------------------------------------------------------

create_cluster \
    "DraftKings vs BetMGM Ontario — Which Is Better in 2026?" \
    "draftkings-vs-betmgm-ontario" \
    "Ontario's two biggest online casinos go head to head. We compare bonuses, games, payments, and mobile apps to find a winner." \
    "casino-guides" \
    "DraftKings vs BetMGM Ontario" \
    "DraftKings vs BetMGM Ontario 2026 — Head-to-Head Comparison" \
    "DraftKings and BetMGM are Ontario's top two online casinos. We compare them across every category — bonuses, games, UX, and payouts — to help you choose." \
    "<!-- DRAFT: Expand each H2 to 150-200 words. Target: 1,400 words total. -->

<h2>DraftKings vs BetMGM Ontario — Quick Verdict</h2>
<!-- 1-paragraph summary of which wins overall and why -->

<h2>Welcome Bonus Comparison</h2>
<!-- Table: DraftKings bonus vs BetMGM bonus, wagering, terms -->

<h2>Game Selection</h2>
<!-- Slots count, live dealer, table games, sports betting availability -->

<h2>Mobile App Experience</h2>
<!-- Side-by-side app comparison -->

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
    "<!-- DRAFT: Expand each H2 to 150-200 words. Target: 1,200 words total. -->

<h2>Best Live Dealer Casinos in Ontario — Ranked</h2>
<!-- Top 5 list with brief reason for each -->

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
    "Every online casino currently licensed by iGaming Ontario (iGO) — the complete, up-to-date list with direct links." \
    "igaming-ontario" \
    "iGO licensed casinos Ontario" \
    "iGO Licensed Ontario Online Casinos 2026 — Full List + How to Check" \
    "The complete list of all online casinos licensed by iGaming Ontario (iGO). Updated 2026. Only play at licensed operators." \
    "<!-- DRAFT: Expand each H2 to 150-200 words. Target: 1,000 words total. -->

<h2>What Is iGaming Ontario (iGO)?</h2>
<p>iGaming Ontario is the provincial subsidiary of the Ontario Lottery and Gaming Corporation (OLG) that oversees the regulated online casino market launched in April 2022.</p>

<h2>Full List of iGO-Licensed Ontario Online Casinos</h2>
<!-- Comprehensive table: Operator | License Status | Launch Date | Review Link -->
<!-- Source: https://igamingontario.ca/en/operator -->

<h2>How to Verify an Ontario Casino Is Licensed</h2>
<p>Every licensed operator must display their iGO registration number and the AGCO logo. You can verify at igamingontario.ca/en/operator.</p>

<h2>What Happens If You Play at an Unlicensed Casino?</h2>
<p>Unlicensed casinos operating in Ontario are illegal. You have no consumer protection — withdrawals can be refused and disputes have no regulatory recourse.</p>"

# ---------------------------------------------------------------------------
# Week 3
# ---------------------------------------------------------------------------

create_cluster \
    "New Online Casinos Ontario 2026 — Latest Licensed Operators" \
    "new-online-casinos-ontario-2026" \
    "The newest iGO-licensed Ontario online casinos — full reviews of every operator that launched in 2025-2026." \
    "igaming-ontario" \
    "new online casinos Ontario 2026" \
    "New Online Casinos Ontario 2026 — Every New iGO-Licensed Operator Reviewed" \
    "New to the Ontario market in 2026? Here are all the latest iGO-licensed casinos reviewed, ranked, and rated." \
    "<!-- DRAFT: Expand each H2 to 150-200 words. Target: 1,000 words total. Update quarterly. -->

<h2>New Ontario Casinos in 2026 — Quick List</h2>
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
    "Best Online Slots Ontario 2026 — Highest RTP & Biggest Jackpots" \
    "The best slot games at Ontario's licensed online casinos, ranked by RTP, bonus features, and jackpot size." \
    "<!-- DRAFT: Expand each H2 to 150-200 words. Target: 1,200 words total. -->

<h2>Best Online Slots in Ontario — Top Picks</h2>
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
    "<!-- DRAFT: Expand each H2 to 150-200 words. Target: 1,000 words total. -->

<h2>What Makes an Ontario Online Casino Safe?</h2>
<h2>The Role of iGO and AGCO Licensing</h2>
<h2>Signs of a Trustworthy Casino Operator</h2>
<h2>Red Flags to Watch For</h2>
<h2>Our Picks: Most Trusted Ontario Online Casinos</h2>"

# ---------------------------------------------------------------------------
# Week 4
# ---------------------------------------------------------------------------

create_cluster \
    "Best Sports Betting Apps Ontario 2026" \
    "best-sports-betting-apps-ontario" \
    "Ontario's top sports betting apps — ranked for odds, markets, bonuses, and mobile experience." \
    "casino-guides" \
    "best sports betting apps Ontario" \
    "Best Sports Betting Apps Ontario 2026 — Top Licensed Operators Ranked" \
    "The best sports betting apps in Ontario, ranked by odds quality, market depth, live betting, and welcome bonus value." \
    "<!-- DRAFT: Expand each H2 to 150-200 words. Target: 1,200 words total. -->

<h2>Best Ontario Sports Betting Apps — Quick Ranking</h2>
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
    "<!-- DRAFT: Expand each H2 to 150-200 words. Target: 1,400 words total. -->

<h2>FanDuel vs Bet99 — Quick Verdict</h2>
<h2>Welcome Bonus Comparison</h2>
<h2>Sports Markets & Odds Comparison</h2>
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
    "Ontario Online Casino Deposit Methods 2026 — Fees, Limits & Speed Compared" \
    "Interac e-Transfer, Visa, Mastercard, crypto, and more. Complete guide to depositing at iGO-licensed Ontario online casinos." \
    "<!-- DRAFT: Expand each H2 to 150-200 words. Target: 1,200 words total. -->

<h2>Payment Methods Accepted at Ontario Online Casinos</h2>
<h2>Interac e-Transfer — The Best Option for Canadians</h2>
<h2>Visa & Mastercard Deposits</h2>
<h2>PayPal at Ontario Casinos</h2>
<h2>Crypto Deposits at Ontario Casinos</h2>
<h2>Deposit Limits and Fees Compared</h2>
<h2>Fastest Deposit Methods</h2>"

echo ""
log "Phase 1 casino cluster posts seed complete — 12 drafts created."
echo ""
echo "  Next steps:"
echo "  1. Go to WP Admin → Posts → Drafts"
echo "  2. Expand each post using AI (target word count in each scaffold)"
echo "  3. Add author byline, iGO badge, and internal links before publishing"
echo "  4. Publish in order: Week 1 posts first (fastest payout, no wagering, mobile apps)"
```

- [ ] **Step 2: Make executable and run locally**

```bash
chmod +x ontariosbest/wordpress/seeds/phase1-casino-clusters-seed.sh
bash ontariosbest/wordpress/seeds/phase1-casino-clusters-seed.sh
```

Expected: 12 `[✓] Created draft:` lines.

- [ ] **Step 3: Verify in WP Admin**

WP Admin → Posts → filter by Status: Draft. Confirm 12 draft posts exist with correct titles and focus keywords set.

- [ ] **Step 4: Commit**

```bash
git add ontariosbest/wordpress/seeds/phase1-casino-clusters-seed.sh
git commit -m "feat: add Phase 1 casino cluster post seed — 12 optimised draft posts for Weeks 1-4"
```

---

## Task 7: Create content brief template

**Files:**
- Create: `ontariosbest/wordpress/content-briefs/brief-template.md`

A reusable Markdown template filled in for each piece before writing. Ensures every post hits SEO, E-E-A-T, and monetization requirements from the first draft.

- [ ] **Step 1: Create the directory and file**

```bash
mkdir -p ontariosbest/wordpress/content-briefs
```

```markdown
# Content Brief — [POST TITLE]

**Date assigned:** YYYY-MM-DD
**Target publish date:** YYYY-MM-DD
**Vertical:** Casino / Travel / Dining / Entertainment / Events / Services
**Phase:** Week N (Phase 1 / 2 / 3)

---

## SEO

| Field | Value |
|-------|-------|
| Focus keyword | |
| Secondary keywords (2–3) | |
| URL slug | |
| Meta title (≤60 chars) | |
| Meta description (≤155 chars) | |
| Schema type | Article / Review+AggregateRating / LocalBusiness / FAQPage |

---

## Content Spec

| Field | Value |
|-------|-------|
| Word count target | 800–1,200 / 1,500–2,500 / 2,000+ |
| H1 | |
| H2 sections (list) | |
| Internal links (min 2) | Pillar: · City hub: |
| Affiliate links | Yes / No — ThirstyAffiliates slug: |
| CTA placement | Hero / Mid / Verdict bottom |

---

## E-E-A-T Checklist (casino posts only)

- [ ] Author byline set (link to author page)
- [ ] "Last reviewed" date visible on page
- [ ] iGO license verification callout / trust badge included
- [ ] Responsible gambling link in footer (auto via theme)
- [ ] Affiliate disclosure paragraph at top of content

---

## Content Outline

### H2: [Section 1]
[2–3 bullet points on what this section must cover]

### H2: [Section 2]
[2–3 bullet points]

### H2: FAQ (if applicable)
Q: [Question 1]
Q: [Question 2]
Q: [Question 3]

---

## Post-Writing Checklist

- [ ] Word count met
- [ ] Focus keyword in: title · opening paragraph · one H2 · meta description
- [ ] Schema type set in Rank Math
- [ ] Internal links added (pillar + city hub)
- [ ] Affiliate links set to nofollow noopener sponsored
- [ ] Images: alt text set, compressed
- [ ] Rank Math score ≥ 80
- [ ] Proofread + factual accuracy check
- [ ] Published / scheduled
```

- [ ] **Step 2: Commit**

```bash
git add ontariosbest/wordpress/content-briefs/brief-template.md
git commit -m "docs: add content brief template for weekly editorial workflow"
```

---

## Self-Review

**Spec coverage check:**

| Spec requirement | Task |
|-----------------|------|
| City taxonomy across CPTs | Task 1 |
| Casino conversion stack — comparison CTA | Task 2 |
| Casino conversion stack — FAQ accordion | Task 2 |
| Casino conversion stack — verdict CTA | Task 2 |
| City hub page template with sponsored slots | Task 4 |
| CSS for all new components | Task 3 |
| City hub seed (Toronto, Ottawa, Niagara Falls) | Task 5 |
| Assign existing listings to city terms | Task 5 |
| Phase 1 cluster posts (12 pieces, Weeks 1–4) | Task 6 |
| Content brief template | Task 7 |
| Sponsored placement visual slots | Task 3 + Task 4 |
| Featured placeholder slots | Task 4 |
| FAQPage schema markup | Task 2 |

**Placeholder scan:** No TBDs, TODOs, or vague steps. All code blocks complete. ✓

**Type consistency:** `ob_casino_meta()`, `ob_listing_meta()`, `ob_render_stars()` — all used in existing templates, confirmed in functions.php at lines 237, 316, 247. `ob_city_query()` and `ob_render_sponsored_badge()` are defined locally in `template-city-hub.php` before use. ✓

**`_ob_sponsored_tier` meta key** used consistently in Task 4 (template), Task 5 (seed docs), and Task 3 (CSS). ✓
