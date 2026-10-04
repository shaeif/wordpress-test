<?php
/**
 * Title: Contact page
 * Slug: signal-shield/page-contact
 * Categories: signal-shield
 * Post Types: page
 * Inserter: no
 *
 * @package SignalShield
 */

$signal_shield_whatsapp = 'https://wa.me/97455127788?text=' . rawurlencode( __( 'Hello Signal & Shield, I would like to talk about our network.', 'signal-shield' ) );
$signal_shield_map      = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( 'West Bay, Doha, Qatar' );
?>
<!-- wp:group {"tagName":"section","className":"ss-section ss-page-hero ss-page-hero--compact","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-page-hero ss-page-hero--compact"><!-- wp:group {"align":"wide","className":"ss-page-hero__inner","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-page-hero__inner"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'Contact', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'Let’s talk about your network', 'signal-shield' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ss-lead"} -->
<p class="ss-lead"><?php esc_html_e( 'Tell us a little about your site and what is not working. A consultant, not a salesperson, will reply within one working day.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"ss-section ss-section--light","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-section--light"><!-- wp:group {"align":"wide","className":"ss-contact","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-contact"><!-- wp:group {"className":"ss-contact__form","layout":{"type":"default"}} -->
<div class="wp-block-group ss-contact__form"><!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Send us a message', 'signal-shield' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[ss_contact_form]
<!-- /wp:shortcode --></div>
<!-- /wp:group -->

<!-- wp:html -->
<aside class="ss-contact__aside" aria-labelledby="ss-contact-direct">
	<h2 id="ss-contact-direct" class="ss-contact__aside-title"><?php esc_html_e( 'Prefer to talk?', 'signal-shield' ); ?></h2>
	<a class="ss-whatsapp" href="<?php echo esc_url( $signal_shield_whatsapp ); ?>" target="_blank" rel="noopener">
		<svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2Zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1a6.7 6.7 0 0 1-3.3-2.9c-.2-.4.2-.4.7-1.3.1-.2 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2 5.2 5.2 0 0 0 1.1 2.7 11.8 11.8 0 0 0 4.5 4c1.7.7 2.3.8 3.2.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2l-.4-.3Z"/></svg>
		<span><?php esc_html_e( 'Chat on WhatsApp', 'signal-shield' ); ?><span class="screen-reader-text"> <?php esc_html_e( '(opens in a new tab)', 'signal-shield' ); ?></span></span>
	</a>
	<dl class="ss-contact__details">
		<div>
			<dt><?php esc_html_e( 'Call', 'signal-shield' ); ?></dt>
			<dd><a href="tel:+97444127788">+974 4412 7788</a></dd>
		</div>
		<div>
			<dt><?php esc_html_e( 'WhatsApp', 'signal-shield' ); ?></dt>
			<dd>+974 5512 7788</dd>
		</div>
		<div>
			<dt><?php esc_html_e( 'Email', 'signal-shield' ); ?></dt>
			<dd><a href="mailto:hello@signalshield.example">hello@signalshield.example</a></dd>
		</div>
		<div>
			<dt><?php esc_html_e( 'Office', 'signal-shield' ); ?></dt>
			<dd>
				<address><?php echo wp_kses( __( 'Office 1204, Level 12<br>Al Wahda Tower, Al Corniche Street<br>West Bay, Doha, Qatar', 'signal-shield' ), array( 'br' => array() ) ); ?></address>
				<a href="<?php echo esc_url( $signal_shield_map ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Open in Google Maps', 'signal-shield' ); ?><span class="screen-reader-text"> <?php esc_html_e( '(opens in a new tab)', 'signal-shield' ); ?></span></a>
			</dd>
		</div>
	</dl>
	<h3 class="ss-contact__hours-title"><?php esc_html_e( 'Business hours', 'signal-shield' ); ?></h3>
	<table class="ss-hours">
		<caption class="screen-reader-text"><?php esc_html_e( 'Office opening hours, Doha time', 'signal-shield' ); ?></caption>
		<tbody>
			<tr><th scope="row"><?php esc_html_e( 'Sunday to Thursday', 'signal-shield' ); ?></th><td><?php esc_html_e( '8:00 am – 5:00 pm', 'signal-shield' ); ?></td></tr>
			<tr><th scope="row"><?php esc_html_e( 'Friday and Saturday', 'signal-shield' ); ?></th><td><?php esc_html_e( 'Closed', 'signal-shield' ); ?></td></tr>
			<tr><th scope="row"><?php esc_html_e( 'During Ramadan', 'signal-shield' ); ?></th><td><?php esc_html_e( '9:00 am – 3:00 pm', 'signal-shield' ); ?></td></tr>
		</tbody>
	</table>
	<p class="ss-contact__note"><?php esc_html_e( 'Outage on a site we support? Call the number above any time. Support clients get a 24/7 line.', 'signal-shield' ); ?></p>
</aside>
<!-- /wp:html --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
