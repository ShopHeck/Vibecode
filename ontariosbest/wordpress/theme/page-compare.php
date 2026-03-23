<?php
/**
 * Template Name: Casino Comparison Tool
 * Slug: /casinos/compare/
 */

get_header();

// Get up to 3 casino IDs from query string: ?c1=ID&c2=ID&c3=ID
$ids = array();
foreach ( array( 'c1', 'c2', 'c3' ) as $key ) {
	if ( ! empty( $_GET[ $key ] ) ) {
		$id = absint( $_GET[ $key ] );
		if ( $id && get_post_type( $id ) === 'casino' ) {
			$ids[] = $id;
		}
	}
}

// All casinos for the picker
$all_casinos = get_posts( array(
	'post_type'      => 'casino',
	'posts_per_page' => -1,
	'orderby'        => 'meta_value_num',
	'meta_key'       => '_casino_overall_rating',
	'order'          => 'DESC',
) );

$fields = array(
	'Overall Rating'    => '_casino_overall_rating',
	'Welcome Bonus'     => '_casino_welcome_bonus',
	'Min. Deposit'      => '_casino_min_deposit',
	'Withdrawal Time'   => '_casino_withdrawal_time',
	'License'           => '_casino_license',
	'Established'       => '_casino_established',
	'Games Score'       => '_casino_score_games',
	'Bonuses Score'     => '_casino_score_bonuses',
	'UX Score'          => '_casino_score_ux',
	'Support Score'     => '_casino_score_support',
	'Payments Score'    => '_casino_score_payments',
);
?>

<div style="background:linear-gradient(135deg,#1a1a2e,#16213e);color:#fff;padding:40px 0;text-align:center;">
	<div class="ast-container">
		<h1 style="color:#fff;margin:0 0 8px;">Compare Ontario Casinos</h1>
		<p style="color:#ccd;margin:0;">Select up to 3 casinos to compare side by side</p>
	</div>
</div>

<div style="padding:32px 0 56px;">
	<div class="ast-container">

		<!-- Picker Form -->
		<form method="get" style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:24px;margin-bottom:36px;">
			<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;align-items:flex-end;">
				<?php foreach ( array( 'c1' => 'Casino 1', 'c2' => 'Casino 2', 'c3' => 'Casino 3 (Optional)' ) as $key => $label ) : ?>
				<div>
					<label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;"><?php echo esc_html( $label ); ?></label>
					<select name="<?php echo $key; ?>" style="width:100%;padding:10px 12px;border:1px solid var(--ob-border);border-radius:6px;font-size:14px;background:#fff;">
						<option value="">— Select —</option>
						<?php foreach ( $all_casinos as $casino ) : ?>
							<option value="<?php echo $casino->ID; ?>" <?php selected( in_array( $casino->ID, $ids ) && ( ( $key === 'c1' && isset( $ids[0] ) && $ids[0] === $casino->ID ) || ( $key === 'c2' && isset( $ids[1] ) && $ids[1] === $casino->ID ) || ( $key === 'c3' && isset( $ids[2] ) && $ids[2] === $casino->ID ) ) ); ?>>
								<?php echo esc_html( $casino->post_title ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endforeach; ?>
				<div>
					<button type="submit" class="ob-btn" style="width:100%;padding:11px;">Compare →</button>
				</div>
			</div>
		</form>

		<?php if ( count( $ids ) >= 2 ) :
			$casinos = array_map( 'get_post', $ids );
		?>

		<!-- Comparison Table -->
		<div style="overflow-x:auto;">
			<table style="width:100%;border-collapse:collapse;background:#fff;border-radius:var(--ob-radius);overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.06);">
				<thead>
					<tr style="background:var(--ob-dark);color:#fff;">
						<th style="padding:16px 20px;text-align:left;font-size:14px;width:180px;">Feature</th>
						<?php foreach ( $casinos as $casino ) : ?>
						<th style="padding:16px 20px;text-align:center;">
							<?php if ( has_post_thumbnail( $casino->ID ) ) : ?>
								<div style="margin-bottom:8px;">
									<?php echo get_the_post_thumbnail( $casino->ID, 'thumbnail', array( 'style' => 'max-width:80px;border-radius:6px;' ) ); ?>
								</div>
							<?php endif; ?>
							<a href="<?php echo get_permalink( $casino->ID ); ?>" style="color:#fff;text-decoration:none;font-size:15px;font-weight:700;">
								<?php echo esc_html( $casino->post_title ); ?>
							</a>
						</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$rating_fields = array( 'Games Score', 'Bonuses Score', 'UX Score', 'Support Score', 'Payments Score' );
					$row_alt       = false;
					foreach ( $fields as $label => $meta_key ) :
						$row_alt = ! $row_alt;
					?>
					<tr style="<?php echo $row_alt ? 'background:#f9f9f9;' : ''; ?>border-bottom:1px solid var(--ob-border);">
						<td style="padding:12px 20px;font-size:14px;font-weight:600;color:#444;"><?php echo esc_html( $label ); ?></td>
						<?php foreach ( $casinos as $casino ) :
							$value = get_post_meta( $casino->ID, $meta_key, true );
						?>
						<td style="padding:12px 20px;text-align:center;font-size:14px;">
							<?php if ( $meta_key === '_casino_overall_rating' && $value ) : ?>
								<span style="font-size:22px;font-weight:900;color:var(--ob-primary);"><?php echo number_format( (float) $value, 1 ); ?></span>
								<div><?php echo ob_render_stars( $value ); ?></div>
							<?php elseif ( in_array( $label, $rating_fields ) && $value ) : ?>
								<span style="font-size:16px;font-weight:700;"><?php echo esc_html( $value ); ?>/5</span>
								<div style="background:#e0e0e0;border-radius:4px;height:6px;margin-top:4px;overflow:hidden;">
									<div style="width:<?php echo ( (float) $value / 5 * 100 ); ?>%;height:100%;background:var(--ob-primary);"></div>
								</div>
							<?php elseif ( $value ) : ?>
								<?php echo esc_html( $value ); ?>
							<?php else : ?>
								<span style="color:#bbb;">—</span>
							<?php endif; ?>
						</td>
						<?php endforeach; ?>
					</tr>
					<?php endforeach; ?>

					<!-- Payment Methods row -->
					<tr style="border-bottom:1px solid var(--ob-border);">
						<td style="padding:12px 20px;font-size:14px;font-weight:600;color:#444;">Payment Methods</td>
						<?php foreach ( $casinos as $casino ) :
							$terms = get_the_terms( $casino->ID, 'payment_method' );
						?>
						<td style="padding:12px 20px;text-align:center;font-size:13px;">
							<?php echo $terms && ! is_wp_error( $terms ) ? esc_html( implode( ', ', wp_list_pluck( $terms, 'name' ) ) ) : '<span style="color:#bbb;">—</span>'; ?>
						</td>
						<?php endforeach; ?>
					</tr>

					<!-- CTA Row -->
					<tr style="background:#fffbf0;">
						<td style="padding:16px 20px;font-size:14px;font-weight:600;">Visit Casino</td>
						<?php foreach ( $casinos as $casino ) :
							$aff = get_post_meta( $casino->ID, '_casino_affiliate_url', true );
						?>
						<td style="padding:16px 20px;text-align:center;">
							<?php if ( $aff ) : ?>
								<a href="<?php echo esc_url( $aff ); ?>"
								   class="ob-btn"
								   target="_blank"
								   rel="nofollow noopener sponsored"
								   style="display:inline-block;">
									Play Now
								</a>
							<?php endif; ?>
							<br>
							<a href="<?php echo get_permalink( $casino->ID ); ?>" style="font-size:12px;color:#888;display:inline-block;margin-top:6px;">Full Review</a>
						</td>
						<?php endforeach; ?>
					</tr>
				</tbody>
			</table>
		</div>

		<p style="font-size:12px;color:#aaa;margin-top:16px;text-align:center;">
			19+ | Gambling can be addictive. Please play responsibly.
			<a href="https://connexontario.ca" target="_blank" rel="nofollow" style="color:#aaa;">ConnexOntario: 1-866-531-2600</a>
		</p>

		<?php else : ?>
			<div style="text-align:center;padding:48px 0;color:#888;">
				<p style="font-size:18px;">Select at least 2 casinos above to start comparing.</p>
			</div>
		<?php endif; ?>

	</div>
</div>

<?php get_footer(); ?>
