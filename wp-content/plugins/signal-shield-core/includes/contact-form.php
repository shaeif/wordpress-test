<?php
/**
 * Contact form: [ss_contact_form]
 *
 * Works with or without JavaScript. With JS the form posts to the REST API
 * and shows errors inline; without it, the form posts to admin-post.php and
 * the result is shown after a redirect back to the page.
 *
 * @package SignalShieldCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Services offered in the dropdown.
 *
 * @return array<string, string>
 */
function ss_core_service_options() {
	return array(
		'wireless'     => __( 'Wireless network design', 'signal-shield-core' ),
		'network'      => __( 'Enterprise network infrastructure', 'signal-shield-core' ),
		'security'     => __( 'Cybersecurity consulting', 'signal-shield-core' ),
		'health-check' => __( 'Follow-up on my health check results', 'signal-shield-core' ),
		'not-sure'     => __( 'Not sure yet, I would like some advice', 'signal-shield-core' ),
	);
}

/**
 * Error messages, shared by server and browser validation.
 *
 * @return array<string, array<string, string>>
 */
function ss_core_contact_messages() {
	return array(
		'name'    => array(
			'required' => __( 'Enter your name.', 'signal-shield-core' ),
			'invalid'  => __( 'Your name should be between 2 and 100 characters.', 'signal-shield-core' ),
		),
		'company' => array(
			'invalid' => __( 'Company name should be 120 characters or fewer.', 'signal-shield-core' ),
		),
		'email'   => array(
			'required' => __( 'Enter your email address.', 'signal-shield-core' ),
			'invalid'  => __( 'Enter an email address in the format name@company.com.', 'signal-shield-core' ),
		),
		'phone'   => array(
			'invalid' => __( 'Enter a phone number using digits, spaces and an optional +, for example +974 5555 1234.', 'signal-shield-core' ),
		),
		'service' => array(
			'required' => __( 'Choose the service you are interested in.', 'signal-shield-core' ),
		),
		'message' => array(
			'required' => __( 'Tell us a little about what you need.', 'signal-shield-core' ),
			'invalid'  => __( 'Your message should be at least 10 characters, so we can help properly.', 'signal-shield-core' ),
		),
	);
}

/**
 * True when a phone number looks plausible: 7 to 15 digits, with optional
 * leading +, spaces, dashes, dots and brackets.
 *
 * @param string $phone Phone number.
 * @return bool
 */
function ss_core_is_phone( $phone ) {
	if ( ! preg_match( '/^\+?[0-9\s().-]+$/', $phone ) ) {
		return false;
	}
	$digits = strlen( (string) preg_replace( '/\D/', '', $phone ) );
	return $digits >= 7 && $digits <= 15;
}

/**
 * Validate and clean a contact submission.
 *
 * @param array $input Raw input (already unslashed).
 * @return array{0: array, 1: array} Clean values and field errors.
 */
function ss_core_validate_contact( $input ) {
	$messages = ss_core_contact_messages();
	$errors   = array();

	$clean = array(
		'name'    => sanitize_text_field( $input['name'] ?? '' ),
		'company' => sanitize_text_field( $input['company'] ?? '' ),
		'email'   => sanitize_email( $input['email'] ?? '' ),
		'phone'   => sanitize_text_field( $input['phone'] ?? '' ),
		'service' => sanitize_key( $input['service'] ?? '' ),
		'message' => sanitize_textarea_field( $input['message'] ?? '' ),
		'source'  => esc_url_raw( $input['source'] ?? '' ),
	);
	$raw_email = trim( (string) ( $input['email'] ?? '' ) );

	$name_length = mb_strlen( $clean['name'] );
	if ( 0 === $name_length ) {
		$errors['name'] = $messages['name']['required'];
	} elseif ( $name_length < 2 || $name_length > 100 ) {
		$errors['name'] = $messages['name']['invalid'];
	}

	if ( mb_strlen( $clean['company'] ) > 120 ) {
		$errors['company'] = $messages['company']['invalid'];
	}

	if ( '' === $raw_email ) {
		$errors['email'] = $messages['email']['required'];
	} elseif ( ! is_email( $raw_email ) || $raw_email !== $clean['email'] ) {
		$errors['email'] = $messages['email']['invalid'];
		$clean['email']  = sanitize_text_field( $raw_email );
	}

	if ( '' !== $clean['phone'] && ! ss_core_is_phone( $clean['phone'] ) ) {
		$errors['phone'] = $messages['phone']['invalid'];
	}

	if ( ! array_key_exists( $clean['service'], ss_core_service_options() ) ) {
		$errors['service'] = $messages['service']['required'];
	}

	$message_length = mb_strlen( trim( $clean['message'] ) );
	if ( 0 === $message_length ) {
		$errors['message'] = $messages['message']['required'];
	} elseif ( $message_length < 10 || $message_length > 5000 ) {
		$errors['message'] = $messages['message']['invalid'];
	}

	return array( $clean, $errors );
}

/**
 * Save an enquiry and email the team.
 *
 * @param array $clean Validated values.
 * @return int|WP_Error Post id.
 */
function ss_core_save_enquiry( $clean ) {
	$options = ss_core_service_options();

	$post_id = wp_insert_post(
		array(
			'post_type'    => 'ss_enquiry',
			'post_status'  => 'private',
			'post_title'   => $clean['name'] . ( $clean['company'] ? ' · ' . $clean['company'] : '' ),
			'post_content' => $clean['message'],
		),
		true
	);
	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	foreach ( array( 'name', 'company', 'email', 'phone', 'service', 'source' ) as $key ) {
		update_post_meta( $post_id, '_ss_' . $key, $clean[ $key ] );
	}

	$subject = sprintf(
		/* translators: 1: service, 2: person's name */
		__( 'New enquiry: %1$s from %2$s', 'signal-shield-core' ),
		$options[ $clean['service'] ],
		$clean['name']
	);
	$body = implode(
		"\n",
		array(
			__( 'A new message arrived through the website contact form.', 'signal-shield-core' ),
			'',
			__( 'Name:', 'signal-shield-core' ) . ' ' . $clean['name'],
			__( 'Company:', 'signal-shield-core' ) . ' ' . ( $clean['company'] ?: '—' ),
			__( 'Email:', 'signal-shield-core' ) . ' ' . $clean['email'],
			__( 'Phone:', 'signal-shield-core' ) . ' ' . ( $clean['phone'] ?: '—' ),
			__( 'Interested in:', 'signal-shield-core' ) . ' ' . $options[ $clean['service'] ],
			'',
			__( 'Message:', 'signal-shield-core' ),
			$clean['message'],
			'',
			__( 'View in WordPress:', 'signal-shield-core' ) . ' ' . admin_url( 'post.php?post=' . $post_id . '&action=edit' ),
		)
	);
	$headers = array( sprintf( 'Reply-To: %s <%s>', str_replace( array( "\r", "\n", '<', '>' ), '', $clean['name'] ), $clean['email'] ) );

	$sent = wp_mail( ss_core_notification_email(), $subject, $body, $headers );
	update_post_meta( $post_id, '_ss_notified', $sent ? 1 : 0 );

	return $post_id;
}

/**
 * Process a contact submission from either route.
 *
 * @param array $input Raw input (unslashed).
 * @return array{ok: bool, errors?: array, values?: array, message?: string}
 */
function ss_core_handle_contact( $input ) {
	if ( ss_core_is_spam( $input ) ) {
		// Pretend it worked so bots learn nothing.
		return array(
			'ok'      => true,
			'message' => ss_core_contact_success_message( '' ),
		);
	}
	if ( ss_core_rate_limited( 'contact' ) ) {
		return array(
			'ok'     => false,
			'errors' => array( '_form' => __( 'You have sent several messages in a short time. Please wait ten minutes, or call us on +974 4412 7788.', 'signal-shield-core' ) ),
			'values' => array(),
		);
	}

	list( $clean, $errors ) = ss_core_validate_contact( $input );
	if ( $errors ) {
		return array(
			'ok'     => false,
			'errors' => $errors,
			'values' => $clean,
		);
	}

	$saved = ss_core_save_enquiry( $clean );
	if ( is_wp_error( $saved ) ) {
		return array(
			'ok'     => false,
			'errors' => array( '_form' => __( 'Your message could not be saved. Please try again, or call us on +974 4412 7788.', 'signal-shield-core' ) ),
			'values' => $clean,
		);
	}

	ss_core_rate_record( 'contact' );

	return array(
		'ok'      => true,
		'message' => ss_core_contact_success_message( $clean['name'] ),
	);
}

/**
 * Confirmation text.
 *
 * @param string $name Person's name.
 * @return string
 */
function ss_core_contact_success_message( $name ) {
	$first = $name ? strtok( $name, ' ' ) : '';
	return $first
		/* translators: %s: first name */
		? sprintf( __( 'Thank you, %s. Your message is with our team and a consultant will reply within one working day.', 'signal-shield-core' ), $first )
		: __( 'Thank you. Your message is with our team and a consultant will reply within one working day.', 'signal-shield-core' );
}

/**
 * Non-JS submission handler.
 */
function ss_core_admin_post_contact() {
	$input  = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- public form, protected by honeypot and rate limit.
	$result = ss_core_handle_contact( is_array( $input ) ? $input : array() );
	ss_core_redirect_back( array_merge( $result, array( 'form' => 'contact' ) ), 'ss-contact' );
}
add_action( 'admin_post_nopriv_ss_contact', 'ss_core_admin_post_contact' );
add_action( 'admin_post_ss_contact', 'ss_core_admin_post_contact' );

/**
 * Build the pre-filled message when arriving from the health check.
 *
 * @return string
 */
function ss_core_contact_prefill_message() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only prefill.
	$raw = isset( $_GET['result'] ) ? sanitize_text_field( wp_unslash( $_GET['result'] ) ) : '';
	if ( ! $raw ) {
		return '';
	}
	$areas   = ss_core_health_check_categories();
	$ratings = ss_core_health_check_rating_labels();
	$parts   = array();
	foreach ( explode( ',', $raw ) as $pair ) {
		list( $area, $rating ) = array_pad( explode( ':', $pair ), 2, '' );
		if ( isset( $areas[ $area ], $ratings[ $rating ] ) ) {
			$parts[] = $areas[ $area ]['label'] . ': ' . $ratings[ $rating ];
		}
	}
	if ( ! $parts ) {
		return '';
	}
	return sprintf(
		/* translators: %s: list of results, e.g. "Wireless: Green, Network: Amber" */
		__( 'I took the network health check. My results were %s. I would like to talk about what to do next.', 'signal-shield-core' ),
		implode( ', ', $parts )
	);
}

/**
 * Render one labelled field.
 *
 * @param array $field  Field definition.
 * @param array $values Current values.
 * @param array $errors Current errors.
 * @param string $prefix Id prefix.
 * @return string
 */
function ss_core_render_field( $field, $values, $errors, $prefix ) {
	$name     = $field['name'];
	$id       = $prefix . '-' . $name;
	$error_id = $id . '-error';
	$hint_id  = $id . '-hint';
	$value    = $values[ $name ] ?? '';
	$error    = $errors[ $name ] ?? '';
	$required = ! empty( $field['required'] );

	$describedby = array();
	if ( ! empty( $field['hint'] ) ) {
		$describedby[] = $hint_id;
	}
	if ( $error ) {
		$describedby[] = $error_id;
	}

	$attrs = array(
		'id'   => $id,
		'name' => $name,
	);
	if ( $required ) {
		$attrs['required']      = 'required';
		$attrs['aria-required'] = 'true';
	}
	if ( $error ) {
		$attrs['aria-invalid'] = 'true';
	}
	if ( $describedby ) {
		$attrs['aria-describedby'] = implode( ' ', $describedby );
	}
	foreach ( array( 'autocomplete', 'maxlength', 'inputmode', 'rows' ) as $extra ) {
		if ( isset( $field[ $extra ] ) ) {
			$attrs[ $extra ] = $field[ $extra ];
		}
	}
	foreach ( $field['messages'] ?? array() as $rule => $text ) {
		$attrs[ 'data-msg-' . $rule ] = $text;
	}
	if ( isset( $field['rules'] ) ) {
		$attrs['data-rules'] = $field['rules'];
	}

	$attr_html = '';
	foreach ( $attrs as $key => $attr_value ) {
		$attr_html .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( (string) $attr_value ) );
	}

	ob_start();
	?>
	<div class="ss-field ss-field--<?php echo esc_attr( $field['type'] ); ?><?php echo $error ? ' has-error' : ''; ?><?php echo ! empty( $field['wide'] ) ? ' ss-field--wide' : ''; ?>">
		<label for="<?php echo esc_attr( $id ); ?>">
			<?php echo esc_html( $field['label'] ); ?>
			<?php if ( $required ) : ?>
				<span class="ss-field__req" aria-hidden="true">*</span>
			<?php else : ?>
				<span class="ss-field__optional"><?php esc_html_e( '(optional)', 'signal-shield-core' ); ?></span>
			<?php endif; ?>
		</label>
		<?php if ( 'textarea' === $field['type'] ) : ?>
			<textarea<?php echo $attr_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>><?php echo esc_textarea( $value ); ?></textarea>
		<?php elseif ( 'select' === $field['type'] ) : ?>
			<select<?php echo $attr_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>>
				<option value=""><?php esc_html_e( 'Choose a service…', 'signal-shield-core' ); ?></option>
				<?php foreach ( $field['options'] as $option_value => $option_label ) : ?>
					<option value="<?php echo esc_attr( $option_value ); ?>"<?php selected( $value, $option_value ); ?>><?php echo esc_html( $option_label ); ?></option>
				<?php endforeach; ?>
			</select>
		<?php else : ?>
			<input type="<?php echo esc_attr( $field['type'] ); ?>" value="<?php echo esc_attr( $value ); ?>"<?php echo $attr_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>>
		<?php endif; ?>
		<?php if ( ! empty( $field['hint'] ) ) : ?>
			<p class="ss-field__hint" id="<?php echo esc_attr( $hint_id ); ?>"><?php echo esc_html( $field['hint'] ); ?></p>
		<?php endif; ?>
		<p class="ss-field__error" id="<?php echo esc_attr( $error_id ); ?>"<?php echo $error ? '' : ' hidden'; ?>><?php echo esc_html( $error ); ?></p>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * [ss_contact_form]
 *
 * @return string
 */
function ss_core_contact_form_shortcode() {
	wp_enqueue_style( 'ss-core' );
	wp_enqueue_script( 'ss-core-forms' );

	$prefix   = 'ss-contact';
	$messages = ss_core_contact_messages();
	$state    = ss_core_get_state( 'contact' );
	$errors   = ( $state && empty( $state['ok'] ) ) ? (array) $state['errors'] : array();
	$values   = ( $state && empty( $state['ok'] ) ) ? (array) $state['values'] : array();
	$success  = $state && ! empty( $state['ok'] );

	if ( ! $state ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only prefill.
		$requested = isset( $_GET['service'] ) ? sanitize_key( wp_unslash( $_GET['service'] ) ) : '';
		if ( array_key_exists( $requested, ss_core_service_options() ) ) {
			$values['service'] = $requested;
		}
		$values['message'] = ss_core_contact_prefill_message();
	}

	$fields = array(
		array(
			'name'         => 'name',
			'label'        => __( 'Your name', 'signal-shield-core' ),
			'type'         => 'text',
			'required'     => true,
			'autocomplete' => 'name',
			'maxlength'    => 100,
			'rules'        => 'min:2',
			'messages'     => $messages['name'],
		),
		array(
			'name'         => 'company',
			'label'        => __( 'Company', 'signal-shield-core' ),
			'type'         => 'text',
			'autocomplete' => 'organization',
			'maxlength'    => 120,
			'messages'     => $messages['company'],
		),
		array(
			'name'         => 'email',
			'label'        => __( 'Work email', 'signal-shield-core' ),
			'type'         => 'email',
			'required'     => true,
			'autocomplete' => 'email',
			'maxlength'    => 190,
			'rules'        => 'email',
			'messages'     => $messages['email'],
		),
		array(
			'name'         => 'phone',
			'label'        => __( 'Phone', 'signal-shield-core' ),
			'type'         => 'tel',
			'autocomplete' => 'tel',
			'maxlength'    => 30,
			'rules'        => 'phone',
			'hint'         => __( 'Include the country code if you are outside Qatar.', 'signal-shield-core' ),
			'messages'     => $messages['phone'],
		),
		array(
			'name'     => 'service',
			'label'    => __( 'Service of interest', 'signal-shield-core' ),
			'type'     => 'select',
			'required' => true,
			'options'  => ss_core_service_options(),
			'wide'     => true,
			'messages' => $messages['service'],
		),
		array(
			'name'      => 'message',
			'label'     => __( 'How can we help?', 'signal-shield-core' ),
			'type'      => 'textarea',
			'required'  => true,
			'rows'      => 6,
			'maxlength' => 5000,
			'rules'     => 'min:10',
			'wide'      => true,
			'hint'      => __( 'For example: the type of site, how many people use it, and what is not working.', 'signal-shield-core' ),
			'messages'  => $messages['message'],
		),
	);

	ob_start();
	?>
	<div class="ss-form-wrap" id="ss-contact">
		<div class="ss-form-success" tabindex="-1" role="status"<?php echo $success ? '' : ' hidden'; ?>>
			<span class="ss-form-success__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12.5 4.5 4.5L19 7.5"/></svg></span>
			<div>
				<h3 class="ss-form-success__title"><?php esc_html_e( 'Message sent', 'signal-shield-core' ); ?></h3>
				<p class="ss-form-success__text"><?php echo esc_html( $success ? $state['message'] : '' ); ?></p>
				<p><?php esc_html_e( 'Need us sooner? Call +974 4412 7788 or message us on WhatsApp.', 'signal-shield-core' ); ?></p>
			</div>
		</div>
		<form class="ss-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate
			data-ss-form="contact"
			data-endpoint="<?php echo esc_url( rest_url( 'signal-shield/v1/contact' ) ); ?>"
			data-error-generic="<?php esc_attr_e( 'Something went wrong sending your message. Please try again, or call us on +974 4412 7788.', 'signal-shield-core' ); ?>"
			data-sending="<?php esc_attr_e( 'Sending…', 'signal-shield-core' ); ?>"
			<?php echo $success ? 'hidden' : ''; ?>>
			<?php echo ss_core_error_summary( $errors, $prefix ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
			<p class="ss-form__intro"><?php echo wp_kses( __( 'Fields marked <span aria-hidden="true">*</span> are required.', 'signal-shield-core' ), array( 'span' => array( 'aria-hidden' => true ) ) ); ?></p>
			<input type="hidden" name="action" value="ss_contact">
			<input type="hidden" name="source" value="<?php echo esc_url( get_permalink() ? get_permalink() : home_url( '/' ) ); ?>">
			<?php echo ss_core_spam_fields( $prefix ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
			<div class="ss-form__grid">
				<?php
				foreach ( $fields as $field ) {
					echo ss_core_render_field( $field, $values, $errors, $prefix ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside.
				}
				?>
			</div>
			<div class="ss-form__footer">
				<button type="submit" class="ss-button wp-element-button"><?php esc_html_e( 'Send message', 'signal-shield-core' ); ?></button>
				<p class="ss-form__fine">
					<?php
					printf(
						/* translators: %s: link to privacy page */
						esc_html__( 'We only use your details to reply to you. Read our %s.', 'signal-shield-core' ),
						'<a href="' . esc_url( home_url( '/privacy/' ) ) . '">' . esc_html__( 'privacy notice', 'signal-shield-core' ) . '</a>'
					);
					?>
				</p>
			</div>
		</form>
	</div>
	<?php
	return (string) ob_get_clean();
}
add_shortcode( 'ss_contact_form', 'ss_core_contact_form_shortcode' );
