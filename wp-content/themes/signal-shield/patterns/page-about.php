<?php
/**
 * Title: About page
 * Slug: signal-shield/page-about
 * Categories: signal-shield
 * Post Types: page
 * Inserter: no
 *
 * @package SignalShield
 */

$signal_shield_values = array(
	array( __( 'Measure first', 'signal-shield' ), __( 'We never guess. Every recommendation starts with real measurements from your building.', 'signal-shield' ) ),
	array( __( 'Say it plainly', 'signal-shield' ), __( 'If your finance manager cannot follow our report, we have not finished writing it.', 'signal-shield' ) ),
	array( __( 'Stay independent', 'signal-shield' ), __( 'No commissions from equipment makers. Our only incentive is getting it right for you.', 'signal-shield' ) ),
	array( __( 'Own the outcome', 'signal-shield' ), __( 'We stay accountable after go-live and come back to prove the results.', 'signal-shield' ) ),
);

$signal_shield_certs = array(
	array( 'cert-wireless.svg', __( 'Wireless design certification', 'signal-shield' ) ),
	array( 'cert-network.svg', __( 'Professional network engineering certification', 'signal-shield' ) ),
	array( 'cert-security.svg', __( 'Information security professional certification', 'signal-shield' ) ),
	array( 'cert-iso.svg', __( 'ISO 27001 Lead Implementer', 'signal-shield' ) ),
	array( 'cert-pm.svg', __( 'Project management professional', 'signal-shield' ) ),
);

$signal_shield_team = array(
	array( 'team-omar.svg', __( 'Omar Haddad', 'signal-shield' ), __( 'Founder & Principal Consultant', 'signal-shield' ), __( 'Fifteen years designing wireless networks for refineries, offshore platforms and LNG sites across the Gulf.', 'signal-shield' ) ),
	array( 'team-priya.svg', __( 'Priya Nair', 'signal-shield' ), __( 'Wireless Design Lead', 'signal-shield' ), __( 'Has surveyed more hotel corridors than she can count, and still enjoys finding the dead spot nobody else could.', 'signal-shield' ) ),
	array( 'team-daniel.svg', __( 'Daniel Okafor', 'signal-shield' ), __( 'Network Infrastructure Lead', 'signal-shield' ), __( 'Builds networks for schools and offices that keep running when a cable gets cut or a switch fails.', 'signal-shield' ) ),
	array( 'team-noora.svg', __( 'Noora Al-Sulaiti', 'signal-shield' ), __( 'Cybersecurity Lead', 'signal-shield' ), __( 'Former bank security analyst who turns technical findings into decisions a board can make.', 'signal-shield' ) ),
);
?>
<!-- wp:group {"tagName":"section","className":"ss-section ss-page-hero","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-page-hero"><!-- wp:group {"align":"wide","className":"ss-page-hero__inner","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-page-hero__inner"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'About us', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'Engineers who speak plainly', 'signal-shield' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ss-lead"} -->
<p class="ss-lead"><?php esc_html_e( 'We are a small Doha team of wireless, network and security specialists. We measure carefully, explain clearly and stay with you after the work is done.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"ss-section ss-section--light","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-section--light"><!-- wp:group {"align":"wide","className":"ss-split","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-split"><!-- wp:group {"className":"ss-split__intro","layout":{"type":"default"}} -->
<div class="wp-block-group ss-split__intro"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'Our story', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Started by a problem we kept seeing', 'signal-shield' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ss-prose-block","layout":{"type":"default"}} -->
<div class="wp-block-group ss-prose-block"><!-- wp:paragraph -->
<p><?php esc_html_e( 'Our founder, Omar Haddad, spent a decade as a wireless engineer on oil and gas sites across the Gulf. Back in Doha, he kept meeting businesses that had spent heavily on new Wi-Fi equipment that never fixed their problem, because nobody had measured the building first.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'He set up Signal & Shield in 2016 to do it differently: measure first, design properly and explain everything in language a business owner can act on. Security joined the picture quickly, since a network that works but leaks data is not much of a network.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Today we are a team of eight working from West Bay, with clients from offshore platforms to boutique hotels, international schools and growing offices in Lusail. We still do not sell equipment, and we still start every project with a site visit.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"ss-section ss-section--surface","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-section--surface"><!-- wp:group {"align":"wide","className":"ss-section__head","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-section__head"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'What we believe', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Four rules we work by', 'signal-shield' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:html -->
<ul class="ss-promises ss-promises--dark alignwide">
<?php foreach ( $signal_shield_values as $signal_shield_value ) : ?>
	<li><h3><?php echo esc_html( $signal_shield_value[0] ); ?></h3><p><?php echo esc_html( $signal_shield_value[1] ); ?></p></li>
<?php endforeach; ?>
</ul>
<!-- /wp:html --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"ss-section ss-section--light","anchor":"certifications","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-section--light" id="certifications"><!-- wp:group {"align":"wide","className":"ss-section__head","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-section__head"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'Certifications', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'Qualified in every area we advise on', 'signal-shield' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php esc_html_e( 'Our consultants hold recognised certifications in wireless design, network engineering, information security and project management, and renew them every year.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:html -->
<ul class="ss-badges alignwide">
<?php foreach ( $signal_shield_certs as $signal_shield_cert ) : ?>
	<li class="ss-badge"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/' . $signal_shield_cert[0] ) ); ?>" alt="<?php echo esc_attr( sprintf( /* translators: %s: certification name */ __( 'Placeholder badge: %s', 'signal-shield' ), $signal_shield_cert[1] ) ); ?>" width="120" height="120" loading="lazy"><span><?php echo esc_html( $signal_shield_cert[1] ); ?></span></li>
<?php endforeach; ?>
</ul>
<!-- /wp:html --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"ss-section ss-section--white","anchor":"team","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-section--white" id="team"><!-- wp:group {"align":"wide","className":"ss-section__head","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-section__head"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'The team', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'The people you will actually work with', 'signal-shield' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"ss-team","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-team"><?php foreach ( $signal_shield_team as $signal_shield_person ) : ?><!-- wp:group {"className":"ss-person","layout":{"type":"default"}} -->
<div class="wp-block-group ss-person"><!-- wp:image {"className":"ss-person__photo"} -->
<figure class="wp-block-image ss-person__photo"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/' . $signal_shield_person[0] ) ); ?>" alt="<?php echo esc_attr( sprintf( /* translators: %s: person name */ __( 'Placeholder portrait for %s', 'signal-shield' ), $signal_shield_person[1] ) ); ?>"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html( $signal_shield_person[1] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ss-person__role"} -->
<p class="ss-person__role"><?php echo esc_html( $signal_shield_person[2] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $signal_shield_person[3] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --><?php endforeach; ?></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"signal-shield/cta-banner"} /-->
