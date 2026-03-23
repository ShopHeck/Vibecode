<?php
/**
 * Homepage Template — OntariosBest.com
 */

get_header();
?>

<!-- =====================================================
     HERO
====================================================== -->
<section class="ob-hero" style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 60%,#0f3460 100%);padding:80px 0 64px;text-align:center;color:#fff;">
	<div class="ast-container">
		<p style="font-size:13px;letter-spacing:2px;text-transform:uppercase;color:var(--ob-primary);margin:0 0 12px;font-weight:600;">Ontario's #1 Discovery Guide</p>
		<h1 style="font-size:clamp(36px,5vw,60px);font-weight:900;color:#fff;margin:0 0 16px;line-height:1.1;">
			Find Ontario's Best
		</h1>
		<p style="font-size:18px;color:#ccd;max-width:560px;margin:0 auto 36px;line-height:1.6;">
			Casinos, restaurants, travel, entertainment, and services — expert-reviewed and ranked so you don't have to guess.
		</p>

		<!-- Search Bar -->
		<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="max-width:560px;margin:0 auto;display:flex;gap:0;border-radius:var(--ob-radius);overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.4);">
			<input
				type="search"
				name="s"
				placeholder="Search casinos, restaurants, travel..."
				style="flex:1;padding:16px 20px;font-size:15px;border:none;outline:none;background:#fff;color:var(--ob-text);"
			>
			<button type="submit" style="background:var(--ob-primary);color:#fff;border:none;padding:16px 28px;font-size:15px;font-weight:700;cursor:pointer;white-space:nowrap;">
				Search
			</button>
		</form>

		<!-- Quick category links -->
		<div style="margin-top:28px;display:flex;flex-wrap:wrap;gap:10px;justify-content:center;">
			<?php
			$quick_links = array(
				'Casinos'       => '/casinos/',
				'Travel'        => '/travel/',
				'Restaurants'   => '/restaurants/',
				'Entertainment' => '/entertainment/',
				'Services'      => '/services/',
			);
			foreach ( $quick_links as $label => $url ) :
			?>
			<a href="<?php echo esc_url( $url ); ?>" style="background:rgba(255,255,255,0.1);color:#fff;padding:8px 18px;border-radius:20px;font-size:13px;text-decoration:none;border:1px solid rgba(255,255,255,0.2);transition:all 0.2s;">
				<?php echo esc_html( $label ); ?>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- =====================================================
     CATEGORY GRID
====================================================== -->
<section style="padding:56px 0;background:#fff;">
	<div class="ast-container">
		<div style="text-align:center;margin-bottom:36px;">
			<h2 style="font-size:28px;font-weight:800;margin:0 0 8px;">Browse by Category</h2>
			<p style="color:#666;margin:0;">Expert-ranked listings across Ontario's top categories</p>
		</div>

		<div class="ob-category-grid">
			<?php
			$categories = array(
				array(
					'icon'  => '🎰',
					'title' => 'Online Casinos',
					'desc'  => 'Ontario-licensed, reviewed',
					'url'   => '/casinos/',
					'count' => wp_count_posts( 'casino' )->publish,
				),
				array(
					'icon'  => '✈️',
					'title' => 'Travel & Tourism',
					'desc'  => 'Hotels, destinations, getaways',
					'url'   => '/travel/',
					'count' => wp_count_posts( 'travel' )->publish,
				),
				array(
					'icon'  => '🍽️',
					'title' => 'Restaurants',
					'desc'  => 'Best dining across Ontario',
					'url'   => '/restaurants/',
					'count' => wp_count_posts( 'restaurant' )->publish,
				),
				array(
					'icon'  => '🎭',
					'title' => 'Entertainment',
					'desc'  => 'Shows, attractions, events',
					'url'   => '/entertainment/',
					'count' => wp_count_posts( 'entertainment' )->publish,
				),
				array(
					'icon'  => '🔧',
					'title' => 'Services',
					'desc'  => 'Local businesses you can trust',
					'url'   => '/services/',
					'count' => wp_count_posts( 'service' )->publish,
				),
				array(
					'icon'  => '🛍️',
					'title' => 'Shopping',
					'desc'  => 'Retailers, boutiques, online shops',
					'url'   => '/shopping/',
					'count' => wp_count_posts( 'shopping' )->publish,
				),
			);
			foreach ( $categories as $cat ) :
			?>
			<a href="<?php echo esc_url( $cat['url'] ); ?>" class="ob-category-card">
				<div class="ob-category-card__icon"><?php echo $cat['icon']; ?></div>
				<div class="ob-category-card__title"><?php echo esc_html( $cat['title'] ); ?></div>
				<div style="font-size:12px;color:#888;margin-top:4px;"><?php echo esc_html( $cat['desc'] ); ?></div>
				<?php if ( $cat['count'] ) : ?>
				<div class="ob-category-card__count"><?php echo $cat['count']; ?> listings</div>
				<?php endif; ?>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- =====================================================
     TOP CASINOS THIS MONTH
====================================================== -->
<section style="padding:56px 0;background:var(--ob-light);">
	<div class="ast-container">
		<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
			<div>
				<h2 style="font-size:26px;font-weight:800;margin:0 0 4px;">Top Ontario Casinos</h2>
				<p style="color:#666;margin:0;font-size:14px;">Licensed, reviewed, and ranked by our experts</p>
			</div>
			<a href="/casinos/" style="font-size:14px;color:var(--ob-primary);font-weight:600;text-decoration:none;">View All Casinos →</a>
		</div>

		<?php
		$top_casinos = new WP_Query( array(
			'post_type'      => 'casino',
			'posts_per_page' => 5,
			'orderby'        => 'meta_value_num',
			'meta_key'       => '_casino_overall_rating',
			'order'          => 'DESC',
		) );
		$rank = 1;
		while ( $top_casinos->have_posts() ) : $top_casinos->the_post();
			$rating   = ob_casino_meta( '_casino_overall_rating' );
			$bonus    = ob_casino_meta( '_casino_welcome_bonus' );
			$aff_link = ob_casino_meta( '_casino_affiliate_url' );
			$badge    = ob_casino_meta( '_casino_badge' );
		?>
		<div class="ob-casino-card" style="margin-bottom:12px;">
			<div class="ob-casino-card__rank">#<?php echo $rank; ?></div>
			<div class="ob-casino-card__logo">
				<?php if ( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail' ); ?></a>
				<?php endif; ?>
			</div>
			<div class="ob-casino-card__info">
				<?php if ( $badge ) : ?>
					<span style="background:var(--ob-primary);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:3px;text-transform:uppercase;display:inline-block;margin-bottom:4px;"><?php echo esc_html( $badge ); ?></span>
				<?php endif; ?>
				<h3 class="ob-casino-card__name" style="font-size:16px;"><a href="<?php the_permalink(); ?>" style="color:var(--ob-text);text-decoration:none;"><?php the_title(); ?></a></h3>
				<?php if ( $rating ) echo ob_render_stars( $rating ); ?>
				<?php if ( $bonus ) : ?>
					<p class="ob-casino-card__bonus">Bonus: <strong><?php echo esc_html( $bonus ); ?></strong></p>
				<?php endif; ?>
			</div>
			<div class="ob-casino-card__cta">
				<?php if ( $aff_link ) : ?>
					<a href="<?php echo esc_url( $aff_link ); ?>" class="ob-btn" target="_blank" rel="nofollow noopener sponsored">Play Now</a>
				<?php endif; ?>
				<br><a href="<?php the_permalink(); ?>" style="font-size:12px;color:#888;display:inline-block;margin-top:6px;">Review</a>
			</div>
		</div>
		<?php
			$rank++;
		endwhile;
		wp_reset_postdata();
		?>

		<p style="font-size:12px;color:#aaa;margin-top:12px;">19+ | Please gamble responsibly. <a href="/responsible-gambling/" style="color:#aaa;">Learn more</a></p>
	</div>
</section>

<!-- =====================================================
     FEATURED LISTINGS (Sponsored)
====================================================== -->
<?php
$featured_args = array(
	'post_type'      => array( 'travel', 'restaurant', 'entertainment', 'service' ),
	'posts_per_page' => 3,
	'meta_query'     => array(
		array(
			'key'   => '_listing_featured',
			'value' => '1',
		),
	),
);
$featured_query = new WP_Query( $featured_args );
if ( $featured_query->have_posts() ) :
?>
<section style="padding:56px 0;background:#fff;">
	<div class="ast-container">
		<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
			<div>
				<h2 style="font-size:26px;font-weight:800;margin:0 0 4px;">Featured Listings</h2>
				<p style="color:#666;margin:0;font-size:14px;">Top-rated picks across Ontario</p>
			</div>
		</div>
		<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:20px;">
		<?php while ( $featured_query->have_posts() ) : $featured_query->the_post();
			$rating  = ob_listing_meta( '_listing_overall_rating' );
			$address = ob_listing_meta( '_listing_address' );
			$badge   = ob_sponsored_badge();
		?>
			<div style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);overflow:hidden;transition:box-shadow 0.2s;">
				<?php if ( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail( 'medium', array( 'style' => 'width:100%;height:180px;object-fit:cover;display:block;' ) ); ?>
					</a>
				<?php endif; ?>
				<div style="padding:16px;">
					<?php if ( $badge ) : ?>
						<span style="background:var(--ob-primary);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:3px;text-transform:uppercase;display:inline-block;margin-bottom:8px;"><?php echo esc_html( $badge ); ?></span>
					<?php endif; ?>
					<h3 style="font-size:16px;font-weight:700;margin:0 0 4px;">
						<a href="<?php the_permalink(); ?>" style="color:var(--ob-text);text-decoration:none;"><?php the_title(); ?></a>
					</h3>
					<?php if ( $rating ) echo ob_render_stars( $rating ); ?>
					<?php if ( $address ) : ?>
						<p style="font-size:13px;color:#888;margin:6px 0 0;">📍 <?php echo esc_html( $address ); ?></p>
					<?php endif; ?>
					<a href="<?php the_permalink(); ?>" style="display:inline-block;margin-top:12px;font-size:13px;font-weight:600;color:var(--ob-primary);text-decoration:none;">View Listing →</a>
				</div>
			</div>
		<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- =====================================================
     LATEST REVIEWS
====================================================== -->
<section style="padding:56px 0;background:var(--ob-light);">
	<div class="ast-container">
		<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;flex-wrap:wrap;gap:12px;">
			<div>
				<h2 style="font-size:26px;font-weight:800;margin:0 0 4px;">Latest Reviews</h2>
				<p style="color:#666;margin:0;font-size:14px;">Fresh picks from the OntariosBest team</p>
			</div>
			<a href="/blog/" style="font-size:14px;color:var(--ob-primary);font-weight:600;text-decoration:none;">View All →</a>
		</div>

		<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;">
		<?php
		$latest = new WP_Query( array(
			'post_type'      => 'post',
			'posts_per_page' => 3,
		) );
		while ( $latest->have_posts() ) : $latest->the_post();
		?>
			<article style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);overflow:hidden;">
				<?php if ( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium', array( 'style' => 'width:100%;height:160px;object-fit:cover;display:block;' ) ); ?></a>
				<?php endif; ?>
				<div style="padding:16px;">
					<p style="font-size:12px;color:#aaa;margin:0 0 6px;text-transform:uppercase;letter-spacing:0.5px;"><?php echo get_the_date(); ?></p>
					<h3 style="font-size:15px;font-weight:700;margin:0 0 8px;line-height:1.4;">
						<a href="<?php the_permalink(); ?>" style="color:var(--ob-text);text-decoration:none;"><?php the_title(); ?></a>
					</h3>
					<p style="font-size:13px;color:#666;margin:0;"><?php echo wp_trim_words( get_the_excerpt(), 18 ); ?></p>
				</div>
			</article>
		<?php endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</section>

<!-- =====================================================
     TRUST / WHY US
====================================================== -->
<section style="padding:56px 0;background:#fff;">
	<div class="ast-container">
		<div style="text-align:center;margin-bottom:40px;">
			<h2 style="font-size:26px;font-weight:800;margin:0 0 8px;">Why Trust OntariosBest?</h2>
			<p style="color:#666;max-width:500px;margin:0 auto;">Every listing is independently researched, tested, and scored before it appears on this site.</p>
		</div>
		<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:28px;text-align:center;">
			<?php
			$pillars = array(
				array( '🔍', 'Independent Reviews', 'We accept no payment for editorial scores or rankings.' ),
				array( '✅', 'Verified Listings', 'Every listing is confirmed accurate before publishing.' ),
				array( '🏆', 'Expert Ranked', 'Scored on quality, value, and Ontario-specific criteria.' ),
				array( '🔄', 'Regularly Updated', 'Reviews and listings are refreshed every quarter.' ),
			);
			foreach ( $pillars as $p ) :
			?>
			<div>
				<div style="font-size:36px;margin-bottom:12px;"><?php echo $p[0]; ?></div>
				<h3 style="font-size:16px;font-weight:700;margin:0 0 6px;"><?php echo esc_html( $p[1] ); ?></h3>
				<p style="font-size:13px;color:#666;margin:0;"><?php echo esc_html( $p[2] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<!-- =====================================================
     NEWSLETTER OPT-IN
====================================================== -->
<section style="padding:56px 0;background:linear-gradient(135deg,var(--ob-dark),#0f3460);color:#fff;text-align:center;">
	<div class="ast-container">
		<h2 style="color:#fff;font-size:26px;font-weight:800;margin:0 0 8px;">Get Ontario's Best in Your Inbox</h2>
		<p style="color:#ccd;margin:0 0 28px;">New reviews, top deals, and insider picks — free, every week.</p>
		<?php if ( function_exists( 'mc4wp_show_form' ) ) : ?>
			<?php mc4wp_show_form(); ?>
		<?php else : ?>
			<form style="max-width:440px;margin:0 auto;display:flex;gap:0;border-radius:var(--ob-radius);overflow:hidden;">
				<input type="email" placeholder="Your email address" style="flex:1;padding:14px 18px;font-size:15px;border:none;outline:none;">
				<button type="submit" style="background:var(--ob-primary);color:#fff;border:none;padding:14px 24px;font-weight:700;cursor:pointer;">Subscribe</button>
			</form>
			<p style="font-size:12px;color:#aaa;margin-top:10px;">No spam. Unsubscribe anytime.</p>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
