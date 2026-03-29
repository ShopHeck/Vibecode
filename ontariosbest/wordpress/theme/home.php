<?php
/**
 * Blog Home Template (Posts Index)
 * Used when a page is set as the Posts Page in Settings > Reading.
 */

get_header();

$is_category = is_category();
$is_tag      = is_tag();
$is_search   = is_search();
$is_author   = is_author();

if ( $is_search ) {
	$page_title    = 'Search Results for: "' . get_search_query() . '"';
	$page_subtitle = get_found_posts() . ' result' . ( get_found_posts() === 1 ? '' : 's' ) . ' found';
} elseif ( $is_category ) {
	$page_title    = 'Category: ' . single_cat_title( '', false );
	$page_subtitle = category_description();
} elseif ( $is_tag ) {
	$page_title    = 'Tag: ' . single_tag_title( '', false );
	$page_subtitle = '';
} elseif ( $is_author ) {
	$page_title    = 'Articles by ' . get_the_author_meta( 'display_name', get_query_var( 'author' ) );
	$page_subtitle = '';
} else {
	$page_title    = 'OntariosBest Blog';
	$page_subtitle = 'Expert guides, reviews, and insider picks for Ontario';
}
?>

<!-- Header -->
<div style="background:linear-gradient(135deg,#1a1a2e,#16213e);color:#fff;padding:44px 0;text-align:center;">
	<div class="ast-container">
		<h1 style="color:#fff;margin:0 0 8px;font-size:clamp(24px,3.5vw,38px);"><?php echo esc_html( $page_title ); ?></h1>
		<?php if ( $page_subtitle ) : ?>
			<p style="color:#ccd;font-size:16px;margin:0;"><?php echo wp_kses_post( $page_subtitle ); ?></p>
		<?php endif; ?>
	</div>
</div>

<div style="padding:40px 0 64px;">
	<div class="ast-container">
		<div style="display:flex;gap:36px;align-items:flex-start;">

			<!-- Posts Grid -->
			<div style="flex:1;min-width:0;">

				<?php if ( have_posts() ) : ?>

				<!-- Category filter tabs (only on main blog archive) -->
				<?php if ( ! $is_category && ! $is_tag && ! $is_search && ! $is_author ) :
					$blog_cats = get_categories( array( 'hide_empty' => true, 'number' => 8 ) );
					if ( $blog_cats ) :
				?>
				<div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:28px;">
					<a href="/blog/" style="padding:6px 16px;border-radius:20px;font-size:13px;background:var(--ob-primary);color:#fff;text-decoration:none;">All</a>
					<?php foreach ( $blog_cats as $cat ) : ?>
					<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
					   style="padding:6px 16px;border-radius:20px;font-size:13px;background:#f0f0f0;color:#444;text-decoration:none;">
						<?php echo esc_html( $cat->name ); ?>
					</a>
					<?php endforeach; ?>
				</div>
				<?php endif; endif; ?>

				<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px;">
				<?php while ( have_posts() ) : the_post(); ?>

					<article style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);overflow:hidden;display:flex;flex-direction:column;">
						<?php if ( has_post_thumbnail() ) : ?>
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'medium', array( 'style' => 'width:100%;height:180px;object-fit:cover;display:block;' ) ); ?>
							</a>
						<?php else : ?>
							<div style="background:linear-gradient(135deg,var(--ob-dark),#0f3460);height:100px;display:flex;align-items:center;justify-content:center;font-size:36px;">
								📰
							</div>
						<?php endif; ?>

						<div style="padding:16px;flex:1;display:flex;flex-direction:column;">
							<!-- Category -->
							<?php
							$cats = get_the_category();
							if ( $cats ) :
							?>
							<a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>"
							   style="font-size:11px;text-transform:uppercase;letter-spacing:0.5px;font-weight:700;color:var(--ob-primary);text-decoration:none;margin-bottom:6px;display:inline-block;">
								<?php echo esc_html( $cats[0]->name ); ?>
							</a>
							<?php endif; ?>

							<h2 style="font-size:16px;font-weight:700;margin:0 0 8px;line-height:1.4;flex:1;">
								<a href="<?php the_permalink(); ?>" style="color:var(--ob-text);text-decoration:none;"><?php the_title(); ?></a>
							</h2>

							<p style="font-size:13px;color:#666;margin:0 0 12px;line-height:1.5;">
								<?php echo wp_trim_words( get_the_excerpt(), 18 ); ?>
							</p>

							<div style="display:flex;align-items:center;justify-content:space-between;margin-top:auto;">
								<span style="font-size:12px;color:#aaa;"><?php echo get_the_date( 'M j, Y' ); ?></span>
								<a href="<?php the_permalink(); ?>" style="font-size:13px;color:var(--ob-primary);font-weight:600;text-decoration:none;">Read →</a>
							</div>
						</div>
					</article>

				<?php endwhile; ?>
				</div>

				<!-- Pagination -->
				<div style="margin-top:36px;text-align:center;">
					<?php
					echo paginate_links( array(
						'prev_text' => '← Previous',
						'next_text' => 'Next →',
					) );
					?>
				</div>

				<?php else : ?>
				<div style="text-align:center;padding:48px 0;color:#888;">
					<p style="font-size:18px;">No articles found.</p>
					<?php if ( $is_search ) : ?>
						<p><a href="/blog/" style="color:var(--ob-primary);">Browse all articles →</a></p>
					<?php endif; ?>
				</div>
				<?php endif; ?>

			</div><!-- posts -->

			<!-- Sidebar -->
			<aside style="width:260px;flex-shrink:0;">

				<!-- Search -->
				<div style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:16px;margin-bottom:20px;">
					<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:flex;gap:0;border-radius:6px;overflow:hidden;border:1px solid var(--ob-border);">
						<input type="search" name="s" value="<?php echo get_search_query(); ?>" placeholder="Search articles..." style="flex:1;padding:9px 12px;font-size:13px;border:none;outline:none;">
						<button type="submit" style="background:var(--ob-primary);color:#fff;border:none;padding:9px 14px;cursor:pointer;">🔍</button>
					</form>
				</div>

				<!-- Categories -->
				<?php
				$sidebar_cats = get_categories( array( 'hide_empty' => true ) );
				if ( $sidebar_cats ) :
				?>
				<div style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:18px;margin-bottom:20px;">
					<h4 style="margin:0 0 12px;font-size:15px;font-weight:700;">Categories</h4>
					<ul style="list-style:none;margin:0;padding:0;">
						<?php foreach ( $sidebar_cats as $cat ) : ?>
						<li style="margin-bottom:6px;display:flex;justify-content:space-between;align-items:center;">
							<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" style="font-size:14px;color:var(--ob-text);text-decoration:none;">
								<?php echo esc_html( $cat->name ); ?>
							</a>
							<span style="font-size:12px;color:#aaa;"><?php echo $cat->count; ?></span>
						</li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>

				<!-- Newsletter -->
				<div style="background:linear-gradient(135deg,var(--ob-dark),#0f3460);color:#fff;border-radius:var(--ob-radius);padding:20px;margin-bottom:20px;text-align:center;">
					<h4 style="color:#fff;margin:0 0 6px;font-size:15px;">Weekly Newsletter</h4>
					<p style="font-size:13px;color:#ccd;margin:0 0 14px;">Ontario's best — free every week.</p>
					<?php if ( function_exists( 'mc4wp_show_form' ) ) : ?>
						<?php mc4wp_show_form(); ?>
					<?php else : ?>
					<form style="display:flex;flex-direction:column;gap:8px;">
						<input type="email" placeholder="Your email" style="padding:10px;border:none;border-radius:6px;font-size:13px;">
						<button type="submit" class="ob-btn" style="font-size:13px;padding:10px;">Subscribe</button>
					</form>
					<?php endif; ?>
				</div>

			</aside>

		</div><!-- flex -->
	</div><!-- .ast-container -->
</div>

<?php get_footer(); ?>
