</div><!-- #main-content -->

<!-- =====================================================
     SITEWIDE FOOTER
====================================================== -->
<footer id="ob-footer" style="background:#111827;color:#9ca3af;margin-top:auto;">

	<!-- Main footer grid -->
	<div class="ast-container" style="padding-top:48px;padding-bottom:40px;">
		<div style="display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:40px;">

			<!-- Col 1: Brand + About -->
			<div>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:14px;">
					<span style="font-size:20px;font-weight:900;color:#fff;">Ontario's</span><span style="font-size:20px;font-weight:900;color:var(--ob-primary);">Best</span>
				</a>
				<p style="font-size:13px;line-height:1.7;margin:0 0 16px;max-width:280px;">
					Ontario's go-to guide for the best casinos, restaurants, travel, entertainment, and services. Expert-reviewed and independently ranked.
				</p>
				<!-- Social placeholders -->
				<div style="display:flex;gap:10px;">
					<?php
					$socials = array(
						'Facebook'  => '#',
						'Instagram' => '#',
						'Twitter/X' => '#',
					);
					foreach ( $socials as $name => $url ) : ?>
					<a href="<?php echo esc_url( $url ); ?>"
					   aria-label="<?php echo esc_attr( $name ); ?>"
					   style="width:32px;height:32px;background:rgba(255,255,255,0.08);border-radius:6px;display:inline-flex;align-items:center;justify-content:center;font-size:13px;color:#9ca3af;text-decoration:none;transition:background 0.2s;"
					   rel="nofollow noopener" target="_blank">
						<?php echo substr( $name, 0, 1 ); ?>
					</a>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Col 2: Categories -->
			<div>
				<h4 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#fff;margin:0 0 14px;">Categories</h4>
				<ul style="list-style:none;margin:0;padding:0;">
					<?php
					$cat_links = array(
						'Online Casinos'  => '/casinos/',
						'Travel'          => '/travel/',
						'Restaurants'     => '/restaurants/',
						'Entertainment'   => '/entertainment/',
						'Services'        => '/services/',
						'Shopping'        => '/shopping/',
					);
					foreach ( $cat_links as $label => $url ) : ?>
					<li style="margin-bottom:8px;">
						<a href="<?php echo esc_url( home_url( $url ) ); ?>" style="font-size:13px;color:#9ca3af;text-decoration:none;transition:color 0.2s;">
							<?php echo esc_html( $label ); ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<!-- Col 3: Quick Links -->
			<div>
				<h4 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#fff;margin:0 0 14px;">Quick Links</h4>
				<ul style="list-style:none;margin:0;padding:0;">
					<?php
					$quick_links = array(
						'Blog'              => '/blog/',
						'Best Of Ontario'   => '/best-of/',
						'Compare Casinos'   => '/casinos/compare/',
						'Advertise With Us' => '/advertise/',
						'About'             => '/about/',
						'Contact'           => '/contact/',
					);
					foreach ( $quick_links as $label => $url ) : ?>
					<li style="margin-bottom:8px;">
						<a href="<?php echo esc_url( home_url( $url ) ); ?>" style="font-size:13px;color:#9ca3af;text-decoration:none;transition:color 0.2s;">
							<?php echo esc_html( $label ); ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<!-- Col 4: Legal -->
			<div>
				<h4 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#fff;margin:0 0 14px;">Legal</h4>
				<ul style="list-style:none;margin:0;padding:0;">
					<?php
					$legal_links = array(
						'Privacy Policy'       => '/privacy-policy/',
						'Terms & Conditions'   => '/terms/',
						'Affiliate Disclosure' => '/affiliate-disclosure/',
						'Responsible Gambling' => '/responsible-gambling/',
					);
					foreach ( $legal_links as $label => $url ) : ?>
					<li style="margin-bottom:8px;">
						<a href="<?php echo esc_url( home_url( $url ) ); ?>" style="font-size:13px;color:#9ca3af;text-decoration:none;transition:color 0.2s;">
							<?php echo esc_html( $label ); ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>

		</div><!-- grid -->
	</div>

	<!-- Responsible Gambling Notice — Sitewide -->
	<div style="border-top:1px solid rgba(255,255,255,0.07);padding:16px 0;text-align:center;">
		<div class="ast-container">
			<p style="font-size:12px;color:#6b7280;margin:0;line-height:1.6;">
				<strong style="color:#9ca3af;">19+ Only.</strong>
				Gambling can be addictive. Please play responsibly. If you or someone you know has a gambling problem, call
				<a href="tel:18665312600" style="color:var(--ob-primary);text-decoration:none;font-weight:600;">ConnexOntario: 1-866-531-2600</a>
				(free, confidential, 24/7) or visit
				<a href="https://connexontario.ca" target="_blank" rel="nofollow noopener" style="color:var(--ob-primary);text-decoration:none;">connexontario.ca</a>.
				&nbsp;|&nbsp;
				<a href="<?php echo esc_url( home_url( '/responsible-gambling/' ) ); ?>" style="color:#6b7280;text-decoration:none;">Responsible Gambling</a>
			</p>
		</div>
	</div>

	<!-- Bottom bar -->
	<div style="border-top:1px solid rgba(255,255,255,0.07);padding:14px 0;">
		<div class="ast-container" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
			<p style="font-size:12px;color:#4b5563;margin:0;">
				&copy; <?php echo date( 'Y' ); ?> OntariosBest.com. All rights reserved.
				<span style="margin:0 8px;">·</span>
				<a href="<?php echo esc_url( home_url( '/affiliate-disclosure/' ) ); ?>" style="color:#4b5563;text-decoration:none;">Affiliate Disclosure</a>
			</p>
			<p style="font-size:12px;color:#4b5563;margin:0;">
				OntariosBest.com is independently operated and not affiliated with any casino, government body, or iGaming Ontario.
			</p>
		</div>
	</div>

</footer>

<style>
/* Footer link hover */
#ob-footer a:hover {
	color: var(--ob-primary) !important;
}

/* Responsive footer grid */
@media (max-width: 900px) {
	#ob-footer .ast-container > div {
		grid-template-columns: 1fr 1fr !important;
	}
}
@media (max-width: 560px) {
	#ob-footer .ast-container > div {
		grid-template-columns: 1fr !important;
	}
}
</style>

<?php wp_footer(); ?>
</body>
</html>
