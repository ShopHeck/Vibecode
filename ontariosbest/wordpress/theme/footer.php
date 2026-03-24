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
				<!-- Social icons -->
				<div style="display:flex;gap:10px;">
					<?php
					$socials = array(
						'Facebook'  => array( 'url' => '#', 'svg' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>' ),
						'Instagram' => array( 'url' => '#', 'svg' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>' ),
						'Twitter/X' => array( 'url' => '#', 'svg' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.745l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>' ),
					);
					foreach ( $socials as $name => $data ) : ?>
					<a href="<?php echo esc_url( $data['url'] ); ?>"
					   aria-label="<?php echo esc_attr( $name ); ?>"
					   style="width:32px;height:32px;background:rgba(255,255,255,0.08);border-radius:6px;display:inline-flex;align-items:center;justify-content:center;color:#9ca3af;text-decoration:none;transition:background 0.2s;"
					   rel="nofollow noopener" target="_blank">
						<?php echo $data['svg']; ?>
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
