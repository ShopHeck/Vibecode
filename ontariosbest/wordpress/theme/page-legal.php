<?php
/**
 * Template Name: Legal Page
 * Used for: Privacy Policy, Terms & Conditions, Affiliate Disclosure
 */

get_header();

while ( have_posts() ) : the_post();
?>

<div style="background:var(--ob-light);border-bottom:1px solid var(--ob-border);padding:36px 0;">
	<div class="ast-container">
		<nav style="font-size:13px;color:#888;margin-bottom:10px;">
			<a href="/" style="color:#888;text-decoration:none;">Home</a>
			<span style="margin:0 6px;">›</span>
			<span><?php the_title(); ?></span>
		</nav>
		<h1 style="margin:0 0 6px;font-size:clamp(24px,3vw,36px);"><?php the_title(); ?></h1>
		<p style="font-size:13px;color:#888;margin:0;">
			Last updated: <strong><?php echo get_the_modified_date( 'F j, Y' ); ?></strong>
		</p>
	</div>
</div>

<div style="padding:40px 0 64px;">
	<div class="ast-container">
		<div style="display:flex;gap:40px;align-items:flex-start;">

			<!-- Table of Contents (auto-generated from h2 headings) -->
			<aside style="width:220px;flex-shrink:0;position:sticky;top:88px;">
				<div style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:18px;">
					<h4 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#888;margin:0 0 12px;">Contents</h4>
					<div id="ob-toc" style="font-size:13px;">
						<!-- Populated by JS below -->
					</div>
				</div>
			</aside>

			<!-- Content -->
			<article style="flex:1;min-width:0;max-width:760px;">
				<div class="ob-legal-content entry-content">
					<?php the_content(); ?>
				</div>
			</article>

		</div>
	</div>
</div>

<style>
.ob-legal-content h2 {
	font-size: 20px;
	font-weight: 700;
	margin: 36px 0 12px;
	padding-top: 8px;
	border-top: 1px solid var(--ob-border);
	color: var(--ob-dark);
}
.ob-legal-content h3 {
	font-size: 16px;
	font-weight: 700;
	margin: 24px 0 8px;
}
.ob-legal-content p,
.ob-legal-content li {
	font-size: 15px;
	line-height: 1.75;
	color: #444;
}
.ob-legal-content ul,
.ob-legal-content ol {
	padding-left: 24px;
	margin-bottom: 16px;
}
.ob-legal-content a {
	color: var(--ob-primary);
}
#ob-toc a {
	display: block;
	padding: 4px 0;
	color: #555;
	text-decoration: none;
	border-left: 2px solid transparent;
	padding-left: 8px;
	transition: all 0.15s;
}
#ob-toc a:hover,
#ob-toc a.active {
	color: var(--ob-primary);
	border-left-color: var(--ob-primary);
}
@media (max-width: 768px) {
	#ob-toc { display: none; }
	aside { display: none; }
}
</style>

<script>
// Auto-generate TOC from h2 tags
(function() {
	var content = document.querySelector('.ob-legal-content');
	var toc     = document.getElementById('ob-toc');
	if (!content || !toc) return;

	var headings = content.querySelectorAll('h2');
	if (headings.length < 2) {
		toc.closest('aside').style.display = 'none';
		return;
	}

	headings.forEach(function(h, i) {
		var id = 'section-' + i;
		h.id = id;
		var a = document.createElement('a');
		a.href = '#' + id;
		a.textContent = h.textContent;
		toc.appendChild(a);
	});

	// Highlight active section on scroll
	var links = toc.querySelectorAll('a');
	window.addEventListener('scroll', function() {
		var scrollY = window.scrollY + 100;
		headings.forEach(function(h, i) {
			if (h.offsetTop <= scrollY) {
				links.forEach(function(l) { l.classList.remove('active'); });
				links[i].classList.add('active');
			}
		});
	});
})();
</script>

<?php
endwhile;
get_footer();
?>
