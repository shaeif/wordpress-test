<?php
/**
 * REST endpoints used by the JavaScript-enhanced forms.
 *
 * POST /wp-json/signal-shield/v1/contact
 * POST /wp-json/signal-shield/v1/checklist
 *
 * @package SignalShieldCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register routes.
 */
function ss_core_register_routes() {
	$routes = array(
		'contact'   => 'ss_core_handle_contact',
		'checklist' => 'ss_core_handle_checklist',
	);
	foreach ( $routes as $route => $handler ) {
		register_rest_route(
			'signal-shield/v1',
			'/' . $route,
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				// Public forms: anyone may submit. Spam is handled by a
				// honeypot, a timing check and per-visitor rate limiting.
				'permission_callback' => '__return_true',
				'callback'            => static function ( WP_REST_Request $request ) use ( $handler ) {
					$params = $request->get_body_params();
					if ( ! $params ) {
						$params = (array) $request->get_json_params();
					}
					$params = array_map(
						static fn( $value ) => is_scalar( $value ) ? (string) $value : '',
						(array) $params
					);
					$result = call_user_func( $handler, $params );
					unset( $result['values'] );
					return new WP_REST_Response( $result, $result['ok'] ? 200 : 422 );
				},
			)
		);
	}
}
add_action( 'rest_api_init', 'ss_core_register_routes' );
