</div><!-- #main-content -->

<!-- =====================================================
     SITEWIDE FOOTER
====================================================== -->
<footer id="ob-footer">

	<div class="ob-footer-inner">
		<div class="ob-footer-top">

			<!-- Brand -->
			<div class="ob-footer-brand">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ob-footer-logo">
					Ontario's <em>Best</em>
				</a>
				<p class="ob-footer-tagline">
					Ontario's definitive guide to travel, entertainment, and regulated gaming — independently written and editorially honest.
				</p>
			</div>

			<!-- Explore -->
			<div class="ob-footer-col">
				<div class="ob-footer-col-title">Explore</div>
				<?php
				$explore = array(
					'Online Casinos'  => '/casinos/',
					'Sports Betting'  => '/sports-betting/',
					'Travel & Hotels' => '/travel/',
					'Restaurants'     => '/restaurants/',
					'Entertainment'   => '/entertainment/',
					'Services'        => '/services/',
					'Blog'            => '/blog/',
				);
				foreach ( $explore as $label => $url ) :
				?>
				<a href="<?php echo esc_url( home_url( $url ) ); ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</div>

			<!-- About -->
			<div class="ob-footer-col">
				<div class="ob-footer-col-title">Company</div>
				<?php
				$company = array(
					'About Us'          => '/about/',
					'Contact'           => '/contact/',
					'Advertise'         => '/advertise/',
					'Best Of Ontario'   => '/best-of/',
				);
				foreach ( $company as $label => $url ) :
				?>
				<a href="<?php echo esc_url( home_url( $url ) ); ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</div>

			<!-- Legal -->
			<div class="ob-footer-col">
				<div class="ob-footer-col-title">Legal</div>
				<?php
				$legal = array(
					'Privacy Policy'       => '/privacy-policy/',
					'Terms & Conditions'   => '/terms/',
					'Affiliate Disclosure' => '/affiliate-disclosure/',
					'Responsible Gambling' => '/responsible-gambling/',
				);
				foreach ( $legal as $label => $url ) :
				?>
				<a href="<?php echo esc_url( home_url( $url ) ); ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</div>

		</div><!-- .ob-footer-top -->

		<!-- Responsible Gambling -->
		<div class="ob-footer-rg">
			<strong>19+ Only.</strong>
			Gambling can be addictive. Please play responsibly. For free, confidential support call
			<a href="tel:18665312600">ConnexOntario: 1-866-531-2600</a> (24/7) or visit
			<a href="https://connexontario.ca" target="_blank" rel="nofollow noopener">connexontario.ca</a>.
			&nbsp;·&nbsp;
			<a href="<?php echo esc_url( home_url( '/responsible-gambling/' ) ); ?>">Responsible Gambling Policy</a>
		</div>

		<!-- Bottom bar -->
		<div class="ob-footer-bottom">
			<p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> OntariosBest.com &nbsp;·&nbsp; Independent travel &amp; iGaming guide for Ontario, Canada &nbsp;·&nbsp; Not affiliated with Tourism Ontario or any government body</p>
			<div class="ob-footer-bottom-links">
				<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">Privacy</a>
				<a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>">Terms</a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a>
			</div>
		</div>

	</div><!-- .ob-footer-inner -->

</footer>

<style>
/* -------------------------------------------------------
   Footer
------------------------------------------------------- */

#ob-footer {
	background: var(--ink);
	color: rgba(245,240,232,0.45);
	margin-top: 64px;
}

.ob-footer-inner {
	max-width: 1120px;
	margin: 0 auto;
	padding: 0 24px;
}

.ob-footer-top {
	display: grid;
	grid-template-columns: 2fr 1fr 1fr 1fr;
	gap: 40px;
	padding: 52px 0 44px;
	border-bottom: 1px solid rgba(245,240,232,0.07);
}

/* Brand col */
.ob-footer-logo {
	font-family: 'Cormorant Garamond', serif;
	font-size: 1.3rem;
	font-weight: 600;
	color: var(--paper);
	text-decoration: none;
	letter-spacing: 0.03em;
	display: inline-block;
	margin-bottom: 12px;
}

.ob-footer-logo em { font-style: italic; color: var(--terra-2); }
.ob-footer-logo:hover { opacity: 0.85; color: var(--paper); }

.ob-footer-tagline {
	font-size: 0.82rem;
	line-height: 1.7;
	color: rgba(245,240,232,0.40);
	max-width: 260px;
	margin: 0;
}

/* Link cols */
.ob-footer-col-title {
	font-family: 'Jost', sans-serif;
	font-size: 0.68rem;
	font-weight: 600;
	letter-spacing: 0.14em;
	text-transform: uppercase;
	color: rgba(245,240,232,0.35);
	margin-bottom: 16px;
}

.ob-footer-col a {
	display: block;
	font-size: 0.85rem;
	color: rgba(245,240,232,0.55);
	text-decoration: none;
	margin-bottom: 9px;
	transition: color 0.2s;
}

.ob-footer-col a:hover { color: var(--paper); }

/* RG notice */
.ob-footer-rg {
	padding: 16px 0;
	font-size: 0.78rem;
	line-height: 1.65;
	color: rgba(245,240,232,0.30);
	border-bottom: 1px solid rgba(245,240,232,0.07);
}

.ob-footer-rg strong { color: rgba(245,240,232,0.55); }

.ob-footer-rg a {
	color: rgba(245,240,232,0.45);
	text-decoration: underline;
	transition: color 0.2s;
}

.ob-footer-rg a:hover { color: var(--paper); }

/* Bottom bar */
.ob-footer-bottom {
	display: flex;
	align-items: center;
	justify-content: space-between;
	flex-wrap: wrap;
	gap: 8px;
	padding: 14px 0;
}

.ob-footer-bottom p {
	font-size: 0.75rem;
	color: rgba(245,240,232,0.22);
	margin: 0;
}

.ob-footer-bottom-links {
	display: flex;
	gap: 18px;
}

.ob-footer-bottom-links a {
	font-size: 0.75rem;
	color: rgba(245,240,232,0.22);
	text-decoration: none;
	transition: color 0.2s;
}

.ob-footer-bottom-links a:hover { color: rgba(245,240,232,0.6); }

/* Responsive */
@media (max-width: 900px) {
	.ob-footer-top {
		grid-template-columns: 1fr 1fr;
		gap: 32px;
	}
	.ob-footer-brand { grid-column: 1 / -1; }
}

@media (max-width: 560px) {
	.ob-footer-top { grid-template-columns: 1fr; }
}
</style>

<?php wp_footer(); ?>
</body>
</html>
