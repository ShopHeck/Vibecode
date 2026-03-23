<?php
/**
 * Blog Post Template
 */

get_header();

while ( have_posts() ) : the_post();
?>

<div class="ob-page-wrap">
	<div class="ast-container" style="padding-top:28px;padding-bottom:56px;">

		<!-- Breadcrumb -->
		<nav style="font-size:13px;color:#888;margin-bottom:20px;">
			<a href="/" style="color:#888;text-decoration:none;">Home</a>
			<span style="margin:0 6px;">›</span>
			<a href="/blog/" style="color:#888;text-decoration:none;">Blog</a>
			<span style="margin:0 6px;">›</span>
			<span style="color:var(--ob-text);"><?php the_title(); ?></span>
		</nav>

		<div style="display:flex;gap:36px;align-items:flex-start;">

			<!-- Article -->
			<article style="flex:1;min-width:0;max-width:760px;">

				<!-- Category tag -->
				<?php
				$cats = get_the_category();
				if ( $cats ) :
				?>
				<div style="margin-bottom:10px;">
					<?php foreach ( $cats as $cat ) : ?>
					<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"
					   style="background:var(--ob-light);color:var(--ob-primary);font-size:12px;font-weight:700;padding:3px 10px;border-radius:4px;text-decoration:none;text-transform:uppercase;letter-spacing:0.5px;">
						<?php echo esc_html( $cat->name ); ?>
					</a>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<h1 style="font-size:clamp(24px,3.5vw,38px);font-weight:900;line-height:1.2;margin:0 0 14px;">
					<?php the_title(); ?>
				</h1>

				<!-- Meta -->
				<div style="display:flex;align-items:center;gap:14px;margin-bottom:24px;flex-wrap:wrap;font-size:13px;color:#888;">
					<span>By <strong style="color:var(--ob-text);"><?php the_author(); ?></strong></span>
					<span>·</span>
					<span><?php echo get_the_date(); ?></span>
					<?php if ( get_the_modified_date() !== get_the_date() ) : ?>
					<span>· Updated <?php echo get_the_modified_date(); ?></span>
					<?php endif; ?>
				</div>

				<!-- Featured Image -->
				<?php if ( has_post_thumbnail() ) : ?>
				<div style="margin-bottom:28px;">
					<?php the_post_thumbnail( 'large', array( 'style' => 'width:100%;height:auto;border-radius:var(--ob-radius);display:block;' ) ); ?>
				</div>
				<?php endif; ?>

				<!-- Content -->
				<div class="ob-review-content entry-content" style="font-size:16px;line-height:1.75;color:#333;">
					<?php the_content(); ?>
				</div>

				<!-- Tags -->
				<?php
				$tags = get_the_tags();
				if ( $tags ) :
				?>
				<div style="margin-top:28px;padding-top:20px;border-top:1px solid var(--ob-border);">
					<span style="font-size:13px;color:#888;">Tags: </span>
					<?php foreach ( $tags as $tag ) : ?>
					<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
					   style="font-size:13px;background:#f0f0f0;color:#555;padding:3px 10px;border-radius:4px;text-decoration:none;display:inline-block;margin:2px;">
						<?php echo esc_html( $tag->name ); ?>
					</a>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<!-- Related Posts -->
				<?php
				$related = get_posts( array(
					'post_type'      => 'post',
					'posts_per_page' => 3,
					'post__not_in'   => array( get_the_ID() ),
					'category__in'   => wp_get_post_categories( get_the_ID() ),
					'orderby'        => 'rand',
				) );
				if ( $related ) :
				?>
				<div style="margin-top:40px;padding-top:28px;border-top:1px solid var(--ob-border);">
					<h3 style="font-size:18px;font-weight:700;margin:0 0 16px;">Related Articles</h3>
					<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
						<?php foreach ( $related as $rpost ) : ?>
						<a href="<?php echo get_permalink( $rpost->ID ); ?>" style="text-decoration:none;color:var(--ob-text);">
							<div style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);overflow:hidden;">
								<?php if ( has_post_thumbnail( $rpost->ID ) ) : ?>
									<?php echo get_the_post_thumbnail( $rpost->ID, 'medium', array( 'style' => 'width:100%;height:130px;object-fit:cover;display:block;' ) ); ?>
								<?php endif; ?>
								<div style="padding:12px;">
									<p style="font-size:14px;font-weight:600;margin:0;line-height:1.4;"><?php echo esc_html( $rpost->post_title ); ?></p>
								</div>
							</div>
						</a>
						<?php endforeach; ?>
					</div>
				</div>
				<?php endif; ?>

			</article>

			<!-- Sidebar -->
			<aside style="width:280px;flex-shrink:0;position:sticky;top:24px;">

				<!-- Top Casinos Widget -->
				<?php
				$sidebar_casinos = new WP_Query( array(
					'post_type'      => 'casino',
					'posts_per_page' => 4,
					'orderby'        => 'meta_value_num',
					'meta_key'       => '_casino_overall_rating',
					'order'          => 'DESC',
				) );
				if ( $sidebar_casinos->have_posts() ) :
				?>
				<div style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:18px;margin-bottom:20px;">
					<h4 style="margin:0 0 12px;font-size:15px;font-weight:700;">Top Ontario Casinos</h4>
					<?php
					$n = 1;
					while ( $sidebar_casinos->have_posts() ) : $sidebar_casinos->the_post();
						$r   = ob_casino_meta( '_casino_overall_rating' );
						$aff = ob_casino_meta( '_casino_affiliate_url' );
					?>
					<div style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #f5f5f5;">
						<span style="font-size:13px;font-weight:700;color:var(--ob-primary);min-width:18px;"><?php echo $n; ?></span>
						<?php if ( has_post_thumbnail() ) : ?>
							<?php the_post_thumbnail( 'thumbnail', array( 'style' => 'width:36px;height:36px;object-fit:contain;border-radius:4px;' ) ); ?>
						<?php endif; ?>
						<div style="flex:1;min-width:0;">
							<a href="<?php the_permalink(); ?>" style="font-size:13px;font-weight:600;color:var(--ob-text);text-decoration:none;display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?php the_title(); ?></a>
							<?php if ( $r ) : ?>
								<span style="font-size:12px;color:var(--ob-primary);">★ <?php echo number_format( (float) $r, 1 ); ?></span>
							<?php endif; ?>
						</div>
						<?php if ( $aff ) : ?>
							<a href="<?php echo esc_url( $aff ); ?>" style="font-size:11px;background:var(--ob-primary);color:#fff;padding:4px 8px;border-radius:4px;text-decoration:none;white-space:nowrap;" target="_blank" rel="nofollow noopener sponsored">Play</a>
						<?php endif; ?>
					</div>
					<?php $n++; endwhile; wp_reset_postdata(); ?>
					<a href="/casinos/" style="font-size:13px;color:var(--ob-primary);font-weight:600;display:block;text-align:center;margin-top:10px;text-decoration:none;">View All Casinos →</a>
				</div>
				<?php endif; ?>

				<!-- Newsletter -->
				<div style="background:linear-gradient(135deg,var(--ob-dark),#0f3460);color:#fff;border-radius:var(--ob-radius);padding:20px;text-align:center;margin-bottom:20px;">
					<h4 style="color:#fff;margin:0 0 6px;font-size:15px;">Stay Updated</h4>
					<p style="font-size:13px;color:#ccd;margin:0 0 14px;">Get Ontario's best deals and reviews weekly.</p>
					<?php if ( function_exists( 'mc4wp_show_form' ) ) : ?>
						<?php mc4wp_show_form(); ?>
					<?php else : ?>
					<form style="display:flex;flex-direction:column;gap:8px;">
						<input type="email" placeholder="Your email" style="padding:10px;border:none;border-radius:6px;font-size:13px;">
						<button type="submit" class="ob-btn" style="font-size:13px;padding:10px;">Subscribe</button>
					</form>
					<?php endif; ?>
				</div>

				<!-- Advertise CTA -->
				<div style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:18px;text-align:center;">
					<p style="font-size:13px;font-weight:700;margin:0 0 4px;">Advertise on OntariosBest</p>
					<p style="font-size:12px;color:#888;margin:0 0 12px;">Sponsored placements available.</p>
					<a href="/advertise/" class="ob-btn-outline" style="font-size:13px;padding:8px 16px;display:inline-block;">Learn More</a>
				</div>

			</aside>

		</div><!-- flex -->
	</div><!-- .ast-container -->
</div>

<?php
endwhile;
get_footer();
?>
