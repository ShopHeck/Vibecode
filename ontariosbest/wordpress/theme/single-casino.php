<?php
/**
 * Template: Single Casino Review
 */

get_header();

while ( have_posts() ) : the_post();

$rating         = ob_casino_meta( '_casino_overall_rating' );
$bonus          = ob_casino_meta( '_casino_welcome_bonus' );
$aff_link       = ob_casino_meta( '_casino_affiliate_url' );
$established    = ob_casino_meta( '_casino_established' );
$license        = ob_casino_meta( '_casino_license' );
$min_deposit    = ob_casino_meta( '_casino_min_deposit' );
$withdrawal     = ob_casino_meta( '_casino_withdrawal_time' );
$score_games    = ob_casino_meta( '_casino_score_games' );
$score_bonuses  = ob_casino_meta( '_casino_score_bonuses' );
$score_ux       = ob_casino_meta( '_casino_score_ux' );
$score_support  = ob_casino_meta( '_casino_score_support' );
$score_payments = ob_casino_meta( '_casino_score_payments' );
$pros           = ob_casino_meta( '_casino_pros' );   // stored as newline-separated text
$cons           = ob_casino_meta( '_casino_cons' );

?>

<div class="ob-page-wrap">
	<div class="ast-container" style="padding-top:32px;padding-bottom:48px;">

		<div style="display:flex;gap:32px;align-items:flex-start;">

			<!-- Main Content -->
			<article style="flex:1;min-width:0;">

				<!-- Header -->
				<div style="display:flex;align-items:center;gap:20px;margin-bottom:24px;">
					<?php if ( has_post_thumbnail() ) : ?>
						<div style="width:120px;flex-shrink:0;">
							<?php the_post_thumbnail( 'medium', array( 'style' => 'max-width:100%;border-radius:8px;' ) ); ?>
						</div>
					<?php endif; ?>
					<div>
						<h1 style="margin:0 0 8px;"><?php the_title(); ?> Review</h1>
						<?php if ( $rating ) : ?>
							<?php echo ob_render_stars( $rating ); ?>
						<?php endif; ?>
						<?php if ( $established ) : ?>
							<p style="font-size:14px;color:#888;margin:6px 0 0;">Established <?php echo esc_html( $established ); ?></p>
						<?php endif; ?>
					</div>
				</div>

				<!-- CTA Bar -->
				<?php if ( $bonus || $aff_link ) : ?>
				<div style="background:#fffbf0;border:1px solid var(--ob-primary);border-radius:var(--ob-radius);padding:16px 20px;display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
					<?php if ( $bonus ) : ?>
						<div>
							<div style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#888;">Welcome Offer</div>
							<div style="font-size:20px;font-weight:700;color:var(--ob-accent);"><?php echo esc_html( $bonus ); ?></div>
						</div>
					<?php endif; ?>
					<?php if ( $aff_link ) : ?>
						<a href="<?php echo esc_url( $aff_link ); ?>"
						   class="ob-btn"
						   target="_blank"
						   rel="nofollow noopener sponsored"
						   style="font-size:16px;padding:14px 32px;">
							Claim Bonus →
						</a>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<!-- Affiliate Disclosure -->
				<p class="affiliate-disclosure">
					<strong>Disclosure:</strong> OntariosBest.com may earn a commission when you click links on this page. This helps us keep the site free. We only recommend Ontario-licensed operators.
				</p>

				<!-- Review Body -->
				<div class="ob-review-content">
					<?php the_content(); ?>
				</div>

				<!-- Pros / Cons -->
				<?php if ( $pros || $cons ) : ?>
				<div class="ob-pros-cons">
					<?php if ( $pros ) : ?>
					<div class="ob-pros">
						<h4>Pros</h4>
						<ul>
							<?php foreach ( explode( "\n", $pros ) as $pro ) : ?>
								<?php if ( trim( $pro ) ) : ?>
									<li><?php echo esc_html( trim( $pro ) ); ?></li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>
					<?php if ( $cons ) : ?>
					<div class="ob-cons">
						<h4>Cons</h4>
						<ul>
							<?php foreach ( explode( "\n", $cons ) as $con ) : ?>
								<?php if ( trim( $con ) ) : ?>
									<li><?php echo esc_html( trim( $con ) ); ?></li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<!-- Quick Facts Table -->
				<h2>Quick Facts</h2>
				<table style="width:100%;border-collapse:collapse;font-size:15px;">
					<tbody>
						<?php if ( $license ) : ?>
						<tr style="border-bottom:1px solid var(--ob-border);">
							<td style="padding:10px 0;font-weight:600;width:40%;">License</td>
							<td style="padding:10px 0;"><?php echo esc_html( $license ); ?></td>
						</tr>
						<?php endif; ?>
						<?php if ( $min_deposit ) : ?>
						<tr style="border-bottom:1px solid var(--ob-border);">
							<td style="padding:10px 0;font-weight:600;">Min. Deposit</td>
							<td style="padding:10px 0;"><?php echo esc_html( $min_deposit ); ?></td>
						</tr>
						<?php endif; ?>
						<?php if ( $withdrawal ) : ?>
						<tr style="border-bottom:1px solid var(--ob-border);">
							<td style="padding:10px 0;font-weight:600;">Withdrawal Time</td>
							<td style="padding:10px 0;"><?php echo esc_html( $withdrawal ); ?></td>
						</tr>
						<?php endif; ?>
						<?php
						$payment_terms = get_the_terms( get_the_ID(), 'payment_method' );
						if ( $payment_terms && ! is_wp_error( $payment_terms ) ) :
						?>
						<tr style="border-bottom:1px solid var(--ob-border);">
							<td style="padding:10px 0;font-weight:600;">Payment Methods</td>
							<td style="padding:10px 0;"><?php echo esc_html( implode( ', ', wp_list_pluck( $payment_terms, 'name' ) ) ); ?></td>
						</tr>
						<?php endif; ?>
					</tbody>
				</table>

			</article>

			<!-- Sidebar Score Box -->
			<aside style="width:260px;flex-shrink:0;position:sticky;top:24px;">

				<?php if ( $rating ) : ?>
				<div class="ob-score-box" style="margin-bottom:16px;">
					<div class="ob-score-box__number"><?php echo number_format( (float) $rating, 1 ); ?></div>
					<div class="ob-score-box__label">Overall Score</div>
					<div style="margin-top:12px;">
						<?php echo ob_render_stars( $rating ); ?>
					</div>
				</div>
				<?php endif; ?>

				<!-- Score Breakdown -->
				<?php
				$scores = array(
					'Games'           => $score_games,
					'Bonuses'         => $score_bonuses,
					'User Experience' => $score_ux,
					'Support'         => $score_support,
					'Payments'        => $score_payments,
				);
				foreach ( $scores as $label => $score ) :
					if ( ! $score ) continue;
					$pct = ( (float) $score / 5 ) * 100;
				?>
				<div class="ob-score-row" style="margin-bottom:10px;">
					<div class="ob-score-row__label" style="font-size:13px;min-width:120px;"><?php echo esc_html( $label ); ?></div>
					<div class="ob-score-row__bar-wrap" style="flex:1;background:#e0e0e0;border-radius:4px;height:8px;overflow:hidden;">
						<div class="ob-score-row__bar" style="width:<?php echo $pct; ?>%;height:100%;background:var(--ob-primary);border-radius:4px;"></div>
					</div>
					<div class="ob-score-row__value" style="font-size:13px;font-weight:700;min-width:28px;text-align:right;"><?php echo esc_html( $score ); ?></div>
				</div>
				<?php endforeach; ?>

				<?php if ( $aff_link ) : ?>
				<a href="<?php echo esc_url( $aff_link ); ?>"
				   class="ob-btn"
				   target="_blank"
				   rel="nofollow noopener sponsored"
				   style="width:100%;text-align:center;display:block;margin-top:20px;box-sizing:border-box;">
					Visit Casino →
				</a>
				<?php endif; ?>

				<p style="font-size:11px;color:#aaa;text-align:center;margin-top:10px;">
					19+ | Gamble Responsibly<br>
					<a href="/responsible-gambling/" style="color:#aaa;">Learn More</a>
				</p>

			</aside>

		</div><!-- flex -->
	</div><!-- .ast-container -->
</div><!-- .ob-page-wrap -->

<?php
endwhile;
get_footer();
?>
