<?php
/**
 * Office Wi-Fi Checklist: [ss_checklist_form] and the protected download.
 *
 * Visitors leave an email address, get a personal download link (valid for
 * seven days) on screen and by email. The PDF itself sits in a folder that
 * Apache refuses to serve directly.
 *
 * @package SignalShieldCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Absolute path of the checklist PDF.
 *
 * @return string
 */
function ss_core_checklist_path() {
	/**
	 * Filters the checklist file path.
	 *
	 * @param string $path Absolute path.
	 */
	return apply_filters( 'ss_core_checklist_path', SS_CORE_DIR . 'downloads/office-wifi-checklist.pdf' );
}

/**
 * Personal download URL for a token.
 *
 * @param string $token Token.
 * @return string
 */
function ss_core_checklist_url( $token ) {
	return add_query_arg( 'ss_download', rawurlencode( $token ), home_url( '/' ) );
}

/**
 * Validate a checklist request, store the lead and send the link.
 *
 * @param array $input Raw input (unslashed).
 * @return array{ok: bool, errors?: array, values?: array, message?: string, download_url?: string}
 */
function ss_core_handle_checklist( $input ) {
	$raw_email = trim( (string) ( $input['email'] ?? '' ) );
	$values    = array(
		'email' => sanitize_text_field( $raw_email ),
		'tips'  => ! empty( $input['tips'] ) ? 1 : 0,
	);

	if ( ss_core_is_spam( $input ) ) {
		return array(
			'ok'           => true,
			'message'      => __( 'Your checklist is ready.', 'signal-shield-core' ),
			'download_url' => home_url( '/' ),
		);
	}
	if ( ss_core_rate_limited( 'checklist' ) ) {
		return array(
			'ok'     => false,
			'errors' => array( '_form' => __( 'Too many requests from your connection. Please wait ten minutes and try again.', 'signal-shield-core' ) ),
			'values' => $values,
		);
	}
	if ( '' === $raw_email ) {
		return array(
			'ok'     => false,
			'errors' => array( 'email' => __( 'Enter your email address to get the checklist.', 'signal-shield-core' ) ),
			'values' => $values,
		);
	}
	if ( ! is_email( $raw_email ) ) {
		return array(
			'ok'     => false,
			'errors' => array( 'email' => __( 'Enter an email address in the format name@company.com.', 'signal-shield-core' ) ),
			'values' => $values,
		);
	}

	$email   = sanitize_email( $raw_email );
	$token   = wp_generate_password( 32, false );
	$post_id = wp_insert_post(
		array(
			'post_type'   => 'ss_lead',
			'post_status' => 'private',
			'post_title'  => $email,
		),
		true
	);
	if ( is_wp_error( $post_id ) ) {
		return array(
			'ok'     => false,
			'errors' => array( '_form' => __( 'We could not prepare your download. Please try again in a moment.', 'signal-shield-core' ) ),
			'values' => $values,
		);
	}

	update_post_meta( $post_id, '_ss_email', $email );
	update_post_meta( $post_id, '_ss_tips', $values['tips'] );
	update_post_meta( $post_id, '_ss_token', $token );
	update_post_meta( $post_id, '_ss_token_expires', time() + 7 * DAY_IN_SECONDS );
	update_post_meta( $post_id, '_ss_downloads', 0 );
	update_post_meta( $post_id, '_ss_source', esc_url_raw( $input['source'] ?? '' ) );

	$url = ss_core_checklist_url( $token );
	ss_core_rate_record( 'checklist' );

	wp_mail(
		$email,
		__( 'Your Office Wi-Fi Checklist', 'signal-shield-core' ),
		implode(
			"\n",
			array(
				__( 'Hello,', 'signal-shield-core' ),
				'',
				__( 'Thanks for requesting the Office Wi-Fi Checklist. You can download it here (the link works for 7 days):', 'signal-shield-core' ),
				$url,
				'',
				__( 'If any of the checks raise questions, reply to this email or call us on +974 4412 7788. We are happy to talk it through.', 'signal-shield-core' ),
				'',
				__( 'Signal & Shield Consulting, Doha', 'signal-shield-core' ),
			)
		)
	);

	return array(
		'ok'           => true,
		/* translators: %s: email address */
		'message'      => sprintf( __( 'Your checklist is ready. We have also emailed the link to %s.', 'signal-shield-core' ), $email ),
		'download_url' => $url,
	);
}

/**
 * Non-JS submission handler.
 */
function ss_core_admin_post_checklist() {
	$input  = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- public form, protected by honeypot and rate limit.
	$result = ss_core_handle_checklist( is_array( $input ) ? $input : array() );
	ss_core_redirect_back( array_merge( $result, array( 'form' => 'checklist' ) ), 'checklist' );
}
add_action( 'admin_post_nopriv_ss_checklist', 'ss_core_admin_post_checklist' );
add_action( 'admin_post_ss_checklist', 'ss_core_admin_post_checklist' );

/**
 * Serve the PDF for a valid token.
 */
function ss_core_maybe_serve_checklist() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- token is the credential.
	if ( ! isset( $_GET['ss_download'] ) ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$token = preg_replace( '/[^A-Za-z0-9]/', '', (string) wp_unslash( $_GET['ss_download'] ) );

	$leads = $token ? get_posts(
		array(
			'post_type'      => 'ss_lead',
			'post_status'    => 'private',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'meta_key'       => '_ss_token', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => $token, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	) : array();

	$file = ss_core_checklist_path();
	if ( ! $leads || (int) get_post_meta( $leads[0], '_ss_token_expires', true ) < time() || ! is_readable( $file ) ) {
		wp_die(
			wp_kses_post(
				sprintf(
					/* translators: %s: link to the checklist form */
					__( 'This download link has expired or is not valid. <a href="%s">Request a fresh link</a>, it only takes a moment.', 'signal-shield-core' ),
					esc_url( home_url( '/#checklist' ) )
				)
			),
			esc_html__( 'Download link expired', 'signal-shield-core' ),
			array( 'response' => 410 )
		);
	}

	update_post_meta( $leads[0], '_ss_downloads', (int) get_post_meta( $leads[0], '_ss_downloads', true ) + 1 );

	nocache_headers();
	header( 'Content-Type: application/pdf' );
	header( 'Content-Disposition: attachment; filename="Signal-Shield-Office-WiFi-Checklist.pdf"' );
	header( 'Content-Length: ' . filesize( $file ) );
	header( 'X-Robots-Tag: noindex' );
	readfile( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_readfile
	exit;
}
add_action( 'template_redirect', 'ss_core_maybe_serve_checklist', 1 );

/**
 * [ss_checklist_form]
 *
 * @return string
 */
function ss_core_checklist_form_shortcode() {
	static $instance = 0;
	++$instance;

	wp_enqueue_style( 'ss-core' );
	wp_enqueue_script( 'ss-core-forms' );

	$prefix  = 'ss-checklist-' . $instance;
	$state   = ss_core_get_state( 'checklist' );
	$success = $state && ! empty( $state['ok'] );
	$errors  = ( $state && empty( $state['ok'] ) ) ? (array) $state['errors'] : array();
	$values  = ( $state && empty( $state['ok'] ) ) ? (array) $state['values'] : array();
	$error   = $errors['email'] ?? ( $errors['_form'] ?? '' );

	ob_start();
	?>
	<div class="ss-form-wrap ss-form-wrap--checklist">
		<div class="ss-form-success ss-form-success--checklist" tabindex="-1" role="status"<?php echo $success ? '' : ' hidden'; ?>>
			<p class="ss-form-success__text"><?php echo esc_html( $success ? $state['message'] : '' ); ?></p>
			<a class="ss-button wp-element-button ss-download-link" href="<?php echo esc_url( $success ? $state['download_url'] : '#' ); ?>">
				<svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4v11m0 0-4.5-4.5M12 15l4.5-4.5M5 19.5h14"/></svg>
				<?php esc_html_e( 'Download the checklist (PDF, 3 pages)', 'signal-shield-core' ); ?>
			</a>
		</div>
		<form class="ss-form ss-form--checklist" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate
			data-ss-form="checklist"
			data-endpoint="<?php echo esc_url( rest_url( 'signal-shield/v1/checklist' ) ); ?>"
			data-error-generic="<?php esc_attr_e( 'Something went wrong. Please try again in a moment.', 'signal-shield-core' ); ?>"
			data-sending="<?php esc_attr_e( 'Preparing…', 'signal-shield-core' ); ?>"
			<?php echo $success ? 'hidden' : ''; ?>>
			<input type="hidden" name="action" value="ss_checklist">
			<input type="hidden" name="source" value="<?php echo esc_url( get_permalink() ? get_permalink() : home_url( '/' ) ); ?>">
			<?php echo ss_core_spam_fields( $prefix ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
			<div class="ss-field ss-field--email<?php echo $error ? ' has-error' : ''; ?>">
				<label for="<?php echo esc_attr( $prefix ); ?>-email"><?php esc_html_e( 'Your work email', 'signal-shield-core' ); ?></label>
				<div class="ss-inline">
					<input type="email" id="<?php echo esc_attr( $prefix ); ?>-email" name="email" required aria-required="true" autocomplete="email" maxlength="190"
						value="<?php echo esc_attr( $values['email'] ?? '' ); ?>"
						data-rules="email"
						data-msg-required="<?php esc_attr_e( 'Enter your email address to get the checklist.', 'signal-shield-core' ); ?>"
						data-msg-invalid="<?php esc_attr_e( 'Enter an email address in the format name@company.com.', 'signal-shield-core' ); ?>"
						<?php echo $error ? 'aria-invalid="true" aria-describedby="' . esc_attr( $prefix ) . '-email-error"' : ''; ?>>
					<button type="submit" class="ss-button wp-element-button"><?php esc_html_e( 'Get the checklist', 'signal-shield-core' ); ?></button>
				</div>
				<p class="ss-field__error" id="<?php echo esc_attr( $prefix ); ?>-email-error"<?php echo $error ? '' : ' hidden'; ?>><?php echo esc_html( $error ); ?></p>
			</div>
			<div class="ss-check">
				<input type="checkbox" id="<?php echo esc_attr( $prefix ); ?>-tips" name="tips" value="1"<?php checked( ! empty( $values['tips'] ) ); ?>>
				<label for="<?php echo esc_attr( $prefix ); ?>-tips"><?php esc_html_e( 'Also send me a short network tip once a month. Unsubscribe any time.', 'signal-shield-core' ); ?></label>
			</div>
			<p class="ss-form__fine">
				<?php
				printf(
					/* translators: %s: link to privacy page */
					esc_html__( 'We will email you the link too. No spam, ever. See our %s.', 'signal-shield-core' ),
					'<a href="' . esc_url( home_url( '/privacy/' ) ) . '">' . esc_html__( 'privacy notice', 'signal-shield-core' ) . '</a>'
				);
				?>
			</p>
		</form>
	</div>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'ss_checklist_form', 'ss_core_checklist_form_shortcode' );
