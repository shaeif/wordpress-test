<?php
/**
 * Title: How we work
 * Slug: signal-shield/home-process
 * Categories: signal-shield
 * Description: Assess, Design, Deploy, Support.
 *
 * @package SignalShield
 */

$signal_shield_steps = array(
	array(
		'title' => __( 'Assess', 'signal-shield' ),
		'text'  => __( 'We visit your site, measure what is really happening and talk to the people who use the network every day.', 'signal-shield' ),
		'time'  => __( 'Usually 1 to 2 weeks', 'signal-shield' ),
	),
	array(
		'title' => __( 'Design', 'signal-shield' ),
		'text'  => __( 'You get a clear plan with drawings, equipment lists and costs, written so management can approve it without a glossary.', 'signal-shield' ),
		'time'  => __( 'Fixed-price proposal', 'signal-shield' ),
	),
	array(
		'title' => __( 'Deploy', 'signal-shield' ),
		'text'  => __( 'We install and test, or supervise your contractor, working around guests, classes and shift patterns.', 'signal-shield' ),
		'time'  => __( 'Tested before handover', 'signal-shield' ),
	),
	array(
		'title' => __( 'Support', 'signal-shield' ),
		'text'  => __( 'After go-live we monitor, fine-tune and stay on call. The same team that designed it looks after it.', 'signal-shield' ),
		'time'  => __( 'Monthly or on demand', 'signal-shield' ),
	),
);
?>
<!-- wp:group {"tagName":"section","className":"ss-section ss-process","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-process"><!-- wp:group {"align":"wide","className":"ss-section__head","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-section__head"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'How we work', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Four steps, no surprises', 'signal-shield' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:html -->
<ol class="ss-steps alignwide">
<?php foreach ( $signal_shield_steps as $signal_shield_i => $signal_shield_step ) : ?>
	<li class="ss-step">
		<span class="ss-step__num" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $signal_shield_i + 1 ) ); ?></span>
		<h3 class="ss-step__title"><?php echo esc_html( $signal_shield_step['title'] ); ?></h3>
		<p><?php echo esc_html( $signal_shield_step['text'] ); ?></p>
		<p class="ss-step__time"><?php echo esc_html( $signal_shield_step['time'] ); ?></p>
	</li>
<?php endforeach; ?>
</ol>
<!-- /wp:html --></section>
<!-- /wp:group -->
