<?php
/**
 * Generic Listing Review Template
 * Used for: travel, restaurant, entertainment, service, shopping
 */

get_header();

while ( have_posts() ) : the_post();

$post_type   = get_post_type();
$rating      = ob_listing_meta( '_listing_overall_rating' );
$address     = ob_listing_meta( '_listing_address' );
$phone       = ob_listing_meta( '_listing_phone' );
$website     = ob_listing_meta( '_listing_website' );
$aff_link    = ob_listing_meta( '_listing_affiliate_url' );
$price_range = ob_listing_meta( '_listing_price_range' );   // $, $$, $$$, $$$$
$price_from  = ob_listing_meta( '_listing_price_from' );
$hours       = ob_listing_meta( '_listing_hours' );
$score_q     = ob_listing_meta( '_listing_score_quality' );
$score_v     = ob_listing_meta( '_listing_score_value' );
$score_e     = ob_listing_meta( '_listing_score_experience' );
$pros        = ob_listing_meta( '_listing_pros' );
$cons        = ob_listing_meta( '_listing_cons' );
$badge       = ob_sponsored_badge();

// Post-type-specific labels
$type_labels = array(
	'restaurant'    => array( 'cta' => 'Book a Table', 'icon' => '🍽️' ),
	'travel'        => array( 'cta' => 'Book Now',     'icon' => '✈️' ),
	'entertainment' => array( 'cta' => 'Get Tickets',  'icon' => '🎭' ),
	'service'       => array( 'cta' => 'Get a Quote',  'icon' => '🔧' ),
	'shopping'      => array( 'cta' => 'Shop Now',     'icon' => '🛍️' ),
);
$label = isset( $type_labels[ $post_type ] ) ? $type_labels[ $post_type ] : array( 'cta' => 'Visit Site', 'icon' => '📍' );

// Breadcrumb post type archive label
$archive_labels = array(
	'restaurant'    => 'Restaurants',
	'travel'        => 'Travel',
	'entertainment' => 'Entertainment',
	'service'       => 'Services',
	'shopping'      => 'Shopping',
);
$archive_label = isset( $archive_labels[ $post_type ] ) ? $archive_labels[ $post_type ] : ucfirst( $post_type );
$archive_url   = get_post_type_archive_link( $post_type );

?>
<div class="ob-page-wrap">
	<div class="ast-container" style="padding-top:24px;padding-bottom:48px;">

		<!-- Breadcrumb -->
		<nav style="font-size:13px;color:#888;margin-bottom:24px;">
			<a href="/" style="color:#888;text-decoration:none;">Home</a>
			<span style="margin:0 6px;">›</span>
			<a href="<?php echo esc_url( $archive_url ); ?>" style="color:#888;text-decoration:none;"><?php echo esc_html( $archive_label ); ?></a>
			<span style="margin:0 6px;">›</span>
			<span style="color:var(--ob-text);"><?php the_title(); ?></span>
		</nav>

		<div style="display:flex;gap:32px;align-items:flex-start;">

			<!-- Main Content -->
			<article style="flex:1;min-width:0;">

				<!-- Header -->
				<div style="display:flex;align-items:flex-start;gap:20px;margin-bottom:24px;flex-wrap:wrap;">
					<?php if ( has_post_thumbnail() ) : ?>
						<div style="width:120px;flex-shrink:0;">
							<?php the_post_thumbnail( 'medium', array( 'style' => 'max-width:100%;border-radius:var(--ob-radius);' ) ); ?>
						</div>
					<?php endif; ?>
					<div style="flex:1;">
						<?php if ( $badge ) : ?>
							<span style="background:var(--ob-primary);color:#fff;font-size:10px;font-weight:700;padding:2px 8px;border-radius:3px;text-transform:uppercase;display:inline-block;margin-bottom:8px;"><?php echo esc_html( $badge ); ?></span>
						<?php endif; ?>
						<h1 style="margin:0 0 6px;font-size:clamp(22px,3vw,32px);"><?php the_title(); ?></h1>
						<?php if ( $rating ) echo ob_render_stars( $rating ); ?>
						<?php if ( $address ) : ?>
							<p style="font-size:14px;color:#666;margin:8px 0 0;">📍 <?php echo esc_html( $address ); ?></p>
						<?php endif; ?>
						<?php if ( $price_range ) : ?>
							<p style="font-size:14px;color:#666;margin:4px 0 0;">💰 <?php echo esc_html( $price_range ); ?></p>
						<?php endif; ?>
					</div>
				</div>

				<!-- CTA Bar -->
				<?php if ( $aff_link || $phone ) : ?>
				<div style="background:#fffbf0;border:1px solid var(--ob-primary);border-radius:var(--ob-radius);padding:16px 20px;display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
					<div>
						<?php if ( $price_from ) : ?>
							<div style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#888;">Starting From</div>
							<div style="font-size:20px;font-weight:700;color:var(--ob-accent);"><?php echo esc_html( $price_from ); ?></div>
						<?php else : ?>
							<div style="font-size:15px;font-weight:600;"><?php echo esc_html( $label['icon'] . ' ' . get_the_title() ); ?></div>
						<?php endif; ?>
					</div>
					<div style="display:flex;gap:10px;flex-wrap:wrap;">
						<?php if ( $phone ) : ?>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>"
							   class="ob-btn-outline"
							   data-ga-event="click_to_call"
							   data-ga-label="<?php echo esc_attr( get_the_title() ); ?>">
								📞 Call Now
							</a>
						<?php endif; ?>
						<?php if ( $aff_link ) : ?>
							<a href="<?php echo esc_url( $aff_link ); ?>"
							   class="ob-btn"
							   target="_blank"
							   rel="nofollow noopener sponsored"
							   data-ga-event="affiliate_click"
							   data-ga-label="<?php echo esc_attr( get_the_title() ); ?>">
								<?php echo esc_html( $label['cta'] ); ?> →
							</a>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>

				<!-- Affiliate Disclosure -->
				<p class="affiliate-disclosure">
					<strong>Disclosure:</strong> OntariosBest.com may earn a commission when you click links on this page. This does not affect our editorial ratings.
				</p>

				<!-- Review Content -->
				<div class="ob-review-content">
					<?php the_content(); ?>
				</div>

				<!-- Pros / Cons -->
				<?php if ( $pros || $cons ) : ?>
				<div class="ob-pros-cons" style="margin-top:32px;">
					<?php if ( $pros ) : ?>
					<div class="ob-pros">
						<h4>What We Love</h4>
						<ul>
							<?php foreach ( array_filter( explode( "\n", $pros ) ) as $pro ) : ?>
								<li><?php echo esc_html( trim( $pro ) ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>
					<?php if ( $cons ) : ?>
					<div class="ob-cons">
						<h4>Keep in Mind</h4>
						<ul>
							<?php foreach ( array_filter( explode( "\n", $cons ) ) as $con ) : ?>
								<li><?php echo esc_html( trim( $con ) ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<!-- Details Table -->
				<h2 style="margin-top:36px;">Details</h2>
				<table style="width:100%;border-collapse:collapse;font-size:15px;">
					<tbody>
						<?php if ( $address ) : ?>
						<tr style="border-bottom:1px solid var(--ob-border);">
							<td style="padding:10px 0;font-weight:600;width:35%;">Address</td>
							<td style="padding:10px 0;"><?php echo esc_html( $address ); ?></td>
						</tr>
						<?php endif; ?>
						<?php if ( $phone ) : ?>
						<tr style="border-bottom:1px solid var(--ob-border);">
							<td style="padding:10px 0;font-weight:600;">Phone</td>
							<td style="padding:10px 0;"><a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></td>
						</tr>
						<?php endif; ?>
						<?php if ( $hours ) : ?>
						<tr style="border-bottom:1px solid var(--ob-border);">
							<td style="padding:10px 0;font-weight:600;">Hours</td>
							<td style="padding:10px 0;"><?php echo nl2br( esc_html( $hours ) ); ?></td>
						</tr>
						<?php endif; ?>
						<?php if ( $website ) : ?>
						<tr style="border-bottom:1px solid var(--ob-border);">
							<td style="padding:10px 0;font-weight:600;">Website</td>
							<td style="padding:10px 0;"><a href="<?php echo esc_url( $website ); ?>" target="_blank" rel="nofollow noopener"><?php echo esc_html( $website ); ?></a></td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>

			</article>

			<!-- Sidebar -->
			<aside style="width:260px;flex-shrink:0;position:sticky;top:24px;">

				<!-- Score Box -->
				<?php if ( $rating ) : ?>
				<div class="ob-score-box" style="margin-bottom:16px;">
					<div class="ob-score-box__number"><?php echo number_format( (float) $rating, 1 ); ?></div>
					<div class="ob-score-box__label">Our Score</div>
					<div style="margin-top:10px;"><?php echo ob_render_stars( $rating ); ?></div>
				</div>

				<!-- Score Breakdown -->
				<?php
				$scores = array(
					'Quality'    => $score_q,
					'Value'      => $score_v,
					'Experience' => $score_e,
				);
				foreach ( $scores as $lbl => $score ) :
					if ( ! $score ) continue;
					$pct = ( (float) $score / 5 ) * 100;
				?>
				<div class="ob-score-row" style="margin-bottom:10px;">
					<div style="font-size:13px;min-width:90px;"><?php echo esc_html( $lbl ); ?></div>
					<div style="flex:1;background:#e0e0e0;border-radius:4px;height:8px;overflow:hidden;">
						<div style="width:<?php echo $pct; ?>%;height:100%;background:var(--ob-primary);border-radius:4px;"></div>
					</div>
					<div style="font-size:13px;font-weight:700;min-width:26px;text-align:right;"><?php echo esc_html( $score ); ?></div>
				</div>
				<?php endforeach; ?>
				<?php endif; ?>

				<!-- CTA -->
				<?php if ( $aff_link ) : ?>
				<a href="<?php echo esc_url( $aff_link ); ?>"
				   class="ob-btn"
				   target="_blank"
				   rel="nofollow noopener sponsored"
				   style="width:100%;text-align:center;display:block;margin-top:20px;box-sizing:border-box;">
					<?php echo esc_html( $label['cta'] ); ?> →
				</a>
				<?php endif; ?>

				<?php if ( $phone ) : ?>
				<a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $phone ) ); ?>"
				   class="ob-btn-outline"
				   style="width:100%;text-align:center;display:block;margin-top:10px;box-sizing:border-box;">
					📞 <?php echo esc_html( $phone ); ?>
				</a>
				<?php endif; ?>

				<!-- Advertise / Claim Listing -->
				<div style="margin-top:24px;background:var(--ob-light);border-radius:var(--ob-radius);padding:16px;text-align:center;font-size:13px;color:#666;">
					<p style="margin:0 0 8px;">Is this your business?</p>
					<a href="/contact/" style="color:var(--ob-primary);font-weight:600;text-decoration:none;">Claim this listing →</a>
				</div>

			</aside>

		</div><!-- flex -->
	</div><!-- .ast-container -->
</div>

<?php
endwhile;
get_footer();
?>
