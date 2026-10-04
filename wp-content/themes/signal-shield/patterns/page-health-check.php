<?php
/**
 * Title: Network health check page
 * Slug: signal-shield/page-health-check
 * Categories: signal-shield
 * Post Types: page
 * Inserter: no
 *
 * @package SignalShield
 */

?>
<!-- wp:group {"tagName":"section","className":"ss-section ss-page-hero ss-page-hero--compact","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-page-hero ss-page-hero--compact"><!-- wp:group {"align":"wide","className":"ss-page-hero__inner","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-page-hero__inner"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'Free · about 5 minutes · no sign-up', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'Network health check', 'signal-shield' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ss-lead"} -->
<p class="ss-lead"><?php esc_html_e( 'Ten plain-language questions about your Wi-Fi, network and security. At the end you get a Green, Amber or Red rating for each area and one practical tip to act on.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"ss-section ss-section--light ss-quiz-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-section--light ss-quiz-section"><!-- wp:shortcode -->
[ss_health_check]
<!-- /wp:shortcode -->

<!-- wp:paragraph {"className":"ss-footnote"} -->
<p class="ss-footnote"><?php esc_html_e( 'Your answers stay in your browser. We do not store or see them unless you choose to send them to us.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph --></section>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"signal-shield/cta-banner"} /-->
