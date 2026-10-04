<?php
/**
 * Title: Services page
 * Slug: signal-shield/page-services
 * Categories: signal-shield
 * Post Types: page
 * Inserter: no
 *
 * @package SignalShield
 */

$signal_shield_services = array(
	array(
		'id'       => 'wireless',
		'label'    => '/wireless',
		'title'    => __( 'Wireless network design', 'signal-shield' ),
		'intro'    => __( 'We work out where every access point should go, how many you need and how they should be set up, based on your floor plans, building materials and how people actually use each space. If you already have Wi-Fi, we measure what is there and tell you exactly what to fix.', 'signal-shield' ),
		'problems' => array(
			__( 'Dead spots in meeting rooms, upper floors, car parks or back-of-house areas', 'signal-shield' ),
			__( 'Wi-Fi that slows to a crawl when the building is busy', 'signal-shield' ),
			__( 'Video calls that drop when people walk between rooms or floors', 'signal-shield' ),
			__( 'Outdoor, industrial or offshore areas where ordinary equipment fails', 'signal-shield' ),
			__( 'New access points that were bought but did not fix the problem', 'signal-shield' ),
		),
		'receive'  => array(
			__( 'Site survey report with signal maps for every floor', 'signal-shield' ),
			__( 'Access point placement plan marked on your floor plans', 'signal-shield' ),
			__( 'Equipment list with quantities and a budget estimate', 'signal-shield' ),
			__( 'Set-up guide for your installer or IT team', 'signal-shield' ),
			__( 'Verification survey after installation, proving the result', 'signal-shield' ),
		),
		'time'     => __( 'Typical project: 2 to 6 weeks', 'signal-shield' ),
	),
	array(
		'id'       => 'network',
		'label'    => '/network',
		'title'    => __( 'Enterprise network infrastructure', 'signal-shield' ),
		'intro'    => __( 'This is everything your Wi-Fi, phones, cameras and computers rely on: switches, cabling, internet lines and the equipment that connects them. We design it to keep working when one part fails, to keep different groups of users apart, and to grow without starting again.', 'signal-shield' ),
		'problems' => array(
			__( 'The whole office stops when one switch or internet line fails', 'signal-shield' ),
			__( 'Nobody is sure what is plugged in where, or why', 'signal-shield' ),
			__( 'Guests, staff, cameras and payment terminals all share one network', 'signal-shield' ),
			__( 'Slow file transfers, choppy calls and unexplained outages', 'signal-shield' ),
			__( 'An office move, new floor or new site to plan from scratch', 'signal-shield' ),
		),
		'receive'  => array(
			__( 'Network design document with diagrams explained in plain English', 'signal-shield' ),
			__( 'Separation plan showing which users and devices go on which network', 'signal-shield' ),
			__( 'Comms room, rack and cabling layout', 'signal-shield' ),
			__( 'Backup plan for internet lines and core equipment', 'signal-shield' ),
			__( 'Handover pack: labelled drawings and as-built records', 'signal-shield' ),
		),
		'time'     => __( 'Typical project: 3 to 10 weeks', 'signal-shield' ),
	),
	array(
		'id'       => 'security',
		'label'    => '/security',
		'title'    => __( 'Cybersecurity consulting', 'signal-shield' ),
		'intro'    => __( 'An independent check of how well your business is protected, from the firewall to passwords, backups, updates and staff habits. We tell you what is urgent, what can wait and what each fix costs, then help you put it right.', 'signal-shield' ),
		'problems' => array(
			__( 'Firewall settings nobody has reviewed in years', 'signal-shield' ),
			__( 'Security questionnaires from clients or insurers you cannot answer confidently', 'signal-shield' ),
			__( 'Backups that have never been tested with a real restore', 'signal-shield' ),
			__( 'Shared or weak passwords on email and key systems', 'signal-shield' ),
			__( 'Questions about standards such as ISO 27001 or Qatar’s National Information Assurance policy', 'signal-shield' ),
		),
		'receive'  => array(
			__( 'Security assessment report with findings rated Red, Amber or Green', 'signal-shield' ),
			__( 'Firewall review with recommended changes, rule by rule', 'signal-shield' ),
			__( 'One-page summary written for management', 'signal-shield' ),
			__( '90-day improvement plan with costs and owners', 'signal-shield' ),
			__( 'Follow-up check once the fixes are in place', 'signal-shield' ),
		),
		'time'     => __( 'Typical project: 2 to 4 weeks', 'signal-shield' ),
	),
);
?>
<!-- wp:group {"tagName":"section","className":"ss-section ss-page-hero","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-page-hero"><!-- wp:group {"align":"wide","className":"ss-page-hero__inner","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-page-hero__inner"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'Services', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading"><?php esc_html_e( 'Three services that work as one', 'signal-shield' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"ss-lead"} -->
<p class="ss-lead"><?php esc_html_e( 'Each service stands on its own, but most clients use two or three together. Here is what each one covers, the problems it solves and what you will have in your hands at the end.', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:html -->
<nav class="ss-jump" aria-label="<?php esc_attr_e( 'Jump to a service', 'signal-shield' ); ?>">
<?php foreach ( $signal_shield_services as $signal_shield_service ) : ?>
	<a href="#<?php echo esc_attr( $signal_shield_service['id'] ); ?>"><span class="ss-mono"><?php echo esc_html( $signal_shield_service['label'] ); ?></span> <?php echo esc_html( $signal_shield_service['title'] ); ?></a>
<?php endforeach; ?>
</nav>
<!-- /wp:html --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<?php foreach ( $signal_shield_services as $signal_shield_i => $signal_shield_service ) : ?>
<!-- wp:group <?php echo wp_json_encode( array( 'tagName' => 'section', 'anchor' => $signal_shield_service['id'], 'className' => 'ss-section ss-service ' . ( 0 === $signal_shield_i % 2 ? 'ss-section--light' : 'ss-section--white' ), 'layout' => array( 'type' => 'constrained' ) ) ); ?> -->
<section class="wp-block-group ss-section ss-service <?php echo 0 === $signal_shield_i % 2 ? 'ss-section--light' : 'ss-section--white'; ?>" id="<?php echo esc_attr( $signal_shield_service['id'] ); ?>"><!-- wp:group {"align":"wide","className":"ss-service__grid","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-service__grid"><!-- wp:group {"className":"ss-service__intro","layout":{"type":"default"}} -->
<div class="wp-block-group ss-service__intro"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php echo esc_html( $signal_shield_service['label'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php echo esc_html( $signal_shield_service['title'] ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html( $signal_shield_service['intro'] ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"ss-service__time"} -->
<p class="ss-service__time"><?php echo esc_html( $signal_shield_service['time'] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ss-service__panel ss-service__panel--problems","layout":{"type":"default"}} -->
<div class="wp-block-group ss-service__panel ss-service__panel--problems"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'Problems it solves', 'signal-shield' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"ss-list ss-list--problems"} -->
<ul class="wp-block-list ss-list ss-list--problems"><?php foreach ( $signal_shield_service['problems'] as $signal_shield_item ) : ?><!-- wp:list-item -->
<li><?php echo esc_html( $signal_shield_item ); ?></li>
<!-- /wp:list-item --><?php endforeach; ?></ul>
<!-- /wp:list --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"ss-service__panel ss-service__panel--receive","layout":{"type":"default"}} -->
<div class="wp-block-group ss-service__panel ss-service__panel--receive"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php esc_html_e( 'What you receive', 'signal-shield' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"ss-list ss-list--receive"} -->
<ul class="wp-block-list ss-list ss-list--receive"><?php foreach ( $signal_shield_service['receive'] as $signal_shield_item ) : ?><!-- wp:list-item -->
<li><?php echo esc_html( $signal_shield_item ); ?></li>
<!-- /wp:list-item --><?php endforeach; ?></ul>
<!-- /wp:list --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<?php endforeach; ?>
<!-- wp:group {"tagName":"section","className":"ss-section ss-included","layout":{"type":"constrained"}} -->
<section class="wp-block-group ss-section ss-included"><!-- wp:group {"align":"wide","className":"ss-section__head","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide ss-section__head"><!-- wp:paragraph {"className":"ss-eyebrow"} -->
<p class="ss-eyebrow"><?php esc_html_e( 'Every engagement', 'signal-shield' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading"><?php esc_html_e( 'What you can always count on', 'signal-shield' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:html -->
<ul class="ss-promises alignwide">
	<li><h3><?php esc_html_e( 'A fixed price up front', 'signal-shield' ); ?></h3><p><?php esc_html_e( 'You approve the scope and cost before any work starts. No open-ended day rates.', 'signal-shield' ); ?></p></li>
	<li><h3><?php esc_html_e( 'One named consultant', 'signal-shield' ); ?></h3><p><?php esc_html_e( 'The person who assesses your site is the person who answers your calls.', 'signal-shield' ); ?></p></li>
	<li><h3><?php esc_html_e( 'Reports in plain language', 'signal-shield' ); ?></h3><p><?php esc_html_e( 'Every report opens with a one-page summary anyone in management can follow.', 'signal-shield' ); ?></p></li>
	<li><h3><?php esc_html_e( 'Independent advice', 'signal-shield' ); ?></h3><p><?php esc_html_e( 'We take no commission from equipment makers, so we recommend only what you need.', 'signal-shield' ); ?></p></li>
</ul>
<!-- /wp:html --></section>
<!-- /wp:group -->

<!-- wp:pattern {"slug":"signal-shield/checklist-offer"} /-->

<!-- wp:pattern {"slug":"signal-shield/cta-banner"} /-->
