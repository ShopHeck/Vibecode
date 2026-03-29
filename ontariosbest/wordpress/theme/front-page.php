<?php
/**
 * Homepage Template — OntariosBest.com
 * Design: Paper / Ink / Terra editorial system
 */

get_header();
?>

<!-- =====================================================
     HERO
====================================================== -->
<section class="ob-home-hero">
	<div class="ob-home-hero__inner">
		<div class="ob-home-hero__content">

			<p class="ob-home-hero__eyebrow">Ontario's #1 Discovery Guide</p>

			<h1 class="ob-home-hero__heading">
				Find Ontario's<br><em>Very Best</em>
			</h1>

			<p class="ob-home-hero__lead">
				Casinos, hotels, restaurants, entertainment, and services — expert-reviewed and ranked so you don't have to guess.
			</p>

			<!-- Search -->
			<form class="ob-home-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
				<input
					type="search"
					name="s"
					placeholder="Search casinos, restaurants, travel..."
					aria-label="Search OntariosBest"
				>
				<button type="submit">Search</button>
			</form>

			<!-- Quick links -->
			<div class="ob-home-quicklinks">
				<?php
				$quick = array(
					'Casinos'       => '/casinos/',
					'Hotels'        => '/travel/',
					'Restaurants'   => '/restaurants/',
					'Entertainment' => '/entertainment/',
					'Services'      => '/services/',
				);
				foreach ( $quick as $label => $url ) :
				?>
				<a href="<?php echo esc_url( home_url( $url ) ); ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</div>

		</div><!-- .hero__content -->

		<!-- Hero stats -->
		<div class="ob-home-hero__stats">
			<?php
			$stats = array(
				array( 'number' => '12+',  'label' => 'Casino Reviews' ),
				array( 'number' => '100%', 'label' => 'iGO Licensed' ),
				array( 'number' => '2026', 'label' => 'Up to Date' ),
			);
			foreach ( $stats as $s ) :
			?>
			<div class="ob-home-stat">
				<span class="ob-home-stat__number"><?php echo esc_html( $s['number'] ); ?></span>
				<span class="ob-home-stat__label"><?php echo esc_html( $s['label'] ); ?></span>
			</div>
			<?php endforeach; ?>
		</div>

	</div><!-- .hero__inner -->
</section>

<!-- =====================================================
     CATEGORY GRID
====================================================== -->
<section class="ob-home-section ob-home-categories">
	<div class="ob-home-section__inner">

		<div class="ob-section-heading">
			<h2>Browse by Category</h2>
			<span class="ob-section-heading__line"></span>
		</div>

		<div class="ob-home-cat-grid">
			<?php
			$categories = array(
				array( 'icon' => '🎰', 'label' => 'Online Casinos',  'url' => '/casinos/',       'cpt' => 'casino' ),
				array( 'icon' => '✈️', 'label' => 'Travel & Hotels',  'url' => '/travel/',        'cpt' => 'travel' ),
				array( 'icon' => '🍽️', 'label' => 'Restaurants',      'url' => '/restaurants/',   'cpt' => 'restaurant' ),
				array( 'icon' => '🎭', 'label' => 'Entertainment',     'url' => '/entertainment/', 'cpt' => 'entertainment' ),
				array( 'icon' => '🔧', 'label' => 'Services',          'url' => '/services/',      'cpt' => 'service' ),
				array( 'icon' => '🛍️', 'label' => 'Shopping',          'url' => '/shopping/',      'cpt' => 'shopping' ),
			);
			foreach ( $categories as $cat ) :
				$count = wp_count_posts( $cat['cpt'] );
				$published = isset( $count->publish ) ? (int) $count->publish : 0;
			?>
			<a href="<?php echo esc_url( home_url( $cat['url'] ) ); ?>" class="ob-home-cat-card">
				<span class="ob-home-cat-card__icon"><?php echo $cat['icon']; ?></span>
				<span class="ob-home-cat-card__label"><?php echo esc_html( $cat['label'] ); ?></span>
				<?php if ( $published > 0 ) : ?>
				<span class="ob-home-cat-card__count"><?php echo $published; ?> listed</span>
				<?php endif; ?>
			</a>
			<?php endforeach; ?>
		</div>

	</div>
</section>

<!-- =====================================================
     TOP CASINOS
====================================================== -->
<section class="ob-home-section ob-home-section--paper2">
	<div class="ob-home-section__inner">

		<div class="ob-section-heading">
			<h2>Top Ontario Casinos</h2>
			<span class="ob-section-heading__line"></span>
			<a href="<?php echo esc_url( home_url( '/casinos/' ) ); ?>" class="ob-section-heading__link">See all →</a>
		</div>

		<?php
		$casinos = new WP_Query( array(
			'post_type'      => 'casino',
			'post_status'    => 'publish',
			'posts_per_page' => 5,
			'orderby'        => 'meta_value_num',
			'meta_key'       => '_casino_overall_rating',
			'order'          => 'DESC',
		) );

		$rank = 1;
		if ( $casinos->have_posts() ) :
			while ( $casinos->have_posts() ) : $casinos->the_post();
				$rating  = get_post_meta( get_the_ID(), '_casino_overall_rating', true );
				$bonus   = get_post_meta( get_the_ID(), '_casino_welcome_bonus', true );
				$aff_url = get_post_meta( get_the_ID(), '_casino_affiliate_url', true );
				$stars   = round( (float) $rating / 2 );
		?>
		<div class="ob-casino-row">
			<span class="ob-casino-row__rank"><?php echo str_pad( $rank, 2, '0', STR_PAD_LEFT ); ?></span>

			<div class="ob-casino-row__logo">
				<?php if ( has_post_thumbnail() ) :
					the_post_thumbnail( array( 60, 60 ), array( 'alt' => get_the_title() ) );
				else : ?>
					<span><?php echo esc_html( substr( get_the_title(), 0, 2 ) ); ?></span>
				<?php endif; ?>
			</div>

			<div class="ob-casino-row__info">
				<a href="<?php the_permalink(); ?>" class="ob-casino-row__name"><?php the_title(); ?></a>
				<?php if ( $bonus ) : ?>
					<p class="ob-casino-row__bonus"><?php echo esc_html( $bonus ); ?></p>
				<?php endif; ?>
			</div>

			<div class="ob-casino-row__rating">
				<span class="ob-casino-row__stars">
					<?php for ( $i = 1; $i <= 5; $i++ ) echo $i <= $stars ? '★' : '☆'; ?>
				</span>
				<span class="ob-casino-row__score"><?php echo esc_html( $rating ); ?></span>
			</div>

			<div class="ob-casino-row__cta">
				<?php if ( $aff_url && strpos( $aff_url, 'REPLACE_WITH' ) === false ) : ?>
				<a href="<?php echo esc_url( $aff_url ); ?>"
				   class="ob-btn ob-btn-terra"
				   target="_blank"
				   rel="nofollow noopener sponsored">
					Play Now
				</a>
				<?php else : ?>
				<a href="<?php the_permalink(); ?>" class="ob-btn-outline">Review</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
			$rank++;
			endwhile;
			wp_reset_postdata();
		else :
		?>
		<p style="color:var(--ink-4);font-size:0.9rem;">Casino reviews coming soon.</p>
		<?php endif; ?>

		<p class="ob-home-age-notice">19+ · iGO Licensed operators only · <a href="<?php echo esc_url( home_url( '/responsible-gambling/' ) ); ?>">Responsible Gambling</a></p>

	</div>
</section>

<!-- =====================================================
     FEATURED LISTINGS (Travel + Restaurant)
====================================================== -->
<section class="ob-home-section">
	<div class="ob-home-section__inner">

		<div class="ob-section-heading">
			<h2>Featured in Ontario</h2>
			<span class="ob-section-heading__line"></span>
		</div>

		<div class="ob-home-listings-grid">
			<?php
			$listing_types = array(
				array( 'cpt' => 'travel',      'icon' => '✈️', 'url' => '/travel/' ),
				array( 'cpt' => 'restaurant',  'icon' => '🍽️', 'url' => '/restaurants/' ),
				array( 'cpt' => 'entertainment','icon'=> '🎭', 'url' => '/entertainment/' ),
				array( 'cpt' => 'service',     'icon' => '🔧', 'url' => '/services/' ),
			);

			foreach ( $listing_types as $lt ) :
				$listings = new WP_Query( array(
					'post_type'      => $lt['cpt'],
					'post_status'    => 'publish',
					'posts_per_page' => 2,
					'orderby'        => 'meta_value_num',
					'meta_key'       => '_listing_overall_rating',
					'order'          => 'DESC',
				) );
				if ( ! $listings->have_posts() ) continue;
				while ( $listings->have_posts() ) : $listings->the_post();
					$rating = get_post_meta( get_the_ID(), '_listing_overall_rating', true );
			?>
			<a href="<?php the_permalink(); ?>" class="ob-home-listing-card">
				<div class="ob-home-listing-card__img">
					<?php if ( has_post_thumbnail() ) :
						the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) );
					else : ?>
						<span><?php echo $lt['icon']; ?></span>
					<?php endif; ?>
				</div>
				<div class="ob-home-listing-card__body">
					<p class="ob-home-listing-card__type"><?php echo esc_html( ucfirst( $lt['cpt'] ) ); ?></p>
					<h3><?php the_title(); ?></h3>
					<?php if ( $rating ) : ?>
						<p class="ob-home-listing-card__rating">
							<?php for ( $i = 1; $i <= 5; $i++ ) echo $i <= round( (float) $rating / 2 ) ? '★' : '☆'; ?>
							<span><?php echo esc_html( $rating ); ?></span>
						</p>
					<?php endif; ?>
				</div>
			</a>
			<?php
				endwhile;
				wp_reset_postdata();
			endforeach;
			?>
		</div>

	</div>
</section>

<!-- =====================================================
     LATEST BLOG POSTS
====================================================== -->
<section class="ob-home-section ob-home-section--paper2">
	<div class="ob-home-section__inner">

		<div class="ob-section-heading">
			<h2>Latest from the Blog</h2>
			<span class="ob-section-heading__line"></span>
			<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="ob-section-heading__link">All articles →</a>
		</div>

		<div class="ob-home-posts-grid">
			<?php
			$posts = new WP_Query( array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => 3,
			) );
			if ( $posts->have_posts() ) :
				while ( $posts->have_posts() ) : $posts->the_post();
					$cats = get_the_category();
			?>
			<a href="<?php the_permalink(); ?>" class="ob-home-post-card">
				<div class="ob-home-post-card__img">
					<?php if ( has_post_thumbnail() ) :
						the_post_thumbnail( 'medium', array( 'alt' => get_the_title() ) );
					else : ?>
						<span>✍️</span>
					<?php endif; ?>
				</div>
				<div class="ob-home-post-card__body">
					<?php if ( $cats ) : ?>
					<p class="ob-home-post-card__cat"><?php echo esc_html( $cats[0]->name ); ?></p>
					<?php endif; ?>
					<h3><?php the_title(); ?></h3>
					<p class="ob-home-post-card__excerpt"><?php echo wp_trim_words( get_the_excerpt(), 16 ); ?></p>
					<p class="ob-home-post-card__date"><?php echo get_the_date( 'M j, Y' ); ?></p>
				</div>
			</a>
			<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>

	</div>
</section>

<!-- =====================================================
     WHY TRUST US
====================================================== -->
<section class="ob-home-section">
	<div class="ob-home-section__inner">

		<div class="ob-section-heading">
			<h2>Why Trust Ontario's Best</h2>
			<span class="ob-section-heading__line"></span>
		</div>

		<div class="ob-home-trust-grid">
			<?php
			$pillars = array(
				array( 'icon' => '✍️', 'title' => 'Independent Reviews',  'body' => 'Our editorial team tests every casino, restaurant, and service before ranking it. No pay-to-play rankings.' ),
				array( 'icon' => '✓',  'title' => 'Verified & Licensed',   'body' => 'Every casino we feature is licensed by iGaming Ontario. Every service provider is vetted before listing.' ),
				array( 'icon' => '★',  'title' => 'Expert Ranked',         'body' => 'Rankings are based on our 10-point methodology covering quality, value, experience, and trustworthiness.' ),
				array( 'icon' => '↻',  'title' => 'Regularly Updated',     'body' => 'We update our guides monthly. Prices, bonuses, and hours are current as of the date shown.' ),
			);
			foreach ( $pillars as $p ) :
			?>
			<div class="ob-home-pillar">
				<span class="ob-home-pillar__icon"><?php echo $p['icon']; ?></span>
				<h3><?php echo esc_html( $p['title'] ); ?></h3>
				<p><?php echo esc_html( $p['body'] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>

<style>
/* -------------------------------------------------------
   Homepage
------------------------------------------------------- */

.ob-home-section {
	padding: 64px 0;
	background: var(--paper);
}

.ob-home-section--paper2 {
	background: var(--paper-2);
}

.ob-home-section__inner {
	max-width: 1120px;
	margin: 0 auto;
	padding: 0 24px;
}

/* -------
   Hero
------- */

.ob-home-hero {
	background: var(--ink);
	padding: 72px 0 64px;
	position: relative;
	overflow: hidden;
}

.ob-home-hero::before {
	content: '';
	position: absolute;
	inset: 0;
	background:
		radial-gradient(ellipse 60% 80% at 90% 50%, rgba(184,92,56,0.08) 0%, transparent 70%),
		radial-gradient(ellipse 50% 60% at 10% 80%, rgba(200,146,42,0.05) 0%, transparent 60%);
	pointer-events: none;
}

.ob-home-hero__inner {
	max-width: 1120px;
	margin: 0 auto;
	padding: 0 24px;
	position: relative;
	z-index: 1;
}

.ob-home-hero__eyebrow {
	font-family: 'Jost', sans-serif;
	font-size: 0.72rem;
	font-weight: 600;
	letter-spacing: 0.16em;
	text-transform: uppercase;
	color: var(--terra-2);
	margin: 0 0 18px;
}

.ob-home-hero__heading {
	font-family: 'Cormorant Garamond', serif;
	font-size: clamp(2.8rem, 6vw, 5rem);
	font-weight: 300;
	line-height: 1.05;
	letter-spacing: -0.01em;
	color: var(--paper);
	margin: 0 0 20px;
}

.ob-home-hero__heading em {
	font-style: italic;
	color: var(--terra-2);
}

.ob-home-hero__lead {
	font-size: 1.05rem;
	color: rgba(245,240,232,0.55);
	max-width: 540px;
	line-height: 1.7;
	margin: 0 0 36px;
}

/* Search bar */
.ob-home-search {
	display: flex;
	max-width: 520px;
	border-radius: var(--ob-radius);
	overflow: hidden;
	box-shadow: 0 4px 24px rgba(0,0,0,0.35);
	margin-bottom: 28px;
}

.ob-home-search input {
	flex: 1;
	padding: 14px 18px;
	font-family: 'Jost', sans-serif;
	font-size: 0.92rem;
	border: none;
	outline: none;
	background: var(--paper);
	color: var(--ink);
}

.ob-home-search input::placeholder { color: var(--ink-4); }

.ob-home-search button {
	background: var(--terra);
	color: var(--paper);
	border: none;
	padding: 14px 24px;
	font-family: 'Jost', sans-serif;
	font-size: 0.82rem;
	font-weight: 600;
	letter-spacing: 0.06em;
	cursor: pointer;
	transition: background 0.2s;
	white-space: nowrap;
}

.ob-home-search button:hover { background: var(--terra-2); }

/* Quick links */
.ob-home-quicklinks {
	display: flex;
	flex-wrap: wrap;
	gap: 8px;
	margin-bottom: 44px;
}

.ob-home-quicklinks a {
	padding: 7px 16px;
	border-radius: 2px;
	font-size: 0.78rem;
	font-weight: 500;
	letter-spacing: 0.04em;
	background: rgba(245,240,232,0.08);
	color: rgba(245,240,232,0.65);
	border: 1px solid rgba(245,240,232,0.12);
	text-decoration: none;
	transition: all 0.2s;
}

.ob-home-quicklinks a:hover {
	background: rgba(245,240,232,0.14);
	color: var(--paper);
}

/* Stats */
.ob-home-hero__stats {
	display: flex;
	gap: 44px;
	flex-wrap: wrap;
	padding-top: 32px;
	border-top: 1px solid rgba(245,240,232,0.10);
}

.ob-home-stat {
	display: flex;
	flex-direction: column;
}

.ob-home-stat__number {
	font-family: 'Cormorant Garamond', serif;
	font-size: 2.2rem;
	font-weight: 300;
	color: var(--terra-2);
	line-height: 1;
	margin-bottom: 3px;
}

.ob-home-stat__label {
	font-size: 0.72rem;
	font-weight: 500;
	letter-spacing: 0.1em;
	text-transform: uppercase;
	color: rgba(245,240,232,0.35);
}

/* -------
   Category grid
------- */

.ob-home-cat-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
	gap: 14px;
}

.ob-home-cat-card {
	background: #fff;
	border: 1px solid var(--border);
	border-radius: var(--ob-radius);
	padding: 28px 16px;
	text-align: center;
	text-decoration: none;
	color: var(--ink);
	transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
	display: block;
}

.ob-home-cat-card:hover {
	border-color: var(--terra);
	box-shadow: 0 6px 24px rgba(184,92,56,0.10);
	transform: translateY(-2px);
	color: var(--ink);
}

.ob-home-cat-card__icon {
	display: block;
	font-size: 1.8rem;
	margin-bottom: 10px;
}

.ob-home-cat-card__label {
	display: block;
	font-family: 'Cormorant Garamond', serif;
	font-size: 1.05rem;
	font-weight: 600;
	color: var(--ink);
	margin-bottom: 3px;
}

.ob-home-cat-card__count {
	display: block;
	font-size: 0.72rem;
	color: var(--ink-4);
}

/* -------
   Casino rows
------- */

.ob-casino-row {
	display: flex;
	align-items: center;
	gap: 18px;
	background: #fff;
	border: 1px solid var(--border);
	border-radius: var(--ob-radius);
	padding: 18px 22px;
	margin-bottom: 12px;
	transition: box-shadow 0.2s, transform 0.2s;
}

.ob-casino-row:hover {
	box-shadow: 0 6px 24px rgba(28,25,23,0.08);
	transform: translateY(-2px);
}

.ob-casino-row__rank {
	font-family: 'Cormorant Garamond', serif;
	font-size: 1.6rem;
	font-weight: 300;
	font-style: italic;
	color: var(--terra);
	opacity: 0.4;
	min-width: 34px;
	text-align: center;
	flex-shrink: 0;
}

.ob-casino-row__logo {
	width: 52px;
	height: 52px;
	background: var(--paper-3);
	border: 1px solid var(--border);
	border-radius: 3px;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
	overflow: hidden;
}

.ob-casino-row__logo img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.ob-casino-row__logo span {
	font-family: 'Cormorant Garamond', serif;
	font-size: 0.9rem;
	font-weight: 600;
	color: var(--ink-3);
}

.ob-casino-row__info {
	flex: 1;
	min-width: 0;
}

.ob-casino-row__name {
	font-family: 'Cormorant Garamond', serif;
	font-size: 1.1rem;
	font-weight: 600;
	color: var(--ink);
	text-decoration: none;
	display: block;
	margin-bottom: 2px;
	transition: color 0.2s;
}

.ob-casino-row__name:hover { color: var(--terra); }

.ob-casino-row__bonus {
	font-size: 0.82rem;
	color: var(--ink-4);
	margin: 0;
}

.ob-casino-row__rating {
	text-align: right;
	flex-shrink: 0;
}

.ob-casino-row__stars {
	display: block;
	color: var(--amber);
	font-size: 0.8rem;
	letter-spacing: 1px;
	margin-bottom: 2px;
}

.ob-casino-row__score {
	font-family: 'Cormorant Garamond', serif;
	font-size: 1.3rem;
	font-weight: 300;
	color: var(--terra);
	line-height: 1;
}

.ob-casino-row__cta { flex-shrink: 0; }

.ob-home-age-notice {
	font-size: 0.75rem;
	color: var(--ink-4);
	margin-top: 18px;
	text-align: center;
}

.ob-home-age-notice a {
	color: var(--ink-4);
	text-decoration: underline;
}

/* -------
   Listings grid
------- */

.ob-home-listings-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
	gap: 16px;
}

.ob-home-listing-card {
	background: #fff;
	border: 1px solid var(--border);
	border-radius: var(--ob-radius);
	overflow: hidden;
	text-decoration: none;
	color: var(--ink);
	transition: box-shadow 0.2s, transform 0.2s;
	display: block;
}

.ob-home-listing-card:hover {
	box-shadow: 0 8px 28px rgba(28,25,23,0.10);
	transform: translateY(-2px);
	color: var(--ink);
}

.ob-home-listing-card__img {
	height: 140px;
	background: var(--paper-3);
	overflow: hidden;
	display: flex;
	align-items: center;
	justify-content: center;
}

.ob-home-listing-card__img img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.ob-home-listing-card__img span { font-size: 2rem; }

.ob-home-listing-card__body {
	padding: 16px;
}

.ob-home-listing-card__type {
	font-size: 0.68rem;
	font-weight: 600;
	letter-spacing: 0.12em;
	text-transform: uppercase;
	color: var(--terra);
	margin: 0 0 5px;
}

.ob-home-listing-card__body h3 {
	font-family: 'Cormorant Garamond', serif;
	font-size: 1.05rem;
	font-weight: 600;
	margin: 0 0 6px;
	color: var(--ink);
}

.ob-home-listing-card__rating {
	font-size: 0.8rem;
	color: var(--amber);
	margin: 0;
}

.ob-home-listing-card__rating span {
	font-size: 0.78rem;
	color: var(--ink-4);
	margin-left: 4px;
}

/* -------
   Blog posts grid
------- */

.ob-home-posts-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
	gap: 20px;
}

.ob-home-post-card {
	background: #fff;
	border: 1px solid var(--border);
	border-radius: var(--ob-radius);
	overflow: hidden;
	display: block;
	text-decoration: none;
	color: var(--ink);
	transition: box-shadow 0.2s, transform 0.2s;
}

.ob-home-post-card:hover {
	box-shadow: 0 8px 28px rgba(28,25,23,0.10);
	transform: translateY(-2px);
	color: var(--ink);
}

.ob-home-post-card__img {
	height: 160px;
	background: var(--paper-3);
	overflow: hidden;
	display: flex;
	align-items: center;
	justify-content: center;
}

.ob-home-post-card__img img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.ob-home-post-card__img span { font-size: 2.5rem; }

.ob-home-post-card__body {
	padding: 18px;
}

.ob-home-post-card__cat {
	font-size: 0.68rem;
	font-weight: 600;
	letter-spacing: 0.12em;
	text-transform: uppercase;
	color: var(--terra);
	margin: 0 0 6px;
}

.ob-home-post-card__body h3 {
	font-family: 'Cormorant Garamond', serif;
	font-size: 1.1rem;
	font-weight: 600;
	margin: 0 0 8px;
	line-height: 1.35;
	color: var(--ink);
}

.ob-home-post-card__excerpt {
	font-size: 0.83rem;
	color: var(--ink-3);
	margin: 0 0 10px;
	line-height: 1.6;
}

.ob-home-post-card__date {
	font-size: 0.72rem;
	color: var(--ink-4);
	margin: 0;
}

/* -------
   Trust pillars
------- */

.ob-home-trust-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
	gap: 20px;
}

.ob-home-pillar {
	background: #fff;
	border: 1px solid var(--border);
	border-radius: var(--ob-radius);
	border-left: 3px solid var(--terra);
	padding: 24px 22px;
}

.ob-home-pillar__icon {
	display: block;
	font-size: 1.4rem;
	margin-bottom: 12px;
}

.ob-home-pillar h3 {
	font-family: 'Jost', sans-serif;
	font-size: 0.9rem;
	font-weight: 600;
	margin: 0 0 8px;
	color: var(--ink);
}

.ob-home-pillar p {
	font-size: 0.83rem;
	color: var(--ink-3);
	line-height: 1.65;
	margin: 0;
}

/* -------
   Responsive
------- */

@media (max-width: 700px) {
	.ob-home-hero { padding: 52px 0 48px; }
	.ob-home-hero__stats { gap: 28px; }
	.ob-casino-row__cta { display: none; }
	.ob-casino-row__rating { display: none; }
}
</style>

<?php get_footer(); ?>
