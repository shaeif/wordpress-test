<?php
/**
 * Plugin Name: Signal & Shield Core
 * Description: Network health check quiz, contact form with saved enquiries, and the gated Office Wi-Fi Checklist download for the Signal & Shield website.
 * Version: 1.0.0
 * Requires at least: 6.6
 * Requires PHP: 8.0
 * Author: Signal & Shield Consulting
 * License: GPL-2.0-or-later
 * Text Domain: signal-shield-core
 *
 * @package SignalShieldCore
 */

defined( 'ABSPATH' ) || exit;

define( 'SS_CORE_VERSION', '1.0.0' );
define( 'SS_CORE_FILE', __FILE__ );
define( 'SS_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'SS_CORE_URL', plugin_dir_url( __FILE__ ) );

require_once SS_CORE_DIR . 'includes/helpers.php';
require_once SS_CORE_DIR . 'includes/post-types.php';
require_once SS_CORE_DIR . 'includes/admin.php';
require_once SS_CORE_DIR . 'includes/contact-form.php';
require_once SS_CORE_DIR . 'includes/checklist.php';
require_once SS_CORE_DIR . 'includes/health-check.php';
require_once SS_CORE_DIR . 'includes/rest.php';

/**
 * Translations.
 */
function ss_core_load_textdomain() {
	load_plugin_textdomain( 'signal-shield-core', false, dirname( plugin_basename( SS_CORE_FILE ) ) . '/languages' );
}
add_action( 'init', 'ss_core_load_textdomain' );

/**
 * Shared front-end assets. Each shortcode enqueues what it needs.
 */
function ss_core_register_assets() {
	wp_register_style( 'ss-core', SS_CORE_URL . 'assets/css/components.css', array(), SS_CORE_VERSION );
	wp_register_script( 'ss-core-forms', SS_CORE_URL . 'assets/js/forms.js', array(), SS_CORE_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
	wp_register_script( 'ss-core-health-check', SS_CORE_URL . 'assets/js/health-check.js', array(), SS_CORE_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
}
add_action( 'wp_enqueue_scripts', 'ss_core_register_assets', 5 );

/**
 * Load component styles inside the block editor too, so pattern previews
 * (like the sample scorecard) look the same as on the site.
 */
function ss_core_editor_assets() {
	if ( is_admin() ) {
		wp_enqueue_style( 'ss-core-editor', SS_CORE_URL . 'assets/css/components.css', array(), SS_CORE_VERSION );
	}
}
add_action( 'enqueue_block_assets', 'ss_core_editor_assets' );

/**
 * Activation: register post types so their capabilities exist straight away.
 */
function ss_core_activate() {
	ss_core_register_post_types();
}
register_activation_hook( __FILE__, 'ss_core_activate' );
