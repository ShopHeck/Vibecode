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
<header id="ob-header" style="background:#fff;border-bottom:1px solid var(--ob-border);position:sticky;top:0;z-index:1000;box-shadow:0 1px 6px rgba(0,0,0,0.06);">
	<div class="ast-container" style="display:flex;align-items:center;justify-content:space-between;padding-top:0;padding-bottom:0;height:64px;gap:20px;">

		<!-- Logo -->
		<div class="ob-logo" style="flex-shrink:0;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="text-decoration:none;display:flex;align-items:center;gap:10px;">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<span style="font-size:22px;font-weight:900;color:var(--ob-dark);">Ontario's</span><span style="font-size:22px;font-weight:900;color:var(--ob-primary);">Best</span>
				<?php endif; ?>
			</a>
		</div>

		<!-- Primary Navigation (desktop) -->
		<nav id="ob-primary-nav" style="flex:1;" aria-label="Primary Navigation">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'ob-nav-menu',
				'fallback_cb'    => function() {
					// Fallback hardcoded nav if no menu assigned
					echo '<ul class="ob-nav-menu">';
					$items = array(
						'Casinos'       => '/casinos/',
						'Travel'        => '/travel/',
						'Restaurants'   => '/restaurants/',
						'Entertainment' => '/entertainment/',
						'Services'      => '/services/',
						'Blog'          => '/blog/',
					);
					foreach ( $items as $label => $url ) {
						echo '<li><a href="' . esc_url( home_url( $url ) ) . '">' . esc_html( $label ) . '</a></li>';
					}
					echo '</ul>';
				},
			) );
			?>
		</nav>

		<!-- Header CTAs -->
		<div style="display:flex;align-items:center;gap:12px;flex-shrink:0;">
			<a href="<?php echo esc_url( home_url( '/advertise/' ) ); ?>"
			   style="font-size:13px;font-weight:700;color:var(--ob-primary);text-decoration:none;display:none;"
			   class="ob-header-advertise">
				Advertise
			</a>
			<?php if ( is_singular( 'casino' ) || is_post_type_archive( 'casino' ) || is_page( 'casinos' ) ) : ?>
				<span style="font-size:11px;background:#1a1a1a;color:#aaa;padding:4px 10px;border-radius:4px;font-weight:600;">19+</span>
			<?php endif; ?>
			<!-- Mobile menu toggle -->
			<button class="ob-hamburger" id="ob-mobile-toggle" aria-label="Open menu" aria-expanded="false">
				<span></span><span></span><span></span>
			</button>
		</div>

	</div>

</header>

<nav class="ob-nav-overlay" id="ob-nav-overlay" aria-label="Mobile navigation">
  <button class="ob-hamburger" id="ob-nav-close" aria-label="Close menu">
    <span></span><span></span><span></span>
  </button>
  <a href="<?php echo esc_url( home_url( '/casinos/' ) ); ?>">Casinos</a>
  <a href="<?php echo esc_url( home_url( '/best-of/' ) ); ?>">Best of Ontario</a>
  <a href="<?php echo esc_url( home_url( '/casinos/compare/' ) ); ?>">Compare</a>
  <a href="<?php echo esc_url( home_url( '/travel/' ) ); ?>">Travel</a>
  <a href="<?php echo esc_url( home_url( '/responsible-gambling/' ) ); ?>" style="color:var(--ob-primary)">19+ | Play Responsibly</a>
</nav>

<style>
/* -------------------------------------------------------
   Navigation Styles
------------------------------------------------------- */

.ob-nav-menu {
	list-style: none;
	margin: 0;
	padding: 0;
	display: flex;
	align-items: center;
	gap: 4px;
	justify-content: center;
}

.ob-nav-menu li {
	position: relative;
}

.ob-nav-menu li a {
	display: block;
	padding: 8px 12px;
	font-size: 14px;
	font-weight: 600;
	color: var(--ob-text);
	text-decoration: none;
	border-radius: 6px;
	transition: background 0.15s, color 0.15s;
	white-space: nowrap;
}

.ob-nav-menu li a:hover,
.ob-nav-menu li.current-menu-item > a,
.ob-nav-menu li.current-post-ancestor > a {
	background: var(--ob-light);
	color: var(--ob-primary);
}

/* Dropdown */
.ob-nav-menu li ul {
	display: none;
	position: absolute;
	top: 100%;
	left: 0;
	background: #fff;
	border: 1px solid var(--ob-border);
	border-radius: var(--ob-radius);
	box-shadow: 0 4px 16px rgba(0,0,0,0.10);
	min-width: 180px;
	list-style: none;
	padding: 6px 0;
	margin: 0;
	z-index: 999;
}

.ob-nav-menu li:hover > ul {
	display: block;
}

.ob-nav-menu li ul li a {
	padding: 8px 16px;
	border-radius: 0;
	font-size: 13px;
	font-weight: 500;
}

/* Show advertise link on larger screens */
@media (min-width: 1024px) {
	.ob-header-advertise {
		display: inline !important;
	}
}

/* Hide desktop nav on small screens — hamburger visibility handled by style.css */
@media (max-width: 900px) {
	#ob-primary-nav {
		display: none;
	}
}
</style>

<script>
(function() {
  var toggle = document.getElementById('ob-mobile-toggle');
  var close  = document.getElementById('ob-nav-close');
  if (!toggle || !close) return;

  function openNav() {
    document.body.classList.add('nav-open');
    toggle.setAttribute('aria-expanded', 'true');
  }
  function closeNav() {
    document.body.classList.remove('nav-open');
    toggle.setAttribute('aria-expanded', 'false');
  }

  toggle.addEventListener('click', function() {
    document.body.classList.contains('nav-open') ? closeNav() : openNav();
  });
  close.addEventListener('click', closeNav);

  // Close when any overlay link is tapped
  document.querySelectorAll('.ob-nav-overlay a').forEach(function(a) {
    a.addEventListener('click', closeNav);
  });

  // Close on Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeNav();
  });
})();
</script>

<div id="main-content">
