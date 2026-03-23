<?php
/**
 * Template Name: Contact
 */

get_header();
?>

<section style="background:linear-gradient(135deg,#1a1a2e,#16213e);color:#fff;padding:48px 0;text-align:center;">
	<div class="ast-container">
		<h1 style="color:#fff;margin:0 0 10px;">Contact Us</h1>
		<p style="color:#ccd;font-size:16px;margin:0;">Questions, corrections, or partnership inquiries — we're here.</p>
	</div>
</section>

<div style="padding:48px 0 64px;">
	<div class="ast-container">
		<div style="display:grid;grid-template-columns:1fr 1.6fr;gap:48px;align-items:flex-start;max-width:960px;margin:0 auto;">

			<!-- Contact Info -->
			<div>
				<h2 style="font-size:20px;font-weight:800;margin:0 0 20px;">Get in Touch</h2>

				<div style="display:flex;flex-direction:column;gap:16px;margin-bottom:32px;">
					<?php
					$info = array(
						array( '📧', 'Email',          'hello@ontariosbest.com',      'mailto:hello@ontariosbest.com' ),
						array( '🕐', 'Response Time',  'Typically within 1 business day', null ),
					);
					foreach ( $info as $item ) : ?>
					<div style="display:flex;gap:14px;align-items:flex-start;">
						<span style="font-size:22px;flex-shrink:0;margin-top:2px;"><?php echo $item[0]; ?></span>
						<div>
							<div style="font-size:12px;text-transform:uppercase;letter-spacing:0.5px;color:#888;font-weight:600;margin-bottom:2px;"><?php echo esc_html( $item[1] ); ?></div>
							<?php if ( $item[3] ) : ?>
								<a href="<?php echo esc_attr( $item[3] ); ?>" style="font-size:15px;color:var(--ob-primary);text-decoration:none;"><?php echo esc_html( $item[2] ); ?></a>
							<?php else : ?>
								<div style="font-size:15px;color:var(--ob-text);"><?php echo esc_html( $item[2] ); ?></div>
							<?php endif; ?>
						</div>
					</div>
					<?php endforeach; ?>
				</div>

				<!-- What to contact for -->
				<div style="background:var(--ob-light);border-radius:var(--ob-radius);padding:20px;">
					<h3 style="font-size:15px;font-weight:700;margin:0 0 12px;">What we can help with:</h3>
					<ul style="list-style:none;margin:0;padding:0;">
						<?php
						$topics = array(
							'Corrections or inaccurate information',
							'Listing a business or claiming a listing',
							'Advertising and sponsorship',
							'Press and media inquiries',
							'General questions',
						);
						foreach ( $topics as $t ) : ?>
						<li style="font-size:14px;color:#555;padding:5px 0;border-bottom:1px solid var(--ob-border);display:flex;align-items:center;gap:8px;">
							<span style="color:var(--ob-primary);font-weight:700;">›</span> <?php echo esc_html( $t ); ?>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>

				<!-- Advertising shortcut -->
				<div style="margin-top:20px;padding:16px;background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);text-align:center;">
					<p style="font-size:13px;color:#666;margin:0 0 10px;">Interested in advertising?</p>
					<a href="/advertise/" class="ob-btn" style="font-size:13px;padding:9px 20px;display:inline-block;">View Advertising Options →</a>
				</div>
			</div>

			<!-- Contact Form -->
			<div>
				<h2 style="font-size:20px;font-weight:800;margin:0 0 20px;">Send a Message</h2>
				<?php
				// WPForms shortcode — replace CONTACT_FORM_ID with actual form ID after creating in WPForms
				if ( function_exists( 'wpforms' ) ) :
					echo do_shortcode( '[wpforms id="CONTACT_FORM_ID"]' );
				else :
				?>
				<form style="display:flex;flex-direction:column;gap:16px;" method="post">
					<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
						<div>
							<label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Name *</label>
							<input type="text" name="name" required style="width:100%;padding:10px 12px;border:1px solid var(--ob-border);border-radius:6px;font-size:14px;box-sizing:border-box;">
						</div>
						<div>
							<label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Email *</label>
							<input type="email" name="email" required style="width:100%;padding:10px 12px;border:1px solid var(--ob-border);border-radius:6px;font-size:14px;box-sizing:border-box;">
						</div>
					</div>
					<div>
						<label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Subject *</label>
						<select name="subject" style="width:100%;padding:10px 12px;border:1px solid var(--ob-border);border-radius:6px;font-size:14px;background:#fff;">
							<option value="">— Select a topic —</option>
							<option>Correction / Inaccurate information</option>
							<option>Claim or add a listing</option>
							<option>Advertising inquiry</option>
							<option>Press / Media</option>
							<option>General question</option>
							<option>Other</option>
						</select>
					</div>
					<div>
						<label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Message *</label>
						<textarea name="message" rows="6" required style="width:100%;padding:10px 12px;border:1px solid var(--ob-border);border-radius:6px;font-size:14px;box-sizing:border-box;resize:vertical;"></textarea>
					</div>
					<button type="submit" class="ob-btn" style="font-size:15px;padding:13px;align-self:flex-start;min-width:160px;">Send Message →</button>
				</form>
				<?php endif; ?>
			</div>

		</div><!-- grid -->
	</div>
</div>

<style>
@media (max-width: 768px) {
	.ast-container > div {
		grid-template-columns: 1fr !important;
	}
}
</style>

<?php get_footer(); ?>
