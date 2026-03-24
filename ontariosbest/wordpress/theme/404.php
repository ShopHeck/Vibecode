<?php
/**
 * 404 Error Template
 */

get_header();
?>

<div style="padding:80px 0 100px;text-align:center;">
	<div class="ast-container" style="max-width:600px;">

		<div style="font-size:96px;line-height:1;margin-bottom:16px;">🔍</div>

		<h1 style="font-size:clamp(28px,4vw,48px);font-weight:900;margin:0 0 12px;color:var(--ob-dark);">
			Page Not Found
		</h1>

		<p style="font-size:18px;color:#666;margin:0 0 32px;line-height:1.6;">
			We couldn't find that page. It may have moved, been deleted, or the URL might be wrong.
		</p>

		<!-- Search -->
		<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="max-width:460px;margin:0 auto 36px;display:flex;border-radius:var(--ob-radius);overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.1);">
			<input
				type="search"
				name="s"
				placeholder="Search OntariosBest..."
				style="flex:1;padding:14px 18px;font-size:15px;border:1px solid var(--ob-border);border-right:none;outline:none;border-radius:var(--ob-radius) 0 0 var(--ob-radius);">
			<button type="submit" style="background:var(--ob-primary);color:#fff;border:none;padding:14px 22px;font-size:15px;font-weight:700;cursor:pointer;border-radius:0 var(--ob-radius) var(--ob-radius) 0;">
				Search
			</button>
		</form>

		<!-- Quick Links -->
		<div style="margin-bottom:32px;">
			<p style="font-size:14px;color:#888;margin:0 0 16px;">Or browse a category:</p>
			<div style="display:flex;flex-wrap:wrap;gap:10px;justify-content:center;">
				<?php
				$links = array(
					'🎰 Casinos'       => '/casinos/',
					'✈️ Travel'         => '/travel/',
					'🍽️ Restaurants'    => '/restaurants/',
					'🎭 Entertainment'  => '/entertainment/',
					'🔧 Services'       => '/services/',
					'📰 Blog'           => '/blog/',
				);
				foreach ( $links as $label => $url ) :
				?>
				<a href="<?php echo esc_url( home_url( $url ) ); ?>"
				   style="background:#fff;border:1px solid var(--ob-border);padding:8px 18px;border-radius:20px;font-size:13px;font-weight:600;color:var(--ob-text);text-decoration:none;transition:all 0.15s;">
					<?php echo esc_html( $label ); ?>
				</a>
				<?php endforeach; ?>
			</div>
		</div>

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ob-btn" style="font-size:15px;padding:13px 32px;display:inline-block;">
			← Back to Homepage
		</a>

	</div>
</div>

<?php get_footer(); ?>
