<?php
/**
 * Template Name: Responsible Gambling
 * Required page for Ontario casino affiliate compliance.
 */

get_header();
?>

<!-- Hero — intentionally NOT casino gold; uses safe/calm palette -->
<section style="background:linear-gradient(135deg,#1a4731,#14532d);color:#fff;padding:56px 0;text-align:center;">
	<div class="ast-container">
		<p style="font-size:13px;letter-spacing:2px;text-transform:uppercase;color:#86efac;margin:0 0 10px;font-weight:600;">Play Responsibly</p>
		<h1 style="color:#fff;font-size:clamp(28px,4vw,44px);margin:0 0 14px;">Responsible Gambling</h1>
		<p style="color:#bbf7d0;font-size:17px;max-width:580px;margin:0 auto;">
			Gambling should be entertainment — not a way to make money or escape problems. If you need help, it's available.
		</p>
		<!-- Prominent helpline -->
		<div style="margin-top:28px;display:inline-block;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);border-radius:var(--ob-radius);padding:16px 28px;">
			<p style="font-size:13px;color:#bbf7d0;margin:0 0 4px;text-transform:uppercase;letter-spacing:0.5px;">Free, Confidential Help — 24/7</p>
			<a href="tel:18665312600" style="font-size:26px;font-weight:900;color:#fff;text-decoration:none;">1-866-531-2600</a>
			<p style="font-size:13px;color:#bbf7d0;margin:4px 0 0;">ConnexOntario</p>
		</div>
	</div>
</section>

<!-- Quick resource links -->
<div style="background:#f0fdf4;border-bottom:1px solid #bbf7d0;padding:14px 0;">
	<div class="ast-container" style="display:flex;flex-wrap:wrap;gap:12px;justify-content:center;align-items:center;">
		<span style="font-size:13px;font-weight:600;color:#166534;">Quick Resources:</span>
		<a href="tel:18665312600" style="font-size:13px;background:#166534;color:#fff;padding:6px 14px;border-radius:6px;text-decoration:none;font-weight:600;">📞 Call ConnexOntario</a>
		<a href="https://connexontario.ca" target="_blank" rel="nofollow noopener" style="font-size:13px;color:#166534;border:1px solid #166534;padding:6px 14px;border-radius:6px;text-decoration:none;font-weight:600;">connexontario.ca</a>
		<a href="https://www.gamesense.com" target="_blank" rel="nofollow noopener" style="font-size:13px;color:#166534;border:1px solid #166534;padding:6px 14px;border-radius:6px;text-decoration:none;font-weight:600;">GameSense</a>
		<a href="https://igamingontario.ca/en/self-exclusion" target="_blank" rel="nofollow noopener" style="font-size:13px;color:#166534;border:1px solid #166534;padding:6px 14px;border-radius:6px;text-decoration:none;font-weight:600;">Self-Exclusion</a>
	</div>
</div>

<div style="padding:48px 0 64px;">
	<div class="ast-container" style="max-width:820px;margin:0 auto;">

		<!-- What is Responsible Gambling -->
		<section style="margin-bottom:48px;">
			<h2 style="font-size:24px;font-weight:800;margin:0 0 16px;color:var(--ob-dark);">What Is Responsible Gambling?</h2>
			<p style="font-size:16px;line-height:1.75;color:#444;margin:0 0 14px;">
				Responsible gambling means keeping gambling as a form of entertainment — something you choose to do for fun, with money you can afford to lose. It means staying in control of how much time and money you spend, and knowing when to stop.
			</p>
			<p style="font-size:16px;line-height:1.75;color:#444;margin:0;">
				Gambling is not a reliable way to make money. Every game has a built-in house edge, which means over time, the house wins. Gambling should never be used to pay debts, cover bills, or solve financial problems.
			</p>
		</section>

		<!-- Warning Signs -->
		<section style="margin-bottom:48px;">
			<h2 style="font-size:24px;font-weight:800;margin:0 0 16px;color:var(--ob-dark);">Warning Signs of Problem Gambling</h2>
			<p style="font-size:16px;line-height:1.75;color:#444;margin:0 0 16px;">
				Problem gambling can affect anyone. Watch for these signs in yourself or someone you care about:
			</p>
			<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
				<?php
				$signs = array(
					'Spending more than you can afford to lose',
					'Chasing losses to try to win back money',
					'Lying to family or friends about gambling',
					'Borrowing money or selling belongings to gamble',
					'Neglecting work, school, or relationships',
					'Feeling anxious or irritable when not gambling',
					'Gambling to escape stress, depression, or loneliness',
					'Unable to stop even when you want to',
				);
				foreach ( $signs as $sign ) : ?>
				<div style="background:#fef2f2;border-left:3px solid #ef4444;border-radius:0 6px 6px 0;padding:10px 14px;font-size:14px;color:#7f1d1d;">
					<?php echo esc_html( $sign ); ?>
				</div>
				<?php endforeach; ?>
			</div>
			<p style="font-size:14px;color:#888;margin:16px 0 0;">
				If you recognise any of these signs, please reach out for help. It's free, confidential, and available 24/7.
			</p>
		</section>

		<!-- Help Resources -->
		<section style="margin-bottom:48px;">
			<h2 style="font-size:24px;font-weight:800;margin:0 0 16px;color:var(--ob-dark);">Help and Support Resources</h2>

			<?php
			$resources = array(
				array(
					'name'  => 'ConnexOntario',
					'desc'  => 'Free, confidential health services information for Ontario residents. Call anytime for mental health, addictions, and problem gambling support.',
					'phone' => '1-866-531-2600',
					'url'   => 'https://connexontario.ca',
					'label' => 'connexontario.ca',
				),
				array(
					'name'  => 'GameSense',
					'desc'  => 'A responsible gambling program offering information, tools, and one-on-one support from trained advisors at Ontario gaming sites.',
					'phone' => null,
					'url'   => 'https://www.gamesense.com',
					'label' => 'gamesense.com',
				),
				array(
					'name'  => 'Gambling Addiction Ontario',
					'desc'  => 'Treatment and support services for problem gambling across Ontario, including residential and outpatient programs.',
					'phone' => '1-888-230-3505',
					'url'   => 'https://www.problemgambling.ca',
					'label' => 'problemgambling.ca',
				),
				array(
					'name'  => 'Gamblers Anonymous Ontario',
					'desc'  => 'A peer-support fellowship for anyone with a gambling problem. Meetings available across Ontario and online.',
					'phone' => null,
					'url'   => 'https://www.gamblersanonymous.org',
					'label' => 'gamblersanonymous.org',
				),
			);
			foreach ( $resources as $r ) : ?>
			<div style="background:#fff;border:1px solid var(--ob-border);border-radius:var(--ob-radius);padding:20px;margin-bottom:12px;display:flex;align-items:flex-start;gap:16px;">
				<div style="width:40px;height:40px;background:#f0fdf4;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;">🛟</div>
				<div>
					<h3 style="font-size:16px;font-weight:700;margin:0 0 4px;"><?php echo esc_html( $r['name'] ); ?></h3>
					<p style="font-size:14px;color:#555;margin:0 0 8px;line-height:1.6;"><?php echo esc_html( $r['desc'] ); ?></p>
					<div style="display:flex;flex-wrap:wrap;gap:10px;">
						<?php if ( $r['phone'] ) : ?>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $r['phone'] ) ); ?>"
							   style="font-size:13px;font-weight:700;color:#166534;text-decoration:none;">
								📞 <?php echo esc_html( $r['phone'] ); ?>
							</a>
						<?php endif; ?>
						<a href="<?php echo esc_url( $r['url'] ); ?>" target="_blank" rel="nofollow noopener"
						   style="font-size:13px;color:#166534;text-decoration:none;">
							🔗 <?php echo esc_html( $r['label'] ); ?>
						</a>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</section>

		<!-- Self-Exclusion -->
		<section style="margin-bottom:48px;">
			<h2 style="font-size:24px;font-weight:800;margin:0 0 16px;color:var(--ob-dark);">Self-Exclusion Programs</h2>
			<p style="font-size:16px;line-height:1.75;color:#444;margin:0 0 16px;">
				If you want to stop gambling, self-exclusion lets you voluntarily ban yourself from Ontario online casinos for a set period. It's a powerful tool to help you take a break.
			</p>
			<div style="background:#fffbf0;border:1px solid var(--ob-primary);border-radius:var(--ob-radius);padding:20px;">
				<h3 style="font-size:16px;font-weight:700;margin:0 0 8px;">iGaming Ontario Self-Exclusion</h3>
				<p style="font-size:14px;color:#555;margin:0 0 12px;line-height:1.6;">
					iGaming Ontario (iGO) offers a province-wide self-exclusion program that blocks you from all regulated Ontario online casino sites simultaneously. Exclusion periods range from 30 days to permanent.
				</p>
				<a href="https://igamingontario.ca/en/self-exclusion" target="_blank" rel="nofollow noopener"
				   style="display:inline-block;background:var(--ob-dark);color:#fff;padding:10px 20px;border-radius:6px;font-size:14px;font-weight:700;text-decoration:none;">
					Apply for Self-Exclusion →
				</a>
			</div>
		</section>

		<!-- Tips for Safe Play -->
		<section style="margin-bottom:48px;">
			<h2 style="font-size:24px;font-weight:800;margin:0 0 16px;color:var(--ob-dark);">Tips for Safer Play</h2>
			<div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
				<?php
				$tips = array(
					array( '💰', 'Set a budget before you play and stick to it. Never gamble with money you need for bills, rent, or food.' ),
					array( '⏱️', 'Set a time limit. Use your phone timer or the casino\'s session timer tools.' ),
					array( '🚫', 'Never chase losses. If you\'re down, walk away — chasing leads to bigger losses.' ),
					array( '🧠', 'Gamble sober. Alcohol and other substances impair judgement and lead to poor decisions.' ),
					array( '⚖️', 'Balance gambling with other hobbies. It should be one form of entertainment, not your only one.' ),
					array( '📊', 'Use casino tools: deposit limits, session limits, cooling-off periods, and reality checks.' ),
				);
				foreach ( $tips as $tip ) : ?>
				<div style="background:#f9fafb;border-radius:var(--ob-radius);padding:14px;display:flex;gap:12px;align-items:flex-start;">
					<span style="font-size:22px;flex-shrink:0;"><?php echo $tip[0]; ?></span>
					<p style="font-size:14px;line-height:1.6;color:#444;margin:0;"><?php echo esc_html( $tip[1] ); ?></p>
				</div>
				<?php endforeach; ?>
			</div>
		</section>

		<!-- Age Verification -->
		<section style="background:#1a1a2e;color:#fff;border-radius:var(--ob-radius);padding:28px;text-align:center;">
			<p style="font-size:40px;margin:0 0 8px;">🔞</p>
			<h2 style="color:#fff;font-size:22px;font-weight:800;margin:0 0 10px;">19+ Only in Ontario</h2>
			<p style="color:#9ca3af;font-size:15px;max-width:500px;margin:0 auto;">
				You must be 19 years of age or older to gamble at any Ontario online casino. All licensed operators are required to verify age before allowing real-money play.
			</p>
		</section>

		<!-- Disclaimer -->
		<p style="font-size:12px;color:#aaa;margin-top:32px;line-height:1.7;text-align:center;">
			OntariosBest.com is an independent affiliate website and does not provide gambling services. We are not affiliated with ConnexOntario, iGaming Ontario, or any casino operator. If you have a gambling problem, please seek help immediately.
		</p>

	</div><!-- .ast-container -->
</div>

<?php get_footer(); ?>
