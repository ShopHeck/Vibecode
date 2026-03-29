<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main-content">Skip to content</a>

<!-- =====================================================
     SITEWIDE HEADER
====================================================== -->
<header id="ob-header">

	<div class="ob-header-inner">

		<!-- Logo -->
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ob-logo" aria-label="Ontario's Best — Home">
			<?php if ( has_custom_logo() ) :
				the_custom_logo();
			else : ?>
				Ontario's <em>Best</em>
			<?php endif; ?>
		</a>

		<!-- Primary Navigation (desktop) -->
		<nav id="ob-primary-nav" aria-label="Primary Navigation">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'ob-nav-menu',
				'fallback_cb'    => function() {
					echo '<ul class="ob-nav-menu">';
					$nav_items = array(
						'Casinos'       => '/casinos/',
						'Travel'        => '/travel/',
						'Restaurants'   => '/restaurants/',
						'Entertainment' => '/entertainment/',
						'Services'      => '/services/',
						'Blog'          => '/blog/',
					);
					foreach ( $nav_items as $label => $url ) {
						echo '<li><a href="' . esc_url( home_url( $url ) ) . '">' . esc_html( $label ) . '</a></li>';
					}
					echo '</ul>';
				},
			) );
			?>
		</nav>

		<!-- Header right -->
		<div class="ob-header-right">
			<?php if ( is_singular( 'casino' ) || is_post_type_archive( 'casino' ) ) : ?>
				<span class="ob-age-badge">19+</span>
			<?php endif; ?>
			<a href="<?php echo esc_url( home_url( '/advertise/' ) ); ?>" class="ob-header-advertise">Advertise</a>
			<button id="ob-mobile-toggle" aria-label="Open menu" aria-expanded="false">
				<span class="ob-hamburger"></span>
			</button>
		</div>

	</div>

	<!-- Mobile nav drawer -->
	<div id="ob-mobile-nav" aria-hidden="true">
		<div class="ob-mobile-nav-inner">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'ob-mobile-menu',
				'fallback_cb'    => function() {
					echo '<ul class="ob-mobile-menu">';
					$nav_items = array(
						'Casinos'       => '/casinos/',
						'Travel'        => '/travel/',
						'Restaurants'   => '/restaurants/',
						'Entertainment' => '/entertainment/',
						'Services'      => '/services/',
						'Blog'          => '/blog/',
						'Advertise'     => '/advertise/',
					);
					foreach ( $nav_items as $label => $url ) {
						echo '<li><a href="' . esc_url( home_url( $url ) ) . '">' . esc_html( $label ) . '</a></li>';
					}
					echo '</ul>';
				},
			) );
			?>
		</div>
	</div>

</header>

<style>
/* -------------------------------------------------------
   Header
------------------------------------------------------- */

#ob-header {
	background: var(--ink);
	border-bottom: 1px solid rgba(245,240,232,0.08);
	position: sticky;
	top: 0;
	z-index: 1000;
}

.ob-header-inner {
	max-width: 1120px;
	margin: 0 auto;
	padding: 0 24px;
	height: 62px;
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 20px;
}

/* Logo */
.ob-logo {
	font-family: 'Cormorant Garamond', serif;
	font-size: 1.3rem;
	font-weight: 600;
	color: var(--paper);
	text-decoration: none;
	letter-spacing: 0.03em;
	flex-shrink: 0;
	transition: opacity 0.2s;
}

.ob-logo:hover { opacity: 0.85; color: var(--paper); }
.ob-logo em { font-style: italic; color: var(--terra-2); }

/* Desktop nav */
#ob-primary-nav { flex: 1; }

.ob-nav-menu {
	list-style: none;
	margin: 0;
	padding: 0;
	display: flex;
	align-items: center;
	gap: 2px;
	justify-content: center;
}

.ob-nav-menu li { position: relative; }

.ob-nav-menu li a {
	display: block;
	padding: 7px 13px;
	font-family: 'Jost', sans-serif;
	font-size: 0.78rem;
	font-weight: 500;
	letter-spacing: 0.06em;
	text-transform: uppercase;
	color: rgba(245,240,232,0.60);
	text-decoration: none;
	transition: color 0.2s;
	white-space: nowrap;
}

.ob-nav-menu li a:hover,
.ob-nav-menu li.current-menu-item > a,
.ob-nav-menu li.current-post-ancestor > a {
	color: var(--paper);
}

/* Dropdown */
.ob-nav-menu li ul {
	display: none;
	position: absolute;
	top: calc(100% + 8px);
	left: 0;
	background: var(--ink-2);
	border: 1px solid rgba(245,240,232,0.1);
	border-radius: var(--ob-radius);
	box-shadow: 0 8px 24px rgba(0,0,0,0.3);
	min-width: 180px;
	list-style: none;
	padding: 6px 0;
	margin: 0;
	z-index: 999;
}

.ob-nav-menu li:hover > ul { display: block; }

.ob-nav-menu li ul li a {
	padding: 9px 16px;
	font-size: 0.78rem;
	color: rgba(245,240,232,0.7);
}

.ob-nav-menu li ul li a:hover { color: var(--paper); }

/* Header right */
.ob-header-right {
	display: flex;
	align-items: center;
	gap: 14px;
	flex-shrink: 0;
}

.ob-age-badge {
	font-family: 'Jost', sans-serif;
	font-size: 0.7rem;
	font-weight: 600;
	letter-spacing: 0.08em;
	background: rgba(245,240,232,0.10);
	color: rgba(245,240,232,0.55);
	padding: 4px 9px;
	border-radius: 2px;
}

.ob-header-advertise {
	font-family: 'Jost', sans-serif;
	font-size: 0.75rem;
	font-weight: 600;
	letter-spacing: 0.08em;
	text-transform: uppercase;
	color: var(--terra-2);
	text-decoration: none;
	transition: color 0.2s;
	display: none;
}

.ob-header-advertise:hover { color: var(--paper); }

/* Hamburger */
#ob-mobile-toggle {
	display: none;
	background: none;
	border: 1px solid rgba(245,240,232,0.20);
	border-radius: 3px;
	padding: 7px 10px;
	cursor: pointer;
	line-height: 1;
}

.ob-hamburger,
.ob-hamburger::before,
.ob-hamburger::after {
	display: block;
	width: 18px;
	height: 1.5px;
	background: var(--paper);
	transition: transform 0.2s;
}

.ob-hamburger { position: relative; }

.ob-hamburger::before,
.ob-hamburger::after {
	content: '';
	position: absolute;
	left: 0;
}

.ob-hamburger::before { top: -5px; }
.ob-hamburger::after  { top:  5px; }

/* Mobile drawer */
#ob-mobile-nav {
	display: none;
	background: var(--ink-2);
	border-top: 1px solid rgba(245,240,232,0.08);
}

.ob-mobile-nav-inner {
	max-width: 1120px;
	margin: 0 auto;
	padding: 12px 24px 16px;
}

.ob-mobile-menu {
	list-style: none;
	margin: 0;
	padding: 0;
}

.ob-mobile-menu li a {
	display: block;
	padding: 11px 0;
	font-family: 'Jost', sans-serif;
	font-size: 0.95rem;
	font-weight: 400;
	color: rgba(245,240,232,0.75);
	text-decoration: none;
	border-bottom: 1px solid rgba(245,240,232,0.07);
	transition: color 0.2s;
}

.ob-mobile-menu li:last-child a { border-bottom: none; }
.ob-mobile-menu li a:hover { color: var(--paper); }

/* Responsive */
@media (min-width: 1024px) {
	.ob-header-advertise { display: inline !important; }
}

@media (max-width: 900px) {
	#ob-primary-nav { display: none; }
	#ob-mobile-toggle { display: block !important; }
}
</style>

<script>
(function() {
	var toggle = document.getElementById('ob-mobile-toggle');
	var nav    = document.getElementById('ob-mobile-nav');
	if (!toggle || !nav) return;
	toggle.addEventListener('click', function() {
		var open = nav.style.display === 'block';
		nav.style.display = open ? 'none' : 'block';
		nav.setAttribute('aria-hidden', String(open));
		toggle.setAttribute('aria-expanded', String(!open));
	});
})();
</script>

<div id="main-content">
