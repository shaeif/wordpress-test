<?php
/**
 * Signal & Shield theme functions.
 *
 * @package SignalShield
 */

defined( 'ABSPATH' ) || exit;

define( 'SIGNAL_SHIELD_THEME_VERSION', '1.0.0' );

require_once get_theme_file_path( 'inc/setup.php' );

/**
 * Theme supports and editor styles.
 */
function signal_shield_setup() {
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/theme.css' );
	add_post_type_support( 'page', 'excerpt' );
	load_theme_textdomain( 'signal-shield', get_theme_file_path( 'languages' ) );
}
add_action( 'after_setup_theme', 'signal_shield_setup' );

/**
 * Front-end stylesheet.
 */
function signal_shield_enqueue_assets() {
	wp_enqueue_style(
		'signal-shield',
		get_theme_file_uri( 'assets/css/theme.css' ),
		array(),
		SIGNAL_SHIELD_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'signal_shield_enqueue_assets' );

/**
 * Pattern category for the theme's sections.
 */
function signal_shield_register_pattern_category() {
	register_block_pattern_category(
		'signal-shield',
		array( 'label' => __( 'Signal & Shield', 'signal-shield' ) )
	);
}
add_action( 'init', 'signal_shield_register_pattern_category' );

/**
 * Favicon, theme colour and a meta description taken from the page excerpt.
 */
function signal_shield_head_meta() {
	printf(
		'<link rel="icon" href="%s" type="image/svg+xml">' . "\n",
		esc_url( get_theme_file_uri( 'assets/images/favicon.svg' ) )
	);
	echo '<meta name="theme-color" content="#0A0E14">' . "\n";

	$description = '';
	if ( is_singular() ) {
		$description = get_the_excerpt();
	}
	if ( ! $description ) {
		$description = get_bloginfo( 'description' );
	}
	if ( $description ) {
		printf( '<meta name="description" content="%s">' . "\n", esc_attr( wp_strip_all_tags( $description ) ) );
	}
}
add_action( 'wp_head', 'signal_shield_head_meta', 2 );

/**
 * Mark the current page in the navigation.
 *
 * The header uses custom links so the menu works straight after install;
 * this adds aria-current and the current-menu-item class when a link
 * points at the page being viewed.
 *
 * @param string $block_content Rendered block.
 * @param array  $block         Parsed block.
 * @return string
 */
function signal_shield_mark_current_nav_link( $block_content, $block ) {
	if ( empty( $block['attrs']['url'] ) || is_admin() ) {
		return $block_content;
	}

	$link_path    = trailingslashit( (string) wp_parse_url( $block['attrs']['url'], PHP_URL_PATH ) );
	$request_uri  = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_url( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/';
	$request_path = trailingslashit( (string) wp_parse_url( $request_uri, PHP_URL_PATH ) );
	$home_path    = trailingslashit( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ) );

	if ( $link_path === $home_path || $link_path !== $request_path ) {
		return $block_content;
	}

	$processor = new WP_HTML_Tag_Processor( $block_content );
	if ( $processor->next_tag( 'li' ) ) {
		$processor->add_class( 'current-menu-item' );
	}
	if ( $processor->next_tag( 'a' ) ) {
		$processor->set_attribute( 'aria-current', 'page' );
	}
	return $processor->get_updated_html();
}
add_filter( 'render_block_core/navigation-link', 'signal_shield_mark_current_nav_link', 10, 2 );
