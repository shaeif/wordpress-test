<?php
/**
 * Title: Case studies page
 * Slug: signal-shield/page-case-studies
 * Categories: signal-shield
 * Post Types: page
 * Inserter: no
 *
 * @package SignalShield
 */

$signal_shield_cases = array(
	array(
		'id'        => 'offshore-platform-wifi',
		'sector'    => __( 'Oil & gas', 'signal-shield' ),
		'title'     => __( 'Reliable Wi-Fi for 140 crew on an offshore gas platform', 'signal-shield' ),
		'meta'      => array( __( 'Offshore, Qatar', 'signal-shield' ), __( '10 weeks', 'signal-shield' ), __( 'Wireless + Network', 'signal-shield' ) ),
		'challenge' => __( 'Crew on a 20-year-old platform only had Wi-Fi in the accommodation block. Steel decks blocked the signal, the old access points kept failing in the heat and salt air, and operations data shared a network with crew internet, which worried the safety team.', 'signal-shield' ),
		'solution'  => __( 'We surveyed every deck during a scheduled crew change and designed coverage with outdoor access points rated for hazardous areas. Operations, crew and contractor traffic each moved onto their own network with strict rules between them. Installation was planned around permit-to-work windows so production never stopped.', 'signal-shield' ),
		'result'    => __( 'Crew now have dependable Wi-Fi in the accommodation, mess, workshops and muster points. Operations traffic is fully isolated, and the platform passed its next security audit with no network findings.', 'signal-shield' ),
		'stats'     => array(
			array( '96%', __( 'of working decks covered, up from about 30%', 'signal-shield' ) ),
			array( '0', __( 'network findings at the next security audit', 'signal-shield' ) ),
			array( '0', __( 'hours of lost production during installation', 'signal-shield' ) ),
		),
	),
	array(
		'id'        => 'hotel-guest-network',
		'sector'    => __( 'Hospitality', 'signal-shield' ),
		'title'     => __( 'A guest network worthy of a five-star hotel', 'signal-shield' ),
		'meta'      => array( __( 'West Bay, Doha', 'signal-shield' ), __( '6 weeks', 'signal-shield' ), __( 'Wireless + Network + Security', 'signal-shield' ) ),
		'challenge' => __( 'A 320-room hotel was getting Wi-Fi complaints in online reviews every week, especially from conference guests. The ballroom slowed to a crawl during events, and guest, staff and payment devices shared far too much of the same network.', 'signal-shield' ),
		'solution'  => __( 'We measured every floor, then redesigned access point placement for the ballroom, pool deck and corridors. Guests, staff, card terminals, room TVs and security cameras each got their own separate network, and events got dedicated high-capacity Wi-Fi that the banqueting team can switch on themselves.', 'signal-shield' ),
		'result'    => __( 'Wi-Fi complaints went from a weekly event to a rare one. The ballroom now handles a full gala dinner of connected guests, and the payment network meets the card industry’s separation requirements. All work was done with the hotel fully open.', 'signal-shield' ),
		'stats'     => array(
			array( '−90%', __( 'Wi-Fi complaints in guest reviews within three months', 'signal-shield' ) ),
			array( '600', __( 'devices connected in the ballroom at once', 'signal-shield' ) ),
			array( '0', __( 'rooms taken out of sale during the upgrade', 'signal-shield' ) ),
		),
	),
	array(
		'id'        => 'office-firewall-overhaul',
		'sector'    => __( 'Corporate office', 'signal-shield' ),
		'title'     => __( 'Cleaning up eight years of firewall rules', 'signal-shield' ),
		'meta'      => array( __( 'Lusail', 'signal-shield' ), __( '4 weeks', 'signal-shield' ), __( 'Security', 'signal-shield' ) ),
		'challenge' => __( 'A 150-person engineering firm had a firewall with more than 400 rules, many added by contractors who had long since left. A major client sent a security questionnaire management could not answer, and a near-miss phishing email had everyone nervous.', 'signal-shield' ),
		'solution'  => __( 'We reviewed every rule with the people who use each system and removed what was no longer needed. Remote access now asks for a second step at sign-in, updates install automatically, and we proved the backups work by actually restoring them. Staff got a two-page guide they could follow.', 'signal-shield' ),
		'result'    => __( 'The firewall went from 412 rules to 86, each one documented with an owner. The firm answered its client’s questionnaire in full, kept the contract, and renewed its cyber insurance at a lower premium.', 'signal-shield' ),
		'stats'     => array(
			array( '412 → 86', __( 'firewall rules, every one documented', 'signal-shield' ) ),
			array( '100%', __( 'of remote access protected by two-step sign-in', 'signal-shield' ) ),
			array( '4 hrs', __( 'to restore critical files, proven in a real test', 'signal-shield' ) ),
		),
	),
);
?>
<!-- wp:group {"tagName":"section","className":"ss-section ss-page-hero","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-page-hero"><!-- wp:group {"align":"wide","className":"ss-page-hero__inner","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-page-hero__inner"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'Case studies', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'Real problems, fixed properly', 'signal-shield' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ss-lead"} -->
<p class="ss-lead"><?php esc_html_e( 'Three recent projects, shared with our clients’ permission. Names and identifying details have been removed.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<nav class="ss-jump" aria-label="<?php esc_attr_e( 'Jump to a case study', 'signal-shield' ); ?>">
<?php foreach ( $signal_shield_cases as $signal_shield_case ) : ?>
	<a href="#<?php echo esc_attr( $signal_shield_case['id'] ); ?>"><span class="ss-mono"><?php echo esc_html( $signal_shield_case['sector'] ); ?></span> <?php echo esc_html( $signal_shield_case['title'] ); ?></a>
<?php endforeach; ?>
</nav>
<!-- /wp:html --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<?php foreach ( $signal_shield_cases as $signal_shield_i => $signal_shield_case ) : ?>
<!-- wp:group <?php echo wp_json_encode( array( 'tagName' => 'article', 'anchor' => $signal_shield_case['id'], 'className' => 'ss-section ss-case ' . ( 0 === $signal_shield_i % 2 ? 'ss-section--light' : 'ss-section--white' ), 'layout' => array( 'type' => 'constrained' ) ) ); ?> -->
<article class="wp-block-group ss-section ss-case <?php echo 0 === $signal_shield_i % 2 ? 'ss-section--light' : 'ss-section--white'; ?>" id="<?php echo esc_attr( $signal_shield_case['id'] ); ?>"><!-- wp:group {"align":"wide","className":"ss-case__head","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-case__head"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php echo esc_html( $signal_shield_case['sector'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php echo esc_html( $signal_shield_case['title'] ); ?></h2>
<!-- /wp:heading -->

<!-- wp:html -->
<ul class="ss-chips" aria-label="<?php esc_attr_e( 'Project details', 'signal-shield' ); ?>">
<?php foreach ( $signal_shield_case['meta'] as $signal_shield_meta ) : ?>
	<li><?php echo esc_html( $signal_shield_meta ); ?></li>
<?php endforeach; ?>
</ul>
<!-- /wp:html --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","className":"ss-case__story","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-case__story"><?php foreach ( array( 'challenge' => __( 'Challenge', 'signal-shield' ), 'solution' => __( 'Solution', 'signal-shield' ), 'result' => __( 'Result', 'signal-shield' ) ) as $signal_shield_key => $signal_shield_heading ) : ?><!-- wp:group <?php echo wp_json_encode( array( 'className' => 'ss-case__part ss-case__part--' . $signal_shield_key, 'layout' => array( 'type' => 'default' ) ) ); ?> -->
<div class="wp-block-group ss-case__part ss-case__part--<?php echo esc_attr( $signal_shield_key ); ?>"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html( $signal_shield_heading ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $signal_shield_case[ $signal_shield_key ] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --><?php endforeach; ?></div>
<!-- /wp:group -->

<!-- wp:html -->
<dl class="ss-stats alignwide">
<?php foreach ( $signal_shield_case['stats'] as $signal_shield_stat ) : ?>
	<div class="ss-stat"><dt><?php echo esc_html( $signal_shield_stat[1] ); ?></dt><dd><?php echo esc_html( $signal_shield_stat[0] ); ?></dd></div>
<?php endforeach; ?>
</dl>
<!-- /wp:html --></article>
<!-- /wp:group -->

<?php endforeach; ?>
<!-- wp:pattern {"slug":"signal-shield/cta-banner"} /-->
