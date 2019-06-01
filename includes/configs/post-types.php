<?php
/**
 * Post types configuration.
 *
 * @package HivePress\Configs
 */

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

return [
	'listing'           => [
		'public'      => true,
		'has_archive' => true,
		'supports'    => [ 'title', 'editor', 'thumbnail', 'author' ],
		'menu_icon'   => 'dashicons-format-aside',
		'rewrite'     => [ 'slug' => 'listing' ],

		'labels'      => [
			'name'               => esc_html__( 'Listings', 'hivepress' ),
			'singular_name'      => esc_html__( 'Listing', 'hivepress' ),
			'add_new_item'       => esc_html__( 'Add New Listing', 'hivepress' ),
			'edit_item'          => esc_html__( 'Edit Listing', 'hivepress' ),
			'new_item'           => esc_html__( 'New Listing', 'hivepress' ),
			'view_item'          => esc_html__( 'View Listing', 'hivepress' ),
			'all_items'          => esc_html__( 'All Listings', 'hivepress' ),
			'search_items'       => esc_html__( 'Search Listings', 'hivepress' ),
			'not_found'          => esc_html__( 'No Listings Found', 'hivepress' ),
			'not_found_in_trash' => esc_html__( 'No Listings Found in Trash', 'hivepress' ),
		],
	],

	'listing_attribute' => [
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => 'edit.php?post_type=hp_listing',
		'supports'     => [ 'title', 'page-attributes' ],

		'labels'       => [
			'name'               => esc_html__( 'Attributes', 'hivepress' ),
			'singular_name'      => esc_html__( 'Attribute', 'hivepress' ),
			'add_new_item'       => esc_html__( 'Add New Attribute', 'hivepress' ),
			'edit_item'          => esc_html__( 'Edit Attribute', 'hivepress' ),
			'new_item'           => esc_html__( 'New Attribute', 'hivepress' ),
			'view_item'          => esc_html__( 'View Attribute', 'hivepress' ),
			'all_items'          => esc_html__( 'Attributes', 'hivepress' ),
			'search_items'       => esc_html__( 'Search Attributes', 'hivepress' ),
			'not_found'          => esc_html__( 'No Attributes Found', 'hivepress' ),
			'not_found_in_trash' => esc_html__( 'No Attributes Found in Trash', 'hivepress' ),
		],
	],

	'vendor'            => [
		'public'       => true,
		'show_in_menu' => false,
		'supports'     => [ 'title', 'editor', 'thumbnail', 'author' ],
		'rewrite'      => [ 'slug' => 'vendor' ],

		'labels'       => [
			'name'               => esc_html__( 'Vendors', 'hivepress' ),
			'singular_name'      => esc_html__( 'Vendor', 'hivepress' ),
			'add_new_item'       => esc_html__( 'Add New Vendor', 'hivepress' ),
			'edit_item'          => esc_html__( 'Edit Vendor', 'hivepress' ),
			'new_item'           => esc_html__( 'New Vendor', 'hivepress' ),
			'view_item'          => esc_html__( 'View Vendor', 'hivepress' ),
			'all_items'          => esc_html__( 'Vendors', 'hivepress' ),
			'search_items'       => esc_html__( 'Search Vendors', 'hivepress' ),
			'not_found'          => esc_html__( 'No Vendors Found', 'hivepress' ),
			'not_found_in_trash' => esc_html__( 'No Vendors Found in Trash', 'hivepress' ),
		],
	],
];