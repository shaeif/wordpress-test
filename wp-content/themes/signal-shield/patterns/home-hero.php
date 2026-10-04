<?php
/**
 * Title: Home hero
 * Slug: signal-shield/home-hero
 * Categories: signal-shield, banner
 * Description: Headline, subline, two calls to action and a sample health check scorecard.
 *
 * @package SignalShield
 */

?>
<!-- wp:group {"tagName":"section","className":"ss-section ss-hero","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-hero"><!-- wp:group {"align":"wide","className":"ss-hero__grid","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-hero__grid"><!-- wp:group {"className":"ss-hero__copy","layout":{"type":"default"}} -->
<div class="wp-block-group ss-hero__copy"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'Network & security consulting · Doha, Qatar', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'Know exactly where your network stands.', 'signal-shield' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ss-lead"} -->
<p class="ss-lead"><?php esc_html_e( 'Wi-Fi that reaches every room, networks that stay up and security you can explain to your board. Independent advice for energy sites, hotels, schools and offices across Qatar.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"ss-hero__actions"} -->
<div class="wp-block-buttons ss-hero__actions"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/network-health-check/' ) ); ?>"><?php esc_html_e( 'Take the free network health check', 'signal-shield' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Book a consultation', 'signal-shield' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"ss-hero__note"} -->
<p class="ss-hero__note"><?php esc_html_e( 'Ten questions · about five minutes · no sign-up needed', 'signal-shield' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:html -->
<figure class="ss-hero__visual">
	<div class="ss-preview">
		<div class="ss-preview__head">
			<span class="ss-preview__title"><?php esc_html_e( 'Your health check', 'signal-shield' ); ?></span>
			<span class="ss-preview__meta"><?php esc_html_e( '10 / 10 answered', 'signal-shield' ); ?></span>
		</div>
		<ul class="ss-preview__rows">
			<li class="ss-preview__row">
				<span><strong><?php esc_html_e( 'Wireless', 'signal-shield' ); ?></strong><?php esc_html_e( 'Coverage looks solid.', 'signal-shield' ); ?></span>
				<span class="ss-rating ss-rating--green"><?php esc_html_e( 'Green', 'signal-shield' ); ?></span>
			</li>
			<li class="ss-preview__row">
				<span><strong><?php esc_html_e( 'Network', 'signal-shield' ); ?></strong><?php esc_html_e( 'Separate guest and staff Wi-Fi.', 'signal-shield' ); ?></span>
				<span class="ss-rating ss-rating--amber"><?php esc_html_e( 'Amber', 'signal-shield' ); ?></span>
			</li>
			<li class="ss-preview__row">
				<span><strong><?php esc_html_e( 'Security', 'signal-shield' ); ?></strong><?php esc_html_e( 'Test a backup restore this month.', 'signal-shield' ); ?></span>
				<span class="ss-rating ss-rating--red"><?php esc_html_e( 'Red', 'signal-shield' ); ?></span>
			</li>
		</ul>
	</div>
	<figcaption><?php esc_html_e( 'Example scorecard from the free health check', 'signal-shield' ); ?></figcaption>
</figure>
<!-- /wp:html --></div>
<!-- /wp:group -->

<!-- wp:html -->
<ul class="ss-proof alignwide" aria-label="<?php esc_attr_e( 'At a glance', 'signal-shield' ); ?>">
	<li><span class="ss-proof__value">2016</span><span class="ss-proof__label"><?php esc_html_e( 'Founded in Doha, independent ever since', 'signal-shield' ); ?></span></li>
	<li><span class="ss-proof__value">180+</span><span class="ss-proof__label"><?php esc_html_e( 'Sites surveyed, onshore and offshore', 'signal-shield' ); ?></span></li>
	<li><span class="ss-proof__value">0</span><span class="ss-proof__label"><?php esc_html_e( 'Commission taken on equipment we recommend', 'signal-shield' ); ?></span></li>
</ul>
<!-- /wp:html --></section>
<!-- /wp:group -->
