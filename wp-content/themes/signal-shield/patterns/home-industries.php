<?php
/**
 * Title: Industries served
 * Slug: signal-shield/home-industries
 * Categories: signal-shield
 * Description: Oil & gas, hospitality, education and corporate offices, each with its typical challenge.
 *
 * @package SignalShield
 */

$signal_shield_industries = array(
	array(
		'name' => __( 'Oil & gas', 'signal-shield' ),
		'text' => __( 'Remote and offshore sites where Wi-Fi has to survive heat, steel and salt air, and safety systems must never share a network with crew internet.', 'signal-shield' ),
		'icon' => '<path d="M4 21h16M7 21V9l5-6 5 6v12M9.5 13h5M9 17h6"/>',
	),
	array(
		'name' => __( 'Hospitality', 'signal-shield' ),
		'text' => __( 'Guests judge you by your Wi-Fi, while card payments, staff systems and room TVs must stay completely separate from them.', 'signal-shield' ),
		'icon' => '<path d="M3 20V8h18v12M3 14h18M7 11h.01M12 11h.01M17 11h.01M9 20v-3h6v3"/><path d="M8 8V4h8v4"/>',
	),
	array(
		'name' => __( 'Education', 'signal-shield' ),
		'text' => __( 'Hundreds of devices connect at 7:30 every morning, and students need protecting from harmful content and from each other.', 'signal-shield' ),
		'icon' => '<path d="m2 9 10-5 10 5-10 5z"/><path d="M6 11v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5M22 9v6"/>',
	),
	array(
		'name' => __( 'Corporate offices', 'signal-shield' ),
		'text' => __( 'Video calls in every room, staff working from anywhere, and clients and insurers asking you to prove your data is safe.', 'signal-shield' ),
		'icon' => '<rect x="4" y="3" width="16" height="18" rx="1"/><path d="M8 7h2M14 7h2M8 11h2M14 11h2M8 15h2M14 15h2M10 21v-3h4v3"/>',
	),
);
?>
<!-- wp:group {"tagName":"section","className":"ss-section ss-section--surface","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-section--surface"><!-- wp:group {"align":"wide","className":"ss-split","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-split"><!-- wp:group {"className":"ss-split__intro","layout":{"type":"default"}} -->
<div class="wp-block-group ss-split__intro"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'Industries', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Built around how your sector works', 'signal-shield' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'A hotel lobby, a school hall and a gas platform need very different networks. We have worked in all four sectors below, so we start with the right questions.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:html -->
<ul class="ss-industries">
<?php foreach ( $signal_shield_industries as $signal_shield_industry ) : ?>
	<li class="ss-industry">
		<span class="ss-icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?php echo $signal_shield_industry['icon']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG markup defined above. ?></svg></span>
		<div>
			<h3 class="ss-industry__name"><?php echo esc_html( $signal_shield_industry['name'] ); ?></h3>
			<p><?php echo esc_html( $signal_shield_industry['text'] ); ?></p>
		</div>
	</li>
<?php endforeach; ?>
</ul>
<!-- /wp:html --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
