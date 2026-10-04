<?php
/**
 * Shared helpers: spam checks, rate limiting and the post-redirect state used
 * when a form is submitted without JavaScript.
 *
 * @package SignalShieldCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Where enquiry notifications are sent.
 *
 * @return string
 */
function ss_core_notification_email() {
	$email = get_option( 'ss_core_notification_email' );
	if ( ! $email || ! is_email( $email ) ) {
		$email = get_option( 'admin_email' );
	}
	/**
	 * Filters the address that receives new enquiry notifications.
	 *
	 * @param string $email Recipient address.
	 */
	return apply_filters( 'ss_core_notification_email', $email );
}

/**
 * Hidden fields every form carries: a honeypot and the render time.
 *
 * @param string $prefix Unique id prefix.
 * @return string
 */
function ss_core_spam_fields( $prefix ) {
	ob_start();
	?>
	<div class="ss-hp" aria-hidden="true">
		<label for="<?php echo esc_attr( $prefix ); ?>-website"><?php esc_html_e( 'Leave this field empty', 'signal-shield-core' ); ?></label>
		<input type="text" id="<?php echo esc_attr( $prefix ); ?>-website" name="website" value="" tabindex="-1" autocomplete="off">
	</div>
	<input type="hidden" name="ss_ts" value="<?php echo esc_attr( (string) time() ); ?>">
	<?php
	return (string) ob_get_clean();
}

/**
 * True when a submission looks automated.
 *
 * @param array $input Raw input.
 * @return bool
 */
function ss_core_is_spam( $input ) {
	if ( ! empty( $input['website'] ) ) {
		return true;
	}
	$rendered = isset( $input['ss_ts'] ) ? (int) $input['ss_ts'] : 0;
	// Humans need more than two seconds to fill in a form. Old timestamps are
	// fine: the page may have been cached or left open.
	if ( $rendered && ( time() - $rendered ) < 2 ) {
		return true;
	}
	return false;
}

/**
 * Rate-limit key for the current visitor.
 *
 * @param string $form Form key.
 * @return string
 */
function ss_core_rate_key( $form ) {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'unknown';
	return 'ss_rl_' . $form . '_' . md5( $ip );
}

/**
 * True when this visitor has already sent five successful submissions of a
 * form in the last ten minutes. Failed validation does not count.
 *
 * @param string $form Form key.
 * @return bool
 */
function ss_core_rate_limited( $form ) {
	/**
	 * Filters how many submissions a visitor may make every ten minutes.
	 *
	 * @param int    $limit Default 5.
	 * @param string $form  Form key.
	 */
	$limit = (int) apply_filters( 'ss_core_rate_limit', 5, $form );
	return (int) get_transient( ss_core_rate_key( $form ) ) >= $limit;
}

/**
 * Count a successful submission towards the rate limit.
 *
 * @param string $form Form key.
 */
function ss_core_rate_record( $form ) {
	$key = ss_core_rate_key( $form );
	set_transient( $key, (int) get_transient( $key ) + 1, 10 * MINUTE_IN_SECONDS );
}

/**
 * Store a non-JS submission result for the page we redirect back to.
 *
 * @param array $state Result data.
 * @return string Key to put in the URL.
 */
function ss_core_store_state( $state ) {
	$key = wp_generate_password( 20, false );
	set_transient( 'ss_state_' . $key, $state, 10 * MINUTE_IN_SECONDS );
	return $key;
}

/**
 * Fetch (once) a non-JS submission result for the given form.
 *
 * @param string $form Form key.
 * @return array|null
 */
function ss_core_get_state( $form ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only lookup of a random key.
	$key = isset( $_GET['ss_state'] ) ? sanitize_key( wp_unslash( $_GET['ss_state'] ) ) : '';
	if ( ! $key ) {
		return null;
	}
	$state = get_transient( 'ss_state_' . $key );
	if ( ! is_array( $state ) || ( $state['form'] ?? '' ) !== $form ) {
		return null;
	}
	return $state;
}

/**
 * Redirect back to the form after a non-JS submission.
 *
 * @param array  $state  Result data.
 * @param string $anchor Element id to scroll to.
 */
function ss_core_redirect_back( $state, $anchor ) {
	$referer = wp_get_referer();
	$target  = $referer ? $referer : home_url( '/' );
	$target  = remove_query_arg( array( 'ss_state' ), $target );
	$target  = add_query_arg( 'ss_state', ss_core_store_state( $state ), $target );
	wp_safe_redirect( $target . '#' . $anchor );
	exit;
}

/**
 * Render a list of server-side errors as a summary box.
 *
 * @param array  $errors Field => message.
 * @param string $prefix Id prefix of the form's fields.
 * @return string
 */
function ss_core_error_summary( $errors, $prefix ) {
	$hidden = empty( $errors ) ? ' hidden' : '';
	ob_start();
	?>
	<div class="ss-form__summary" role="alert" tabindex="-1"<?php echo $hidden; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		<p class="ss-form__summary-title"><?php esc_html_e( 'Please check the highlighted fields:', 'signal-shield-core' ); ?></p>
		<ul>
			<?php foreach ( $errors as $field => $message ) : ?>
				<?php if ( '_form' === $field ) : ?>
					<li><?php echo esc_html( $message ); ?></li>
				<?php else : ?>
					<li><a href="#<?php echo esc_attr( $prefix . '-' . $field ); ?>"><?php echo esc_html( $message ); ?></a></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php
	return (string) ob_get_clean();
}
