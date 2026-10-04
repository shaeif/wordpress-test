<?php
/**
 * Admin screens: list columns, a read-only detail box and the notification setting.
 *
 * @package SignalShieldCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enquiry list columns.
 *
 * @return array
 */
function ss_core_enquiry_columns() {
	return array(
		'cb'         => '<input type="checkbox" />',
		'title'      => __( 'Name', 'signal-shield-core' ),
		'ss_company' => __( 'Company', 'signal-shield-core' ),
		'ss_email'   => __( 'Email', 'signal-shield-core' ),
		'ss_phone'   => __( 'Phone', 'signal-shield-core' ),
		'ss_service' => __( 'Interested in', 'signal-shield-core' ),
		'ss_date'    => __( 'Received', 'signal-shield-core' ),
	);
}
add_filter( 'manage_ss_enquiry_posts_columns', 'ss_core_enquiry_columns' );

/**
 * Enquiry column values.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post id.
 */
function ss_core_enquiry_column_value( $column, $post_id ) {
	switch ( $column ) {
		case 'ss_company':
			echo esc_html( get_post_meta( $post_id, '_ss_company', true ) ?: '—' );
			break;
		case 'ss_email':
			$email = get_post_meta( $post_id, '_ss_email', true );
			printf( '<a href="mailto:%1$s">%2$s</a>', esc_attr( $email ), esc_html( $email ) );
			break;
		case 'ss_phone':
			echo esc_html( get_post_meta( $post_id, '_ss_phone', true ) ?: '—' );
			break;
		case 'ss_service':
			$options = ss_core_service_options();
			$service = get_post_meta( $post_id, '_ss_service', true );
			echo esc_html( $options[ $service ] ?? $service );
			break;
		case 'ss_date':
			echo esc_html( get_the_date( 'j M Y, g:i a', $post_id ) );
			break;
	}
}
add_action( 'manage_ss_enquiry_posts_custom_column', 'ss_core_enquiry_column_value', 10, 2 );

/**
 * Checklist download list columns.
 *
 * @return array
 */
function ss_core_lead_columns() {
	return array(
		'cb'           => '<input type="checkbox" />',
		'title'        => __( 'Email', 'signal-shield-core' ),
		'ss_tips'      => __( 'Wants tips', 'signal-shield-core' ),
		'ss_downloads' => __( 'Downloads', 'signal-shield-core' ),
		'ss_date'      => __( 'Requested', 'signal-shield-core' ),
	);
}
add_filter( 'manage_ss_lead_posts_columns', 'ss_core_lead_columns' );

/**
 * Checklist download column values.
 *
 * @param string $column  Column key.
 * @param int    $post_id Post id.
 */
function ss_core_lead_column_value( $column, $post_id ) {
	if ( 'ss_tips' === $column ) {
		echo get_post_meta( $post_id, '_ss_tips', true ) ? esc_html__( 'Yes', 'signal-shield-core' ) : esc_html__( 'No', 'signal-shield-core' );
	} elseif ( 'ss_downloads' === $column ) {
		echo esc_html( (string) (int) get_post_meta( $post_id, '_ss_downloads', true ) );
	} elseif ( 'ss_date' === $column ) {
		echo esc_html( get_the_date( 'j M Y, g:i a', $post_id ) );
	}
}
add_action( 'manage_ss_lead_posts_custom_column', 'ss_core_lead_column_value', 10, 2 );

/**
 * Let the date columns sort by date.
 *
 * @param array $columns Sortable columns.
 * @return array
 */
function ss_core_sortable_columns( $columns ) {
	$columns['ss_date'] = 'date';
	return $columns;
}
add_filter( 'manage_edit-ss_enquiry_sortable_columns', 'ss_core_sortable_columns' );
add_filter( 'manage_edit-ss_lead_sortable_columns', 'ss_core_sortable_columns' );

/**
 * Remove "Edit" and "Quick Edit" row actions: records are read-only.
 *
 * @param array   $actions Row actions.
 * @param WP_Post $post    Post.
 * @return array
 */
function ss_core_row_actions( $actions, $post ) {
	if ( in_array( $post->post_type, array( 'ss_enquiry', 'ss_lead' ), true ) ) {
		unset( $actions['inline hide-if-no-js'] );
		if ( isset( $actions['edit'] ) ) {
			$actions['edit'] = sprintf(
				'<a href="%s">%s</a>',
				esc_url( get_edit_post_link( $post->ID ) ),
				esc_html__( 'View', 'signal-shield-core' )
			);
		}
	}
	return $actions;
}
add_filter( 'post_row_actions', 'ss_core_row_actions', 10, 2 );

/**
 * Read-only details box on the enquiry and download screens.
 */
function ss_core_add_meta_boxes() {
	add_meta_box( 'ss-enquiry-details', __( 'Message details', 'signal-shield-core' ), 'ss_core_render_enquiry_box', 'ss_enquiry', 'normal', 'high' );
	add_meta_box( 'ss-lead-details', __( 'Download details', 'signal-shield-core' ), 'ss_core_render_lead_box', 'ss_lead', 'normal', 'high' );
	remove_meta_box( 'slugdiv', array( 'ss_enquiry', 'ss_lead' ), 'normal' );
}
add_action( 'add_meta_boxes', 'ss_core_add_meta_boxes' );

/**
 * Enquiry details.
 *
 * @param WP_Post $post Post.
 */
function ss_core_render_enquiry_box( $post ) {
	$options = ss_core_service_options();
	$service = get_post_meta( $post->ID, '_ss_service', true );
	$email   = get_post_meta( $post->ID, '_ss_email', true );
	$rows    = array(
		__( 'Name', 'signal-shield-core' )          => esc_html( get_post_meta( $post->ID, '_ss_name', true ) ),
		__( 'Company', 'signal-shield-core' )       => esc_html( get_post_meta( $post->ID, '_ss_company', true ) ?: '—' ),
		__( 'Email', 'signal-shield-core' )         => sprintf( '<a href="mailto:%1$s">%2$s</a>', esc_attr( $email ), esc_html( $email ) ),
		__( 'Phone', 'signal-shield-core' )         => esc_html( get_post_meta( $post->ID, '_ss_phone', true ) ?: '—' ),
		__( 'Interested in', 'signal-shield-core' ) => esc_html( $options[ $service ] ?? $service ),
		__( 'Sent from', 'signal-shield-core' )     => esc_html( get_post_meta( $post->ID, '_ss_source', true ) ?: '—' ),
		__( 'Received', 'signal-shield-core' )      => esc_html( get_the_date( 'j F Y, g:i a', $post ) ),
	);
	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( $rows as $label => $value ) {
		printf( '<tr><th scope="row">%s</th><td>%s</td></tr>', esc_html( $label ), $value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values escaped above.
	}
	echo '</tbody></table>';
	printf( '<h3>%s</h3>', esc_html__( 'Message', 'signal-shield-core' ) );
	echo '<div style="white-space:pre-wrap;background:#f6f7f7;padding:12px 14px;border-radius:4px;">' . esc_html( $post->post_content ) . '</div>';
	printf(
		'<p><a class="button button-primary" href="mailto:%1$s?subject=%2$s">%3$s</a></p>',
		esc_attr( $email ),
		rawurlencode( __( 'Re: your enquiry to Signal & Shield', 'signal-shield-core' ) ),
		esc_html__( 'Reply by email', 'signal-shield-core' )
	);
}

/**
 * Checklist download details.
 *
 * @param WP_Post $post Post.
 */
function ss_core_render_lead_box( $post ) {
	$rows = array(
		__( 'Email', 'signal-shield-core' )      => esc_html( get_post_meta( $post->ID, '_ss_email', true ) ),
		__( 'Wants tips', 'signal-shield-core' ) => get_post_meta( $post->ID, '_ss_tips', true ) ? esc_html__( 'Yes', 'signal-shield-core' ) : esc_html__( 'No', 'signal-shield-core' ),
		__( 'Downloads', 'signal-shield-core' )  => esc_html( (string) (int) get_post_meta( $post->ID, '_ss_downloads', true ) ),
		__( 'Requested', 'signal-shield-core' )  => esc_html( get_the_date( 'j F Y, g:i a', $post ) ),
		__( 'Sent from', 'signal-shield-core' )  => esc_html( get_post_meta( $post->ID, '_ss_source', true ) ?: '—' ),
	);
	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( $rows as $label => $value ) {
		printf( '<tr><th scope="row">%s</th><td>%s</td></tr>', esc_html( $label ), $value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values escaped above.
	}
	echo '</tbody></table>';
}

/**
 * Hide the title field and "Publish" box: these records are not edited.
 */
function ss_core_admin_styles() {
	$screen = get_current_screen();
	if ( $screen && in_array( $screen->post_type, array( 'ss_enquiry', 'ss_lead' ), true ) && 'post' === $screen->base ) {
		echo '<style>#titlediv #title{pointer-events:none;background:#f6f7f7}#submitdiv .misc-pub-section,#minor-publishing-actions,#publishing-action{display:none}</style>';
	}
}
add_action( 'admin_head', 'ss_core_admin_styles' );

/**
 * Bubble count of enquiries from the last 7 days on the menu.
 */
function ss_core_menu_bubble() {
	global $menu;
	$recent = new WP_Query(
		array(
			'post_type'      => 'ss_enquiry',
			'post_status'    => 'private',
			'fields'         => 'ids',
			'posts_per_page' => 1,
			'no_found_rows'  => false,
			'date_query'     => array( array( 'after' => '7 days ago' ) ),
		)
	);
	$count = (int) $recent->found_posts;
	if ( ! $count || ! is_array( $menu ) ) {
		return;
	}
	foreach ( $menu as $key => $item ) {
		if ( 'edit.php?post_type=ss_enquiry' === $item[2] ) {
			$menu[ $key ][0] .= sprintf( ' <span class="awaiting-mod"><span class="pending-count">%d</span></span>', $count ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
	}
}
add_action( 'admin_menu', 'ss_core_menu_bubble', 99 );

/**
 * "Enquiry notifications" email field under Settings > General.
 */
function ss_core_register_settings() {
	register_setting(
		'general',
		'ss_core_notification_email',
		array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_email',
			'default'           => '',
		)
	);
	add_settings_field(
		'ss_core_notification_email',
		'<label for="ss_core_notification_email">' . esc_html__( 'Enquiry notifications', 'signal-shield-core' ) . '</label>',
		static function () {
			printf(
				'<input type="email" id="ss_core_notification_email" name="ss_core_notification_email" value="%1$s" class="regular-text" placeholder="%2$s"><p class="description">%3$s</p>',
				esc_attr( get_option( 'ss_core_notification_email', '' ) ),
				esc_attr( get_option( 'admin_email' ) ),
				esc_html__( 'New contact form messages are emailed here. Leave empty to use the administration email address. Every message is also saved under Enquiries.', 'signal-shield-core' )
			);
		},
		'general'
	);
}
add_action( 'admin_init', 'ss_core_register_settings' );
