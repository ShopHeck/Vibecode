<?php
/**
 * Casino Archive Template
 * Handles the /casinos/ WordPress post type archive.
 * Loads the same template as template-casino-archive.php.
 */

get_header();
?>

<div class="ob-page-wrap">

	<div class="ob-page-header" style="background:var(--ob-dark);color:#fff;padding:48px 0;text-align:center;">
		<div class="ast-container">
			<h1 style="color:#fff;margin:0 0 12px;">Best Online Casinos in Ontario</h1>
			<p style="color:#ccc;font-size:17px;max-width:600px;margin:0 auto;">
				Reviewed and ranked by our experts. Only iGaming Ontario licensed operators.
			</p>
		</div>
	</div>

	<div class="ob-page-content" style="padding:40px 0;">
		<div class="ast-container">
			<div style="display:flex;gap:32px;align-items:flex-start;">

				<!-- Main Listing -->
				<div style="flex:1;min-width:0;">

					<?php
					$features    = get_terms( array( 'taxonomy' => 'casino_feature',  'hide_empty' => true ) );
					$pay_methods = get_terms( array( 'taxonomy' => 'payment_method',  'hide_empty' => true ) );

					// Active filter
					$active_feature = isset( $_GET['feature'] ) ? sanitize_text_field( $_GET['feature'] ) : '';
					$active_payment = isset( $_GET['payment'] ) ? sanitize_text_field( $_GET['payment'] ) : '';

					// Feature filter pills
					if ( ! empty( $features ) && ! is_wp_error( $features ) ) :
					?>
					<div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px;">
						<a href="<?php echo esc_url( get_post_type_archive_link( 'casino' ) ); ?>"
						   style="padding:6px 16px;border-radius:20px;font-size:13px;text-decoration:none;<?php echo ! $active_feature ? 'background:var(--ob-primary);color:#fff;' : 'background:#f0f0f0;color:#444;'; ?>">
							All
						</a>
						<?php foreach ( $features as $feat ) : ?>
						<a href="<?php echo esc_url( add_query_arg( 'feature', $feat->slug ) ); ?>"
						   style="padding:6px 16px;border-radius:20px;font-size:13px;text-decoration:none;<?php echo $active_feature === $feat->slug ? 'background:var(--ob-primary);color:#fff;' : 'background:#f0f0f0;color:#444;'; ?>">
							<?php echo esc_html( $feat->name ); ?> (<?php echo $feat->count; ?>)
						</a>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>

					<?php
					// Build query with optional taxonomy filters
					$args = array(
						'post_type'      => 'casino',
						'posts_per_page' => 20,
						'orderby'        => 'meta_value_num',
						'meta_key'       => '_casino_overall_rating',
						'order'          => 'DESC',
						'paged'          => max( 1, get_query_var( 'paged' ) ),
					);

					$tax_query = array();
					if ( $active_feature ) {
						$tax_query[] = array(
							'taxonomy' => 'casino_feature',
							'field'    => 'slug',
							'terms'    => $active_feature,
						);
					}
					if ( $active_payment ) {
						$tax_query[] = array(
							'taxonomy' => 'payment_method',
							'field'    => 'slug',
							'terms'    => $active_payment,
						);
					}
					if ( ! empty( $tax_query ) ) {
						$args['tax_query'] = $tax_query;
					}

					$casino_query = new WP_Query( $args );
					$rank = 1;

					if ( $casino_query->have_posts() ) :
						while ( $casino_query->have_posts() ) : $casino_query->the_post();
							$rating   = ob_casino_meta( '_casino_overall_rating' );
							$bonus    = ob_casino_meta( '_casino_welcome_bonus' );
							$aff_link = ob_casino_meta( '_casino_affiliate_url' );
							$badge    = ob_casino_meta( '_casino_badge' );
					?>

					<div class="ob-casino-card">
						<div class="ob-casino-card__rank">#<?php echo $rank; ?></div>
						<div class="ob-casino-card__logo">
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'casino-logo', array( 'alt' => get_the_title() ) ); ?>
								</a>
							<?php endif; ?>
						</div>
						<div class="ob-casino-card__info">
							<?php if ( $badge ) : ?>
								<span style="background:var(--ob-primary);color:#fff;font-size:11px;font-weight:700;padding:2px 8px;border-radius:3px;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px;display:inline-block;">
									<?php echo esc_html( $badge ); ?>
								</span>
							<?php endif; ?>
							<h2 class="ob-casino-card__name">
								<a href="<?php the_permalink(); ?>" style="color:var(--ob-text);text-decoration:none;"><?php the_title(); ?></a>
							</h2>
							<?php if ( $rating ) echo ob_render_stars( $rating ); ?>
							<?php if ( $bonus ) : ?>
								<p class="ob-casino-card__bonus">Welcome Offer: <strong><?php echo esc_html( $bonus ); ?></strong></p>
							<?php endif; ?>
						</div>
						<div class="ob-casino-card__cta">
							<?php if ( $aff_link ) : ?>
								<a href="<?php echo esc_url( $aff_link ); ?>"
								   class="ob-btn"
								   target="_blank"
								   rel="nofollow noopener sponsored">
									Play Now
								</a>
							<?php endif; ?>
							<br>
							<a href="<?php the_permalink(); ?>" style="font-size:13px;color:#888;display:inline-block;margin-top:8px;">Read Review</a>
						</div>
					</div>

					<?php
						$rank++;
						endwhile;
						wp_reset_postdata();
					else :
						echo '<p style="color:#888;">No casinos found.</p>';
					endif;
					?>

					<!-- Pagination -->
					<div style="margin-top:28px;">
						<?php
						echo paginate_links( array(
							'total'   => $casino_query->max_num_pages,
							'current' => max( 1, get_query_var( 'paged' ) ),
						) );
						?>
					</div>

					<p style="font-size:12px;color:#aaa;margin-top:16px;">
						19+ | Gambling can be addictive. Please play responsibly.
						<a href="/responsible-gambling/" style="color:#aaa;">Learn More</a>
					</p>

				</div><!-- main -->

				<!-- Sidebar -->
				<aside style="width:280px;flex-shrink:0;">

					<?php if ( ! empty( $features ) && ! is_wp_error( $features ) ) : ?>
					<div style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:20px;margin-bottom:24px;">
						<h4 style="margin:0 0 14px;font-size:15px;">Filter by Feature</h4>
						<ul style="list-style:none;margin:0;padding:0;">
							<?php foreach ( $features as $feat ) : ?>
							<li style="margin-bottom:6px;">
								<a href="<?php echo esc_url( add_query_arg( 'feature', $feat->slug ) ); ?>"
								   style="font-size:14px;color:<?php echo $active_feature === $feat->slug ? 'var(--ob-primary)' : 'var(--ob-text)'; ?>;text-decoration:none;font-weight:<?php echo $active_feature === $feat->slug ? '700' : '400'; ?>;">
									<?php echo esc_html( $feat->name ); ?>
									<span style="color:#aaa;font-size:12px;">(<?php echo $feat->count; ?>)</span>
								</a>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $pay_methods ) && ! is_wp_error( $pay_methods ) ) : ?>
					<div style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:20px;margin-bottom:24px;">
						<h4 style="margin:0 0 14px;font-size:15px;">Filter by Payment</h4>
						<ul style="list-style:none;margin:0;padding:0;">
							<?php foreach ( $pay_methods as $pm ) : ?>
							<li style="margin-bottom:6px;">
								<a href="<?php echo esc_url( add_query_arg( 'payment', $pm->slug ) ); ?>"
								   style="font-size:14px;color:<?php echo $active_payment === $pm->slug ? 'var(--ob-primary)' : 'var(--ob-text)'; ?>;text-decoration:none;font-weight:<?php echo $active_payment === $pm->slug ? '700' : '400'; ?>;">
									<?php echo esc_html( $pm->name ); ?>
									<span style="color:#aaa;font-size:12px;">(<?php echo $pm->count; ?>)</span>
								</a>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>

					<div style="background:var(--ob-dark);color:#fff;border-radius:var(--ob-radius);padding:20px;text-align:center;">
						<p style="font-size:13px;margin:0 0 8px;opacity:0.7;">19+ | Please gamble responsibly</p>
						<a href="/responsible-gambling/" style="color:var(--ob-primary);font-size:13px;">Learn More</a>
					</div>

					<div style="margin-top:20px;background:linear-gradient(135deg,var(--ob-dark),#0f3460);color:#fff;border-radius:var(--ob-radius);padding:20px;text-align:center;">
						<p style="font-size:14px;font-weight:700;margin:0 0 6px;">Want to be listed?</p>
						<p style="font-size:12px;color:#ccd;margin:0 0 14px;">Sponsored placements available for Ontario-licensed casinos.</p>
						<a href="/advertise/" class="ob-btn" style="font-size:13px;padding:9px 18px;display:inline-block;">Advertise</a>
					</div>

				</aside>

			</div><!-- flex -->
		</div><!-- .ast-container -->
	</div><!-- .ob-page-content -->

</div><!-- .ob-page-wrap -->

<?php get_footer(); ?>
