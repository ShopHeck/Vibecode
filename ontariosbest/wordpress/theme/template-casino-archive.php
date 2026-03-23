<?php
/**
 * Template: Casino Directory Index
 * Usage: Set as page template or used by archive-casino.php
 */

get_header();
?>

<div class="ob-page-wrap">

	<div class="ob-page-header" style="background:var(--ob-dark);color:#fff;padding:48px 0;text-align:center;">
		<div class="ast-container">
			<h1 style="color:#fff;margin:0 0 12px;">Best Online Casinos in Ontario</h1>
			<p style="color:#ccc;font-size:17px;max-width:600px;margin:0 auto;">
				Reviewed and ranked by our experts. Only licensed Ontario operators.
			</p>
		</div>
	</div>

	<div class="ob-page-content" style="padding:40px 0;">
		<div class="ast-container">
			<div style="display:flex;gap:32px;align-items:flex-start;">

				<!-- Main Listing -->
				<div style="flex:1;min-width:0;">

					<?php
					// Filter bar
					$features    = get_terms( array( 'taxonomy' => 'casino_feature', 'hide_empty' => true ) );
					$pay_methods = get_terms( array( 'taxonomy' => 'payment_method', 'hide_empty' => true ) );
					?>

					<!-- Listings -->
					<?php
					$args = array(
						'post_type'      => 'casino',
						'posts_per_page' => 20,
						'orderby'        => 'meta_value_num',
						'meta_key'       => '_casino_overall_rating',
						'order'          => 'DESC',
					);

					// Apply taxonomy filters from query string
					$tax_query = array();
					if ( ! empty( $_GET['feature'] ) ) {
						$tax_query[] = array(
							'taxonomy' => 'casino_feature',
							'field'    => 'slug',
							'terms'    => sanitize_text_field( $_GET['feature'] ),
						);
					}
					if ( ! empty( $_GET['payment'] ) ) {
						$tax_query[] = array(
							'taxonomy' => 'payment_method',
							'field'    => 'slug',
							'terms'    => sanitize_text_field( $_GET['payment'] ),
						);
					}
					if ( ! empty( $tax_query ) ) {
						$args['tax_query'] = $tax_query;
					}

					$casino_query = new WP_Query( $args );
					$rank = 1;

					if ( $casino_query->have_posts() ) :
						while ( $casino_query->have_posts() ) : $casino_query->the_post();

							$rating     = ob_casino_meta( '_casino_overall_rating' );
							$bonus      = ob_casino_meta( '_casino_welcome_bonus' );
							$aff_link   = ob_casino_meta( '_casino_affiliate_url' );
							$badge      = ob_casino_meta( '_casino_badge' ); // e.g. "Editor's Choice", "Best Bonus"
							?>

							<div class="ob-casino-card">

								<div class="ob-casino-card__rank">#<?php echo $rank; ?></div>

								<div class="ob-casino-card__logo">
									<?php if ( has_post_thumbnail() ) : ?>
										<a href="<?php the_permalink(); ?>">
											<?php the_post_thumbnail( 'thumbnail', array( 'alt' => get_the_title() ) ); ?>
										</a>
									<?php endif; ?>
								</div>

								<div class="ob-casino-card__info">
									<?php if ( $badge ) : ?>
										<span style="background:var(--ob-primary);color:#fff;font-size:11px;font-weight:700;padding:2px 8px;border-radius:3px;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:6px;display:inline-block;">
											<?php echo esc_html( $badge ); ?>
										</span>
									<?php endif; ?>

									<h3 class="ob-casino-card__name">
										<a href="<?php the_permalink(); ?>" style="color:var(--ob-text);text-decoration:none;">
											<?php the_title(); ?>
										</a>
									</h3>

									<?php if ( $rating ) : ?>
										<?php echo ob_render_stars( $rating ); ?>
									<?php endif; ?>

									<?php if ( $bonus ) : ?>
										<p class="ob-casino-card__bonus">
											Welcome Offer: <strong><?php echo esc_html( $bonus ); ?></strong>
										</p>
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
									<a href="<?php the_permalink(); ?>"
									   style="font-size:13px;color:#888;display:inline-block;margin-top:8px;">
										Read Review
									</a>
								</div>

							</div>

							<?php
							$rank++;
						endwhile;
						wp_reset_postdata();
					else :
						echo '<p>No casinos found.</p>';
					endif;
					?>

				</div><!-- .main -->

				<!-- Sidebar -->
				<aside style="width:280px;flex-shrink:0;">

					<div style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:20px;margin-bottom:24px;">
						<h4 style="margin:0 0 14px;font-size:15px;">Filter by Feature</h4>
						<?php if ( ! empty( $features ) ) : ?>
							<ul style="list-style:none;margin:0;padding:0;">
								<?php foreach ( $features as $feature ) : ?>
									<li style="margin-bottom:6px;">
										<a href="<?php echo esc_url( add_query_arg( 'feature', $feature->slug ) ); ?>"
										   style="font-size:14px;color:var(--ob-text);text-decoration:none;">
											<?php echo esc_html( $feature->name ); ?>
											<span style="color:#aaa;font-size:12px;">(<?php echo $feature->count; ?>)</span>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>

					<div style="background:var(--ob-dark);color:#fff;border-radius:var(--ob-radius);padding:20px;text-align:center;">
						<p style="font-size:13px;margin:0 0 8px;opacity:0.7;">19+ | Please gamble responsibly</p>
						<a href="/responsible-gambling/" style="color:var(--ob-primary);font-size:13px;">Learn More</a>
					</div>

				</aside>

			</div><!-- flex -->
		</div><!-- .ast-container -->
	</div><!-- .ob-page-content -->

</div><!-- .ob-page-wrap -->

<?php get_footer(); ?>
