<?php
/**
 * Listing controller.
 *
 * @package HivePress\Controllers
 */

namespace HivePress\Controllers;

use HivePress\Helpers as hp;
use HivePress\Models;
use HivePress\Forms;
use HivePress\Menus;
use HivePress\Blocks;
use HivePress\Emails;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Listing controller class.
 *
 * @class Listing
 */
class Listing extends Controller {

	/**
	 * Controller name.
	 *
	 * @var string
	 */
	protected static $name;

	/**
	 * Controller routes.
	 *
	 * @var array
	 */
	protected static $routes = [];

	/**
	 * Class initializer.
	 *
	 * @param array $args Controller arguments.
	 */
	public static function init( $args = [] ) {
		$args = hp\merge_arrays(
			[
				'routes' => [
					[
						'path'      => '/listings',
						'rest'      => true,
						'endpoints' => [
							[
								'path'    => '/(?P<id>\d+)',
								'methods' => 'POST',
								'action'  => 'update_listing',
							],

							[
								'path'    => '/(?P<id>\d+)',
								'methods' => 'DELETE',
								'action'  => 'delete_listing',
							],
						],
					],

					'view_listings'  => [
						'match'  => 'is_listings_view_page',
						'action' => 'render_listings_view_page',
					],

					'view_listing'   => [
						'match'  => 'is_listing_view_page',
						'action' => 'render_listing_view_page',
					],

					'edit_listings'  => [
						'title'    => esc_html__( 'My Listings', 'hivepress' ),
						'path'     => '/account/listings',
						'redirect' => 'redirect_listings_edit_page',
						'action'   => 'render_listings_edit_page',
					],

					'edit_listing'   => [
						'title'    => esc_html__( 'Edit Listing', 'hivepress' ),
						'path'     => '/account/listings/(?P<listing_id>\d+)',
						'redirect' => 'redirect_listing_edit_page',
						'action'   => 'render_listing_edit_page',
					],

					'submit_listing' => [
						'path'     => '/submit-listing',
						'redirect' => 'redirect_listing_submit_page',
					],

					'submit_details' => [
						'title' => esc_html__( 'Add Details', 'hivepress' ),
						'path'  => '/submit-listing/details',
					],
				],
			],
			$args
		);

		parent::init( $args );
	}

	/**
	 * Updates listing.
	 *
	 * @param WP_REST_Request $request API request.
	 * @return WP_Rest_Response
	 */
	public function update_listing( $request ) {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return hp\rest_error( 401 );
		}

		// Get listing.
		$listing = Models\Listing::get( $request->get_param( 'id' ) );

		if ( is_null( $listing ) ) {
			return hp\rest_error( 404 );
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_others_posts' ) && ( get_current_user_id() !== $listing->get_user_id() || ! in_array( $listing->get_status(), [ 'auto-draft', 'draft', 'publish' ], true ) ) ) {
			return hp\rest_error( 403 );
		}

		// Validate form.
		$form = new Forms\Listing_Update();

		if ( $listing->get_status() === 'auto-draft' ) {
			$form = new Forms\Listing_Submit();
		}

		$form->set_values( $request->get_params() );

		if ( ! $form->validate() ) {
			return hp\rest_error( 400, $form->get_errors() );
		}

		// Update listing.
		$listing->fill( $form->get_values() );

		if ( ! $listing->save() ) {
			return hp\rest_error( 400, esc_html__( 'Error updating listing', 'hivepress' ) );
		}

		return new \WP_Rest_Response(
			[
				'data' => [
					'id' => $listing->get_id(),
				],
			],
			200
		);
	}

	/**
	 * Deletes listing.
	 *
	 * @param WP_REST_Request $request API request.
	 * @return WP_Rest_Response
	 */
	public function delete_listing( $request ) {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return hp\rest_error( 401 );
		}

		// Get listing.
		$listing = Models\Listing::get( $request->get_param( 'id' ) );

		if ( is_null( $listing ) ) {
			return hp\rest_error( 404 );
		}

		// Check permissions.
		if ( ! current_user_can( 'delete_others_posts' ) && ( get_current_user_id() !== $listing->get_user_id() || ! in_array( $listing->get_status(), [ 'auto-draft', 'draft', 'publish' ], true ) ) ) {
			return hp\rest_error( 403 );
		}

		// Delete listing.
		if ( ! $listing->delete() ) {
			return hp\rest_error( 400, esc_html__( 'Error deleting listing', 'hivepress' ) );
		}

		return new \WP_Rest_Response( (object) [], 204 );
	}

	/**
	 * Matches listings view page.
	 *
	 * @return bool
	 */
	public function is_listings_view_page() {

		// Get page ID.
		$page_id = absint( get_option( 'hp_page_listings' ) );

		return ( 0 !== $page_id && is_page( $page_id ) ) || is_post_type_archive( 'hp_listing' ) || is_tax( 'hp_listing_category' );
	}

	/**
	 * Matches listings view page.
	 *
	 * @return string
	 */
	public function render_listings_view_page() {
		$output = ( new Blocks\Element( [ 'file_path' => 'header' ] ) )->render();

		if ( ( is_page() && get_option( 'hp_page_listings_display_subcategories' ) ) || ( is_tax() && get_term_meta( get_queried_object_id(), 'hp_display_subcategories', true ) ) ) {
			$output .= ( new Blocks\Template( [ 'template_name' => 'listing_categories_view_page' ] ) )->render();
		} else {
			$output .= ( new Blocks\Template( [ 'template_name' => 'listings_view_page' ] ) )->render();
		}

		$output .= ( new Blocks\Element( [ 'file_path' => 'footer' ] ) )->render();

		return $output;
	}

	/**
	 * Checks listing view page.
	 *
	 * @return bool
	 */
	public function is_listing_view_page() {
		return is_singular( 'hp_listing' );
	}

	/**
	 * Renders listing view page.
	 *
	 * @return string
	 */
	public function render_listing_view_page() {
		the_post();

		$output  = ( new Blocks\Element( [ 'file_path' => 'header' ] ) )->render();
		$output .= ( new Blocks\Listing( [ 'template_name' => 'listing_view_page' ] ) )->render();
		$output .= ( new Blocks\Element( [ 'file_path' => 'footer' ] ) )->render();

		return $output;
	}

	/**
	 * Redirects listings edit page.
	 *
	 * @return mixed
	 */
	public function redirect_listings_edit_page() {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return User::get_url( 'login_user' );
		}
	}

	/**
	 * Renders listings edit page.
	 *
	 * @return string
	 */
	public function render_listings_edit_page() {
		query_posts(
			[
				'post_type'      => hp\prefix( 'listing' ),
				'post_status'    => [ 'draft', 'pending', 'publish' ],
				'author'         => get_current_user_id(),
				'posts_per_page' => -1,
			]
		);

		$output  = ( new Blocks\Element( [ 'file_path' => 'header' ] ) )->render();
		$output .= ( new Blocks\Template( [ 'template_name' => 'listings_edit_page' ] ) )->render();
		$output .= ( new Blocks\Element( [ 'file_path' => 'footer' ] ) )->render();

		return $output;
	}

	/**
	 * Redirects listing edit page.
	 *
	 * @return mixed
	 */
	public function redirect_listing_edit_page() {

		// Check authentication.
		if ( ! is_user_logged_in() ) {
			return User::get_url( 'login_user' );
		}

		// Get listing.
		$listing = Models\Listing::get( get_query_var( 'hp_listing_id' ) );

		if ( is_null( $listing ) || get_current_user_id() !== $listing->get_user_id() || ! in_array( $listing->get_status(), [ 'draft', 'publish' ], true ) ) {
			return self::get_url( 'edit_listings' );
		}

		return false;
	}

	/**
	 * Renders listing edit page.
	 *
	 * @return string
	 */
	public function render_listing_edit_page() {
		$output  = ( new Blocks\Element( [ 'file_path' => 'header' ] ) )->render();
		$output .= ( new Blocks\Template( [ 'template_name' => 'listing_edit_page' ] ) )->render();
		$output .= ( new Blocks\Element( [ 'file_path' => 'footer' ] ) )->render();

		return $output;
	}

	/**
	 * Redirects listing submit page.
	 *
	 * @return mixed
	 */
	public function redirect_listing_submit_page() {

		// Get menu items.
		$items = Menus\Listing_Submit::get_items();

		if ( ! empty( $items ) ) {
			return reset( $items )['url'];
		}

		return false;
	}
}
