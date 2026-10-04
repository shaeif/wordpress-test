<?php
/**
 * Title: Client testimonials
 * Slug: signal-shield/home-testimonials
 * Categories: signal-shield, testimonials
 * Description: Three client quotes.
 *
 * @package SignalShield
 */

$signal_shield_quotes = array(
	array(
		'quote' => __( 'Guests used to complain about the Wi-Fi by the pool and on the upper floors almost every week. Since the redesign, Wi-Fi has disappeared from our review summaries completely.', 'signal-shield' ),
		'name'  => __( 'Rania H.', 'signal-shield' ),
		'role'  => __( 'Director of Rooms, five-star hotel, West Bay', 'signal-shield' ),
	),
	array(
		'quote' => __( 'They explained our firewall findings to the board in twenty minutes without a single acronym. For the first time we knew what to fix first and why.', 'signal-shield' ),
		'name'  => __( 'Khalid A.', 'signal-shield' ),
		'role'  => __( 'Finance & Admin Manager, engineering firm, Lusail', 'signal-shield' ),
	),
	array(
		'quote' => __( 'Exam mornings were chaos with 900 laptops connecting at once. This year not one exam started late because of the network.', 'signal-shield' ),
		'name'  => __( 'Mariam S.', 'signal-shield' ),
		'role'  => __( 'Head of IT, international school, Al Rayyan', 'signal-shield' ),
	),
);
?>
<!-- wp:group {"tagName":"section","className":"ss-section ss-section--light","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-section--light"><!-- wp:group {"align":"wide","className":"ss-section__head","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-section__head"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'Client stories', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'What our clients notice', 'signal-shield' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"ss-cards ss-cards--3","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-cards ss-cards--3"><?php foreach ( $signal_shield_quotes as $signal_shield_quote ) : ?><!-- wp:quote {"className":"ss-quote"} -->
<blockquote class="wp-block-quote ss-quote"><!-- wp:paragraph -->
<p><?php echo esc_html( $signal_shield_quote['quote'] ); ?></p>
<!-- /wp:paragraph --><cite><strong><?php echo esc_html( $signal_shield_quote['name'] ); ?></strong><br><?php echo esc_html( $signal_shield_quote['role'] ); ?></cite></blockquote>
<!-- /wp:quote --><?php endforeach; ?></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"ss-footnote"} -->
<p class="ss-footnote"><?php esc_html_e( 'Names shortened at our clients’ request.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph --></section>
<!-- /wp:group -->
