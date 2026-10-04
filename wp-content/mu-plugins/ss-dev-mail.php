<?php
/**
 * Plugin Name: Signal & Shield – Local mail catcher
 * Description: Development only. Sends all site email to Mailpit when SS_SMTP_HOST is set (see docker-compose.yml). Do not deploy.
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'phpmailer_init',
	static function ( $phpmailer ) {
		$host = getenv( 'SS_SMTP_HOST' );
		if ( ! $host ) {
			return;
		}
		$phpmailer->isSMTP();
		$phpmailer->Host     = $host;
		$phpmailer->Port     = (int) ( getenv( 'SS_SMTP_PORT' ) ?: 1025 );
		$phpmailer->SMTPAuth = false;
		$phpmailer->SMTPAutoTLS = false;
	}
);

// WordPress sends from wordpress@<site host>. On "localhost" that address is
// invalid and PHPMailer refuses to send, so use a placeholder sender locally.
add_filter(
	'wp_mail_from',
	static function ( $from ) {
		if ( getenv( 'SS_SMTP_HOST' ) && str_ends_with( $from, '@localhost' ) ) {
			return 'website@signalshield.example';
		}
		return $from;
	}
);
