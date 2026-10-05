<?php
/**
 * Graceful behaviour when the Signal & Shield Core plugin is not active.
 *
 * The contact form, health check and checklist download are shortcodes from
 * the plugin. Without it WordPress would print them as raw text, so this
 * swaps each one for a short fallback and tells administrators how to fix it.
 *
 * @package SignalShield
 */

defined( 'ABSPATH' ) || exit;

define( 'SIGNAL_SHIELD_CORE_PLUGIN', 'signal-shield-core/signal-shield-core.php' );

/**
 * Fallback markup for each plugin shortcode.
 *
 * @param string $tag Shortcode tag.
 * @return string
 */
function signal_shield_shortcode_fallback( $tag ) {
	$contact = home_url( '/contact/' );
	$phone   = '<a href="tel:+97444127788">+974 4412 7788</a>';
	$email   = '<a href="mailto:hello@signalshield.example">hello@signalshield.example</a>';

	switch ( $tag ) {
		case 'ss_checklist_form':
			$text   = __( 'Ask us for the checklist and we will email it to you, usually the same working day.', 'signal-shield' );
			$button = array( 'mailto:hello@signalshield.example?subject=' . rawurlencode( __( 'Office Wi-Fi Checklist', 'signal-shield' ) ), __( 'Request the checklist by email', 'signal-shield' ) );
			break;
		case 'ss_health_check':
			$text   = __( 'The online health check is unavailable right now. Book a free consultation and we will go through the questions with you.', 'signal-shield' );
			$button = array( $contact, __( 'Book a free consultation', 'signal-shield' ) );
			break;
		case 'ss_contact_form':
			$text   = __( 'Our online form is unavailable right now. Please call or email us and a consultant will reply within one working day.', 'signal-shield' );
			$button = null;
			break;
		default:
			return '';
	}

	$html  = '<div class="ss-fallback">';
	$html .= '<p>' . esc_html( $text ) . '</p>';
	$html .= '<p class="ss-fallback__contact">' . $phone . '<br>' . $email . '</p>';
	if ( $button ) {
		$html .= sprintf(
			'<div class="wp-block-buttons"><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="%1$s">%2$s</a></div></div>',
			esc_url( $button[0], array( 'https', 'http', 'mailto' ) ),
			esc_html( $button[1] )
		);
	}
	if ( current_user_can( 'activate_plugins' ) ) {
		$html .= '<p class="ss-fallback__admin">' . esc_html__( 'Note for administrators: activate the Signal & Shield Core plugin to show the form here. Only logged-in administrators see this note.', 'signal-shield' ) . '</p>';
	}
	$html .= '</div>';

	return $html;
}

/**
 * Replace unregistered plugin shortcodes inside Shortcode blocks.
 *
 * @param string $block_content Rendered block.
 * @return string
 */
function signal_shield_replace_missing_shortcodes( $block_content ) {
	return preg_replace_callback(
		'/\[(ss_checklist_form|ss_health_check|ss_contact_form)\]/',
		static function ( $matches ) {
			return shortcode_exists( $matches[1] ) ? $matches[0] : signal_shield_shortcode_fallback( $matches[1] );
		},
		$block_content
	);
}
add_filter( 'render_block_core/shortcode', 'signal_shield_replace_missing_shortcodes' );

/**
 * Admin notice with a one-click fix when the plugin is missing or inactive.
 */
function signal_shield_plugin_notice() {
	if ( shortcode_exists( 'ss_contact_form' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$installed = array_key_exists( SIGNAL_SHIELD_CORE_PLUGIN, get_plugins() );

	if ( $installed ) {
		$url    = wp_nonce_url(
			admin_url( 'plugins.php?action=activate&plugin=' . rawurlencode( SIGNAL_SHIELD_CORE_PLUGIN ) ),
			'activate-plugin_' . SIGNAL_SHIELD_CORE_PLUGIN
		);
		$action = sprintf( '<a class="button button-primary" href="%s">%s</a>', esc_url( $url ), esc_html__( 'Activate Signal & Shield Core', 'signal-shield' ) );
	} else {
		$action = sprintf(
			'<a class="button button-primary" href="%s">%s</a>',
			esc_url( admin_url( 'plugin-install.php?tab=upload' ) ),
			esc_html__( 'Upload the plugin', 'signal-shield' )
		);
	}

	printf(
		'<div class="notice notice-warning"><p><strong>%1$s</strong> %2$s</p><p>%3$s</p></div>',
		esc_html__( 'Signal & Shield:', 'signal-shield' ),
		$installed
			? esc_html__( 'the contact form, network health check and checklist download need the Signal & Shield Core plugin, which is installed but not active.', 'signal-shield' )
			: esc_html__( 'the contact form, network health check and checklist download need the Signal & Shield Core plugin. Upload signal-shield-core.zip under Plugins › Add New › Upload Plugin, then activate it.', 'signal-shield' ),
		$action // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above.
	);
}
add_action( 'admin_notices', 'signal_shield_plugin_notice' );
