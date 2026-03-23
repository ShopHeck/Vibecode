<?php
/**
 * Template Name: Advertise / Sponsorship Page
 * Slug: /advertise/
 */

get_header();
?>

<!-- Hero -->
<section style="background:linear-gradient(135deg,#1a1a2e,#0f3460);color:#fff;padding:64px 0;text-align:center;">
	<div class="ast-container">
		<p style="font-size:12px;letter-spacing:2px;text-transform:uppercase;color:var(--ob-primary);margin:0 0 10px;">Work With Us</p>
		<h1 style="color:#fff;margin:0 0 14px;">Reach Ontario's Best Audience</h1>
		<p style="color:#ccd;font-size:17px;max-width:580px;margin:0 auto;">
			OntariosBest.com connects Ontario consumers with the best casinos, restaurants, travel, entertainment, and services. Put your brand in front of a qualified, local audience.
		</p>
	</div>
</section>

<!-- Stats Bar -->
<div style="background:var(--ob-primary);color:#fff;padding:20px 0;">
	<div class="ast-container">
		<div style="display:flex;justify-content:center;gap:48px;flex-wrap:wrap;text-align:center;">
			<?php
			$stats = array(
				array( '100K+', 'Monthly Visitors' ),
				array( '500+',  'Listings Reviewed' ),
				array( '90%',   'Ontario-Based Traffic' ),
				array( '4.8/5', 'Avg. Listing Rating' ),
			);
			foreach ( $stats as $s ) :
			?>
			<div>
				<div style="font-size:26px;font-weight:900;"><?php echo $s[0]; ?></div>
				<div style="font-size:13px;opacity:0.85;"><?php echo $s[1]; ?></div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<div style="padding:56px 0;">
	<div class="ast-container">

		<!-- Sponsorship Tiers -->
		<div style="text-align:center;margin-bottom:40px;">
			<h2 style="font-size:28px;font-weight:800;margin:0 0 8px;">Sponsorship Options</h2>
			<p style="color:#666;max-width:500px;margin:0 auto;">Flexible options to match your goals and budget</p>
		</div>

		<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;margin-bottom:56px;">

			<!-- Tier 1: Featured Listing -->
			<div style="background:#fff;border:2px solid var(--ob-border);border-radius:var(--ob-radius);padding:28px;text-align:center;">
				<div style="font-size:32px;margin-bottom:12px;">📌</div>
				<h3 style="font-size:20px;font-weight:800;margin:0 0 8px;">Featured Listing</h3>
				<p style="font-size:13px;color:#666;margin:0 0 16px;">Your listing pinned to the top of your category with a gold "Featured" badge. Maximum visibility to active searchers.</p>
				<ul style="list-style:none;padding:0;margin:0 0 20px;font-size:14px;text-align:left;">
					<li style="padding:5px 0;border-bottom:1px solid #f0f0f0;">✅ Pinned at top of category archive</li>
					<li style="padding:5px 0;border-bottom:1px solid #f0f0f0;">✅ Gold "Featured" badge</li>
					<li style="padding:5px 0;border-bottom:1px solid #f0f0f0;">✅ Included in homepage "Featured Listings"</li>
					<li style="padding:5px 0;">✅ Monthly performance report</li>
				</ul>
				<a href="#inquiry" class="ob-btn" style="display:block;text-align:center;">Get a Quote</a>
			</div>

			<!-- Tier 2: Sponsored Content -->
			<div style="background:#fff;border:2px solid var(--ob-primary);border-radius:var(--ob-radius);padding:28px;text-align:center;position:relative;">
				<div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:var(--ob-primary);color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:12px;text-transform:uppercase;letter-spacing:0.5px;">Most Popular</div>
				<div style="font-size:32px;margin-bottom:12px;">📝</div>
				<h3 style="font-size:20px;font-weight:800;margin:0 0 8px;">Sponsored Review</h3>
				<p style="font-size:13px;color:#666;margin:0 0 16px;">A full editorial review of your business written and published by our team. Labelled as sponsored and permanently indexed on Google.</p>
				<ul style="list-style:none;padding:0;margin:0 0 20px;font-size:14px;text-align:left;">
					<li style="padding:5px 0;border-bottom:1px solid #f0f0f0;">✅ Full review page (500–1000 words)</li>
					<li style="padding:5px 0;border-bottom:1px solid #f0f0f0;">✅ Score breakdown + pros/cons</li>
					<li style="padding:5px 0;border-bottom:1px solid #f0f0f0;">✅ Featured on category archive + homepage</li>
					<li style="padding:5px 0;border-bottom:1px solid #f0f0f0;">✅ Schema markup for Google rich results</li>
					<li style="padding:5px 0;">✅ Quarterly refresh included</li>
				</ul>
				<a href="#inquiry" class="ob-btn" style="display:block;text-align:center;">Get a Quote</a>
			</div>

			<!-- Tier 3: Lead Gen -->
			<div style="background:#fff;border:2px solid var(--ob-border);border-radius:var(--ob-radius);padding:28px;text-align:center;">
				<div style="font-size:32px;margin-bottom:12px;">📊</div>
				<h3 style="font-size:20px;font-weight:800;margin:0 0 8px;">Lead Generation</h3>
				<p style="font-size:13px;color:#666;margin:0 0 16px;">We capture qualified Ontario leads for your business through custom landing pages, forms, and click-to-call placements. You pay per lead.</p>
				<ul style="list-style:none;padding:0;margin:0 0 20px;font-size:14px;text-align:left;">
					<li style="padding:5px 0;border-bottom:1px solid #f0f0f0;">✅ Custom lead capture form</li>
					<li style="padding:5px 0;border-bottom:1px solid #f0f0f0;">✅ Click-to-call button placement</li>
					<li style="padding:5px 0;border-bottom:1px solid #f0f0f0;">✅ Leads delivered by email in real time</li>
					<li style="padding:5px 0;">✅ Pay per qualified lead (CPL model)</li>
				</ul>
				<a href="#inquiry" class="ob-btn" style="display:block;text-align:center;">Get a Quote</a>
			</div>

		</div>

		<!-- Categories We Cover -->
		<div style="background:var(--ob-light);border-radius:var(--ob-radius);padding:36px;margin-bottom:56px;text-align:center;">
			<h2 style="font-size:22px;font-weight:800;margin:0 0 8px;">Categories We Cover</h2>
			<p style="color:#666;margin:0 0 24px;">We accept sponsorships across all our content categories</p>
			<div style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;">
				<?php
				$cats = array( '🎰 Online Casinos', '✈️ Travel & Tourism', '🍽️ Restaurants', '🎭 Entertainment', '🔧 Services', '🛍️ Shopping' );
				foreach ( $cats as $cat ) :
				?>
				<span style="background:#fff;border:1px solid var(--ob-border);padding:8px 16px;border-radius:20px;font-size:14px;font-weight:600;">
					<?php echo esc_html( $cat ); ?>
				</span>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- Inquiry Form -->
		<div id="inquiry" style="max-width:640px;margin:0 auto;">
			<div style="text-align:center;margin-bottom:28px;">
				<h2 style="font-size:26px;font-weight:800;margin:0 0 8px;">Get in Touch</h2>
				<p style="color:#666;margin:0;">Tell us about your business and we'll put together a custom proposal.</p>
			</div>

			<?php
			// WPForms shortcode for the advertising inquiry form (form ID configured in WPForms)
			// Replace 123 with the actual form ID after creating it in WPForms
			if ( function_exists( 'wpforms' ) ) :
				echo do_shortcode( '[wpforms id="ADVERTISE_FORM_ID"]' );
			else :
			?>
			<form style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:28px;">
				<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
					<div>
						<label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Your Name *</label>
						<input type="text" style="width:100%;padding:10px 12px;border:1px solid var(--ob-border);border-radius:6px;font-size:14px;box-sizing:border-box;" required>
					</div>
					<div>
						<label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Business Name *</label>
						<input type="text" style="width:100%;padding:10px 12px;border:1px solid var(--ob-border);border-radius:6px;font-size:14px;box-sizing:border-box;" required>
					</div>
				</div>
				<div style="margin-bottom:16px;">
					<label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Email *</label>
					<input type="email" style="width:100%;padding:10px 12px;border:1px solid var(--ob-border);border-radius:6px;font-size:14px;box-sizing:border-box;" required>
				</div>
				<div style="margin-bottom:16px;">
					<label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Category</label>
					<select style="width:100%;padding:10px 12px;border:1px solid var(--ob-border);border-radius:6px;font-size:14px;background:#fff;">
						<option>Online Casino</option>
						<option>Travel & Tourism</option>
						<option>Restaurant</option>
						<option>Entertainment</option>
						<option>Service</option>
						<option>Shopping</option>
						<option>Other</option>
					</select>
				</div>
				<div style="margin-bottom:16px;">
					<label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Sponsorship Interest</label>
					<select style="width:100%;padding:10px 12px;border:1px solid var(--ob-border);border-radius:6px;font-size:14px;background:#fff;">
						<option>Featured Listing</option>
						<option>Sponsored Review</option>
						<option>Lead Generation</option>
						<option>Not Sure — Tell Me More</option>
					</select>
				</div>
				<div style="margin-bottom:20px;">
					<label style="font-size:13px;font-weight:600;display:block;margin-bottom:6px;">Tell us about your business</label>
					<textarea rows="4" style="width:100%;padding:10px 12px;border:1px solid var(--ob-border);border-radius:6px;font-size:14px;box-sizing:border-box;resize:vertical;"></textarea>
				</div>
				<button type="submit" class="ob-btn" style="width:100%;font-size:16px;padding:14px;">Send Inquiry →</button>
				<p style="font-size:12px;color:#aaa;text-align:center;margin-top:12px;">We typically respond within 1 business day.</p>
			</form>
			<?php endif; ?>
		</div>

	</div><!-- .ast-container -->
</div>

<?php get_footer(); ?>
