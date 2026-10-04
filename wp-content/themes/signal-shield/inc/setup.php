<?php
/**
 * Creates the site's pages on theme activation (and via `wp signal-shield setup`).
 *
 * Each page holds a reference to one of the theme's page patterns, so the
 * layout renders straight away. Opening a page in the editor turns the
 * pattern into ordinary, editable blocks.
 *
 * @package SignalShield
 */

defined( 'ABSPATH' ) || exit;

/**
 * Pages the site needs, keyed by slug.
 *
 * @return array<string, array{title: string, pattern: string, template: string, excerpt: string, menu_order: int}>
 */
function signal_shield_page_definitions() {
	return array(
		'home'                  => array(
			'title'      => __( 'Home', 'signal-shield' ),
			'pattern'    => 'signal-shield/page-home',
			'template'   => '',
			'excerpt'    => __( 'Wi-Fi design, business networks and cybersecurity consulting for oil & gas, hospitality, education and corporate offices in Doha and across Qatar.', 'signal-shield' ),
			'menu_order' => 0,
		),
		'services'              => array(
			'title'      => __( 'Services', 'signal-shield' ),
			'pattern'    => 'signal-shield/page-services',
			'template'   => 'page-designed',
			'excerpt'    => __( 'Wireless network design, enterprise network infrastructure and cybersecurity consulting: what each service covers, the problems it solves and what you receive.', 'signal-shield' ),
			'menu_order' => 1,
		),
		'network-health-check'  => array(
			'title'      => __( 'Network Health Check', 'signal-shield' ),
			'pattern'    => 'signal-shield/page-health-check',
			'template'   => 'page-designed',
			'excerpt'    => __( 'Answer 10 plain-language questions and get a free Green / Amber / Red scorecard for your Wi-Fi, network and security.', 'signal-shield' ),
			'menu_order' => 2,
		),
		'case-studies'          => array(
			'title'      => __( 'Case Studies', 'signal-shield' ),
			'pattern'    => 'signal-shield/page-case-studies',
			'template'   => 'page-designed',
			'excerpt'    => __( 'How we fixed offshore Wi-Fi, upgraded a hotel guest network and overhauled an office firewall in Qatar.', 'signal-shield' ),
			'menu_order' => 3,
		),
		'about'                 => array(
			'title'      => __( 'About', 'signal-shield' ),
			'pattern'    => 'signal-shield/page-about',
			'template'   => 'page-designed',
			'excerpt'    => __( 'A Doha-based team of network and security consultants who explain things clearly and stay accountable after go-live.', 'signal-shield' ),
			'menu_order' => 4,
		),
		'contact'               => array(
			'title'      => __( 'Contact', 'signal-shield' ),
			'pattern'    => 'signal-shield/page-contact',
			'template'   => 'page-designed',
			'excerpt'    => __( 'Book a consultation with Signal & Shield Consulting in Doha. Call, WhatsApp or send us a message. Open Sunday to Thursday.', 'signal-shield' ),
			'menu_order' => 5,
		),
		'privacy'               => array(
			'title'      => __( 'Privacy', 'signal-shield' ),
			'pattern'    => 'signal-shield/page-privacy',
			'template'   => '',
			'excerpt'    => __( 'How Signal & Shield Consulting handles the details you share through this website.', 'signal-shield' ),
			'menu_order' => 6,
		),
	);
}

/**
 * Create any missing pages and point the front page at Home.
 *
 * @param bool $force Overwrite the content of existing pages with the pattern reference.
 * @return array<string, string> Slug => what happened.
 */
function signal_shield_create_pages( $force = false ) {
	$report = array();

	foreach ( signal_shield_page_definitions() as $slug => $page ) {
		$content  = sprintf( '<!-- wp:pattern {"slug":"%s"} /-->', $page['pattern'] );
		$existing = get_page_by_path( $slug, OBJECT, 'page' );

		if ( $existing && ! $force ) {
			$report[ $slug ] = 'exists';
			continue;
		}

		$postarr = array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => $page['title'],
			'post_name'    => $slug,
			'post_content' => $content,
			'post_excerpt' => $page['excerpt'],
			'menu_order'   => $page['menu_order'],
		);

		if ( $existing ) {
			$postarr['ID'] = $existing->ID;
			$page_id       = wp_update_post( $postarr, true );
			$report[ $slug ] = 'reset';
		} else {
			$page_id         = wp_insert_post( $postarr, true );
			$report[ $slug ] = 'created';
		}

		if ( is_wp_error( $page_id ) ) {
			$report[ $slug ] = 'error: ' . $page_id->get_error_message();
			continue;
		}

		update_post_meta( $page_id, '_wp_page_template', $page['template'] ? $page['template'] : 'default' );
	}

	$home    = get_page_by_path( 'home', OBJECT, 'page' );
	$privacy = get_page_by_path( 'privacy', OBJECT, 'page' );
	if ( $home ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home->ID );
	}
	if ( $privacy ) {
		update_option( 'wp_page_for_privacy_policy', $privacy->ID );
	}

	return $report;
}

/**
 * Run once when the theme is activated.
 */
function signal_shield_after_switch_theme() {
	if ( get_option( 'signal_shield_pages_created' ) ) {
		return;
	}
	signal_shield_create_pages();
	update_option( 'signal_shield_pages_created', 1 );
}
add_action( 'after_switch_theme', 'signal_shield_after_switch_theme' );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	/**
	 * Create the Signal & Shield pages.
	 *
	 * ## OPTIONS
	 *
	 * [--force]
	 * : Reset existing pages to the theme's original layout.
	 *
	 * ## EXAMPLES
	 *
	 *     wp signal-shield setup
	 *     wp signal-shield setup --force
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Flags.
	 */
	$signal_shield_cli = static function ( $args, $assoc_args ) {
		$report = signal_shield_create_pages( ! empty( $assoc_args['force'] ) );
		update_option( 'signal_shield_pages_created', 1 );
		foreach ( $report as $slug => $status ) {
			WP_CLI::log( sprintf( '%-22s %s', $slug, $status ) );
		}
		WP_CLI::success( 'Pages are ready.' );
	};
	WP_CLI::add_command( 'signal-shield setup', $signal_shield_cli );
}
