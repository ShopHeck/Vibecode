<?php
/**
 * Template Name: Best Of Landing Page
 * Usage: /best-of/best-ontario-casinos/, /best-of/best-restaurants-toronto/, etc.
 * Configured via ACF fields on the page.
 */

get_header();

$hero_title    = get_post_meta( get_the_ID(), '_bestof_hero_title', true ) ?: get_the_title();
$hero_subtitle = get_post_meta( get_the_ID(), '_bestof_hero_subtitle', true );
$post_type     = get_post_meta( get_the_ID(), '_bestof_post_type', true ) ?: 'casino';
$taxonomy      = get_post_meta( get_the_ID(), '_bestof_taxonomy', true );
$term_slug     = get_post_meta( get_the_ID(), '_bestof_term_slug', true );
$limit         = absint( get_post_meta( get_the_ID(), '_bestof_limit', true ) ) ?: 10;
$intro_text    = get_post_meta( get_the_ID(), '_bestof_intro', true );
$show_table    = get_post_meta( get_the_ID(), '_bestof_show_table', true );

// Determine rating/affiliate meta keys by post type
$is_casino = ( $post_type === 'casino' );
$rating_key = $is_casino ? '_casino_overall_rating' : '_listing_overall_rating';
$aff_key    = $is_casino ? '_casino_affiliate_url'  : '_listing_affiliate_url';
$bonus_key  = $is_casino ? '_casino_welcome_bonus'  : '_listing_price_range';

// Query
$args = array(
	'post_type'      => $post_type,
	'posts_per_page' => $limit,
	'orderby'        => 'meta_value_num',
	'meta_key'       => $rating_key,
	'order'          => 'DESC',
);
if ( $taxonomy && $term_slug ) {
	$args['tax_query'] = array( array(
		'taxonomy' => $taxonomy,
		'field'    => 'slug',
		'terms'    => $term_slug,
	) );
}
$query = new WP_Query( $args );
?>

<!-- Hero -->
<section style="background:linear-gradient(135deg,#1a1a2e,#0f3460);color:#fff;padding:56px 0;text-align:center;">
	<div class="ast-container">
		<p style="font-size:12px;letter-spacing:2px;text-transform:uppercase;color:var(--ob-primary);margin:0 0 10px;">Ontario's Best</p>
		<h1 style="color:#fff;font-size:clamp(28px,4vw,48px);margin:0 0 14px;line-height:1.15;"><?php echo esc_html( $hero_title ); ?></h1>
		<?php if ( $hero_subtitle ) : ?>
			<p style="color:#ccd;font-size:17px;max-width:600px;margin:0 auto;"><?php echo esc_html( $hero_subtitle ); ?></p>
		<?php endif; ?>
		<div style="margin-top:20px;font-size:13px;color:#aaa;">
			Last updated: <strong style="color:#ccd;"><?php echo get_the_modified_date( 'F Y' ); ?></strong>
			&nbsp;·&nbsp;
			<?php echo $query->found_posts; ?> listings reviewed
		</div>
	</div>
</section>

<!-- Quick nav / jump links -->
<?php if ( $query->have_posts() ) : ?>
<div style="background:#fff;border-bottom:1px solid var(--ob-border);padding:12px 0;">
	<div class="ast-container">
		<div style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
			<span style="font-size:13px;color:#888;font-weight:600;">Jump to:</span>
			<?php
			$jump_query = clone $query;
			$jump_query->rewind_posts();
			$n = 1;
			while ( $jump_query->have_posts() ) : $jump_query->the_post();
			?>
				<a href="#listing-<?php echo get_the_ID(); ?>"
				   style="font-size:13px;color:var(--ob-primary);text-decoration:none;padding:4px 10px;background:#fffbf0;border-radius:4px;">
					#<?php echo $n; ?> <?php the_title(); ?>
				</a>
			<?php $n++; endwhile; wp_reset_postdata(); ?>
		</div>
	</div>
</div>
<?php endif; ?>

<div style="padding:40px 0 64px;">
	<div class="ast-container">
		<div style="display:flex;gap:36px;align-items:flex-start;">

			<!-- Main -->
			<div style="flex:1;min-width:0;">

				<!-- Intro text (editorial content) -->
				<?php if ( $intro_text ) : ?>
				<div style="background:#fffbf0;border-left:4px solid var(--ob-primary);padding:16px 20px;border-radius:0 var(--ob-radius) var(--ob-radius) 0;margin-bottom:28px;font-size:15px;line-height:1.7;color:#444;">
					<?php echo wp_kses_post( $intro_text ); ?>
				</div>
				<?php endif; ?>

				<!-- Affiliate Disclosure -->
				<p class="affiliate-disclosure">
					<strong>Disclosure:</strong> OntariosBest.com may earn a commission when you click links on this page. Our rankings are editorially independent.
				</p>

				<!-- Listings -->
				<?php
				$rank = 1;
				while ( $query->have_posts() ) : $query->the_post();
					$rating   = get_post_meta( get_the_ID(), $rating_key, true );
					$aff_link = get_post_meta( get_the_ID(), $aff_key, true );
					$bonus    = get_post_meta( get_the_ID(), $bonus_key, true );
					$badge    = $is_casino ? ob_casino_meta( '_casino_badge' ) : ob_sponsored_badge();
					$address  = ! $is_casino ? ob_listing_meta( '_listing_address' ) : '';
				?>
				<div id="listing-<?php echo get_the_ID(); ?>" style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:20px;margin-bottom:16px;display:flex;gap:20px;align-items:flex-start;">

					<!-- Rank -->
					<div style="min-width:40px;text-align:center;">
						<div style="font-size:28px;font-weight:900;color:var(--ob-primary);line-height:1;">#<?php echo $rank; ?></div>
					</div>

					<!-- Thumbnail -->
					<?php if ( has_post_thumbnail() ) : ?>
					<div style="width:100px;flex-shrink:0;">
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'thumbnail', array( 'style' => 'max-width:100%;border-radius:6px;' ) ); ?></a>
					</div>
					<?php endif; ?>

					<!-- Info -->
					<div style="flex:1;min-width:0;">
						<?php if ( $badge ) : ?>
							<span style="background:var(--ob-primary);color:#fff;font-size:10px;font-weight:700;padding:2px 7px;border-radius:3px;text-transform:uppercase;display:inline-block;margin-bottom:6px;"><?php echo esc_html( $badge ); ?></span>
						<?php endif; ?>
						<h2 style="font-size:18px;font-weight:700;margin:0 0 4px;">
							<a href="<?php the_permalink(); ?>" style="color:var(--ob-text);text-decoration:none;"><?php the_title(); ?></a>
						</h2>
						<?php if ( $rating ) echo ob_render_stars( $rating ); ?>
						<?php if ( $bonus ) : ?>
							<p style="font-size:14px;color:#555;margin:6px 0 0;">
								<?php echo $is_casino ? 'Bonus: ' : ''; ?><strong style="color:var(--ob-accent);"><?php echo esc_html( $bonus ); ?></strong>
							</p>
						<?php endif; ?>
						<?php if ( $address ) : ?>
							<p style="font-size:13px;color:#888;margin:4px 0 0;">📍 <?php echo esc_html( $address ); ?></p>
						<?php endif; ?>
						<p style="font-size:13px;color:#666;margin:8px 0 0;"><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
					</div>

					<!-- CTA -->
					<div style="flex-shrink:0;text-align:center;min-width:100px;">
						<?php if ( $aff_link ) : ?>
							<a href="<?php echo esc_url( $aff_link ); ?>"
							   class="ob-btn"
							   target="_blank"
							   rel="nofollow noopener sponsored"
							   style="display:block;text-align:center;">
								<?php echo $is_casino ? 'Play Now' : 'Visit'; ?>
							</a>
						<?php endif; ?>
						<a href="<?php the_permalink(); ?>" style="font-size:12px;color:#888;display:inline-block;margin-top:8px;">Full Review</a>
					</div>

				</div>
				<?php
					$rank++;
				endwhile;
				wp_reset_postdata();
				?>

				<!-- Page content (editorial body, FAQ, etc.) -->
				<div style="margin-top:36px;" class="ob-review-content">
					<?php the_content(); ?>
				</div>

			</div><!-- main -->

			<!-- Sidebar -->
			<aside style="width:260px;flex-shrink:0;position:sticky;top:24px;">

				<div style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:18px;margin-bottom:20px;">
					<h4 style="margin:0 0 12px;font-size:15px;">Quick Summary</h4>
					<?php
					$summary_query = new WP_Query( $args );
					$n = 1;
					while ( $summary_query->have_posts() && $n <= 5 ) : $summary_query->the_post();
						$r = get_post_meta( get_the_ID(), $rating_key, true );
					?>
					<div style="display:flex;align-items:center;gap:10px;padding:6px 0;border-bottom:1px solid #f0f0f0;">
						<span style="font-size:13px;font-weight:700;color:var(--ob-primary);min-width:20px;">#<?php echo $n; ?></span>
						<a href="<?php the_permalink(); ?>" style="font-size:13px;color:var(--ob-text);text-decoration:none;flex:1;"><?php the_title(); ?></a>
						<?php if ( $r ) : ?>
							<span style="font-size:12px;font-weight:700;color:var(--ob-primary);"><?php echo number_format( (float) $r, 1 ); ?></span>
						<?php endif; ?>
					</div>
					<?php $n++; endwhile; wp_reset_postdata(); ?>
				</div>

				<?php if ( $is_casino ) : ?>
				<div style="background:#1a1a1a;color:#aaa;border-radius:var(--ob-radius);padding:14px;font-size:12px;text-align:center;">
					19+ | Please gamble responsibly<br>
					<a href="/responsible-gambling/" style="color:var(--ob-primary);">Learn More</a>
				</div>
				<?php endif; ?>

				<div style="margin-top:16px;background:linear-gradient(135deg,var(--ob-dark),#0f3460);color:#fff;border-radius:var(--ob-radius);padding:18px;text-align:center;">
					<p style="font-size:13px;font-weight:700;margin:0 0 6px;">Want to be featured?</p>
					<p style="font-size:12px;color:#ccd;margin:0 0 12px;">Sponsored placements available for this category.</p>
					<a href="/advertise/" class="ob-btn" style="font-size:13px;padding:8px 16px;display:inline-block;">Advertise</a>
				</div>

			</aside>

		</div><!-- flex -->
	</div><!-- .ast-container -->
</div>

<?php get_footer(); ?>
