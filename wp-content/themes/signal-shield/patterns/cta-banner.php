<?php
/**
 * Title: Consultation call to action
 * Slug: signal-shield/cta-banner
 * Categories: signal-shield, call-to-action
 * Description: Lime banner inviting visitors to book a consultation.
 *
 * @package SignalShield
 */

?>
<!-- wp:group {"tagName":"section","className":"ss-section ss-cta","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-cta"><!-- wp:group {"align":"wide","className":"ss-cta__inner","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-cta__inner"><!-- wp:group {"className":"ss-cta__copy","layout":{"type":"default"}} -->
<div class="wp-block-group ss-cta__copy"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Not sure where to start?', 'signal-shield' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Book a free 30-minute consultation. We will listen, ask a few questions and tell you honestly whether you need us.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons {"className":"ss-cta__actions"} -->
<div class="wp-block-buttons ss-cta__actions"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Book a consultation', 'signal-shield' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/network-health-check/' ) ); ?>"><?php esc_html_e( 'Take the health check', 'signal-shield' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
