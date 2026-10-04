<?php
/**
 * Title: Page not found
 * Slug: signal-shield/not-found
 * Categories: signal-shield
 * Inserter: no
 *
 * @package SignalShield
 */

?>
<!-- wp:group {"tagName":"section","className":"ss-section ss-page-hero ss-not-found","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-page-hero ss-not-found"><!-- wp:group {"align":"wide","className":"ss-page-hero__inner","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-page-hero__inner"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'Error 404 · no signal', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'This page is out of range', 'signal-shield' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ss-lead"} -->
<p class="ss-lead"><?php esc_html_e( 'The link may be old or mistyped. Try one of these instead.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go to the home page', 'signal-shield' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact us', 'signal-shield' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
