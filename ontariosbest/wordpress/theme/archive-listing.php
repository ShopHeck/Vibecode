<?php
/**
 * Generic Listing Archive Template
 * Used for: travel, restaurant, entertainment, service, shopping archives
 */

get_header();

$post_type = get_queried_object()->name ?? get_post_type();

$config = array(
	'restaurant'    => array(
		'title'    => 'Best Restaurants in Ontario',
		'subtitle' => 'Expert-reviewed dining across Toronto, Ottawa, and beyond',
		'icon'     => '🍽️',
		'filter'   => 'restaurant_cuisine',
		'cta'      => 'Book a Table',
	),
	'travel'        => array(
		'title'    => 'Best Travel & Tourism in Ontario',
		'subtitle' => 'Hotels, destinations, and experiences worth the trip',
		'icon'     => '✈️',
		'filter'   => 'listing_region',
		'cta'      => 'Book Now',
	),
	'entertainment' => array(
		'title'    => 'Best Entertainment in Ontario',
		'subtitle' => 'Shows, attractions, events, and experiences',
		'icon'     => '🎭',
		'filter'   => 'entertainment_type',
		'cta'      => 'Get Tickets',
	),
	'service'       => array(
		'title'    => 'Best Services in Ontario',
		'subtitle' => 'Local businesses and professionals you can trust',
		'icon'     => '🔧',
		'filter'   => 'service_category',
		'cta'      => 'Get a Quote',
	),
	'shopping'      => array(
		'title'    => 'Best Shopping in Ontario',
		'subtitle' => 'Retailers, boutiques, and online shops worth your time',
		'icon'     => '🛍️',
		'filter'   => 'listing_region',
		'cta'      => 'Shop Now',
	),
);

$c = isset( $config[ $post_type ] ) ? $config[ $post_type ] : array(
	'title'    => 'Ontario\'s Best ' . ucfirst( $post_type ),
	'subtitle' => '',
	'icon'     => '📍',
	'filter'   => 'listing_region',
	'cta'      => 'View',
);

// Active filter
$active_filter = isset( $_GET['filter'] ) ? sanitize_text_field( $_GET['filter'] ) : '';
$active_region = isset( $_GET['region'] ) ? sanitize_text_field( $_GET['region'] ) : '';

?>

<!-- Page Header -->
<div style="background:linear-gradient(135deg,#1a1a2e,#16213e);color:#fff;padding:48px 0;text-align:center;">
	<div class="ast-container">
		<p style="font-size:32px;margin:0 0 8px;"><?php echo $c['icon']; ?></p>
		<h1 style="color:#fff;margin:0 0 10px;font-size:clamp(26px,4vw,42px);"><?php echo esc_html( $c['title'] ); ?></h1>
		<?php if ( $c['subtitle'] ) : ?>
			<p style="color:#ccd;font-size:16px;margin:0;"><?php echo esc_html( $c['subtitle'] ); ?></p>
		<?php endif; ?>
	</div>
</div>

<div style="padding:40px 0;">
	<div class="ast-container">
		<div style="display:flex;gap:32px;align-items:flex-start;">

			<!-- Listings -->
			<div style="flex:1;min-width:0;">

				<!-- Filter bar -->
				<?php
				$filter_terms = get_terms( array( 'taxonomy' => $c['filter'], 'hide_empty' => true ) );
				if ( ! empty( $filter_terms ) && ! is_wp_error( $filter_terms ) ) :
				?>
				<div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:24px;">
					<a href="<?php echo esc_url( get_post_type_archive_link( $post_type ) ); ?>"
					   style="padding:6px 16px;border-radius:20px;font-size:13px;text-decoration:none;<?php echo ! $active_filter ? 'background:var(--ob-primary);color:#fff;' : 'background:#f0f0f0;color:#444;'; ?>">
						All
					</a>
					<?php foreach ( $filter_terms as $term ) : ?>
					<a href="<?php echo esc_url( add_query_arg( 'filter', $term->slug ) ); ?>"
					   style="padding:6px 16px;border-radius:20px;font-size:13px;text-decoration:none;<?php echo $active_filter === $term->slug ? 'background:var(--ob-primary);color:#fff;' : 'background:#f0f0f0;color:#444;'; ?>">
						<?php echo esc_html( $term->name ); ?> (<?php echo $term->count; ?>)
					</a>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<?php
				// Build query
				$args = array(
					'post_type'      => $post_type,
					'posts_per_page' => 20,
					'orderby'        => 'meta_value_num',
					'meta_key'       => '_listing_overall_rating',
					'order'          => 'DESC',
				);

				$tax_query = array();
				if ( $active_filter ) {
					$tax_query[] = array(
						'taxonomy' => $c['filter'],
						'field'    => 'slug',
						'terms'    => $active_filter,
					);
				}
				if ( $active_region ) {
					$tax_query[] = array(
						'taxonomy' => 'listing_region',
						'field'    => 'slug',
						'terms'    => $active_region,
					);
				}
				if ( ! empty( $tax_query ) ) {
					$args['tax_query'] = $tax_query;
				}

				$listing_query = new WP_Query( $args );

				if ( $listing_query->have_posts() ) :
					while ( $listing_query->have_posts() ) : $listing_query->the_post();
						$rating   = ob_listing_meta( '_listing_overall_rating' );
						$address  = ob_listing_meta( '_listing_address' );
						$phone    = ob_listing_meta( '_listing_phone' );
						$aff_link = ob_listing_meta( '_listing_affiliate_url' );
						$price    = ob_listing_meta( '_listing_price_range' );
						$badge    = ob_sponsored_badge();
						$featured = ob_is_featured();
				?>
				<div style="background:#fff;border:1px solid <?php echo $featured ? 'var(--ob-primary)' : 'var(--ob-border)'; ?>;border-radius:var(--ob-radius);padding:18px;display:flex;gap:16px;align-items:flex-start;margin-bottom:12px;<?php echo $featured ? 'box-shadow:0 2px 12px rgba(232,160,32,0.15);' : ''; ?>">

					<?php if ( has_post_thumbnail() ) : ?>
					<div style="width:110px;flex-shrink:0;">
						<a href="<?php the_permalink(); ?>">
							<?php the_post_thumbnail( 'thumbnail', array( 'style' => 'width:110px;height:80px;object-fit:cover;border-radius:6px;display:block;' ) ); ?>
						</a>
					</div>
					<?php endif; ?>

					<div style="flex:1;min-width:0;">
						<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:8px;flex-wrap:wrap;">
							<div>
								<?php if ( $badge ) : ?>
									<span style="background:var(--ob-primary);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:3px;text-transform:uppercase;display:inline-block;margin-bottom:4px;"><?php echo esc_html( $badge ); ?></span>
								<?php elseif ( $featured ) : ?>
									<span style="background:#1a1a2e;color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:3px;text-transform:uppercase;display:inline-block;margin-bottom:4px;">Featured</span>
								<?php endif; ?>
								<h2 style="font-size:17px;font-weight:700;margin:0 0 4px;">
									<a href="<?php the_permalink(); ?>" style="color:var(--ob-text);text-decoration:none;"><?php the_title(); ?></a>
								</h2>
								<?php if ( $rating ) echo ob_render_stars( $rating ); ?>
								<?php if ( $address ) : ?>
									<p style="font-size:13px;color:#888;margin:4px 0 0;">📍 <?php echo esc_html( $address ); ?></p>
								<?php endif; ?>
								<?php if ( $price ) : ?>
									<p style="font-size:13px;color:#888;margin:2px 0 0;">💰 <?php echo esc_html( $price ); ?></p>
								<?php endif; ?>
							</div>
							<div style="display:flex;flex-direction:column;gap:6px;flex-shrink:0;align-items:flex-end;">
								<?php if ( $aff_link ) : ?>
									<a href="<?php echo esc_url( $aff_link ); ?>" class="ob-btn" target="_blank" rel="nofollow noopener sponsored" style="font-size:13px;padding:8px 16px;">
										<?php echo esc_html( $c['cta'] ); ?>
									</a>
								<?php elseif ( $phone ) : ?>
									<a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>" class="ob-btn" style="font-size:13px;padding:8px 16px;">
										📞 Call
									</a>
								<?php endif; ?>
								<a href="<?php the_permalink(); ?>" style="font-size:12px;color:#888;text-decoration:none;">Read Review →</a>
							</div>
						</div>
					</div>
				</div>
				<?php
					endwhile;
					wp_reset_postdata();
				else :
					echo '<p style="color:#888;">No listings found.</p>';
				endif;
				?>

				<!-- Pagination -->
				<div style="margin-top:28px;">
					<?php
					echo paginate_links( array(
						'total'   => $listing_query->max_num_pages,
						'current' => max( 1, get_query_var( 'paged' ) ),
					) );
					?>
				</div>

			</div><!-- listings -->

			<!-- Sidebar -->
			<aside style="width:260px;flex-shrink:0;">

				<!-- Region filter -->
				<?php
				$regions = get_terms( array( 'taxonomy' => 'listing_region', 'hide_empty' => true, 'parent' => 0 ) );
				if ( ! empty( $regions ) && ! is_wp_error( $regions ) ) :
				?>
				<div style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:18px;margin-bottom:20px;">
					<h4 style="margin:0 0 12px;font-size:15px;">Filter by Region</h4>
					<ul style="list-style:none;margin:0;padding:0;">
						<?php foreach ( $regions as $region ) : ?>
						<li style="margin-bottom:6px;">
							<a href="<?php echo esc_url( add_query_arg( 'region', $region->slug ) ); ?>"
							   style="font-size:14px;color:<?php echo $active_region === $region->slug ? 'var(--ob-primary)' : 'var(--ob-text)'; ?>;text-decoration:none;font-weight:<?php echo $active_region === $region->slug ? '700' : '400'; ?>;">
								<?php echo esc_html( $region->name ); ?>
								<span style="color:#aaa;font-size:12px;">(<?php echo $region->count; ?>)</span>
							</a>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>

				<!-- Advertise CTA -->
				<div style="background:linear-gradient(135deg,var(--ob-dark),#0f3460);color:#fff;border-radius:var(--ob-radius);padding:20px;text-align:center;">
					<p style="font-size:14px;font-weight:700;margin:0 0 6px;">Get Featured Here</p>
					<p style="font-size:12px;color:#ccd;margin:0 0 14px;">Reach Ontario's best audiences with a sponsored placement.</p>
					<a href="/advertise/" class="ob-btn" style="font-size:13px;padding:9px 18px;display:inline-block;">Learn More</a>
				</div>

			</aside>

		</div><!-- flex -->
	</div><!-- .ast-container -->
</div>

<?php get_footer(); ?>
