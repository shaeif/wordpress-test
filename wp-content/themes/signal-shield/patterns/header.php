<?php
/**
 * Title: Site header
 * Slug: signal-shield/header
 * Categories: header
 * Block Types: core/template-part/header
 * Inserter: no
 *
 * @package SignalShield
 */

$signal_shield_nav = array(
	array( __( 'Services', 'signal-shield' ), '/services/' ),
	array( __( 'Health check', 'signal-shield' ), '/network-health-check/' ),
	array( __( 'Case studies', 'signal-shield' ), '/case-studies/' ),
	array( __( 'About', 'signal-shield' ), '/about/' ),
	array( __( 'Contact', 'signal-shield' ), '/contact/' ),
);
?>
<!-- wp:group {"className":"ss-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group ss-header"><!-- wp:group {"align":"wide","className":"ss-header__inner","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide ss-header__inner"><!-- wp:html -->
<a class="ss-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
	<span class="ss-logo__bars" aria-hidden="true"><span></span><span></span><span></span></span>
	<span class="ss-logo__text"><?php esc_html_e( 'Signal & Shield', 'signal-shield' ); ?><span class="screen-reader-text"> <?php esc_html_e( 'Consulting, home', 'signal-shield' ); ?></span></span>
</a>
<!-- /wp:html -->

<!-- wp:group {"className":"ss-header__actions","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
<div class="wp-block-group ss-header__actions"><!-- wp:navigation {"overlayBackgroundColor":"midnight","overlayTextColor":"fog","overlayMenu":"mobile","className":"ss-nav","layout":{"type":"flex","justifyContent":"right"},"ariaLabel":"<?php echo esc_attr( __( 'Main', 'signal-shield' ) ); ?>"} -->
<?php foreach ( $signal_shield_nav as $signal_shield_item ) : ?>
<!-- wp:navigation-link <?php echo wp_json_encode( array( 'label' => $signal_shield_item[0], 'url' => home_url( $signal_shield_item[1] ), 'kind' => 'custom', 'isTopLevelLink' => true ) ); ?> /-->
<?php endforeach; ?>
<!-- /wp:navigation -->

<!-- wp:buttons {"className":"ss-header__cta"} -->
<div class="wp-block-buttons ss-header__cta"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Book a consultation', 'signal-shield' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
