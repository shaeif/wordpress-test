<?php
/**
 * Private post types that store contact enquiries and checklist downloads.
 *
 * @package SignalShieldCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register the Enquiry and Checklist Download post types.
 */
function ss_core_register_post_types() {
	$shared = array(
		'public'              => false,
		'publicly_queryable'  => false,
		'exclude_from_search' => true,
		'show_ui'             => true,
		'show_in_rest'        => false,
		'show_in_nav_menus'   => false,
		'has_archive'         => false,
		'rewrite'             => false,
		'query_var'           => false,
		'supports'            => array( 'title' ),
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
		'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
	);

	register_post_type(
		'ss_enquiry',
		array_merge(
			$shared,
			array(
				'labels'        => array(
					'name'               => __( 'Enquiries', 'signal-shield-core' ),
					'singular_name'      => __( 'Enquiry', 'signal-shield-core' ),
					'menu_name'          => __( 'Enquiries', 'signal-shield-core' ),
					'all_items'          => __( 'All enquiries', 'signal-shield-core' ),
					'edit_item'          => __( 'Enquiry', 'signal-shield-core' ),
					'search_items'       => __( 'Search enquiries', 'signal-shield-core' ),
					'not_found'          => __( 'No enquiries yet. Messages sent through the contact form will appear here.', 'signal-shield-core' ),
					'not_found_in_trash' => __( 'No enquiries in the bin.', 'signal-shield-core' ),
				),
				'menu_icon'     => 'dashicons-email-alt',
				'menu_position' => 25,
			)
		)
	);

	register_post_type(
		'ss_lead',
		array_merge(
			$shared,
			array(
				'labels'       => array(
					'name'               => __( 'Checklist downloads', 'signal-shield-core' ),
					'singular_name'      => __( 'Checklist download', 'signal-shield-core' ),
					'menu_name'          => __( 'Checklist downloads', 'signal-shield-core' ),
					'all_items'          => __( 'Checklist downloads', 'signal-shield-core' ),
					'edit_item'          => __( 'Checklist download', 'signal-shield-core' ),
					'search_items'       => __( 'Search by email', 'signal-shield-core' ),
					'not_found'          => __( 'Nobody has requested the checklist yet.', 'signal-shield-core' ),
					'not_found_in_trash' => __( 'No downloads in the bin.', 'signal-shield-core' ),
				),
				'show_in_menu' => 'edit.php?post_type=ss_enquiry',
			)
		)
	);
}
add_action( 'init', 'ss_core_register_post_types' );
