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
if ( ! function_exists( 'ob_city_query' ) ) :
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
				'value'   => 'standard',
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
endif;

/**
 * Helper: render a sponsored tier badge above a listing card.
 *
 * @param int $post_id
 */
if ( ! function_exists( 'ob_render_sponsored_badge' ) ) :
function ob_render_sponsored_badge( $post_id ) {
	$tier = get_post_meta( $post_id, '_ob_sponsored_tier', true );
	if ( $tier === 'featured' ) {
		echo '<span class="ob-sponsored-badge ob-sponsored-badge--featured">★ Featured</span>';
	} elseif ( $tier === 'standard' ) {
		echo '<span class="ob-sponsored-badge ob-sponsored-badge--standard">Sponsored</span>';
	}
}
endif;

/**
 * Helper: render a placeholder slot when no featured listing is present.
 *
 * @param string $vertical  Human-readable vertical name.
 */
if ( ! function_exists( 'ob_render_featured_placeholder' ) ) :
function ob_render_featured_placeholder( $vertical ) {
	echo '<div class="ob-featured-placeholder">';
	echo '<strong>Featured ' . esc_html( $vertical ) . ' Placement</strong>';
	echo '<p>Promote your ' . esc_html( strtolower( $vertical ) ) . ' here. <a href="' . esc_url( home_url( '/advertise/' ) ) . '">Learn about sponsorships →</a></p>';
	echo '</div>';
}
endif;

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
					<?php if ( $rating ) echo '<div class="ob-casino-card__rating">' . ob_render_stars( $rating ) . '</div>'; ?>
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
			<?php if ( ! $has_featured_restaurant && $restaurant_query->found_posts > 0 ) : ?>
			<div style="grid-column:1/-1;"><?php ob_render_featured_placeholder( 'Restaurant' ); ?></div>
			<?php endif; ?>
			</div>
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
