<?php
/**
 * Template Name: About
 */

get_header();
?>

<section style="background:linear-gradient(135deg,#1a1a2e,#16213e);color:#fff;padding:56px 0;text-align:center;">
	<div class="ast-container">
		<p style="font-size:12px;letter-spacing:2px;text-transform:uppercase;color:var(--ob-primary);margin:0 0 10px;font-weight:600;">About Us</p>
		<h1 style="color:#fff;margin:0 0 14px;font-size:clamp(28px,4vw,44px);">Ontario's Most Trusted Discovery Guide</h1>
		<p style="color:#ccd;font-size:17px;max-width:600px;margin:0 auto;">
			We help Ontario residents and visitors find the best casinos, restaurants, travel experiences, entertainment, and services — independently reviewed and honestly ranked.
		</p>
	</div>
</section>

<div style="padding:56px 0 64px;">
	<div class="ast-container">

		<!-- Mission -->
		<div style="max-width:720px;margin:0 auto 56px;text-align:center;">
			<h2 style="font-size:26px;font-weight:800;margin:0 0 16px;">Our Mission</h2>
			<p style="font-size:17px;line-height:1.8;color:#444;">
				Ontario is packed with incredible experiences — but finding the best ones takes time. OntariosBest.com cuts through the noise by doing the research for you. We review, score, and rank the province's top experiences so you can make confident decisions, fast.
			</p>
		</div>

		<!-- How We Review -->
		<div style="margin-bottom:56px;">
			<h2 style="font-size:26px;font-weight:800;margin:0 0 28px;text-align:center;">How We Review</h2>
			<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:24px;">
				<?php
				$process = array(
					array( '🔍', 'Independent Research',   'We research each listing ourselves. We are not paid to rank anything higher than it deserves.' ),
					array( '✅', 'Hands-On Testing',       'Our team tests products and visits locations directly wherever possible.' ),
					array( '📊', 'Scored on Criteria',     'Every listing is scored across multiple criteria — quality, value, experience — not just a gut feeling.' ),
					array( '🔄', 'Regularly Updated',      'Reviews are refreshed at least quarterly. Stale information is removed or flagged.' ),
				);
				foreach ( $process as $p ) : ?>
				<div style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:24px;text-align:center;">
					<div style="font-size:36px;margin-bottom:12px;"><?php echo $p[0]; ?></div>
					<h3 style="font-size:16px;font-weight:700;margin:0 0 8px;"><?php echo esc_html( $p[1] ); ?></h3>
					<p style="font-size:14px;color:#666;margin:0;line-height:1.6;"><?php echo esc_html( $p[2] ); ?></p>
				</div>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- Editorial Standards -->
		<div style="background:var(--ob-light);border-radius:var(--ob-radius);padding:36px;margin-bottom:56px;max-width:800px;margin-left:auto;margin-right:auto;">
			<h2 style="font-size:22px;font-weight:800;margin:0 0 16px;">Editorial Independence</h2>
			<p style="font-size:15px;line-height:1.75;color:#444;margin:0 0 12px;">
				OntariosBest.com earns revenue through affiliate commissions and sponsored placements. This is how we keep the site free for readers. However, this does not influence our editorial ratings or rankings.
			</p>
			<p style="font-size:15px;line-height:1.75;color:#444;margin:0 0 12px;">
				<strong>Sponsored and featured listings are clearly labelled.</strong> Our scores and star ratings are determined independently by our editorial team. We have never accepted payment to improve a score or remove a negative review.
			</p>
			<p style="font-size:15px;line-height:1.75;color:#444;margin:0;">
				For more information on how we make money, see our <a href="/affiliate-disclosure/" style="color:var(--ob-primary);">Affiliate Disclosure</a>.
			</p>
		</div>

		<!-- What We Cover -->
		<div style="margin-bottom:56px;text-align:center;">
			<h2 style="font-size:26px;font-weight:800;margin:0 0 8px;">What We Cover</h2>
			<p style="color:#666;margin:0 0 28px;">Six categories across Ontario — expert-ranked and independently reviewed</p>
			<div class="ob-category-grid">
				<?php
				$cats = array(
					array( '🎰', 'Online Casinos',  '/casinos/',       'Licensed Ontario operators only' ),
					array( '✈️', 'Travel & Tourism', '/travel/',        'Hotels, resorts, and destinations' ),
					array( '🍽️', 'Restaurants',      '/restaurants/',   'Best dining across the province' ),
					array( '🎭', 'Entertainment',    '/entertainment/', 'Shows, attractions, and events' ),
					array( '🔧', 'Services',         '/services/',      'Local businesses you can trust' ),
					array( '🛍️', 'Shopping',         '/shopping/',      'Retailers, boutiques, and more' ),
				);
				foreach ( $cats as $c ) : ?>
				<a href="<?php echo esc_url( home_url( $c[2] ) ); ?>" class="ob-category-card">
					<div class="ob-category-card__icon"><?php echo $c[0]; ?></div>
					<div class="ob-category-card__title"><?php echo esc_html( $c[1] ); ?></div>
					<div style="font-size:12px;color:#888;margin-top:4px;"><?php echo esc_html( $c[3] ); ?></div>
				</a>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- Casino Commitment -->
		<div style="background:#1a1a2e;color:#fff;border-radius:var(--ob-radius);padding:32px;margin-bottom:56px;max-width:800px;margin-left:auto;margin-right:auto;">
			<h2 style="color:#fff;font-size:20px;font-weight:800;margin:0 0 12px;">Our Commitment to Responsible Gambling</h2>
			<p style="color:#9ca3af;font-size:15px;line-height:1.75;margin:0 0 16px;">
				OntariosBest.com only recommends online casinos that are licensed by iGaming Ontario (iGO). We take responsible gambling seriously — every casino page includes a 19+ notice and links to support resources.
			</p>
			<a href="/responsible-gambling/" style="display:inline-block;background:var(--ob-primary);color:#fff;padding:10px 20px;border-radius:6px;font-size:14px;font-weight:700;text-decoration:none;">
				Responsible Gambling Resources →
			</a>
		</div>

		<!-- CTA: Work With Us -->
		<div style="text-align:center;max-width:600px;margin:0 auto;">
			<h2 style="font-size:24px;font-weight:800;margin:0 0 10px;">Want to Be Featured?</h2>
			<p style="color:#666;margin:0 0 20px;">We offer sponsored placements, featured listings, and lead generation for Ontario businesses across all our categories.</p>
			<a href="/advertise/" class="ob-btn" style="font-size:16px;padding:14px 32px;display:inline-block;">See Advertising Options →</a>
		</div>

	</div><!-- .ast-container -->
</div>

<?php get_footer(); ?>
