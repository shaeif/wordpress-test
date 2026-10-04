<?php
/**
 * Title: Site footer
 * Slug: signal-shield/footer
 * Categories: footer
 * Block Types: core/template-part/footer
 * Inserter: no
 *
 * @package SignalShield
 */

?>
<!-- wp:group {"className":"ss-footer","layout":{"type":"constrained"}} -->
<div class="wp-block-group ss-footer"><!-- wp:group {"align":"wide","className":"ss-footer__grid","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-footer__grid"><!-- wp:group {"className":"ss-footer__brand","layout":{"type":"default"}} -->
<div class="wp-block-group ss-footer__brand"><!-- wp:html -->
<a class="ss-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
	<span class="ss-logo__bars" aria-hidden="true"><span></span><span></span><span></span></span>
	<span class="ss-logo__text"><?php esc_html_e( 'Signal & Shield', 'signal-shield' ); ?><span class="screen-reader-text"> <?php esc_html_e( 'Consulting, home', 'signal-shield' ); ?></span></span>
</a>
<!-- /wp:html -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Independent wireless, network and cybersecurity consulting for businesses across Qatar.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/network-health-check/' ) ); ?>"><?php esc_html_e( 'Take the free health check', 'signal-shield' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ss-footer__col","layout":{"type":"default"}} -->
<div class="wp-block-group ss-footer__col"><!-- wp:heading {"level":2,"className":"ss-footer__heading"} -->
<h2 class="wp-block-heading ss-footer__heading"><?php esc_html_e( 'Explore', 'signal-shield' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"ss-footer__links"} -->
<ul class="wp-block-list ss-footer__links"><!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Services', 'signal-shield' ); ?></a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/network-health-check/' ) ); ?>"><?php esc_html_e( 'Network health check', 'signal-shield' ); ?></a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/case-studies/' ) ); ?>"><?php esc_html_e( 'Case studies', 'signal-shield' ); ?></a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About us', 'signal-shield' ); ?></a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'signal-shield' ); ?></a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ss-footer__col","layout":{"type":"default"}} -->
<div class="wp-block-group ss-footer__col"><!-- wp:heading {"level":2,"className":"ss-footer__heading"} -->
<h2 class="wp-block-heading ss-footer__heading"><?php esc_html_e( 'Services', 'signal-shield' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"ss-footer__links"} -->
<ul class="wp-block-list ss-footer__links"><!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/services/#wireless' ) ); ?>"><?php esc_html_e( 'Wireless network design', 'signal-shield' ); ?></a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/services/#network' ) ); ?>"><?php esc_html_e( 'Enterprise network infrastructure', 'signal-shield' ); ?></a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="<?php echo esc_url( home_url( '/services/#security' ) ); ?>"><?php esc_html_e( 'Cybersecurity consulting', 'signal-shield' ); ?></a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ss-footer__col","layout":{"type":"default"}} -->
<div class="wp-block-group ss-footer__col"><!-- wp:heading {"level":2,"className":"ss-footer__heading"} -->
<h2 class="wp-block-heading ss-footer__heading"><?php esc_html_e( 'Visit or call', 'signal-shield' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ss-footer__address"} -->
<p class="ss-footer__address"><?php echo wp_kses( __( 'Office 1204, Level 12, Al Wahda Tower<br>West Bay, Doha, Qatar', 'signal-shield' ), array( 'br' => array() ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="tel:+97444127788">+974 4412 7788</a><br><a href="mailto:hello@signalshield.example">hello@signalshield.example</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ss-footer__hours"} -->
<p class="ss-footer__hours"><?php esc_html_e( 'Sunday to Thursday, 8:00 am to 5:00 pm', 'signal-shield' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"ss-footer__base","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide ss-footer__base"><!-- wp:paragraph -->
<p><?php echo esc_html( sprintf( /* translators: %s: current year */ __( '© %s Signal & Shield Consulting W.L.L. All rights reserved.', 'signal-shield' ), gmdate( 'Y' ) ) ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>"><?php esc_html_e( 'Privacy', 'signal-shield' ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
