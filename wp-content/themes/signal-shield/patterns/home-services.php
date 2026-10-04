<?php
/**
 * Title: Service highlights
 * Slug: signal-shield/home-services
 * Categories: signal-shield, services
 * Description: Three cards for Wireless, Network and Security.
 *
 * @package SignalShield
 */

$signal_shield_services = array(
	array(
		'anchor' => 'wireless',
		'label'  => '/wireless',
		'title'  => __( 'Wi-Fi that reaches every room', 'signal-shield' ),
		'text'   => __( 'We measure your building, then plan exactly where each access point goes, from hotel corridors to offshore decks. No more hunting for a signal.', 'signal-shield' ),
		'link'   => __( 'Wireless network design', 'signal-shield' ),
		'icon'   => '<path d="M2.5 9.5a15 15 0 0 1 19 0"/><path d="M5.5 13a10 10 0 0 1 13 0"/><path d="M8.5 16.5a5 5 0 0 1 7 0"/><circle cx="12" cy="20" r="1" fill="currentColor"/>',
	),
	array(
		'anchor' => 'network',
		'label'  => '/network',
		'title'  => __( 'A network that stays up', 'signal-shield' ),
		'text'   => __( 'Switches, cabling and internet links designed to keep working on your busiest day, keep different users apart and grow without a rebuild.', 'signal-shield' ),
		'link'   => __( 'Enterprise network infrastructure', 'signal-shield' ),
		'icon'   => '<rect x="9" y="2.5" width="6" height="5" rx="1"/><rect x="2.5" y="16.5" width="6" height="5" rx="1"/><rect x="15.5" y="16.5" width="6" height="5" rx="1"/><path d="M12 7.5v4.5M5.5 16.5V12h13v4.5"/>',
	),
	array(
		'anchor' => 'security',
		'label'  => '/security',
		'title'  => __( 'Security you can explain', 'signal-shield' ),
		'text'   => __( 'An independent review of your firewall, passwords, backups and updates, with a clear report that tells you what to fix first and what it costs.', 'signal-shield' ),
		'link'   => __( 'Cybersecurity consulting', 'signal-shield' ),
		'icon'   => '<path d="M12 2.5 20 5.5v6c0 5-3.4 8.6-8 10-4.6-1.4-8-5-8-10v-6z"/><path d="m8.5 12 2.5 2.5 4.5-5"/>',
	),
);
?>
<!-- wp:group {"tagName":"section","className":"ss-section ss-section--light","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-section--light"><!-- wp:group {"align":"wide","className":"ss-section__head","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-section__head"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'What we do', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Three services. One accountable team.', 'signal-shield' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ss-lead"} -->
<p class="ss-lead"><?php esc_html_e( 'Most problems we see sit somewhere between the Wi-Fi, the network behind it and the security around both. We look at all three, so nothing falls between contractors.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"ss-cards ss-cards--3","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-cards ss-cards--3"><?php foreach ( $signal_shield_services as $signal_shield_service ) : ?><!-- wp:group {"className":"ss-card","layout":{"type":"default"}} -->
<div class="wp-block-group ss-card"><!-- wp:html -->
<div class="ss-card__top"><span class="ss-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?php echo $signal_shield_service['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG markup defined above. ?></svg></span><span class="ss-mono"><?php echo esc_html( $signal_shield_service['label'] ); ?></span></div>
<!-- /wp:html -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html( $signal_shield_service['title'] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $signal_shield_service['text'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ss-card__link"} -->
<p class="ss-card__link"><a href="<?php echo esc_url( home_url( '/services/#' . $signal_shield_service['anchor'] ) ); ?>"><?php echo esc_html( $signal_shield_service['link'] ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --><?php endforeach; ?></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
